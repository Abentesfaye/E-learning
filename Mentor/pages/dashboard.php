<?php
include("../includes/notification.php");
include("../includes/conn.php");
session_start(); // Ensure session is started

// Check for error or success messages and display notifications
if (isset($_SESSION['errorMsg'])) {
    $errorMsg = $_SESSION['errorMsg'];
    showNotification($errorMsg);
    unset($_SESSION['errorMsg']); // Clear the error message from the session
}

if (isset($_SESSION['successMsg'])) {
    $successMsg = $_SESSION['successMsg'];
    showGoodNotification($successMsg);
    unset($_SESSION['successMsg']); // Clear the success message from the session
}

// Check if mentor is logged in
if (!isset($_SESSION['mentorID'])) {
    header('location: ../index.php');
    exit(); // It's a good practice to exit after header('location') to prevent further script execution
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
   
    <div id="notificationtext" class="notificationtext"></div>    
</body>
</html>