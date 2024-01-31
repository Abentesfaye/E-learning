<?php
include("./includes/notification.php");
session_start(); // Ensure session is started
if (isset($_SESSION['errorMsg'])) {
    $errorMsg = $_SESSION['errorMsg'];
    showNotification($errorMsg);
    // Clear the error message from the session to prevent displaying it multiple times
    unset($_SESSION['errorMsg']);
}
if (isset($_SESSION['successMsg'])) {
    $successMsg = $_SESSION['successMsg'];
    showGoodNotification($successMsg);
    // Clear the error message from the session to prevent displaying it multiple times
    unset($_SESSION['successMsg']);
}
if (isset($_SESSION['mentor_id'])) {
    header('location: ./pages/dashboard.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="icon" type="logo" href="../assets/logo/logo.png" />
    <title>Mentor - login</title>
    <link rel="stylesheet" href="../font.css">
</head>

<body class="bg-success">

    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">

            <div class="col-md-6 text-center">
                <h1 class="text-white mt-3">METTU UNIVERSITY E-LEARNING PLATFORM<br>Mentors Portal</h1>
            </div>

            <div class="col-md-6 bg-white rounded p-4 shadow-lg">
                <h2 class="text-dark text-center">Mentor Login</h2>
                <form action="./serverSide/login.php" method="post">

                    <div class="form-group">
                        <label for="username" class="text-muted">Username</label>
                        <input type="text" id="username" name="username" class="form-control"
                            placeholder="Enter your username" required>
                    </div>

                    <div class="form-group">
                        <label for="password" class="text-muted">Password</label>
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Enter your password" required>
                    </div>

                    <button type="submit" class="btn btn-success btn-block">Login</button>

                </form>

                <a href="#" class="text-success d-block mt-3 text-center">Forgot Password?</a>
                <a href="./pages/request.php" class="text-success d-block text-center">Want to become a mentor? Request Admin</a>
            </div>

        </div>
    </div>
    <div id="notificationtext" class="notificationtext"></div>   
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>
