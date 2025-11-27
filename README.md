# 📘 IT Services Office Equipment Management System

*Final Project for Web System Technologies (IT0049)*

## 🧾 Overview

The **IT Services Office Equipment Management System** is a web-based platform developed using **CodeIgniter 4** to streamline the management of school equipment, including borrowing, returning, reservations, and user account management.
This system ensures efficient tracking, secure access, and transparent reporting for the ITSO.

---

## 🚀 Key Features / System Modules

### **1. User Management Module**

* User registration with email verification
* View, edit, and deactivate user accounts
* Three user categories:

  * **ITSO Personnel** – Full system access
  * **Associates** – Can borrow and reserve equipment
  * **Students** – Can borrow equipment
* ITSO Personnel are the only users allowed to access the system dashboard

### **2. Login & Password Reset Module**

* Secure login with validation
* Password reset via email
* Supports dummy emails for testing

### **3. Equipment Management Module**

* Add, view, edit, deactivate equipment
* Each item has:

  * Unique equipment ID
  * Item count (to determine availability)
* Equipment categories include (but are not limited to):

  * Laptops + chargers
  * DLP projectors + cables and remote
  * VGA/HDMI cables
  * Keyboards & mouse (Mac lab)
  * Wacom tablets + pen
  * Speakers, webcams, extension cords
  * Crimping tools, cable testers
  * Lab room keys

### **4. Borrowing Module**

* Borrowing process for Associates and Students
* Equipment automatically includes relevant accessories
* Sends email notifications upon borrowing

### **5. Return Module**

* Handles equipment returns
* Sends email notifications upon return
* Ensures all accessories are accounted for

### **6. Reservation Module**

* Associates can reserve equipment at least **1 day before the event**
* Reservations can be **cancelled** or **rescheduled**
* Equipment availability validation included

### **7. Reports Module**

Generates:

* Active equipment list
* Unusable equipment report
* Borrowing history per user

### **8. About Page**

* Displays group name and member details

---

## 🛠️ Technologies Used

* **CodeIgniter 4** (PHP Framework)
* **Bootstrap / CSS Framework**
* **MySQL / MariaDB** (or any chosen database engine)
* **PHPMailer** or equivalent (for email notifications)

---

## 📂 Project Structure (Simplified)

```
/app
  /Controllers
  /Models
  /Views
/public
  /uploads
  /thumbnails
  index.php
/writable
```

---

## 🧪 Installation & Setup

1. **Clone or download** the repository
2. Install dependencies:

   ```bash
   composer install
   ```
3. Copy `.env.example` → `.env` and configure:

   * Database name
   * Email credentials
   * Base URL
4. Run migrations (if applicable):

   ```bash
   php spark migrate
   ```
5. Start the local server:

   ```bash
   php spark serve
   ```

---

## 👥 Group Information

**Project Title:** IT Services Office Equipment Management System
**Group Name:** *AXION*
**Members:**

* Gagan, Alexa Mhel V.
* Gonzales, Michaela Marie V.
* Oxina, Shane D.


---

## 📄 License

This project is created for academic purposes only.