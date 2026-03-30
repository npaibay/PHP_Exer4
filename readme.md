# PHP MVC Refactor - School Encoding Module

## Default Admin Credentials
- Username: admin
- Password: admin12345

---

## Overview

This project is a refactored version of the School Encoding Module originally implemented using procedural PHP and later improved using Object-Oriented Programming (OOP).

This version further enhances the system by implementing the **Model-View-Controller (MVC) architecture**, ensuring better separation of concerns, maintainability, and scalability.

---

## MVC Architecture

The system follows a **single-entry point architecture**: public/index.php

All requests are routed through this file using query parameters: index.php?controller=<name>&action=<method>


---

## Project Folder Structure
/project-folder
│
├── /app
│ ├── /Core
│ │ ├── Controller.php
│ │ ├── Database.php
│ │ ├── Auth.php
│ │ └── SessionManager.php
│ │
│ ├── /Controllers
│ │ ├── AuthController.php
│ │ ├── HomeController.php
│ │ ├── ProgramController.php
│ │ ├── SubjectController.php
│ │ └── UserController.php
│ │
│ ├── /Models
│ │ ├── User.php
│ │ ├── Subject.php
│ │ └── Program.php
│ │
│ ├── /Views
│ │ ├── /layouts
│ │ │ ├── header.php
│ │ │ └── footer.php
│ │ │
│ │ ├── /auth
│ │ │ └── login.php
│ │ │
│ │ ├── /home
│ │ │ └── index.php
│ │ │
│ │ ├── /programs
│ │ │ ├── list.php
│ │ │ ├── new.php
│ │ │ └── edit.php
│ │ │
│ │ ├── /subjects
│ │ │ ├── list.php
│ │ │ ├── new.php
│ │ │ └── edit.php
│ │ │
│ │ └── /users
│ │ ├── list.php
│ │ ├── new.php
│ │ ├── edit.php
│ │ └── change_password.php
│ │
│ └── /Helpers
│ ├── FlashMessage.php
│ ├── Hash.php
│ └── Validator.php
│
├── /config
│ └── config.php
│
├── /public
│ └── index.php ← Single Entry Point
│
└── school.sql


---

## MVC Components Explanation

### Controllers
Controllers handle user requests, process input, and coordinate between models and views.

- **AuthController** – Handles login and logout
- **HomeController** – Handles dashboard/home page
- **ProgramController** – Handles program CRUD operations
- **SubjectController** – Handles subject CRUD operations
- **UserController** – Handles user management and password updates

---

### Models
Models handle all database-related logic using prepared statements.

- **User** – User management and authentication data
- **Subject** – Subject data operations
- **Program** – Program data operations

---

### Views
Views handle presentation (UI) and display data passed from controllers.

- Organized by modules (auth, home, programs, subjects, users)
- Layouts (`header.php`, `footer.php`) provide consistent UI structure

---

### Core Classes

#### Controller
Base class that provides:
- View rendering
- Redirect functionality

#### Database
Handles MySQL connection using configuration settings.

#### Auth
Handles:
- Login and logout
- Session validation
- Role-based access control

#### SessionManager
Handles:
- Session start
- Set, get, and remove session values
- Session destruction

---

### Helper Classes

#### FlashMessage
Handles temporary session-based success and error messages.

#### Hash
Handles password hashing and verification.

#### Validator
Handles input validation such as required fields and minimum length.

---

## Features

- Login and logout
- Session-based authentication
- Role-based access control
- Home dashboard
- Program management (CRUD)
- Subject management (CRUD)
- User management (CRUD)
- Change password functionality
- Search and reset functionality

---

## Improvements from OOP Version

- Implemented **MVC architecture**
- Introduced **single entry point (index.php)**
- Separated concerns:
  - Controllers → logic
  - Models → data
  - Views → UI
- Removed direct access to multiple public PHP pages
- Centralized layout using reusable views
- Improved maintainability and scalability

---

## Notes

- Uses **prepared statements** for database security
- Passwords are hashed using PHP `password_hash()`
- Follows **PSR-1 / PSR-4 conventions**
- Old procedural pages in `/public` are deprecated and replaced by MVC routing

---

## How to Run

1. Place the project inside: C:\xampp\htdocs\

2. Start Apache and MySQL in XAMPP

3. Import `school.sql` into phpMyAdmin

4. Open in browser: http://localhost/PHP_Exer4/public/index.php


---

## Summary

This project demonstrates the transition from:
- Procedural PHP → Object-Oriented Programming → MVC Architecture

The final system follows best practices in:
- Code organization
- Separation of concerns
- Maintainability
- Scalability