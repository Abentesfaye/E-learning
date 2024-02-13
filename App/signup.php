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
    <link rel="stylesheet" href="./style/signup.css">
    <link rel="icon" type="logo" href="../assets/logo/logo.png" />
    <link rel="stylesheet" href="./style/style.css">
   <link rel="stylesheet" href="../font.css">
    <title>Signup - Mettu University E-Learning</title>
</head>
<body>
  <div class="signup-container">
      <img src="../assets/logo/logo.png" alt="Mettu University Logo">
      <h2>Signup and Start Learning</h2>
      <form class="signup-form" action="./serverSide/signup.php" method="post">
          <div class="form-group">
              <label for="firstName">First Name</label>
              <input type="text" id="firstName" name="firstName" placeholder="Enter your first name" required>
          </div>
          <div class="form-group">
              <label for="lastName">Last Name</label>
              <input type="text" id="lastName" name="lastName" placeholder="Enter your last name" required>
          </div>
          <div class="form-group">
              <label for="phone">Phone Number</label>
              <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
          </div>
        
          <div class="form-group">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" placeholder="Enter your password" required>
          </div>
          <div class="form-group">
              <label for="confirmPassword">Confirm Password</label>
              <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
          </div>
         
          <button type="submit" class="signup-button">Sign Up</button>
      </form>
      <a href="index.php" class="login-link">Already have an account? Login here</a>
  </div>

  <div id="notificationtext" class="notificationtext"></div>  
  <div id="notification" class="notification"></div>
  <script src="./script/signupValidation.js"></script>
</body>
</html>