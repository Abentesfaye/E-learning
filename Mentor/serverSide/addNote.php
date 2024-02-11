<?php
session_start();
include("../includes/conn.php");

// Check if mentor is logged in
if (!isset($_SESSION["mentorID"])) {
    header('location: ../index.php');
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get course_id and mentor_id from session
$courseId = $_SESSION['course_id'];
$mentorId = $_SESSION['mentorID'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve and sanitize form data
    $chapterId = $_POST['chapterDropdown'];
    $topicId = $_POST['topicDropdown'];
    $note = $_POST['note'];

    // Check if row exists in EducationContent table for the given chapter and topic IDs
    $checkQuery = "SELECT * FROM educationcontent WHERE chapter_id = ? AND topic_id = ?";
    $stmtCheck = $conn->prepare($checkQuery);
    $stmtCheck->bind_param("ii", $chapterId, $topicId);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        // Row already exists, update the existing row
        $updateQuery = "UPDATE educationcontent SET note = ? WHERE chapter_id = ? AND topic_id = ?";
        $stmtUpdate = $conn->prepare($updateQuery);
        $stmtUpdate->bind_param("sii", $note, $chapterId, $topicId);
        if ($stmtUpdate->execute()) {
            $_SESSION['successMsg'] = "Note updated successfully.";
        } else {
            $_SESSION['errorMsg'] = "Error updating existing row.";
        }
    } else {
        // Row does not exist, insert a new row
        $insertQuery = "INSERT INTO educationcontent (course_id, chapter_id, topic_id, note) VALUES (?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($insertQuery);
        $stmtInsert->bind_param("iiis", $courseId, $chapterId, $topicId, $note);
        if ($stmtInsert->execute()) {
            // Change status in AssignedCourses table to "preparing"
            $updateStatusQuery = "UPDATE AssignedCourse SET status = 'preparing' WHERE course_id = ? AND mentor_id = ?";
            $stmtUpdateStatus = $conn->prepare($updateStatusQuery);
            $stmtUpdateStatus->bind_param("ii", $courseId, $mentorId);
            if ($stmtUpdateStatus->execute()) {
                $_SESSION['successMsg'] = "Note inserted successfully.";
            } else {
                $_SESSION['errorMsg'] = "Error updating course status.";
            }
        } else {
            $_SESSION['errorMsg'] = "Error inserting new row.";
        }
    }
    header("Location: ../pages/manageCourse.php?course_id=$courseId");
    exit();
}

// Close the connection
$conn->close();
?>
