<?php
session_start();
include("../includes/conn.php"); 
include("../includes/notification.php");
$activePage = "createClass";
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
// Fetch departments from the database
$departmentsQuery = "SELECT id, department_name FROM department";
$departmentsResult = $conn->query($departmentsQuery);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Class</title>
    <!-- Include Bootstrap CSS -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../font.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <?php include("../includes/nav_sidebar.php"); ?>

    <!-- MAIN -->
    <main>
    <?php include("../includes/pageHeader.php"); ?>
        <div class="container mt-5">
            <h2 class="text-center">Create Class</h2>
            <form method="post" action="../serverSide/process_class.php">
            <div class="mb-3">
                    <label for="department" class="form-label">Select Department</label>
                    <select class="form-select" id="department" name="department" required>
                        <option value="" disabled selected>Select Department</option>
                        <?php
                        // Populate department options from the database
                        while ($row = $departmentsResult->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['department_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="className" class="form-label">Class Name</label>
                    <input type="text" class="form-control" id="className" name="className" required>
                </div>

                <button type="submit" class="btn btn-primary">Add Class</button>
            </form>
        </div>
    </main>
<section>
<div id="notificationtext" class="notificationtext"></div> 
    <!-- Include Bootstrap JS -->
    <script src="../../bootstrap/js/jquery.slim.min.js"></script>
    <script src="../../bootstrap/js/popper.min.js"></script>
    <script src="../../bootstrap/js/bootstrap.min.js"></script>
    <script src="../js/script.js"></script>
</body>
</html>
