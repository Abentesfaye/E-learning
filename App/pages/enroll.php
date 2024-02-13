<?php
session_start();

if (!isset($_SESSION['userID'])){
    header('Location:  ../index.html');
    exit;
}

include("../includes/conn.php");

$userID = $_SESSION['userID'];

if (!isset($_GET['course_id'])) {
    $_SESSION['errorMsg'] = "Course ID not provided.";
    header("Location: home.php");
    exit;
}

$courseID = $_GET['course_id'];

// Check if the user is already enrolled in the course
$queryCheckEnrollment = "SELECT * FROM enrolled WHERE user_id = ? AND course_id = ?";
$stmtCheckEnrollment = $conn->prepare($queryCheckEnrollment);
$stmtCheckEnrollment->bind_param("ii", $userID, $courseID);
$stmtCheckEnrollment->execute();
$stmtCheckEnrollment->store_result();

if ($stmtCheckEnrollment->num_rows > 0) {
   
    $_SESSION['errorMsg'] = "You are already enrolled in this course.";
    header("Location: home.php");
    exit;
}

$stmtCheckEnrollment->close();

$queryInsertEnrollment = "INSERT INTO enrolled (user_id, course_id) VALUES (?, ?)";
$stmtInsertEnrollment = $conn->prepare($queryInsertEnrollment);
$stmtInsertEnrollment->bind_param("ii", $userID, $courseID);

if ($stmtInsertEnrollment->execute()) {
    $_SESSION['successMsg'] = "Enrollment successful!";
    header("Location: education_content.php?course_id=$courseID");
    exit;
} else {
    $_SESSION['errorMsg'] = "Enrollment failed. Please try again.";
    header("Location: home.php");
    exit;
}

$stmtInsertEnrollment->close();
mysqli_close($conn);
?>
