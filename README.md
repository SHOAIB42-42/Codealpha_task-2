# EstateHub - Final Year Project 🎓

**Smart Property & Rental Management System**

EstateHub is a comprehensive, modern, and professional web application designed to digitize real estate operations. It streamlines property listings, tenant lease management, advanced billing/invoicing, and provides an AI-styled Smart Chatbot to assist users.

---

## 🌟 Core Features (Phase 1, 2, 3 Completed)

### 1. Advanced Property Management
* Dynamic property listings with gallery sliders.
* Categorization (Residential, Commercial, Industrial, Land).
* Real-time availability status tracking (Available, Occupied, Sold).
* Advanced Search & Filters by City, Rent/Sale, Bedrooms, and Price.

### 2. Complete Tenant Portal
* Secure tenant login and session management.
* Personalized dashboard showing assigned property details.
* Rent payment history with printable PDF receipts.
* Profile settings and password management.

### 3. Advanced Billing & Payment System
* Auto-generated invoices with unique Invoice Numbers.
* Multi-method payment tracking (Cash, Bank Transfer, Credit Card).
* Payment analytics: Pending dues calculation and revenue tracking.
* Export to PDF receipts with professional layouts and stamps.

### 4. AI-Styled Smart Chatbot
* Floating Chatbot Assistant using AJAX for seamless interaction.
* Natural Language Processing: Converts user text to SQL queries.
* Detects keywords for Agents, Property Search, and Rent Status.
* e.g., *"Show 3 bedroom houses for rent"* or *"Check my rent status"*.

### 5. Analytics & Dashboard Reports
* Visualized analytics using **Chart.js**.
* Real-time pie charts and revenue graphs.
* Quick activity logs and notifications system.

### 6. Security & Optimization
* Built securely using **PHP PDO Prepared Statements** (SQL Injection Prevention).
* Relational database with strictly enforced **Foreign Key Constraints** and cascading actions.
* Secure password hashing using `password_hash()`.

---

## 📂 Project Structure

```text
EstateHub/
│
├── admin/          # Admin Dashboard & Management modules
├── ajax/           # Asynchronous backend requests
├── assets/         # CSS, JS (Chatbot logic), Images, Icons
├── chatbot/        # NLP Engine and API for the Assistant
├── config/         # Database connection (PDO)
├── database/       # Schema and initial SQL dumps
├── includes/       # Shared UI components (Header, Footer, Sidebar, Auth)
├── tenant/         # Tenant Portal & Dashboard
├── uploads/        # Uploaded property galleries and profiles
├── login.php       # Unified secure login system
├── logout.php      # Session destroyer
└── estatehub.sql   # Final Database Export
```

---

## 🗄️ Database & ER Diagram Explanation

The database `estatehub_db` is highly normalized (3NF) to avoid redundancy and ensure data integrity.

### Key Entities & Relationships:
1. **users**: Core authentication table. (1-to-1 with `tenants` and `admin`).
2. **property_categories**: Lookup table for property types. (1-to-M with `properties`).
3. **properties**: Stores listing data. (1-to-M with `property_images`).
4. **property_images**: Stores gallery paths.
5. **tenants**: Stores lease data. Links `user_id` and `property_id`. (1-to-M with `payments`).
6. **payments**: Advanced billing table with invoice generation.
7. **activity_logs** & **notifications**: Tracks system events securely.

---

## 🚀 Setup & Installation Guide

1. **Prerequisites:**
   * Install **XAMPP** (PHP 7.4+ and MariaDB/MySQL).
   * Ensure `mod_rewrite` is enabled if needed.

2. **Database Setup:**
   * Open XAMPP Control Panel and start **Apache** and **MySQL**.
   * Go to `http://localhost/phpmyadmin/`.
   * Create a new database named `estatehub_db`.
   * Import the `estatehub.sql` file provided in the root directory.

3. **File Setup:**
   * Extract the project folder `EstateHub` into `C:/xampp/htdocs/`.
   * Open your browser and navigate to `http://localhost/Estatehub/login.php`.

4. **Default Credentials:**
   * **Admin Account:** Username: `admin` | Password: `password`
   * **Tenant Account:** Username: `tenant1` | Password: `password`

---

*Developed for Final Year Database System Project Submission.*