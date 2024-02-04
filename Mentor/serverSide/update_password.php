<?php
session_start();
include("../includes/conn.php");

$errorMsg = "";
$successMsg = "";

// Check if mentor is logged in
if (!isset($_SESSION['mentorID'])) {
    $errorMsg = 'Mentor not provided!';
    $_SESSION['errorMsg'] = $errorMsg;
    header('location: ../index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Hash the new password for security
    $newPassword = sanitizeInput($_POST["confirmPassword"]);
    $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    $mentorID = $_SESSION['mentorID'];

    // Retrieve mentor information
    $checkQuery = "SELECT * FROM mentors WHERE mentor_id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("i", $mentorID);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        $mentorData = $result->fetch_assoc();

        // Check if the password is not already updated
        if (!password_needs_rehash($mentorData['password'], PASSWORD_BCRYPT)) {
            $errorMsg = 'Password already updated!';
            $_SESSION['errorMsg'] = $errorMsg;
            header('Location: ../index.php');
            exit();
        } else {
            // Update the password
            $updateQuery = "UPDATE mentors SET password = ? WHERE mentor_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("si", $hashedNewPassword, $mentorID);

            if ($updateStmt->execute()) {
                $successMsg = 'Password updated successfully!';
                $_SESSION['successMsg'] = $successMsg;
                header('Location: ../index.php');
                exit();
            } else {
                // Log the error for debugging
                error_log("Error updating password: " . $updateStmt->error);
                $errorMsg = 'Error updating password. Please try again.';
                $_SESSION['errorMsg'] = $errorMsg;
                header('Location: ../index.php');
                exit();
            }
        }
    } else {
        $errorMsg = 'Mentor not found!';
        $_SESSION['errorMsg'] = $errorMsg;
        header('Location: ../index.php');
        exit();
    }
} else {
    $errorMsg = 'Invalid request!';
    $_SESSION['errorMsg'] = $errorMsg;
    header('Location: ../index.php');
    exit();
}

// Close the connection
$conn->close();
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>
