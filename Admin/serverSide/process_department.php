<?php
include("../includes/conn.php");
session_start();
$errorMsg = "";
$successMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $department_name = sanitizeInput($_POST["departmentName"]);

    // Check if the user with the provided username exists
    $checkQuery = "SELECT id FROM department WHERE department_name = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $department_name);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $errorMsg = "Department already exists!";
        $_SESSION['errorMsg'] = $errorMsg;
        header("Location: ../pages/createDpt.php");
    } else {
        $insertQuery = "INSERT INTO department (department_name) VALUES (?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("s", $department_name);

        // Execute the prepared statement for insertion
        $insertStmt->execute();
        
        // Close the prepared statement
        $insertStmt->close();
        $successMsg = "Department Created SuccessFully!";
        $_SESSION['successMsg'] = $successMsg;
        // Redirect to the desired page after successful insertion
        header("Location: ../pages/createDpt.php");
        exit(); // Ensure script stops here
    }
    $checkStmt->close();
    $conn->close();
}

// Function to sanitize input (you might already have this)
function sanitizeInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}
?>
