<?php
    # Database Configuration
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'user_db';
    $port = 3307;

    # Establish Connection
    $conn = new mysqli($host, $username, $password, $database, $port);

    # Check Connection
    if ($conn->connect_error) {
        die('<h1>Connection Failed: ' . $conn->connect_error . '</h1>');
    }

    # Ensure 'reset_token' column exists in 'user_form' table
    $check_column_query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'user_form' AND COLUMN_NAME = 'reset_token'";
    $check_column = $conn->query($check_column_query);

    if ($check_column -> num_rows == 0) {
        $alter_query = "ALTER TABLE `user_form` ADD `reset_token` VARCHAR(255) NULL AFTER `password`";
        if (!$conn->query($alter_query)) {
            die('Error adding reset_token column: ' . $conn->error);
        }
    }
?>