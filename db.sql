CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    phone VARCHAR(15) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_picture VARCHAR(255), -- Assuming the profile picture URL is stored as a string
    isActive BOOLEAN DEFAULT true, -- Assuming isActive is a boolean field with a default value of true
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Assuming createdAt is a timestamp field with the current timestamp as the default
);
