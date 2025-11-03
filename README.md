# **PawPortal - The Pet Adoption and Shelter Management System**

**PawPortal** is a full-stack database-driven web application that simplifies the operations of animal shelters.  
It provides an organized and efficient system for managing **pet adoptions, fostering, donations, and medical care** — helping shelters match pets with loving homes while maintaining clear and reliable records.

<table align="center" cellspacing="0" cellpadding="0">
<tr><td style="padding:0; margin:0; line-height:0;">
  <img src="https://github.com/user-attachments/assets/90fe479e-e713-4d78-8515-2658823efe54" width="100%">
  <img src="https://github.com/user-attachments/assets/bd7e585e-3db7-476b-bd73-6c72d33064f9" width="100%">
</td></tr>
</table>

> 💻 The live PHP web app is hosted on UBC CS servers and accessible only to enrolled students and instructors.

## 🧩 Overview

**Goal:** To design and implement a complete shelter management system that connects all aspects of pet care — from rescue to adoption — within one cohesive platform.  

The system supports:
- 🐶 Pet registration with breed, species, and medical records  
- 🏠 Adoption and foster management with application tracking  
- 💰 Donation logging and donor information tracking  
- 👩‍⚕️ Veterinarian, medical testing, and vaccination history  
- 👩‍💼 Staff and shelter data management  

It’s designed to be scalable, relational, and user-friendly — enabling real-world shelter operations to be handled in one place.

---
## ⚙️ Features

- **Comprehensive pet database:** Store, update, and track animal details
- **Adoption management:** Handle applications, fees, and adopter details
- **Foster assignments:** Match foster parents with suitable pets
- **Donation tracking:** Record donor contributions to shelters
- **Medical tracking:** Log veterinary tests and vaccination history
- **Data integrity:** Built with strong referential constraints and cascading updates/deletes
- **User feedback:** Clear success and error messages for each operation
- **Interactive UI:** Simple PHP-based web interface styled with CSS
---

## 🧠 Tech Stack

| Layer | Technology |
|-------|-------------|
| **Database** | Oracle SQL |
| **Back-End** | PHP |
| **Front-End** | HTML + CSS |

---
## 💾 Example SQL Query

```sql
SELECT A.aName AS Adopter, P.pName AS Pet, adoptionDate
FROM Adopter A
JOIN Adopt D ON A.aid = D.aid
JOIN Adopted_Pet P ON D.pid = P.pid
ORDER BY adoptionDate DESC;
