<?php
session_start();
include("../includes/conn.php");
include("../includes/notification.php");

$secondPage = "manageMentor";
$activePage = "mentorRequest";
$_SESSION['next'] = $secondPage;
$_SESSION['activePage'] = $activePage;
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
if (!isset($_SESSION['adminID'])) {
    header('location: ../index.php');
    exit();
}

// Retrieve data from the database
$query = "SELECT c.id, c.course_name, d.department_name, cl.class_name 
          FROM course c
          INNER JOIN class cl ON c.class_id = cl.id
          INNER JOIN department d ON cl.department_id = d.id";

$result = $conn->query($query);

// Check if there is an error message in the session
if (isset($_SESSION['errorMsg'])) {
    $errorMsg = $_SESSION['errorMsg'];
    showNotification($errorMsg);
    unset($_SESSION['errorMsg']);
}

if (isset($_SESSION['successMsg'])) {
    $successMsg = $_SESSION['successMsg'];
    showGoodNotification($successMsg);
    unset($_SESSION['successMsg']);
}

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
<div id="notificationtext" class="notificationtext"></div>   
    <?php include("../includes/nav_sidebar.php"); ?>

    <!-- MAIN -->
    <main>
        <?php include("../includes/pageHeader.php"); ?>
       
<div class="container mt-5">
    <h2>Course Requests</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Mentor</th>
                    <th>Course</th>
                    <th>Department</th>
                    <th>Class</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Your SQL query here
                $sql = "SELECT cr.*, m.first_name AS mentor_first_name, m.last_name AS mentor_last_name, 
                c.course_name, d.department_name, cl.class_name
                FROM course_requests cr
                JOIN mentors m ON cr.mentor_id = m.mentor_id
                JOIN course c ON cr.course_id = c.id
                JOIN class cl ON c.class_id = cl.id
                JOIN department d ON cl.department_id = d.id
                ORDER BY FIELD(cr.status, 'pending', 'active', 'rejected')";
        $result = $conn->query($sql);
        

                if ($result->num_rows > 0) {
                    $count = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$count}</td>";
                        echo "<td>{$row['mentor_first_name']} {$row['mentor_last_name']}</td>";
                        echo "<td>{$row['course_name']}</td>";
                        echo "<td>{$row['department_name']}</td>";
                        echo "<td>{$row['class_name']}</td>";
                        echo "<td>{$row['status']}</td>";
                        echo "<td><a href='manage_request.php?course_id={$row['course_id']}&mentor_id={$row['mentor_id']}' class='btn btn-primary'>Manage</a></td>";
                        echo "</tr>";
                        $count++;
                    }
                } else {
                    echo "<tr><td colspan='7'>No course requests found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

    </main>
</body>
</html>
