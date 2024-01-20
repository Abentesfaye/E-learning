<?php
session_start();
include("../includes/conn.php");
include("../includes/notification.php");
$activePage = "createCourse";
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
            <h2 class="text-center">Create Course</h2>
            <form method="post" action="../serverSide/process_course.php">
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
                    <label for="class" class="form-label">Select Class</label>
                    <select class="form-select" id="class" name="class" required>
                        <option value="" disabled selected>Select Class</option>
                        <!-- Class options will be dynamically populated here -->
                    </select>
                </div>
                <div class="mb-3">
                    <label for="courseName" class="form-label">Course Name</label>
                    <input type="text" class="form-control" id="courseName" name="courseName" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Add Course</button>
            </form>
        </div>
    </main>
</section>
<div id="notificationtext" class="notificationtext"></div> 
<script src="../../bootstrap/js/jquery.slim.min.js"></script>
<script src="../../bootstrap/js/popper.min.js"></script>
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<script src="../js/script.js"></script>
<script src="../js/fetchClass.js"></script>
</body>
</html>
