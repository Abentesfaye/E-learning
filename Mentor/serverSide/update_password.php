<?php
session_start();
include("../includes/conn.php");

$errorMsg = "";
$successMsg = "";

// Check if mentor is logged in
if (!isset($_SESSION['mentorID'])) {
    $errorMsg = 'Mentor Not provided!';
    $_SESSION['errorMsg'] = $errorMsg;
    header('location: ../index.php');
    exit(); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Hash the password for security
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

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
        if (strlen($mentorData['password']) !== 8) {
            $errorMsg = 'Password Already Updated!';
            $_SESSION['errorMsg'] = $errorMsg;
            header('Location: ../index.php');
            exit();
        } else {
            // Update the password
            $updateQuery = "UPDATE mentors SET password = ? WHERE mentor_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("si", $password, $mentorID);

            if ($updateStmt->execute()) {
                $successMsg = 'Password Updated Successfully!';
                $_SESSION['successMsg'] = $successMsg;
                header('Location: ../index.php');
                exit();
            } else {
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
    $errorMsg = 'Invalid Request!';
    $_SESSION['errorMsg'] = $errorMsg;
    header('Location: ../index.php');
    exit();
}
// Close the connection
$conn->close();
?>
