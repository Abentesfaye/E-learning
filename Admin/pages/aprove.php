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

$updateAssignedCourseQuery = "UPDATE assignedcourse SET status = 'confirmed' WHERE course_id = $courseRequestId";
$resultAssignedCourse = mysqli_query($conn, $updateAssignedCourseQuery);
if (!$resultAssignedCourse) {
    $_SESSION['errorMsg'] = "Error updating assigned_course table: " . mysqli_error($conn);
    header("location: ./mentorRequest.php");
    exit();
}

$updateCourseRequestQuery = "UPDATE course_requests SET status = 'approved' WHERE course_id = $courseRequestId AND mentor_id = $mentorId";
$resultCourseRequest = mysqli_query($conn, $updateCourseRequestQuery);
if (!$resultCourseRequest) {
    $_SESSION['errorMsg'] = "Error updating course_request table: " . mysqli_error($conn);
    header("location: ./mentorRequest.php");
    exit();
}

$_SESSION['successMsg'] = "Course request approved successfully.";


header("location: ./mentorRequest.php");
exit();
?>
