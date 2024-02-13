<?php
session_start();
include("../includes/conn.php");
include("../includes/notification.php");

if (!isset($_SESSION['adminID'])) {
    header('location: ../index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    if (!isset($_POST['comment'])) {
        $_SESSION['errorMsg'] = "Please provide a comment.";
        header("location: ./mentorRequest.php");
        exit();
    }

    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
    $mentor_id = mysqli_real_escape_string($conn, $_POST['mentor_id']);

 $update_assigned_course_query = "UPDATE assignedcourse SET status = 'reviewed' WHERE course_id = $course_id AND mentor_id = $mentor_id";
 $update_assigned_course_result = mysqli_query($conn, $update_assigned_course_query);

 if (!$update_assigned_course_result) {
     $_SESSION['errorMsg'] = "Failed to update assigned course status.";
     header("location: ./mentorRequest.php");
     exit();
 }

    $update_comment_query = "UPDATE course_requests 
                             SET comment = '$comment' 
                             WHERE course_id = '$course_id' AND mentor_id = '$mentor_id'";
    $update_comment_result = mysqli_query($conn, $update_comment_query);

    if (!$update_comment_result) {
        $_SESSION['errorMsg'] = "Failed to update comment for the course request.";
        header("location: ./mentorRequest.php");
        exit();
    }

    $_SESSION['successMsg'] = "Comment updated successfully.";
    header("location: ./mentorRequest.php");
    exit();
} else {
    $_SESSION['errorMsg'] = "Invalid request method.";
    header("location: ./mentorRequest.php");
    exit();
}
?>
