<?php
include("../includes/notification.php");
session_start(); 
$activePage = "createDpt";
$_SESSION['activePage'] = $activePage;
// Check if there is an error message in the session
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
if (!isset($_SESSION['adminID'])) {
    header('location: ../index.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../font.css">
    <link rel="stylesheet" href="../css/style.css">
    
</head>
<body>
    <?php include("../includes/nav_sidebar.php"); ?>

    <!-- MAIN -->
    <main>
    <?php include("../includes/pageHeader.php"); ?>
    <div class="container">
    <h2 class="text-center">Create Department</h2>
    <form action="../serverSide/process_department.php" method="post">
        <div class="form-group">
            <label for="departmentName">Department Name:</label>
            <input type="text" class="form-control" id="departmentName" name="departmentName" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Submit</button>
    </form>
</div>
    </section>  
    <div id="notificationtext" class="notificationtext"></div>   
</body>
<!-- Include Bootstrap JS -->
<script src="../../bootstrap/js/jquery.slim.min.js"></script>
<script src="../../bootstrap/js/popper.min.js"></script>
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<script src="../js/script.js"></script>
</html>
