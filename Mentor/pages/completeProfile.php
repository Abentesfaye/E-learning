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

$mentorID = $_SESSION['mentorID'];

// Retrieve mentor information
$checkQuery = "SELECT * FROM mentors WHERE mentor_id = ?";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param("i", $mentorID);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows > 0) {
    $mentorData = $result->fetch_assoc();
    if (strlen($mentorData['password']) !== 8) {
        header('Location: ../index.php');
    }
} else {
    // Handle mentor not found, redirect to index or another appropriate page
    header('location: ../index.php');
    exit();
}

// Close the statement
$checkStmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Profile</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../font.css">
    <style>
        body {
            background-color: #27ae60;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin-top: 5%;
        }

        .btn-success {
            background-color: #2ecc71;
            color: #fff;
            transition: background-color 0.3s ease;
        }

        .btn-success:hover {
            background-color: #27ae60;
        }

        #notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 15px;
            background-color: #e74c3c; /* Red color */
            color: #fff;
            border-radius: 5px;
            display: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="mt-4 text-center">Welcome, <?php echo $mentorData['first_name'] . ' ' . $mentorData['last_name']; ?></h1>
        <p class="text-center">Please update your password to access your Mentor Dashboard</p>

        <form action="../serverSide/update_password.php" method="post" onsubmit="return validatePassword()">
            <div class="form-group">
                <label for="newPassword">Enter new password</label>
                <input type="password" id="newPassword" name="newPassword" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="confirmPassword">Confirm the password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" required>
                <small id="passwordMatchError" class="text-danger"></small>
            </div>

            <button type="submit" class="btn btn-success mt-4">Submit</button>
        </form>
    </div>

    <div id="notification"></div>
    <div id="notificationtext" class="notificationtext"></div>           
    <script scr="../../bootstrap/js/bootstrap.min.js"></script>
    <script>
        function validatePassword() {
    var newPassword = document.getElementById("newPassword").value;
    var confirmPassword = document.getElementById("confirmPassword").value;
    var passwordMatchError = document.getElementById("passwordMatchError");

    if (newPassword.length < 8) {
        passwordMatchError.textContent = "Password must be at least 8 characters long.";
        return false;
    }

    if (!/[A-Za-z]/.test(newPassword) || !/\d/.test(newPassword)) {
        passwordMatchError.textContent = "Password must contain both letters and numbers.";
        return false;
    }

    if (newPassword !== confirmPassword) {
        passwordMatchError.textContent = "Passwords do not match!";
        return false;
    } else {
        passwordMatchError.textContent = "";
        return true;
    }
}
    </script>
</body>

</html>
