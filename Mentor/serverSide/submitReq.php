<?php
session_start();
include("../includes/conn.php");
include("../includes/calculateProgress.php");

$mentorId = $_SESSION["mentorID"];
$courseId = $_SESSION['course_id'];

// Check if there is no existing request for the course and mentor
$queryCheckRequest = "SELECT id FROM course_requests WHERE mentor_id = ? AND course_id = ?";
$stmtCheckRequest = $conn->prepare($queryCheckRequest);
$stmtCheckRequest->bind_param("ii", $mentorId, $courseId);
$stmtCheckRequest->execute();
$stmtCheckRequest->store_result();
if ($stmtCheckRequest->num_rows > 0) {
    $_SESSION['errorMsg'] = "There is already a pending request for this course.";
    header("location: ../pages/manageCourse.php?course_id=$courseId");
    exit(); // Exit after redirection
}
$stmtCheckRequest->close();

// Retrieve progress from assignedcourse table
$queryProgress = "SELECT progress FROM assignedcourse WHERE course_id = ? AND mentor_id = ?";
$stmtProgress = $conn->prepare($queryProgress);
$stmtProgress->bind_param("ii",$courseId, $mentorId);
$stmtProgress->execute();
$stmtProgress->store_result();
if ($stmtProgress->num_rows > 0) {
    $stmtProgress->bind_result($courseProgress);
    $stmtProgress->fetch();
    if ($courseProgress < 90) {
        $_SESSION['errorMsg'] = "Course must be at least 90% completed. Your progress is: " . $courseProgress;
        header("location: ../pages/manageCourse.php?course_id=$courseId");
        exit(); // Exit after redirection
    }
} else {
    $_SESSION['errorMsg'] = "No progress found for this course.";
    header("location: ../pages/manageCourse.php?course_id=$courseId");
    exit(); // Exit after redirection
}
$stmtProgress->close();

// Check if a cover exists for the course
$queryCover = "SELECT cover FROM coursecover WHERE course_id = ?";
$stmtCover = $conn->prepare($queryCover);
$stmtCover->bind_param("i", $courseId);
$stmtCover->execute();
$stmtCover->bind_result($cover);
$stmtCover->fetch();
$stmtCover->close();

if (empty($cover)) {
    $_SESSION['errorMsg'] = "Please upload a cover for the course.";
    header("location: ../pages/manageCourse.php?course_id=$courseId");
    exit(); // Exit after redirection
}

// Insert a new course request
$insertQuery = "INSERT INTO course_requests (mentor_id, course_id) VALUES (?, ?)";
$stmtInsert = $conn->prepare($insertQuery);
$stmtInsert->bind_param("ii", $mentorId, $courseId);
if ($stmtInsert->execute()) {
    $_SESSION['successMsg'] = "Course request sent successfully.";
    
    // Update assignedcourse status to pending
    $updateStatusQuery = "UPDATE assignedcourse SET status = 'submited' WHERE mentor_id = ? AND course_id = ?";
    $stmtUpdateStatus = $conn->prepare($updateStatusQuery);
    $stmtUpdateStatus->bind_param("ii", $mentorId, $courseId);
    $stmtUpdateStatus->execute();
    $stmtUpdateStatus->close();
    
} else {
    $_SESSION['errorMsg'] = "Error sending request to admin.";
}
$stmtInsert->close();
header("location: ../pages/manageCourse.php?course_id=$courseId");
exit(); // Exit after redirection
?>
