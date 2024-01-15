<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/style.css"> 
    <link rel="stylesheet" href="../font.css">
    <link rel="icon" type="logo" href="../assets/logo/logo.png" />
    <title>Mentor Login - Mettu University E-Learning</title>
</head>
<body>

<div class="main">
    <div class="banner">
        <img src="../assets/logo/logo.jpg" alt="Mettu University Logo"> 
    </div>

    <div class="container">
        <h2>Mentor Login</h2>
        <form action="#" method="post"> <!-- Replace "#" with your actual form action -->
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
        <a href="#" class="forgot-password">Forgot Password?</a> <br>
        <a href="./pages/request.php" class="become-mentor">Want to become a mentor? Request Admin</a>
    </div>
</div>

</body>
</html>
