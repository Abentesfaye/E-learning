CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    phone VARCHAR(15) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_picture VARCHAR(255), 
    isActive BOOLEAN DEFAULT true, 
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);
CREATE TABLE mentors (
    mentor_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    email_address VARCHAR(255) NOT NULL,
    photo VARCHAR(255),
    document_path VARCHAR(255), -- Store the path to the document file
    why_mentor TEXT,
    gender VARCHAR(10),
    id_photo VARCHAR(255),
    username VARCHAR(50) UNIQUE ,
    password VARCHAR(255) ,
    account_code VARCHAR(20) UNIQUE NOT NULL,
    status ENUM('active', 'pending', 'rejected') DEFAULT 'pending',
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    username VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    profilepicture VARCHAR(255) -- Adjust the data type as needed
);
CREATE TABLE department (
    id INT PRIMARY KEY AUTO_INCREMENT,
    department_name VARCHAR(255)
);
CREATE TABLE class (
    id INT PRIMARY KEY AUTO_INCREMENT,
    class_name VARCHAR(255),
    department_id int
);
CREATE TABLE course (
    id int PRIMARY KEY AUTO_INCREMENT,
    course_name VARCHAR(255),
    class_id int
);
CREATE TABLE AssignedCourse (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT,
    mentor_id INT
    status ENUM('not_prepared', 'preparing', 'submited', 'reviewing', 'reviewed' 'confirmed' 'rejected') DEFAULT 'not_prepared',
);
CREATE TABLE chapters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    chapter_number INT,
    chapter_name VARCHAR(255),
    description VARCHAR(255)
);
CREATE TABLE topics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chapter_id INT,
    topic_name VARCHAR(255),
    description VARCHAR(255)
);
