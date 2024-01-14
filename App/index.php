<?php
session_start();
include("./includes/notification.php");

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
    <link rel="icon" type="../logo/logo.png" href="../logo/logo.png" />
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mina&family=Poppins:ital,wght@0,100;0,400;0,500;0,600;0,700;0,800;1,100;1,500;1,600;1,800&family=Whisper&display=swap" rel="stylesheet">
    <title>Login - Mettu University E-Learning</title>
      
</head>
<body>
    <div class="login-container">
      <img src="../logo/logo.png" alt="Mettu University Logo"> <!-- Replace "your-logo.png" with your actual logo -->
      <h2>Welcome to Mettu University E-Learning Center</h2>
      <p>Login</p>
      <form class="login-form" action="./serverSide/login.php" method="post">
          <div class="form-group">
              <label for="phone">Phone Number</label>
              <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
          </div>
          <div class="form-group">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" placeholder="Enter your password" required>
          </div>
          <button type="submit" class="login-button">Login</button>
      </form>
      <a href="#" class="forgot-password">Forgot Password?</a> <br>
     <p class="create-account">Don't have an account?</p> <a href="signup.php" class="create-account">Signup</a>
  </div>
 
 <!-- Add the notification element -->
 <div id="notification" class="notification"></div>

</body>
</html>
