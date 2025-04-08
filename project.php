<!-- Test Oracle file for UBC CPSC304
  Created by Jiemin Zhang
  Modified by Simona Radu
  Modified by Jessica Wong (2018-06-22)
  Modified by Jason Hall (23-09-20)
  This file shows the very basics of how to execute PHP commands on Oracle.
  Specifically, it will drop a table, create a table, insert values update
  values, and then query for values
  IF YOU HAVE A TABLE CALLED "demoTable" IT WILL BE DESTROYED

  The script assumes you already have a server set up All OCI commands are
  commands to the Oracle libraries. To get the file to work, you must place it
  somewhere where your Apache server can run it, and you must rename it to have
  a ".php" extension. You must also change the username and password on the
  oci_connect below to be your ORACLE username and password
-->

<?php
// The preceding tag tells the web server to parse the following text as PHP
// rather than HTML (the default)

// The following 3 lines allow PHP errors to be displayed along with the page
// content. Delete or comment out this block when it's no longer needed.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database access configuration
$config["dbuser"] = "ora_zhibek26";			// change "cwl" to your own CWL
$config["dbpassword"] = "a38422853";	    // change to 'a' + your student number
$config["dbserver"] = "dbhost.students.cs.ubc.ca:1522/stu";
$db_conn = NULL;	// login credentials are used in connectToDB()

$success = true;	// keep track of errors so page redirects only if there are no errors

$show_debug_alert_messages = False; // show which methods are being triggered (see debugAlertMessage())

// The next tag tells the web server to stop parsing the text as PHP. Use the
// pair of tags wherever the content switches to PHP
?>

<html>

<head>
	<title>CPSC 304 PHP/Oracle Demonstration</title>
	<link rel = "stylesheet" type = "text/css" href = "styles.css">
</head>

<body>
	<header>
		<img src = "images/paw-logo.png" alt = "Paw Logo" class = "logo">
		<h1 class = "title"> Paw Portal </h1>
    </header>

	<div class = "grid-container">

		<div class = "grid-item">
			<h2>Reset</h2>
			<form method="POST" action="project.php">
				<input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
				<p><input type="submit" value="Reset" name="reset"></p>
			</form>
		</div>

		<div class = "grid-item">
			<h2>Insert a New Shelter Pet Entry</h2>
			<form method="POST" action="project.php">
				<input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
				Pet ID (pid): <input type="text" name="insPid" placeholder="e.g. SP1000"> 
				Name: <input type="text" name="insPname"> 
				Breed: <input type="text" name="insBreed"> 
				Species: <input type="text" name="insSpecies"> 
				Record Number: <input type="text" name="insRecordNum" placeholder="e.g. 1"> 
				<input type="submit" value="Insert" name="insertSubmit"></p>
			</form>
		</div>

		<div class = "grid-item">
			<h2> Delete a Pet Entry </h2>
			<form method="POST" action="project.php">
				<input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
				Pet ID (pid): <input type="text" name="delPid" placeholder="e.g. SP1000">
				<input type="submit" value="Delete" name="deleteSubmit"> 
			</form>
		</div>	


		<div class = "grid-item">
			<h2> Select Adopter(s) </h2>
			<form method="GET" action="project.php" id="conditionsForm">
				<input type="hidden" id="selectionQueryRequest" name="selectionQueryRequest">
				Conditions:
				<br></br>
				<div class="select-attr-container">
					<select name="attr1" class="select-attr">
						<option value="aid">Adopter ID</option>
						<option value="aName">Name</option>
						<option value="address">Address</option>
						<option value="phoneNum">Phone Number</option>
						<option value="numOfChildren">Number Of Children</option>
					</select>
					<button type="button" id="addCond" class="extraCondsButton">+</button>
				</div>
				<p>=</p>
				<input type="text" name="cond1" placeholder="Condition">
				<div id="extraConds"> 
				</div>
				<input type="submit" value="Submit" name="selectionSubmit"> 
			</form>
		</div>


		<div class = "grid-item">
			<h2> View Foster Parent Information </h2>
			<form method = "GET" action = "project.php">
				Foster Parent ID <input type = "checkbox" name = "attributes[]" value = "fid"> 
				Name <input type = "checkbox" name = "attributes[]" value = "fname"> 
				Phone Number <input type = "checkbox" name = "attributes[]" value = "phonenum"> 
				Address <input type = "checkbox" name = "attributes[]" value = "faddress">
				Experience Level <input type = "checkbox" name = "attributes[]" value = "experiencelevel">
				<input type = "submit" name = "projectionSubmit" value = "View Selected Attributes">
			</form>
		</div>

	<script>
    let conditionCounter = 2;  
    
    document.getElementById('addCond').addEventListener('click', handleAddClick);

	function handleAddClick() {
        const newCondition = document.createElement('div');
        newCondition.classList.add('conditionForm');

        newCondition.innerHTML = `
			<div class="cond-container">
		        <select name="logic${conditionCounter}" class="select-logic">
                	<option value="AND">AND</option>
                	<option value="OR">OR</option>
            	</select>
				<div class="select-attr-container">
				<select name="attr${conditionCounter}" class="select-attr">
					<option value="aid">Adopter ID</option>
					<option value="aName">Name</option>
					<option value="address">Address</option>
					<option value="phoneNum">Phone Number</option>
					<option value="numOfChildren">Number Of Children</option>
				</select>
				<button id="removeCond${conditionCounter}" class="extraCondsButton">-</button>
				</div>
			<p>=</p>
            <input type="text" name="cond${conditionCounter}" placeholder="Condition">
		</div>
        `;

		document.getElementById('extraConds').appendChild(newCondition);

		const removeCondButton = document.getElementById(`removeCond${conditionCounter}`);
		removeCondButton.addEventListener('click', (e) => {
			e.preventDefault();
			newCondition.remove();
		});


        conditionCounter++;
    }

	</script>

		<div class = "grid-item">
			<h2>Update Adopter</h2>

			<form method="POST" action="project.php">
				<input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
				Adopter ID (aid): <input type="text" name="aid" placeholder="e.g. A100"> 
				Updated Name: <input type="text" name="newName">
				New Address: <input type="text" name="newAddress">
				New Phone Number: <input type="text" name="newPhoneNum" placeholder="(XXX)-YYY-ZZZZ">
				Updated Number of Children: <input type="text" name="newNumChildren">

				<input type="submit" value="Update" name="updateSubmit"></p>
			</form>
		</div>
	
		<div class="grid-item">
			<h2>Get Pets' Medical Information by filtering: </h2>
			<form method="GET" action="project.php">
				<input type="hidden" name="shelterMedicalJoinRequest">
				
				<label for="joinAttr">Filter By:</label>
				<select name="joinAttr" required>
					<option value="pid">Pet ID</option>
					<option value="pName">Name</option>
					<option value="breed">Breed</option>
					<option value="species">Species</option>
				</select>

				<input type="text" name="joinValue" placeholder="Enter value..." required>
				<input type="submit" name="shelterMedicalJoinSubmit" value="Search">
			</form>
		</div>


		<div class = "grid-item">
			<h2>Find Donors Who Have Donated to All Shelters</h2>
			<form method = "GET" action = "project.php">
				<input type = "submit" name = "divisionSubmit" value = "Search">
			</form>
		</div>

		<div class="grid-item">
    		<h2>Average Donation Amount by Donor Type</h2>
    		<form method="GET" action="project.php">
        		<input type="submit" name="avgDonationAggSubmit" value="View">
			</form>
		</div>

		<div class="grid-item">
    		<h2>All Shelters with an Average Donation Amount Above the Overall Average  </h2>
    		<form method="GET" action="project.php">
        		<input type="submit" name="nestedAggGroupbySubmit" value="View">
			</form>
		</div>

		<div class="grid-item">
		<h2>Shelters With Donations Over X</h2>
			<form method="GET" action="project.php">
				<input type="hidden" name="shelterDonationHavingRequest">
				Amount: <input type="text" name="minDonationAmount" placeholder="e.g. 500">
				<input type="submit" name="shelterDonationHavingSubmit" value="Search">
			</form>
		</div>

		<div class = "grid-item">
			<h2>All Data</h2>
			<form method="GET" action="project.php">
				<input type="hidden" id="displayTuplesRequest" name="displayTuplesRequest">
				<input type="submit" name="displayTuples"></p>
			</form>
		</div>

	</div>

	<?php
	function debugAlertMessage($message)
	{
		global $show_debug_alert_messages;

		if ($show_debug_alert_messages) {
			echo "<script type='text/javascript'>alert('" . $message . "');</script>";
		}
	}

	
	//takes a plain (no bound variables) SQL command and executes it
	function executePlainSQL($cmdstr)
	{ 
		global $db_conn, $success;

		$statement = oci_parse($db_conn, $cmdstr);
		if (!$statement) {
			$e = OCI_Error($db_conn); // For oci_parse errors pass the connection handle
			return ["success" => false, "message" => htmlentities($e['message'])];
		}

		$r = oci_execute($statement, OCI_DEFAULT);
		if (!$r) {
			$e = oci_error($statement); // For oci_execute errors pass the statementhandle
			return ["success" => false, "message" => htmlentities($e['message'])];
		}

		// return ["success" => true, "message" => "Completed Successfully!"];
		return $statement;
	}

	function executeBoundSQL($cmdstr, $list)
	{
		/* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
		In this case you don't need to create the statement several times. Bound variables cause a statement to only be
		parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
		See the sample code below for how this function is used */

		global $db_conn;
		$statement = oci_parse($db_conn, $cmdstr);

		if (!$statement) {
			$e = OCI_Error($db_conn);
			return ["success" => false, "message" => htmlentities($e['message'])];
		}

		foreach ($list as $tuple) {
			foreach ($tuple as $bind => $val) {
				oci_bind_by_name($statement, $bind, $val);
				unset($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
			}

			$r = oci_execute($statement, OCI_DEFAULT);
			if (!$r) {
				$e = OCI_Error($statement); // For oci_execute errors, pass the statementhandle
				// return ["success" => false, "message" => getErrorMessage($e['message'])];
				// return ["success" => false, "message" => htmlentities($e['message'])];
				// throw new Exception(getErrorMessage($e['code'], $e['message']));
				throw new Exception(getErrorMessage($e['message']));
			}
		}
		
		return $statement;
	}

	function getErrorMessage($msg) {

		return "Database Error (Invalid Input)";

		// if (preg_match('/ORA-(\d{5})/', $msg, $matches)) {
		// 	$errorCode = str_pad($matches[1], 5, "0", STR_PAD_LEFT); // Ensure it's a 5-digit string
		// } else {
		// 	return "Database Error: " . htmlentities($msg);
		// }

		// // ORA-00001: UNIQUE constraint
		// $errorMap = [
		// 	"00001" => "The Pet ID already exists. Please input a unique ID.",
		// 	"02291" => "Foreign key constraint violation. Make sure related records exist.",
        // 	"02292"  => "Cannot delete this record because it is referenced in another table.",
		// ];

		// return $errorMap[$errorCode] ?? "Database Error: " . htmlentities($msg);
	}	

	function printResult($result, $tableName)
	{ //prints results from a select statement
		echo "<br><h2 class = 'table-header'>Retrieved Data: $tableName</h2>";
		echo "<table class = 'styled-table'>";

		// column names, as referred to in database (oracle returns them in caps)
		$dbColumnNames = [
			"hasMedicalRecord" => ["RECORDNUM", "MEDICALCONDITIONS", "VACCINEHISTORY"],
			"Pet" => ["PID", "PNAME", "BREED", "SPECIES", "RECORDNUM"],
			"shelterPet" => ["PID"],
			"adoptedPet" => ["PID", "AID"],
			"Adopter" => ["AID", "ANAME", "ADDRESS", "PHONENUM", "NUMOFCHILDREN"],
			"fosterParent" => ["FID", "FNAME", "FADDRESS", "PHONENUM", "EXPERIENCELEVEL"],
			"Shelter" => ["SHADDRESS", "SHNAME"],
			"Donor" => ["DID", "DNAME", "DONATIONTYPE"],
			"Donates" => ["SHADDRESS", "DID", "DONATIONAMOUNT"],
			"Shelters and their Average Donations" => ["SHADDRESS", "AVGDONATION"],

			// join
			"shelterMedicalJoin" => ["PNAME", "BREED", "SPECIES", "MEDICALCONDITIONS", "VACCINEHISTORY"],
			// group by with aggregation
			"AvgDonationAgg" => ["DONATIONTYPE", "AVGAMOUNT"],
			// having with aggregation
			"ShelterHaving" => ["SHNAME", "SHADDRESS", "TOTALDONATED"]
		];
	

		// new column names for readability 
		$columnNames = [
			"hasMedicalRecord" => ["Record Number", "Medical Conditions", "Vaccine History"],
			"Pet" => ["Pet ID", "Name", "Breed", "Species", "Record Number"],
			"shelterPet" => ["Pet ID"],
			"adoptedPet" => ["Pet ID", "Adoption ID"],
			"Adopter" => ["Adopter ID", "Name", "Address", "Phone Number", "Number of Children"],
			"fosterParent" => ["Foster ID", "Name", "Address", "Phone Number", "Experience Level"],
			"Shelter" => ["Shelter Address", "Shelter Name"],
			"Donor" => ["Donor ID", "Donor Name", "Type of Donor"],
			"Donates" => ["Shelter Address", "Donor ID", "Donation Amount ($)"],
			"Shelters and their Average Donations" => ["Shelter Address", "Average Donation Amount"],
			"shelterMedicalJoin" => ["Pet Name", "Breed", "Species", "Medical Conditions", "Vaccine History"],
			"AvgDonationAgg" => ["Donor Type", "Average Donation Amount ($)"],
			"ShelterHaving" => ["Shelter Name", "Shelter Address", "Total Donated ($)"]
		];

		if (array_key_exists($tableName, $columnNames)) {
			echo "<tr>";
			foreach ($columnNames[$tableName] as $columnName) {
				echo "<th>$columnName</th>";
			}
			echo "</tr>";

			while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
				echo "<tr>";
				foreach ($dbColumnNames[$tableName] as $dbColumnName) {
					echo "<td>" . $row[$dbColumnName] . "</td>";
				}
				echo "</tr>";
			}
		}
		echo "</table>";
	}


	function connectToDB()
	{
		global $db_conn;
		global $config;

		// Your username is ora_(CWL_ID) and the password is a(student number). For example,
		// ora_platypus is the username and a12345678 is the password.
		// $db_conn = oci_connect("ora_cwl", "a12345678", "dbhost.students.cs.ubc.ca:1522/stu");
		$db_conn = oci_connect($config["dbuser"], $config["dbpassword"], $config["dbserver"]);

		if ($db_conn) {
			debugAlertMessage("Database is Connected");
			return true;
		} else {
			debugAlertMessage("Cannot connect to Database");
			$e = OCI_Error(); // For oci_connect errors pass no handle
			echo htmlentities($e['message']);
			return false;
		}
	}

	function disconnectFromDB()
	{
		global $db_conn;

		debugAlertMessage("Disconnect from Database");
		oci_close($db_conn);
	}

	// UPDATE: updates Adopter's attributes
	function handleUpdateRequest()
	{
		global $db_conn;
		$alertMessage = "";

		if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateSubmit'])) {
			try {
				$aid = $_POST["aid"];
				$aname = $_POST["newName"];
				$aphonenum = $_POST["newPhoneNum"];

				if (empty($aid)) {
					throw new Exception("Adopter ID is required.");
				}

				if (empty($aname)) {
					throw new Exception("Name is required.");
				}

				if (empty($aphonenum)) {
					throw new Exception("Phone number is required.");
				}

				// Check if the Adopter ID exists
				$checkQuery = "SELECT COUNT(*) AS count FROM Adopter WHERE aid = :aid";
				$checkResult = executeBoundSQL($checkQuery, array([":aid" => $aid]));
				$row = oci_fetch_assoc($checkResult);
				if (!$row || $row['COUNT'] == 0) {
					throw new Exception("Adopter ID doesn't exist.");
				}

				$fields = array(
					":bind1" => ["column" => "aName", "val" => $_POST['newName']],
					":bind2" => ["column" => "address", "val" => $_POST['newAddress']],
					":bind3" => ["column" => "phoneNum", "val" => $_POST['newPhoneNum']],
					":bind4" => ["column" => "numOfChildren", "val" => $_POST['newNumChildren']]
				);

				$attributesToUpdate = [];
				$tuple = [];

				foreach ($fields as $bind => $field) {
					if (!empty($field["val"])) { 
						$attributesToUpdate[] = $field["column"] . "=" . $bind;
						$tuple[$bind] = $field["val"];
					}
				}

				// Check if phone number is unique
				if (isset($tuple[":bind3"])) {
					$checkPhoneQuery = "SELECT COUNT(*) AS count FROM Adopter WHERE phoneNum = :bind3 AND aid != :aid";
					$phoneCheckResult = executeBoundSQL($checkPhoneQuery, array(array(":bind3" => $tuple[":bind3"], ":aid" => $aid)));
					$phoneCheckRow = OCI_Fetch_Array($phoneCheckResult, OCI_ASSOC);

					if ($phoneCheckRow['COUNT'] > 0) {
						throw new Exception("Phone number must be unique.");
					}
				}

				if (!empty($attributesToUpdate)) {
					$query = "UPDATE Adopter SET " . implode(",", $attributesToUpdate) . " WHERE aid=:aid";
					$tuple[":aid"] = $aid;
					$updateResult = executeBoundSQL($query, array($tuple));

					if (!is_resource($updateResult)) {
						throw new Exception("Update Failed");
					}
				} 

				oci_commit($db_conn);
				$alertMessage = "Updated Successfully!";

			} catch (Exception $e) {
				oci_rollback($db_conn);
				$alertMessage = "Error: " . addslashes($e->getMessage());
			}
		}

		echo "
		<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
		<script>
			var message = '" . addslashes($alertMessage) . "';
			if (message) {
				Swal.fire({
					title: message.includes('Error') ? 'Error!' : 'Success!',
					text: message,
					icon: message.includes('Error') ? 'error' : 'success'
				});
			}
		</script>";
	}

	function handleResetRequest()
	{
		global $db_conn;
		$sqlfile = file_get_contents('schema.sql');
		$sqlstatements = explode(";", $sqlfile);

		foreach ($sqlstatements as $query) {
			$query = trim($query);
			if (!empty($query)) {
				executePlainSQL($query);
			}
		}
		
		oci_commit($db_conn);
		echo "<br> Database Reset <br>";
	}

	// INSERTION: inserts a new shelter pet entry
	function handleInsertRequest()
	{
		global $db_conn;
		$alertMessage = "";

		if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insertSubmit'])) {
			try {
				// Getting the values from user and insert data into the table
				$tuple = array(
					":bind1" => $_POST['insPid'],
					":bind2" => $_POST['insPname'],
					":bind3" => $_POST['insBreed'],
					":bind4" => $_POST['insSpecies'],
					":bind5" => $_POST['insRecordNum'],
				);

				$recordNum = array(":bind5" => $_POST['insRecordNum']);
				$pid = array(":bind1" => $_POST['insPid']);
				$alltuples = array($tuple);

				
				// if given recordNum doesn't exist in hasMedicalRecord,
				// create a new tuple with it in hasMedicalRecord
				$dataInHasMedicalRecord = executeBoundSQL("SELECT recordNum FROM hasMedicalRecord WHERE recordNum = :bind5", array($recordNum));
				if (!OCI_Fetch_Array($dataInHasMedicalRecord, OCI_ASSOC)) {
					$insertMedicalRecord = executeBoundSQL("INSERT INTO hasMedicalRecord VALUES (:bind5, 'N/A', 'N/A')", array($recordNum));
				} 

				$insertPetResult = executeBoundSQL("INSERT INTO Pet VALUES (:bind1, :bind2, :bind3, :bind4, :bind5)", $alltuples);
				if (!$insertPetResult) {
					$e = OCI_Error($db_conn);
					throw new Exception("Invalid Pet ID");
				}

				$insertShelterPetResult = executeBoundSQL("INSERT INTO shelterPet VALUES (:bind1)", array($pid));
				oci_commit($db_conn);
				$alertMessage = "Pet added successfully!";
			
			} catch (Exception $e) {
				oci_rollback($db_conn);
				$alertMessage = "Error: " . addslashes($e->getMessage());
			}
		}

		echo "
		<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
		<script>
			var message = '" . addslashes($alertMessage) . "';
			if (message) {
				Swal.fire({
					title: message.includes('Error') ? 'Error!' : 'Success!',
					text: message,
					icon: message.includes('Error') ? 'error' : 'success'
				});
			}
		</script>";
	}

	// DELETTION: deletes a pet entry by pid
	function handleDeleteRequest()
	{
		global $db_conn;
		$alertMessage = "";

		if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteSubmit'])) {
			try {
				$petID = $_POST['delPid'];
				if (empty($petID)) {
					throw new Exception("Pet ID cannot be blank");
				}

				$tuple = array(":bind1" => $_POST['delPid']);
				$alltuples = array($tuple);

				// check if Pet ID exists
				$recordNumQuery = executeBoundSQL("SELECT recordNum FROM Pet WHERE pid = :bind1", $alltuples);
				$recordRow = OCI_Fetch_Array($recordNumQuery, OCI_ASSOC);

				if (!$recordRow || empty($recordRow["RECORDNUM"])) {
					throw new Exception("Pet ID does not exist.");
				}
				
				// delete pet's record in hasMedicalRecord, then delete the pet
				executePlainSQL("DELETE FROM hasMedicalRecord WHERE recordNum='" . $recordRow["RECORDNUM"] . "'");
				executeBoundSQL("DELETE FROM Pet WHERE pid = :bind1", $alltuples);
				oci_commit($db_conn);
				$alertMessage = "Pet entry deleted successfully!";

			} catch (Exception $e) {
				oci_rollback($db_conn);
				$alertMessage = "Error: " . addslashes($e->getMessage());
			}
		}

		echo "
		<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
		<script>
			var message = '" . addslashes($alertMessage) . "';
			if (message) {
				Swal.fire({
					title: message.includes('Error') ? 'Error!' : 'Success!',
					text: message,
					icon: message.includes('Error') ? 'error' : 'success'
				});
			}
		</script>";
	}

	// SELECTION: select Adopter's certain attribute with condition
	function handleSelectionRequest()
	{
		global $db_conn;

		$conditions = []; // stores conditions of WHERE clause with placeholders for the values (user inputs)
		$params = []; // stores user inputs, which will be bound to the placeholders in the conditions array
		$i = 1;
		$query = "SELECT * FROM Adopter WHERE ";

		while (isset($_GET["attr$i"]) && isset($_GET["cond$i"])) {
			$attr = $_GET["attr$i"];
			$cond = $_GET["cond$i"];

			if (isset($_GET["logic$i"])) {
				$conditions[] = $_GET["logic$i"];
			}

			$conditions[] = "$attr=:attr$i";
			$params[":attr$i"] = $cond;

			$i++;
		}
		
		$query .= implode(" ", $conditions);

		$result = executeBoundSQL($query, array($params));
		printResult($result, "Adopter");
	}

	// PROJECTION: View Foster Parent Information with selected attributes to view
	function handleProjectionRequest() 
	{
		if (!empty($_GET['attributes'])) {
			global $db_conn;
			$selectedAttributes = $_GET['attributes'];
			$columns = implode(", ", array_map('strtoupper', $selectedAttributes));

			$query = "SELECT $columns FROM fosterParent";
			$stmt = oci_parse($db_conn, $query);
			oci_execute($stmt);

			echo "<h2>Foster Parent</h2>";
			echo "<table border = '1'><tr>";

			foreach ($selectedAttributes as $attribute) {
				echo "<th>$attribute</th>";
			}
			echo "</tr>";

			while ($row = oci_fetch_assoc($stmt)) {
				echo "<tr>";
				foreach ($selectedAttributes as $attribute) {
					$column = strtoupper($attribute);
					echo "<td>" . htmlspecialchars($row[$column]) . "</td>";
				}
				echo "</tr>";
			}

			echo "</table>";
			oci_free_statement($stmt);
		} else {
			echo "
			<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
			<script>
				Swal.fire({
					title: 'Error!',
					text: 'Please select at least one attribute.',
					icon: 'error'
				});
			</script>";
		}
	}

	// DIVISION: Find Donors Who Have Donated to All Shelters
	function handleDivisionRequest() {
		global $db_conn;

		$result = executePlainSQL("SELECT * FROM Donor D WHERE NOT EXISTS 
									((SELECT S.shAddress FROM Shelter S)
										MINUS
										(SELECT Ds.shAddress
											FROM Donates Ds
											WHERE Ds.did=D.did))");
		printResult($result, "Donor");
	}
	// NESTED GROUP BY with aggregation: All Shelters with an Average Donation Amount Above the Overall Average
	function handleNestedAggGroupby() {
		global $db_conn;

		$result = executePlainSQL("SELECT D.shAddress, AVG(D.donationAmount) as avgDonation
									FROM Donates D
									GROUP BY D.shAddress
									HAVING AVG(D.donationAmount) > (SELECT AVG(D2.donationAmount)
																	FROM Donates D2)");
		printResult($result, "Shelters and their Average Donations");
	}

	// Displays all data
	function handleDisplayRequest()
	{
		global $db_conn;
		$hasMedicalRecord = executePlainSQL("SELECT * FROM hasMedicalRecord");
		$pet = executePlainSQL("SELECT * FROM Pet");
		$shelterPet = executePlainSQL("SELECT * FROM shelterPet");
		$adoptedPet = executePlainSQL("SELECT * FROM adoptedPet");
		$adopter = executePlainSQL("SELECT * FROM Adopter");
		$fosterParent = executePlainSQL("SELECT * FROM fosterParent");
		$donor = executePlainSQL("SELECT * FROM Donor");
		$donates = executePlainSQL("SELECT * FROM Donates");
		$shelter = executePlainSQL("SELECT * FROM Shelter");

		printResult($hasMedicalRecord, "hasMedicalRecord");
		printResult($pet, "Pet");
		printResult($shelterPet, "shelterPet");
		printResult($adoptedPet, "adoptedPet");
		printResult($adopter, "Adopter");
		printResult($fosterParent, "fosterParent");
		printResult($donor, "Donor");
		printResult($donates, "Donates");
		printResult($shelter, "Shelter");
	}

	// HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
	function handlePOSTRequest()
	{
		if (connectToDB()) {
			if (array_key_exists('resetTablesRequest', $_POST)) {
				handleResetRequest();
			} else if (array_key_exists('updateQueryRequest', $_POST)) {
				handleUpdateRequest();
			} else if (array_key_exists('insertQueryRequest', $_POST)) {
				handleInsertRequest();
			} else if (array_key_exists('deleteQueryRequest', $_POST)) {
				handleDeleteRequest();
			} else if (array_key_exists('selectionQueryRequest', $_POST)) {
				handleSelectionRequest();
			}

			disconnectFromDB();
		}
	}

	// HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
	function handleGETRequest()
	{
		if (connectToDB()) {
			if (array_key_exists('displayTuples', $_GET)) {
				handleDisplayRequest();
			} elseif (array_key_exists('selectionSubmit', $_GET)) {
				handleSelectionRequest();
			} elseif (array_key_exists('projectionSubmit', $_GET)) {
				handleProjectionRequest();
			} elseif (array_key_exists('shelterMedicalJoinSubmit', $_GET)) {
				handleShelterJoinRequest();
			} elseif (array_key_exists('divisionSubmit', $_GET)) {
				handleDivisionRequest();
			} elseif (array_key_exists('avgDonationAggSubmit', $_GET)) {
				handleAvgDonationAggRequest();
			} elseif (array_key_exists('shelterDonationHavingSubmit', $_GET)) {
				handleShelterDonationHavingRequest();
			} elseif (array_key_exists('nestedAggGroupbySubmit', $_GET)) {
				handleNestedAggGroupby();
			}

			disconnectFromDB();
		}
	}

	if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit']) || isset($_POST['deleteSubmit'])) {
		handlePOSTRequest();
	} else if (
			isset($_GET['displayTuplesRequest']) || 
			isset($_GET["selectionSubmit"]) || 
			isset($_GET["projectionSubmit"]) || 
			isset($_GET['divisionSubmit']) || 
			isset($_GET['shelterMedicalJoinSubmit']) || 
			isset($_GET['avgDonationAggSubmit']) ||
			isset($_GET['shelterDonationHavingSubmit']) ||
			isset($_GET['nestedAggGroupbySubmit'])
		) {
		handleGETRequest();
	}


	 // JOIN: Filter Pets by either pid, name, breed, species to find medical information
	 function handleShelterJoinRequest() {
		global $db_conn;
	
		if (!isset($_GET['joinAttr']) || !isset($_GET['joinValue']) || trim($_GET['joinValue']) === "") {
			echo "
			<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
			<script>
				Swal.fire({
					title: 'Error!',
					text: 'Please select an attribute and provide a value.',
					icon: 'error'
				});
			</script>";
			return;
		}
		
		$attr = strtoupper($_GET['joinAttr']);
	    $value = trim($_GET['joinValue']);
		$validAttrs = ["PID", "PNAME", "BREED", "SPECIES"];

		if (!in_array($attr, $validAttrs)) {
			echo "
			<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
			<script>
				Swal.fire({
					title: 'Error!',
					text: 'Invalid attribute selected.',
					icon: 'error'
				});
			</script>";
			return;
		}
	
		$bindVar = ":bindVal";
		$query = "
			SELECT P.pName, P.breed, P.species, H.medicalConditions, H.vaccineHistory
			FROM Pet P
			JOIN hasMedicalRecord H ON P.recordNum = H.recordNum
			JOIN shelterPet S ON P.pid = S.pid
			WHERE P.$attr = $bindVar
		";

		$params = [$bindVar => $value];
		$result = executeBoundSQL($query, [$params]);

		if ($result && OCI_Fetch_Array($result, OCI_ASSOC)) {
			oci_execute($result);
			printResult($result, "shelterMedicalJoin");
			echo "
			<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
			<script>
				Swal.fire({
					title: 'Success!',
					text: 'Medical info retrieved successfully!',
					icon: 'success'
				});
			</script>";	
		} else {
			echo "
			<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
			<script>
				Swal.fire({
					title: 'No Results',
					text: 'No medical info found for the entered value.',
					icon: 'info'
				});
			</script>";
		}
	}
	
	// GROUP BY aggregation: get average donation amount by donation type
	function handleAvgDonationAggRequest() {
		global $db_conn;
	
		$query = "
			SELECT D.donationType, AVG(DS.donationAmount) AS avgAmount
			FROM Donor D
			JOIN Donates DS ON D.did = DS.did
			GROUP BY D.donationType
			ORDER BY D.donationType
		";

		$result = executePlainSQL($query);
		printResult($result, "AvgDonationAgg");
	}

	// HAVING with aggregation: Shelters With Donations Over X
	function handleShelterDonationHavingRequest() {
		global $db_conn;
	
		if (isset($_GET['minDonationAmount']) && is_numeric($_GET['minDonationAmount'])) {
			$min = $_GET['minDonationAmount'];
	
			$query = "
				SELECT S.shName, S.shAddress, SUM(Ds.donationAmount) AS totalDonated
				FROM Shelter S
				JOIN Donates Ds ON S.shAddress = Ds.shAddress
				GROUP BY S.shName, S.shAddress
				HAVING SUM(Ds.donationAmount) > $min
			";
	
			$result = executePlainSQL($query);
			
			if ($result && oci_fetch_array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
				oci_execute($result); 
				printResult($result, "ShelterHaving");

				$alertMessage = "Query completed successfully!";
				$alertType = "success";
			} else {
				$alertMessage = "No shelters found with donation amounts over $min.";
				$alertType = "error";
			}
		} else {
			$alertMessage = "Error: Please enter a valid numeric amount.";
			$alertType = "error";
		}

		echo "
		<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
		<script>
			var message = '" . addslashes($alertMessage) . "';
			var type = '$alertType';
			if (message) {
				Swal.fire({
					title: type === 'error' ? 'Error!' : 'Success!',
					text: message,
					icon: type
				});
			}
		</script>";
	}
	?>
</body>

</html>
