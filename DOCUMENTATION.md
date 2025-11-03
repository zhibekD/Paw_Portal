# 🐾 Pet Adoption & Shelter Management System 
**Duration:** Feb – Apr 2024  


## 📘 Overview
The **Pet Adoption & Shelter Management System** is a relational database project designed to help animal shelters efficiently manage operations such as pet registration, adoptions, fostering, donations, and medical tracking.  

The system allows shelters to:
- Register new animals and track their medical history  
- Record adoption and fostering details  
- Manage donors and donations  
- Maintain staff and veterinary information  
- Ensure data integrity and operational transparency  

This project was implemented using **Oracle SQL**, with additional web-based interactivity developed in **PHP** and **CSS** for a simple user interface.

---

## 🧩 System Design

### 🎯 Domain Description
The application focuses on the **daily workflow of animal shelters** — including registering rescued pets, processing adoption and foster applications, recording donations, and tracking medical histories.  

Example use cases:
- A shelter staff member adds a rescued pet and schedules a veterinary check-up.  
- A vet logs medical test results for that pet.  
- An adopter applies for adoption and pays the fee upon approval.  
- A donor contributes to a shelter, and their donation is tracked in the database.

---

## 🧱 Database Schema

### 📋 Entities
Key entities in the schema include:
- **Pet:** Stores species, breed, name, and associated medical records.  
- **ShelterPet** / **AdoptedPet:** Subsets of pets managed by the shelter or already adopted.  
- **FosterParent:** Information on foster caregivers and their experience level.  
- **Adopter:** Tracks individuals adopting pets, including family information.  
- **Donor:** Records donation types and links them to shelters.  
- **Vet / Staff:** Details for veterinary and administrative personnel.  
- **MedicalRecord & performedMedicalTest:** Capture pet health data and vet-performed procedures.  
- **Shelter:** Represents the physical shelter locations.  

Each table was built with **referential integrity constraints** and **cascading updates/deletes** to preserve data consistency.

You can view the complete schema here:  
📄 [schema.sql](./schema.sql)

---

## 🧮 Normalization
All tables were decomposed to achieve **Boyce–Codd Normal Form (BCNF)**.  
- Redundant dependencies such as `breed → species` were removed.  
- Primary and foreign keys were carefully chosen to preserve dependencies and eliminate anomalies.  
- Each relation now satisfies:  
  - Every determinant is a candidate key.  
  - No transitive dependencies remain.

This ensures the database remains consistent, minimal, and efficient during insertions and deletions.

---

## ⚙️ Implementation Details

### 💾 Back-End (SQL)
Implemented features:
- **CRUD operations:** Insert, update, delete, and selection  
- **JOIN queries:** Combine pet, adopter, and shelter data  
- **Aggregation:** GROUP BY, HAVING, and nested queries  
- **Division queries:** Identify foster parents qualified across multiple species  
- **Error handling:** Rejects duplicate or invalid inserts (through constraint enforcement)

Example query:
```sql
SELECT aName, pName, adoptionDate
FROM Adopter A
JOIN Adopt D ON A.aid = D.aid
JOIN Adopted_P_
