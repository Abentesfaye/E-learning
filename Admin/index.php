<?php
include("./includes/notification.php");
session_start();

// Check if there is an error message in the session
if (isset($_SESSION['errorMsg'])) {
    $errorMsg = $_SESSION['errorMsg'];
    showNotification($errorMsg);
    // Clear the error message from the session to prevent displaying it multiple times
    unset($_SESSION['errorMsg']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"> 
    <link rel="icon" type="logo" href="../assets/logo/logo.png" />
    <title>Welcome Mentor - Mettu University E-Learning</title>
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #27ae60;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .main {
            display: flex;
            justify-content: space-between;
        }

        .banner {
            width: 40%;
            box-sizing: border-box;
            text-align: center;
        }

        .banner img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .banner h1 {
            color: white;
            font-size: 24px;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 40%;
            box-sizing: border-box;
            text-align: center;
        }

        .container h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #666;
            font-size: 16px;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .login-button {
            background-color: #2ecc71; /* Darker green button color */
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .login-button:hover {
            background-color: #27ae60; /* Lighter green on hover */
        }

        .forgot-password,
        .become-mentor {
            color: #27ae60; /* Green color for links */
            text-decoration: none;
            font-size: 14px;
            margin-top: 10px;
            display: inline-block;
        }
    </style>
    <link rel="stylesheet" href="../font.css">
</head>
<body>

<div class="main">
    <div class="banner">
        <img src="../assets/logo/logo.png" alt="Mettu University Logo"> 
        <h1>METTU UNIVERSITY E-LEARNING PLATFORM<br>Admin's Portal</h1>
    </div>

    <div class="container">
        <h2>Admin Login</h2>
        <form action="./serverSide/login.php" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="login-button">Login</button>
        </form>
    </div>
</div>
<div id="notification" class="notification"></div>
</body>
</html>
