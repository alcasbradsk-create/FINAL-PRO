-- ============================================================
-- ISPSC Faculty Profiling System - database export
-- Database: users_db
--
-- Import once into MySQL (phpMyAdmin > Import, or:
--   mysql -u root -p < database.sql)
-- Includes the users table, the faculty table and sample records.
-- Default test login:  username  admin   password  admin12345
-- ============================================================

CREATE DATABASE IF NOT EXISTS users_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE users_db;

-- ------------------------------------------------------------
-- Table: users (registration and login from the midterm project)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    firstname VARCHAR(100) NOT NULL,
    middlename VARCHAR(100) DEFAULT NULL,
    lastname VARCHAR(100) NOT NULL,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY users_username_unique (username),
    UNIQUE KEY users_email_unique (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Table: faculty (Faculty Management module)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS faculty (
    id INT(11) NOT NULL AUTO_INCREMENT,
    firstname VARCHAR(100) NOT NULL,
    middlename VARCHAR(100) DEFAULT NULL,
    lastname VARCHAR(100) NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'Active',
    position VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL,
    contact_num VARCHAR(30) NOT NULL,
    gender VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    employment_status VARCHAR(50) NOT NULL DEFAULT 'Permanent',
    specialization VARCHAR(150) NOT NULL,
    date_hired DATE NOT NULL,
    birthday DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Sample records for testing
-- ------------------------------------------------------------

-- Default test account. Password: admin12345 (stored as a bcrypt hash)
INSERT INTO users (firstname, middlename, lastname, username, email, password) VALUES
('System', NULL, 'Administrator', 'admin', 'admin@example.com', '$2y$12$3/.uCYq8lEO5lswo613lsu5dQYempg1DPjvKjyzSp7Efs07DQTBgi');

-- Sample faculty members (fictional people)
INSERT INTO faculty (firstname, middlename, lastname, status, position, department, contact_num, gender, email, employment_status, specialization, date_hired, birthday) VALUES
('Elena', 'Mendoza', 'Rivera', 'Active', 'Associate Professor II', 'Information Technology', '+63 917 555 0142', 'Female', 'e.rivera@example.edu', 'Permanent', 'Database Systems', '2012-06-01', '1981-03-14'),
('Ramon', 'Agustin', 'Villanueva', 'Active', 'Professor I', 'Agriculture', '+63 918 555 0187', 'Male', 'r.villanueva@example.edu', 'Permanent', 'Crop Science', '2004-07-15', '1972-11-02'),
('Kristine', NULL, 'Bautista', 'Active', 'Instructor I', 'Teacher Education', '+63 905 555 0110', 'Female', 'k.bautista@example.edu', 'Temporary', 'Early Childhood Education', '2024-08-12', '1996-05-21'),
('Paolo', 'Santos', 'Gacayan', 'Active', 'Assistant Professor III', 'Engineering', '+63 921 555 0163', 'Male', 'p.gacayan@example.edu', 'Permanent', 'Structural Engineering', '2015-01-05', '1985-09-30'),
('Liza', 'Ramos', 'Tolentino', 'Inactive', 'Instructor III', 'Arts and Sciences', '+63 927 555 0121', 'Female', 'l.tolentino@example.edu', 'Contractual', 'English Literature', '2018-06-18', '1988-01-09'),
('Dennis', 'Cortez', 'Alcantara', 'Active', 'Assistant Professor I', 'Information Technology', '+63 915 555 0199', 'Male', 'd.alcantara@example.edu', 'Permanent', 'Network Security', '2017-06-01', '1987-07-22'),
('Jasmine', NULL, 'Ordonio', 'Active', 'Instructor II', 'Fisheries', '+63 906 555 0135', 'Female', 'j.ordonio@example.edu', 'Permanent', 'Aquaculture', '2019-08-01', '1990-12-03'),
('Marco', 'Lim', 'Galang', 'Active', 'Associate Professor I', 'Engineering', '+63 919 555 0158', 'Male', 'm.galang@example.edu', 'Permanent', 'Electrical Power Systems', '2010-06-14', '1979-04-17'),
('Rowena', 'Pineda', 'Soriano', 'Active', 'Professor II', 'Teacher Education', '+63 908 555 0177', 'Female', 'r.soriano@example.edu', 'Permanent', 'Educational Leadership', '2001-06-11', '1968-08-28'),
('Gilbert', NULL, 'Ancheta', 'Active', 'Instructor I', 'Agriculture', '+63 920 555 0104', 'Male', 'g.ancheta@example.edu', 'Part-time', 'Animal Science', '2023-01-16', '1993-10-11');
