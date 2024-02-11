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
   
    // Upload  file
    $referenceFile = uploadFile('referenceFile', '../../EducationContent/File/');
    if (!$referenceFile) {
        $_SESSION['errorMsg'] = "Failed to upload Referance.";
    }

    if (!$referenceFile) {
        header("Location: ../pages/manageCourse.php?course_id=$courseId");
        exit();
    } else {
        // Check if row exists in EducationContent table for the given chapter and topic IDs
        $checkQuery = "SELECT * FROM educationcontent WHERE chapter_id = ? AND topic_id = ?";
        $stmtCheck = $conn->prepare($checkQuery);
        $stmtCheck->bind_param("ii", $chapterId, $topicId);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        if ($resultCheck->num_rows > 0) {
            // Row already exists, update the existing row
            $updateQuery = "UPDATE educationcontent SET file_ = ?, file_title= ?, file_description = ? WHERE chapter_id = ? AND topic_id = ?";
            $stmtUpdate = $conn->prepare($updateQuery);
            $stmtUpdate->bind_param("sssii", $referenceFile, $file_title, $fileDescription, $chapterId, $topicId);
            if ($stmtUpdate->execute()) {
                $_SESSION['successMsg'] = "Inserted successfully.";
            } else {
                $_SESSION['errorMsg'] = "Error Inserting Please try again.";
            }
        } else {
            // Row does not exist, insert a new row
            $insertQuery = "INSERT INTO educationcontent (course_id, chapter_id, topic_id, file_, file_title,  file_description) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmtInsert = $conn->prepare($insertQuery);
            $stmtInsert->bind_param("iiissss", $courseId, $chapterId, $topicId, $referenceFile, $file_title, $videoTitle, $fileDescription);
            if ($stmtInsert->execute()) {
                // Change status in AssignedCourses table to "preparing"
                $updateStatusQuery = "UPDATE AssignedCourse SET status = 'preparing' WHERE course_id = ? AND mentor_id = ?";
                $stmtUpdateStatus = $conn->prepare($updateStatusQuery);
                $stmtUpdateStatus->bind_param("ii", $courseId, $mentorId);
                if ($stmtUpdateStatus->execute()) {
                    $_SESSION['successMsg'] = "Course status updated successfully.<br>";
                } else {
                    $_SESSION['errorMsg'] = "Error updating course status.";
                }
                $_SESSION['successMsg'] = "Uploaded successfully";
            } else {
                $_SESSION['errorMsg'] = "Error inserting new row.<br>";
            }
        }
        header("Location: ../pages/manageCourse.php?course_id=$courseId");
        exit();
    }
}

// Function to upload a file and return its destination path
function uploadFile($inputName, $targetDir)
{
    if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
        $tempName = $_FILES[$inputName]['tmp_name'];
        $fileName = uniqid() . '_' . basename($_FILES[$inputName]['name']);
        $targetPath = $targetDir . $fileName;

        if (move_uploaded_file($tempName, $targetPath)) {
            return $targetPath;
        }
    }

    return false;
}
?>
