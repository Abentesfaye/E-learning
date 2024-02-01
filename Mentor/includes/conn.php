<?php
// Database configuration
$host = "localhost";    
$username = "root";
$password = "";
$database = "e-learning";

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
