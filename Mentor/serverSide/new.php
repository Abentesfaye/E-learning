<?php
session_start();
include("../includes/conn.php");
if (!isset($_SESSION["mentorID"])) {
    header('location: ../index.php');
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
   // Get course_id and mentor_id from session
   $courseId = $_SESSION['course_id'];
   $mentorId = $_SESSION['mentorID'];
$errorMsg = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve and sanitize form data
    $chapterId = $_POST['chapterDropdown'];
    $topicId = $_POST['topicDropdown'];
    $videoTitle = $_POST['videoTitle'];
    $videoDescription = $_POST['videoDescription'];

    // Upload video file
    $videoUploadDir = uploadFile('video', '../../EducationContent/video/');
    if (!$videoUploadDir ) {
        
        $_SESSION['errorMsg'] = "Failed to upload video.";
        header("Location: ../pages/manageCourse.php");
        exit();
    }

    // Upload thumbnail
    $thumbnailUploadDir = uploadFile('thumbnail', '../../EducationContent/video/thumbnail/');
    if (!$thumbnailUploadDir) {
        
        $_SESSION['errorMsg'] = "Failed to upload thumbnail.";
        header("Location: ../pages/manageCourse.php");
        exit();
    }


    // Prepare the INSERT statement
    $insertQuery = "INSERT INTO mentors (first_name, last_name, phone_number, email_address, photo, document_path, why_mentor, gender, id_photo, username, password, account_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, NULL, ?)";
    $insertStmt = $conn->prepare($insertQuery);

    // Check if the statement is prepared successfully
    if (!$insertStmt) {
        die('Error in prepare statement: ' . $conn->error);
       
    }

    // Bind parameters to the prepared statement
    $insertStmt->bind_param("ssssssssss", $firstName, $lastName, $phone, $email, $photoPath, $educationDocPath, $whyMentor, $gender, $idProofPath, $accountNumber);

    // Execute the prepared statement
    $insertSuccess = $insertStmt->execute();

    if ($insertSuccess) {
       
        // Get the mentor ID of the inserted record
        $mentorID = $conn->insert_id;

        // Create a session and store the mentor ID and account number
        $_SESSION['mentorID'] = $mentorID;
        $_SESSION['accountNumber'] = $accountNumber;

        // Redirect to a success page or login page
        header("Location: ../pages/reqConf.php");
        exit();
    } else {
        
        // Log the error
        error_log("Error: " . $conn->error);
        echo $conn->error;
        $_SESSION['errorMsg'] = "Registration failed. Please try again later.";
    }

    // Close the insert statement
    $insertStmt->close();
}

// Close the connection
$conn->close();

function sanitizeInput($data)
{
    return htmlspecialchars(strip_tags(trim($data)));
}

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

function generateAccountNumber()
{
    return uniqid('MEUM');
}

function isAlreadyRegistered($conn, $phone, $email)
{
    $checkQuery = "SELECT mentor_id FROM mentors WHERE phone_number = ? OR email_address = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ss", $phone, $email);
    $checkStmt->execute();
    $checkStmt->store_result();
    
    return $checkStmt->num_rows > 0;
}

?>
