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

// Get course_id from session
if (!isset($_SESSION['course_id'])) {
    $_SESSION['errorMsg'] = "Course ID not found in session.";
    header("Location: ../index.php");
    exit();
}
$courseId = $_SESSION['course_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Upload cover image
    $coverImage = uploadFile('coverImage', '../../EducationContent/CoverImage/');
    if (!$coverImage) {
        $_SESSION['errorMsg'] = "Failed to upload cover image.";
        header("Location: ../pages/manageCourse.php?course_id=$courseId");
        exit();
    }

    // Insert cover image into database
    $insertQuery = "INSERT INTO coursecover (course_id, cover) VALUES (?, ?)";
    $stmtInsert = $conn->prepare($insertQuery);
    $stmtInsert->bind_param("is", $courseId, $coverImage);
    if ($stmtInsert->execute()) {
        $_SESSION['successMsg'] = "Course cover image added successfully.";
    } else {
        $_SESSION['errorMsg'] = "Error uploading cover image.";
    }
    $stmtInsert->close();
    header("Location: ../pages/manageCourse.php?course_id=$courseId");
    exit();
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
