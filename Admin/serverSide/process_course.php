<?php
session_start();
include("../includes/conn.php");

$errorMsg = "";
$successMsg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $department_id = sanitizeInput($_POST["department"]);
    $class_id = sanitizeInput($_POST["class"]);
    $course_name = sanitizeInput($_POST["courseName"]);

    // Check if the selected department and class exist
    $checkDepartmentQuery = "SELECT id FROM department WHERE id = ?";
    $checkDepartmentStmt = $conn->prepare($checkDepartmentQuery);
    $checkDepartmentStmt->bind_param("s", $department_id);
    $checkDepartmentStmt->execute();
    $checkDepartmentStmt->store_result();

    if ($checkDepartmentStmt->num_rows > 0) {
        $checkClassQuery = "SELECT id FROM class WHERE id = ?";
        $checkClassStmt = $conn->prepare($checkClassQuery);
        $checkClassStmt->bind_param("s", $class_id);
        $checkClassStmt->execute();
        $checkClassStmt->store_result();

        if ($checkClassStmt->num_rows > 0) {
            // Check if the course already exists in the selected class
            $checkCourseQuery = "SELECT id FROM course WHERE course_name = ? AND class_id = ?";
            $checkCourseStmt = $conn->prepare($checkCourseQuery);
            $checkCourseStmt->bind_param("ss", $course_name, $class_id);
            $checkCourseStmt->execute();
            $checkCourseStmt->store_result();

            if ($checkCourseStmt->num_rows > 0) {
                $errorMsg = "Course already exists in the selected class!";
                $_SESSION['errorMsg'] = $errorMsg;
                header('Location: ../pages/createCourse.php');
            } else {
                // Insert course into the database
                $insertQuery = "INSERT INTO course (course_name, class_id) VALUES (?, ?)";
                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->bind_param("ss", $course_name, $class_id);
                $insertStmt->execute();

                $insertStmt->close();

                $successMsg = "Course Created Successfully!";
                $_SESSION['successMsg'] = $successMsg;

                header("Location: ../pages/createCourse.php");
                exit();
            }
        } else {
            $errorMsg = "Selected Class does not exist!";
            $_SESSION['errorMsg'] = $errorMsg;
            header('Location: ../pages/createCourse.php');
        }
    } else {
        $errorMsg = "Selected Department does not exist!";
        $_SESSION['errorMsg'] = $errorMsg;
        header('Location: ../pages/createCourse.php');
    }

    // Close connections
    $checkDepartmentStmt->close();
    $checkClassStmt->close();
    $checkCourseStmt->close();
    $conn->close();
} else {
    // Handle cases where the form was not submitted
    header('Location: ../pages/createCourse.php');
    exit();
}
function sanitizeInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}
?>
