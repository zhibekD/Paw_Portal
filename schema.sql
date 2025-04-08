---------------------------------------------- Drop Tables -------------------------------------------------

DROP TABLE demoTable CASCADE CONSTRAINTS;
DROP TABLE adopt CASCADE CONSTRAINTS;
DROP TABLE fosters CASCADE CONSTRAINTS;
DROP TABLE performedMedicalTest CASCADE CONSTRAINTS;
DROP TABLE WorksAt CASCADE CONSTRAINTS;
DROP TABLE Donates CASCADE CONSTRAINTS;
DROP TABLE shelterPet CASCADE CONSTRAINTS;
DROP TABLE adoptedPet CASCADE CONSTRAINTS;
DROP TABLE Pet CASCADE CONSTRAINTS;
DROP TABLE fosterParent CASCADE CONSTRAINTS;
DROP TABLE fosterAssignment CASCADE CONSTRAINTS;
DROP TABLE Shelter CASCADE CONSTRAINTS;
DROP TABLE Staff CASCADE CONSTRAINTS;
DROP TABLE AdoptionApplication CASCADE CONSTRAINTS;
DROP TABLE Donor CASCADE CONSTRAINTS;
DROP TABLE Adopter CASCADE CONSTRAINTS;
DROP TABLE hasMedicalRecord CASCADE CONSTRAINTS;
DROP TABLE Vet CASCADE CONSTRAINTS;
DROP TABLE belongsTo CASCADE CONSTRAINTS;

---------------------------------------------- Create Tables ------------------------------------------------

CREATE TABLE demoTable (
    id int PRIMARY KEY, 
    name VARCHAR(30)
);

CREATE TABLE hasMedicalRecord (
    recordNum VARCHAR(20) PRIMARY KEY,
    medicalConditions VARCHAR(40),
    vaccineHistory VARCHAR(40)
);

CREATE TABLE Adopter (
    aid VARCHAR(20) PRIMARY KEY,
    aName VARCHAR(20) NOT NULL,
    address VARCHAR(20),
    phoneNum VARCHAR(20) NOT NULL,
    numOfChildren INTEGER,
    UNIQUE(phoneNum)
);

CREATE TABLE fosterParent (
    fid VARCHAR(20) PRIMARY KEY,
    fName VARCHAR(20) NOT NULL,
    fAddress VARCHAR(40) NOT NULL,
    phoneNum VARCHAR(20) UNIQUE NOT NULL,
    experienceLevel VARCHAR(20)
);

CREATE TABLE fosterAssignment(
    faid VARCHAR(20) PRIMARY KEY,
    startDate VARCHAR(20) not NULL,
    endDate VARCHAR(20) not NULL
);

CREATE TABLE Pet (
    pid VARCHAR(20) PRIMARY KEY,
    pName VARCHAR(20) NOT NULL,
    breed VARCHAR(20),
    species VARCHAR(20),
    recordNum VARCHAR(20),
    FOREIGN KEY(recordNum) REFERENCES hasMedicalRecord(recordNum) 
        ON DELETE CASCADE
);

CREATE TABLE shelterPet (
    pid VARCHAR(20) PRIMARY KEY,
    FOREIGN KEY(pid) REFERENCES Pet(pid)
        ON DELETE CASCADE
);

CREATE TABLE adoptedPet (
    pid VARCHAR(20) PRIMARY KEY,
    aid VARCHAR(20),
    FOREIGN KEY(pid) REFERENCES Pet(pid)
        ON DELETE CASCADE,
    FOREIGN KEY(aid) REFERENCES Adopter(aid)
        ON DELETE CASCADE
);

CREATE TABLE fosters(
    faid VARCHAR(20) PRIMARY KEY,
    pid VARCHAR(20),
    fid VARCHAR(20),
    FOREIGN KEY (faid) REFERENCES fosterAssignment(faid)
        ON DELETE CASCADE ,
    FOREIGN KEY (pid) REFERENCES shelterPet(pid)
        ON DELETE CASCADE ,
    FOREIGN KEY (fid) REFERENCES fosterParent(fid)
        ON DELETE CASCADE 
        
);

CREATE TABLE AdoptionApplication (
    aaid VARCHAR(20) PRIMARY KEY,
    adoptionFee INTEGER,
    adoptionDate VARCHAR(20)
);

CREATE TABLE adopt(
    aaid VARCHAR(20) PRIMARY KEY,
    pid VARCHAR(20),
    aid VARCHAR(20),
    FOREIGN KEY (aaid) REFERENCES AdoptionApplication(aaid)
        ON DELETE CASCADE,
    FOREIGN KEY (pid) REFERENCES AdoptedPet(pid)
        ON DELETE CASCADE,
    UNIQUE (pid),
    FOREIGN KEY (aid) REFERENCES Adopter(aid)
        ON DELETE CASCADE
        
);

CREATE TABLE Shelter (
    shAddress VARCHAR(20) PRIMARY KEY,
    shName VARCHAR(40) NOT NULL
);

CREATE TABLE Staff (
    eid VARCHAR(20) PRIMARY KEY,
    hourlyWage INTEGER,
    position VARCHAR(40)
);
            
CREATE TABLE Donor (
    did VARCHAR(20) PRIMARY KEY,
    dName VARCHAR(40),
    donationType VARCHAR(20)
);

CREATE TABLE Vet (
    vid VARCHAR(20) PRIMARY KEY,
    vName VARCHAR(20) not NULL,
    licenseExpiryDate VARCHAR(20) not NULL
);

CREATE TABLE performedMedicalTest(
    vid VARCHAR(20),
    recordNum VARCHAR(20),
    testType VARCHAR(20),
    testResult VARCHAR(20),
    PRIMARY KEY (vid, recordNum),
    FOREIGN KEY (vid) REFERENCES Vet(vid)
        ON DELETE CASCADE,
    FOREIGN KEY (recordNum) REFERENCES hasMedicalRecord(recordNum)
        ON DELETE CASCADE
        
);

CREATE TABLE WorksAt (
    eid VARCHAR(20),
    shAddress VARCHAR(20),
    PRIMARY KEY (eid, shAddress),
    FOREIGN KEY (eid) REFERENCES Staff(eid)
        ON DELETE CASCADE,
    FOREIGN KEY (shAddress) REFERENCES Shelter(shAddress)
        ON DELETE CASCADE
);

CREATE TABLE Donates (
    shAddress VARCHAR(40),
    did VARCHAR(20),
    donationAmount INTEGER,
    PRIMARY KEY (shAddress, did),
    FOREIGN KEY(shAddress) REFERENCES Shelter(shAddress)
        ON DELETE CASCADE,
    FOREIGN KEY(did) REFERENCES Donor(did)
        ON DELETE CASCADE
);

CREATE TABLE belongsTo (
    pid VARCHAR(20),
    shAddress VARCHAR(20)
);

---------------------------------------------- Insertions -----------------------------------------------------

INSERT INTO hasMedicalRecord VALUES ('1', 'no medical conditions', 'vaccinated');
INSERT INTO hasMedicalRecord VALUES ('2', 'influenza', 'vaccinated');
INSERT INTO hasMedicalRecord VALUES ('3', 'Arthritis', 'not vaccinated');
INSERT INTO hasMedicalRecord VALUES ('4', 'Hypothyroidism', 'not vaccinated');
INSERT INTO hasMedicalRecord VALUES ('5', 'Cancer', 'vaccinated');
INSERT INTO hasMedicalRecord VALUES ('6','Skin Infection','vaccinated');
INSERT INTO hasMedicalRecord VALUES ('7', 'no medical conditions', 'vaccinated');
INSERT INTO hasMedicalRecord VALUES ('8', 'influenza', 'vaccinated');
INSERT INTO hasMedicalRecord VALUES ('9', 'Arthritis', 'not vaccinated');
INSERT INTO hasMedicalRecord VALUES ('10', 'no medical conditions', 'vaccinated');

INSERT INTO Pet VALUES ('SP1000', 'Buddy', 'Golden Retriever', 'Dog', '1');
INSERT INTO Pet VALUES ('SP1001', 'Cleo', 'Maine Coon', 'Cat', '2');
INSERT INTO Pet VALUES ('SP1002', 'Princess', 'Poodle', 'Dog', '3');
INSERT INTO Pet VALUES ('SP1003', 'Feathers', 'Parrot', 'Bird', '4');
INSERT INTO Pet VALUES ('SP1004', 'Sunny', 'Labrador', 'Dog', '5');
INSERT INTO Pet VALUES ('AP1000', 'Max', 'British Shorthair', 'Cat', '6');
INSERT INTO Pet VALUES ('AP1001', 'Mia', 'Corgi', 'Dog', '7');
INSERT INTO Pet VALUES ('AP1002', 'Angel', 'DC Hamster', 'Hamster', '8');
INSERT INTO Pet VALUES ('AP1003', 'Teddy', 'Teddy Guinea Pig', 'Guinea Pig', '9');
INSERT INTO Pet VALUES ('AP1004', 'Misty', 'Painted Turtle', 'Turtle', '10');

INSERT INTO shelterPet VALUES ('SP1000');
INSERT INTO shelterPet VALUES ('SP1001');
INSERT INTO shelterPet VALUES ('SP1002');
INSERT INTO shelterPet VALUES ('SP1003');
INSERT INTO shelterPet VALUES ('SP1004');

INSERT INTO AdoptionApplication VALUES ('AA1', 300, '2021-10-01');
INSERT INTO AdoptionApplication VALUES ('AA2', 250, '2021-04-22');
INSERT INTO AdoptionApplication VALUES ('AA3', 100, '2019-03-13');
INSERT INTO AdoptionApplication VALUES ('AA4', 125, '2020-12-01');
INSERT INTO AdoptionApplication VALUES ('AA5', 200, '2025-01-12');

INSERT INTO Adopter VALUES ('12345', 'Seva', '1236 University Blvd', 123684569, 1);
INSERT INTO Adopter VALUES ('32594', 'Gregor', '3853 Granville St', 186492345, 3);
INSERT INTO Adopter VALUES ('65894', 'Jordon', '1234 West Mall', 15569878, 1);
INSERT INTO Adopter VALUES ('64892', 'Paul', '6543 Lower Mall', 12369656532, 0);
INSERT INTO Adopter VALUES ('54681', 'Andy', '4738 East Mall', 12368846325, 0);
INSERT INTO Adopter VALUES ('A1000','Beth','1234 W 8th Ave','604 123 4567',1);
INSERT INTO Adopter VALUES ('A1001','Adam','3402 E 10th Ave','778 382 1820',4);
INSERT INTO Adopter VALUES ('A1002','Primrose','9281 E 20th Ave','778 231 2381',4);
INSERT INTO Adopter VALUES ('A1003','Lily','1928 Renfrew St','604 192 1029',3);
INSERT INTO Adopter VALUES ('A1004','Mike','2813 Dunbar St','778 291 0001',1);

INSERT INTO adoptedPet VALUES ('AP1000', 'A1000');
INSERT INTO adoptedPet VALUES ('AP1001', 'A1001');
INSERT INTO adoptedPet VALUES ('AP1002', 'A1002');
INSERT INTO adoptedPet VALUES ('AP1003', 'A1003');
INSERT INTO adoptedPet VALUES ('AP1004', 'A1004');

INSERT INTO adopt VALUES ('AA1', 'AP1000', '12345');
INSERT INTO adopt VALUES ('AA2', 'AP1001', '32594');
INSERT INTO adopt VALUES ('AA3', 'AP1002', '65894');
INSERT INTO adopt VALUES ('AA4', 'AP1003', '64892');
INSERT INTO adopt VALUES ('AA5', 'AP1004', '54681');

INSERT INTO fosterParent VALUES ('FP1', 'Ash', '1234 Pallet Town', '778 109 1480', 'Beginner');
INSERT INTO fosterParent VALUES ('FP2', 'Dawn', '5801 Twinleaf Town', '604 103 5910', 'Beginner');
INSERT INTO fosterParent VALUES ('FP3', 'Brock', '6803 Pewter City', '802 684 0023', 'Expert');
INSERT INTO fosterParent VALUES ('FP4', 'Serena', '5123 Vaniville Town', '556 402 4950', 'Intermediate');
INSERT INTO fosterParent VALUES ('FP5', 'Clement', '145 0395 Lumiose City', '019 589 1289', 'Expert');

INSERT INTO fosterAssignment VALUES ('FA1', '2021-12-22', '2022-01-01');
INSERT INTO fosterAssignment VALUES ('FA2', '2021-12-12', '2022-03-01');
INSERT INTO fosterAssignment VALUES ('FA3', '2023-01-31', '2023-05-01');
INSERT INTO fosterAssignment VALUES ('FA4', '2024-02-14', '2024-02-28');
INSERT INTO fosterAssignment VALUES ('FA5', '2025-10-01', '2023-12-29');

INSERT INTO fosters VALUES ('FA1', 'SP1000', 'FP1');
INSERT INTO fosters VALUES ('FA2', 'SP1001', 'FP2');
INSERT INTO fosters VALUES ('FA3', 'SP1002', 'FP3');
INSERT INTO fosters VALUES ('FA4', 'SP1003', 'FP4');
INSERT INTO fosters VALUES ('FA5', 'SP1004', 'FP5');

INSERT INTO Shelter VALUES ('1205 E 7th Ave', 'BC SPCA Vancouver');
INSERT INTO Shelter VALUES ('420 Boyne St','New Westminster Animal Shelter');
INSERT INTO Shelter VALUES ('5216 Glencarin Dr','Wildlife Rescue Association of BC');
INSERT INTO Shelter VALUES ('500 Mariner Wy','Coquitlam Animal Shelter');
INSERT INTO Shelter VALUES ('5903 Vermillion City', 'Happy Furry Friends');
INSERT INTO Shelter VALUES ('9054 Canalave City', 'Angry Spiky Enemies');
INSERT INTO Shelter VALUES ('6965 Shalour City', 'Mild Hairless Animals');
INSERT INTO Shelter VALUES ('2252 Snowpoint City', 'Icy Soft Beasts');
INSERT INTO Shelter VALUES ('0059 Oreburgh City', 'Fiery Rough Cuties ');

INSERT INTO Staff VALUES ('S1', '25', 'Receptionist');
INSERT INTO Staff VALUES ('S2', '32', 'Animal Shelter Welfare Coordinator');
INSERT INTO Staff VALUES ('S3', '32', 'Animal Care Attendant');
INSERT INTO Staff VALUES ('S4', '34', 'Animal Shelter Welfare Coordinator');
INSERT INTO Staff VALUES ('S5', '32', 'Shelter Attendant');

INSERT INTO Donor VALUES ('12345', 'Raymond', 'individual');
INSERT INTO Donor VALUES ('45678', 'Jibek', 'individual');
INSERT INTO Donor VALUES ('98745', 'Cathy', 'individual');
INSERT INTO Donor VALUES ('65412', 'Leo', 'individual');
INSERT INTO Donor VALUES ('12396', 'UBC', 'organization');
INSERT INTO Donor VALUES ('D1','Annie','Individual');
INSERT INTO Donor VALUES ('D2','CTV','Organization');
INSERT INTO Donor VALUES ('D3','Pacific Coastal Airlines','Organization');
INSERT INTO Donor VALUES ('D4','Peter','Individual');
INSERT INTO Donor VALUES ('D5','ROYALE','Organization');

INSERT INTO Donates VALUES ('1205 E 7th Ave','D1',1500);
INSERT INTO Donates VALUES ('5903 Vermillion City','D2',2000);
INSERT INTO Donates VALUES ('500 Mariner Wy','D2',2000);
INSERT INTO Donates VALUES ('9054 Canalave City','D2',3000);
INSERT INTO Donates VALUES ('6965 Shalour City','D2',2100);
INSERT INTO Donates VALUES ('1205 E 7th Ave','D2',2100);
INSERT INTO Donates VALUES ('420 Boyne St','D2',1250);
INSERT INTO Donates VALUES ('5216 Glencarin Dr','D2',1500);
INSERT INTO Donates VALUES ('2252 Snowpoint City','D2',2100);
INSERT INTO Donates VALUES ('0059 Oreburgh City','D2',1750);
INSERT INTO Donates VALUES ('5216 Glencarin Dr','D3',1700);
INSERT INTO Donates VALUES ('500 Mariner Wy','D4',1500);
INSERT INTO Donates VALUES ('5216 Glencarin Dr','D5',300);

INSERT INTO Vet VALUES ('V1345', 'Abby', '2025-03-12');
INSERT INTO Vet VALUES ('V1346', 'Michael', '2027-12-31');
INSERT INTO Vet VALUES ('V1928', 'Anna', '2026-04-12');
INSERT INTO Vet VALUES ('V2837', 'Sam', '2025-06-23');
INSERT INTO Vet VALUES ('V8273', 'Jim', '2027-01-02');

INSERT INTO performedMedicalTest VALUES ('V1345', '1', 'blood test', 'within normal range');
INSERT INTO performedMedicalTest VALUES ('V1346', '2', 'blood test', 'within normal range');
INSERT INTO performedMedicalTest VALUES ('V1928', '3', 'X-ray', 'tumour');
INSERT INTO performedMedicalTest VALUES ('V2837', '4', 'X-ray', 'swallowed object');
INSERT INTO performedMedicalTest VALUES ('V8273', '5', 'CT scan', 'testing required');

INSERT INTO WorksAt VALUES ('S1', '1205 E 7th Ave');
INSERT INTO WorksAt VALUES ('S2', '9054 Canalave City');
INSERT INTO WorksAt VALUES ('S3', '6965 Shalour City');
INSERT INTO WorksAt VALUES ('S4', '0059 Oreburgh City');
INSERT INTO WorksAt VALUES ('S5', '420 Boyne St');

INSERT INTO belongsTo VALUES ('SP1000', '5903 Vermillion City');
INSERT INTO belongsTo VALUES ('SP1001', '2252 Snowpoint City');
INSERT INTO belongsTo VALUES ('SP1002', '2252 Snowpoint City');
INSERT INTO belongsTo VALUES ('AP1000', '0059 Oreburgh City');
INSERT INTO belongsTo VALUES ('AP1001', '6965 Shalour City');

