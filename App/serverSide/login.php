<?php
session_start();
include("../includes/conn.php");

$errorMsg = "";
$userID = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = sanitizeInput($_POST["phone"]);
    $password = sanitizeInput($_POST["password"]);

    // Check if the user with the provided phone number exists
    $checkQuery = "SELECT id, password FROM users WHERE phone = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $phone);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // User with the provided phone number exists, fetch user details
        $checkStmt->bind_result($userID, $hashedPassword);
        $checkStmt->fetch();

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // Password is correct, create a session and store the user ID
            $_SESSION['userID'] = $userID;

            // Redirect to a dashboard or home page
            header("Location: ../pages/home.php");
            exit();
        } else {
            $_SESSION['errorMsg'] = "Incorrect password. Please try again.";
            header("Location: ../index.php");
        }
    } else {
        $_SESSION['errorMsg'] = "User not found. Please check your phone number.";
        header("Location: ../index.php");
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
