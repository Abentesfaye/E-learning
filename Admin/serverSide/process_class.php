<?php
session_start();
include("../includes/conn.php");

$errorMsg = "";
$successMsg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $department_id = sanitizeInput($_POST["department"]);
    $class_name = sanitizeInput($_POST["className"]);

    // Check if the class already exists
    $checkQuery = "SELECT id FROM class WHERE class_name = ? AND department_id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ss", $class_name, $department_id);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $errorMsg = "Class already exists!";
        $_SESSION['errorMsg'] = $errorMsg;
        header('Location: ../pages/createClass.php');
        exit();
    } else {
        // Insert the class if it doesn't exist
        $insertQuery = "INSERT INTO class (class_name, department_id) VALUES (?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("ss", $class_name, $department_id);

        if ($insertStmt->execute()) {
            $successMsg = "Class created successfully!";
            $_SESSION['successMsg'] = $successMsg;
        } else {
            $errorMsg = "Error creating class: " . $insertStmt->error;
            $_SESSION['errorMsg'] = $errorMsg;
        }

        $insertStmt->close();
    }
}

// Close the database connection
$conn->close();

// Redirect back to the form page
header("Location: ../pages/createClass.php");
exit();
function sanitizeInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}
?>
