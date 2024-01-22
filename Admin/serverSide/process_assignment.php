<?php
session_start();
include("../includes/conn.php");

$errorMsg = "";
$successMsg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $department_id = sanitizeInput($_POST["department"]);
    $class_id = sanitizeInput($_POST["class"]);
    $course_id = sanitizeInput($_POST["course"]);
    $mentor_id = $_SESSION['mentorID'];

    // Fetch mentor details
    $mentorSql = "SELECT * FROM mentors WHERE mentor_id = ?";
    $mentorStmt = $conn->prepare($mentorSql);
    $mentorStmt->bind_param("i", $mentor_id);
    $mentorStmt->execute();
    $mentorResult = $mentorStmt->get_result();
    $mentorDetails = $mentorResult->fetch_assoc();

    // Fetch course details
    $courseSql = "SELECT * FROM course WHERE id = ?";
    $courseStmt = $conn->prepare($courseSql);
    $courseStmt->bind_param("s", $course_id);
    $courseStmt->execute();
    $courseResult = $courseStmt->get_result();
    $courseDetails = $courseResult->fetch_assoc();

    // Fetch class details
    $classSql = "SELECT * FROM class WHERE id = ?";
    $classStmt = $conn->prepare($classSql);
    $classStmt->bind_param("s", $class_id);
    $classStmt->execute();
    $classResult = $classStmt->get_result();
    $classDetails = $classResult->fetch_assoc();

    // Fetch department details
    $departmentSql = "SELECT * FROM department WHERE id = ?";
    $departmentStmt = $conn->prepare($departmentSql);
    $departmentStmt->bind_param("s", $department_id);
    $departmentStmt->execute();
    $departmentResult = $departmentStmt->get_result();
    $departmentDetails = $departmentResult->fetch_assoc();

    // Check if the course is already assigned to the mentor
    $checkQuery = "SELECT id FROM AssignedCourse WHERE course_id = ? AND mentor_id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ss", $course_id, $mentor_id);

    if (!$checkStmt->execute()) {
        // Handle the execution error
        $errorMsg = "Error executing check query: " . $checkStmt->error;
        $_SESSION['errorMsg'] = $errorMsg;
        header('Location: ../pages/manageMentor.php');
        exit();
    }

    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $errorMsg = "This Mentor is already Assigned to this Course!";
        $_SESSION['errorMsg'] = $errorMsg;
        header('Location: ../pages/manageMentor.php');
        exit();
    }

    $checkStmt->close();

    // Insert the assignment if it doesn't exist
    $insertQuery = "INSERT INTO AssignedCourse (course_id, mentor_id) VALUES (?, ?)";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param("ss", $course_id, $mentor_id);

    if ($insertStmt->execute()) {
        $successMsg = "Assigned Successfully!";
        $_SESSION['successMsg'] = $successMsg;

        // Send email to mentor
        $to = $mentorDetails['email_address'];
        $subject = "Assigned To A Course";
        $message = "
            <html>
            <head>
                <style>
                    body {
                        font-family: 'Arial', sans-serif;
                        background-color: #f4f4f4;
                        padding: 20px;
                    }

                    .email-container {
                        max-width: 600px;
                        margin: 0 auto;
                        background-color: #ffffff;
                        padding: 20px;
                        border-radius: 10px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    }

                    h2 {
                        color: #333333;
                        font-size: 20px
                    }

                    p {
                        color: #555555;
                    }
                </style>
            </head>
            <body>
                <div class='email-container'>
                    <h2>Assigned To A Course</h2>
                    <p>Dear {$mentorDetails['first_name']} {$mentorDetails['last_name']},</p>
                    <p>You are assigned to the course {$courseDetails['course_name']} for the class {$classDetails['class_name']} in the department of {$departmentDetails['department_name']}. 
                    You will receive the course outline and other information in your Mentor dashboard. 
                    Please sign in and prepare the course.</p>
                    <br>
                    <p>Best regards,<br>Admin</p>
                </div>
            </body>
            </html>
        ";

        $headers = "From: abentesfaye11@gmail.com\r\n";
        $headers .= "Content-type: text/html\r\n";

        mail($to, $subject, $message, $headers);

        header('Location: ../pages/dashboard.php');
    } else {
        $errorMsg = "Error assigning course: " . $insertStmt->error;
        $_SESSION['errorMsg'] = $errorMsg;
        header('Location: ../pages/dashboard.php');
    }

    $insertStmt->close();
    $mentorStmt->close();
    $courseStmt->close();
    $classStmt->close();
    $departmentStmt->close();
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
