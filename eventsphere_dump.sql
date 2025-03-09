-- Create a new database named 'user_db'
CREATE DATABASE user_db;

-- Select the 'user_db' database to use
USE user_db;

-- Create a table named 'user_form' with the following columns:
-- id: Auto-incremented primary key
-- username: Stores the user's name
-- email: Stores the user's email address
-- password: Stores the user's hashed password
-- image: Stores the filename of the user's profile image
CREATE TABLE user_form (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100),
    email VARCHAR(100),
    password VARCHAR(100),
    image VARCHAR(100)
);

-- Uncomment the following line if you want to truncate (clear) the table
-- TRUNCATE TABLE user_form;

-- Add a new column 'reset_token' to store password reset tokens
ALTER TABLE user_form ADD COLUMN reset_token VARCHAR(255) NULL;

-- Insert dummy data into the 'user_form' table
-- Adding two sample users with their details
INSERT INTO user_form (username, email, password, image, reset_token) 
VALUES 
    ('Joronski', 'joronrecio@gmail.com', '$2y$10$LdHLj5uE4W0E5yFNLWc1nuMmHmbjAlbrg.1Nxre/VFXD1ZcCBJgqS', 'Joronski DP(Art).jpg', NULL),
    ('Mikylla', 'mikylladeleon@gmail.com', '26dc318942685872cf79c5eb96c9bb13', 'miky.jpg', NULL);

-- Retrieve all records from the 'user_form' table
SELECT * FROM user_form;