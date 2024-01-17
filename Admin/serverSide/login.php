<?php
session_start();
include("../includes/conn.php");

$errorMsg = "";
$userID = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitizeInput($_POST["username"]);
    $password = sanitizeInput($_POST["password"]);

    // Check if the user with the provided username exists
    $checkQuery = "SELECT id, password FROM admins WHERE username = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // User with the provided username exists, fetch user details
        $checkStmt->bind_result($adminID, $fetchedPassword);
        $checkStmt->fetch();

        // Verify the password
        if ($password === $fetchedPassword) {
            // Password is correct, create a session and store the user ID
            $_SESSION['adminID'] = $adminID;

            // Redirect to a dashboard or home page
            header("Location: ../pages/dashboard.php");
            exit();
        } else {
            $_SESSION['errorMsg'] = "Invalid username or password.";
            header("Location: ../index.php");
            exit();
        }
    } else {
        $_SESSION['errorMsg'] = "User not found.";
        header("Location: ../index.php");
        exit();
    }

    // Close the statement
    $checkStmt->close();
}

// Close the connection
$conn->close();

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>
