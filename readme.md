# PHP OOP Refactor - School Encoding Module

## Default Admin Credentials
- Username: admin
- Password: admin12345

## Project Folder Structure
/project-folder
- /app
  - /Core
    - Database.php
    - Auth.php
    - SessionManager.php
  - /Models
    - User.php
    - Subject.php
    - Program.php
  - /Helpers
    - FlashMessage.php
    - Hash.php
- /config
  - config.php
- /public
  - login.php
  - logout.php
  - home.php
  - users_list.php
  - users_new.php
  - users_edit.php
  - subject_list.php
  - subject_new.php
  - subject_edit.php
  - program_list.php
  - program_new.php
  - program_edit.php
  - change_password.php
- school.sql

## Class Explanations

### Database
Handles the MySQL database connection using mysqli and configuration constants from `config/config.php`.

### SessionManager
Handles session operations such as starting a session, setting values, getting values, checking existing keys, removing values, and destroying the session.

### Auth
Handles authentication and authorization logic including login, logout, checking whether the user is logged in, checking admin access, checking admin or staff access, and getting the current logged-in user.

### User
Handles user-related database operations such as creating users, updating users, checking if a username exists, listing users, retrieving a user by ID or username, and updating a user's password.

### Subject
Handles subject-related database operations such as creating subjects, updating subjects, checking if a subject code exists, listing subjects, searching subjects, and retrieving a subject by ID.

### Program
Handles program-related database operations such as creating programs, updating programs, checking if a program code exists, listing programs, searching programs, and retrieving a program by ID.

### FlashMessage
Displays session-based flash success and error messages.

### Hash
Handles password hashing and password verification.

## Features Preserved
- Login and logout
- Session-based authentication
- Role-based access control
- Home page
- User management
- Subject management
- Program management
- Change password

## Notes
- The system uses prepared statements for database security.
- Passwords are hashed using PHP password hashing functions.
- Business logic was moved into classes to follow object-oriented programming principles.
- Files were reorganized based on PSR-1 and PSR-4 conventions.