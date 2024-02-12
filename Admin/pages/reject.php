<?php
session_start();
include("../includes/conn.php");
include("../includes/notification.php");

if (!isset($_SESSION['adminID'])) {
    header('location: ../index.php');
    exit();
}

if (!isset($_GET['course_id']) || !isset($_GET['mentor_id'])) {
    $_SESSION['errorMsg'] = "Invalid request. Please select a course request.";
    header("location: ./mentorRequest.php");
    exit();
}

$courseRequestId = $_GET['course_id'];
$mentorId = $_GET['mentor_id'];

// Update assigned_course table
$updateAssignedCourseQuery = "UPDATE assignedcourse SET status = 'rejected' WHERE course_id = $courseRequestId";
$resultAssignedCourse = mysqli_query($conn, $updateAssignedCourseQuery);
if (!$resultAssignedCourse) {
    $_SESSION['errorMsg'] = "Error updating assigned_course table: " . mysqli_error($conn);
    header("location: ./mentorRequest.php");
    exit();
}

// Update course_request table
$updateCourseRequestQuery = "UPDATE course_requests SET status = 'rejected' WHERE course_id = $courseRequestId AND mentor_id = $mentorId";
$resultCourseRequest = mysqli_query($conn, $updateCourseRequestQuery);
if (!$resultCourseRequest) {
    $_SESSION['errorMsg'] = "Error updating course_request table: " . mysqli_error($conn);
    header("location: ./mentorRequest.php");
    exit();
}

// Success message
$_SESSION['successMsg'] = "Course request rejected successfully.";

// Redirect to mentorRequest.php or any other appropriate page
header("location: ./mentorRequest.php");
exit();
?>
