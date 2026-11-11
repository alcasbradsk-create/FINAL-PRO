ISPSC FACULTY PROFILING SYSTEM
Final Project - Faculty Management Module (PHP + MySQL)
=========================================================

PROJECT TITLE
    ISPSC Faculty Profiling System

MEMBERS
    Brandon S. Onga-ong
    Louie Garcia

DATABASE NAME
    users_db

    Tables:
      users    - accounts used for registration and login
      faculty  - faculty records (Faculty Management module)


HOW TO IMPORT THE DATABASE
    1. Open the XAMPP Control Panel and start Apache and MySQL.
    2. Go to http://localhost/phpmyadmin
    3. Click the "Import" tab.
    4. Choose the file  database.sql  (in this project folder) and click "Import".
       This creates the database users_db with the users and faculty tables
       and the sample records.

    (Command line alternative:  mysql -u root -p < database.sql)


HOW TO RUN THE SYSTEM
    Option A - XAMPP
      1. Copy the project folder into  C:\xampp\htdocs\  (for example
         C:\xampp\htdocs\FINAL-PRO).
      2. Start Apache and MySQL in the XAMPP Control Panel.
      3. Open  http://localhost/FINAL-PRO/  in a browser.

    Option B - start_server.bat
      1. Start MySQL in the XAMPP Control Panel.
      2. Double-click  start_server.bat  in the project folder.
      3. Open  http://localhost:8000/  in a browser.

    The database connection is in  config/db.php . It uses the default
    XAMPP settings (host localhost, user root, no password, database users_db).


DEFAULT LOGIN
    Username:  admin
    Password:  admin12345

    (You can also log in with the email admin@example.com, or create a new
    account on the Create Account page.)


FEATURES
    - Registration and login (from the midterm project)
    - Dashboard with the Faculty Management menu
    - Faculty list showing Faculty ID, Full Name, Sex, Email, Department and
      Position, with View, Edit and Delete buttons
    - Add Faculty form with validation and a success message
    - View Faculty page with all details and a Back button
    - Edit Faculty form pre-filled with the current record and a success message
    - Delete with a confirmation (Cancel / Delete) and a success message
    - "No faculty records" message when the table is empty
    - Search and pagination on the faculty list
    - Faculty pages cannot be opened without logging in (redirects to login)
    - Logout


FILES
    index.php            Landing page
    login.php            Login
    register.php         Create account
    logout.php           Logout
    dashboard.php        Dashboard (Faculty Management menu)
    faculty.php          Faculty list + delete
    faculty_form.php     Add / Edit faculty
    faculty_view.php     View faculty details
    config/db.php        Database connection (PDO)
    includes/header.php  Shared header (include)
    includes/footer.php  Shared footer (include)
    includes/auth.php    Login check for protected pages
    includes/bootstrap.php  Session, security and helper functions
    css/style.css        Design / styles
    database.sql         Database export (tables + sample records)


SECURITY NOTES
    - All database queries use PDO prepared statements.
    - Passwords are stored with password_hash() and checked with password_verify().
    - Forms are protected with CSRF tokens; output is escaped with htmlspecialchars().
