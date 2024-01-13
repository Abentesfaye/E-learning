<?php
session_start();

// Check if there is an error message in the session
if (isset($_SESSION['errorMsg'])) {
    $errorMsg = $_SESSION['errorMsg'];
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
    <link rel="icon" type="../logo/logo.png" href="../logo/logo.png" />
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mina&family=Poppins:ital,wght@0,100;0,400;0,500;0,600;0,700;0,800;1,100;1,500;1,600;1,800&family=Whisper&display=swap" rel="stylesheet">
    <title>Signup - Mettu University E-Learning</title>
    <style>
        /* Add custom notification styles here */
        .notification {
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
  <div class="signup-container">
      <img src="../logo/logo.png" alt="Mettu University Logo">
      <h2>Signup and Start Learning</h2>

      <!-- Display the error message if it exists -->
      <?php if (!empty($errorMsg)): ?>
          <div id="notification" class="notification"><?php echo $errorMsg; ?></div>
      <?php endif; ?>

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
      <a href="index.html" class="login-link">Already have an account? Login here</a>
  </div>

  <!-- Add the notification element -->
  <div id="notification" class="notification"></div>
  <script src="./script/signupValidation.js"></script>
  <script>
          document.addEventListener("DOMContentLoaded", function () {
              const notification = document.getElementById('notification');
              const errorMsg = '<?php echo $errorMsg; ?>';

              if (errorMsg) {
                  showNotification(errorMsg);
              }

              function showNotification(message) {
                  // Show the notification
                  notification.innerText = message;
                  notification.style.display = 'block';

                  // Hide the notification after 3 seconds
                  setTimeout(function () {
                      notification.style.display = 'none';
                  }, 3000);
              }
          });
      </script>

</body>
</html>
