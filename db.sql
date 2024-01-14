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
