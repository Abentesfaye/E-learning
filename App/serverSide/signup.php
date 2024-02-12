<?php
session_start();
include("../includes/conn.php");

// Initialize error message and user ID variables
$errorMsg = "";
$userID = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve and sanitize form data
    $firstName = sanitizeInput($_POST["firstName"]);
    $lastName = sanitizeInput($_POST["lastName"]);
    $phone = sanitizeInput($_POST["phone"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password for security

    // Validate form data (add your validation logic here)

    // Check if the phone number is already in the database
    $checkQuery = "SELECT id FROM users WHERE phone = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $phone);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // Phone number is already in use, set the error message in the session
        $_SESSION['errorMsg'] = "Phone number is already registered. Please use a different number.";
        // Redirect to the signup page
        header("Location: ../signup.php");
        exit();
    } else {
        // Insert user data into the database
        $insertQuery = "INSERT INTO users (first_name, last_name, phone, password) VALUES (?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("ssss", $firstName, $lastName, $phone, $password);

        if ($insertStmt->execute()) {
            // Get the user ID of the inserted record
            $userID = $insertStmt->insert_id;

            // Create a session and store the user ID
            $_SESSION['userID'] = $userID;

            $_SESSION['successMsg'] = "Registered Successfuly";
            header("Location: ../index.php");
            exit();
        } else {
            // Log the error
            error_log("Error: " . $insertStmt->error);
            $_SESSION['errorMsg'] = "Registration failed. Please try again later.";
        }

        // Close the insert statement
        $insertStmt->close();
    }

    // Close the check statement
    $checkStmt->close();
}

// Close the connection
$conn->close();
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

?>
