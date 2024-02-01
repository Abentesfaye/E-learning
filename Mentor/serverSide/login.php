<?php
session_start();
include("../includes/conn.php");
include("../includes/notification.php");

$errorMsg = "";
$successMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitizeInput($_POST["username"]);
    $password = sanitizeInput($_POST["password"]);

    // Check if the user with the provided username exists
    $checkQuery = "SELECT mentor_id, password FROM mentors WHERE username = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // User with the provided username exists, fetch user details
        $checkStmt->bind_result($mentor_id, $hashedPassword);
        $checkStmt->fetch();
    
        // Verify the password
        if (password_verify($password, $hashedPassword)) { //tring to verify hashed password but not comparing it well
            // Password is correct, proceed to the dashboard
            $_SESSION["mentorID"] = $mentor_id;
            $successMsg = "Login Success!";
            $_SESSION['successMsg'] = $successMsg;
            header("Location: ../pages/dashboard.php");
            exit();
        } elseif (strlen($hashedPassword) === 8) {
            // Password is temporary, redirect to complete profile page
            $_SESSION["mentorID"] = $mentor_id;
            $errorMsg = 'Please complete your profile to access your dashboard';
            $_SESSION['errorMsg'] = $errorMsg;
            header('Location: ../pages/completeProfile.php');
            exit();
        } else {
            // Incorrect password
            $_SESSION['errorMsg'] = "Invalid username or password.";
            header("Location: ../index.php");
            exit();
        }
    } else {
        // User not found
        $_SESSION['errorMsg'] = "User not found.";
        header("Location: ../index.php");
        exit();
    }
}    

// Close the connection
$conn->close();

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>
