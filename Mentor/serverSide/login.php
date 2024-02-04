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
        if (password_needs_rehash($hashedPassword, PASSWORD_BCRYPT)){
            if ($password === $hashedPassword) {
                $_SESSION["mentorID"] = $mentor_id;
                $errorMsg = 'Please complete your profile to access your dashboard';
                $_SESSION["errorMsg"] = $errorMsg;
                header('Location: ../pages/completeProfile.php');
                exit();
            } else {
                $errorMsg = "invalid Username or Password";
                $_SESSION["errorMsg"] = $errorMsg;
                header("location: ../index.php");
            }
        }

        elseif (password_verify($password, $hashedPassword)) {
                     // Password is correct, proceed to the dashboard
                  $_SESSION["mentorID"] = $mentor_id;
                  $successMsg = "Login Success!";
                  $_SESSION['successMsg'] = $successMsg;
                  header("Location: ../pages/dashboard.php");
            } else {
                $errorMsg = "Invalid Username or Password";
                $_SESSION["errorMsg"] = $errorMsg;
                header("location: ../index.php");
                exit();
            }
    } else {
        // User not found
         // Set error message and redirect
         $errorMsg = "Invalid Username or Password.";
    $_SESSION['errorMsg'] = $errorMsg;
    header("Location: ../index.php");
       
    }

   
    exit();
}

// Close the connection
$conn->close();

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>
