CREATE DATABASE IF NOT EXISTS school;
USE school;

CREATE TABLE IF NOT EXISTS subject
    (
        subject_id INT AUTO_INCREMENT PRIMARY KEY,
        code VARCHAR(50) NOT NULL UNIQUE,
        title VARCHAR(100) NOT NULL,
        unit INT NOT NULL
    );

CREATE TABLE IF NOT EXISTS program
    (
        program_id INT AUTO_INCREMENT PRIMARY KEY,
        code VARCHAR(50) NOT NULL UNIQUE,
        title VARCHAR(100) NOT NULL,
        years INT NOT NULL
    );

CREATE TABLE IF NOT EXISTS users
    (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        account_type ENUM('admin', 'staff', 'teacher', 'student') NOT NULL,
        created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        created_by INT NOT NULL DEFAULT 0,
        updated_on DATETIME NULL,
        updated_by INT NULL
    );

INSERT INTO users (username, password, account_type, created_on, created_by) VALUES
    ('admin', '$2y$10$JUSlOkXetr51tdi3gLoR6uqekB8RZuJVw1BkovdOJbvTL0xaKKnim', 'admin', NOW(), 0);

INSERT INTO subject (code, title, unit) VALUES
    ('GE 1213', 'Life and Works of Rizal', 3),
    ('GE 3219', 'Elective II', 3),
    ('PE 2217', 'Path-Fit IV', 2),
    ('CS 2238', 'Basic Electronics', 5),
    ('CS 2239', 'Software Engineering I', 3),
    ('MATH 2272', 'Integral Calculus', 5),
    ('CS 2240', 'Applications Development and Emerging Technologies', 3),
    ('IT 2241', 'Event-Driven Programming', 3),
    ('GE 4120', 'Elective III', 3),
    ('MATH 2138', 'Linear Algebra', 3),
    ('CS 3142', 'Algorithms and Complexity', 3),
    ('CS 3143', 'Automata Theory and Formal Languages', 3),
    ('CS 3144', 'Human Computer Interaction', 3),
    ('CS 3145', 'Software Engineering II', 3),
    ('CS 3246', 'Elective Math/CS Elective I', 3),
    ('CS 3247', 'Networks and Communications', 3),
    ('CS 3248', 'CS Thesis Writing I', 3),
    ('CS 3249', 'Programming Languages', 3),
    ('MATH 3274', 'Probability and Statistics', 3),
    ('CS 3350', 'CS Practicum', 3);

INSERT INTO program (code, title, years) VALUES
    ('BSCS', 'Bachelor of Science in Computer Science', 4),
    ('BSIT', 'Bachelor of Science in Information Technology', 4),
    ('BSIS', 'Bachelor of Science in Information Systems', 4),
    ('BSDS', 'Bachelor of Science in Data Science', 4);