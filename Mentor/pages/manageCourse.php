<?php
session_start();

// Include necessary files and start the session
include("../includes/conn.php");
include("../includes/notification.php");

// Prevent direct access
if (!isset($_SESSION["mentorID"])) {
    header('location: ../index.php');
    exit();
}

// Initialize variables
$courseId = null;
$mentorId = $_SESSION['mentorID'];

// Check if course_id is set in the URL
if (!isset($_GET['course_id'])) {
    $_SESSION["errorMsg"] = "Course not found";
    header("location: manageCourse.php");
    exit();
}

// Assign the course_id value
$courseId = $_GET['course_id'];

// Check if error or success message exists in session and display them
if (isset($_SESSION['errorMsg'])) {
    showNotification($_SESSION['errorMsg']);
    unset($_SESSION['errorMsg']);
}
if (isset($_SESSION['successMsg'])) {
    showGoodNotification($_SESSION['successMsg']);
    unset($_SESSION['successMsg']);
}

// Retrieve assigned course details
$query = "SELECT ac.status, c.course_name, d.department_name, cl.class_name
          FROM assignedcourse ac
          INNER JOIN course c ON ac.course_id = c.id
          INNER JOIN class cl ON c.class_id = cl.id
          INNER JOIN department d ON cl.department_id = d.id
          WHERE ac.course_id = ? AND ac.mentor_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $courseId, $mentorId); 
$stmt->execute();
$result = $stmt->get_result();

// Check if a row was found
if ($row = $result->fetch_assoc()) {
    $status = $row['status'];
    $courseName = $row['course_name'];
    $departmentName = $row['department_name'];
    $className = $row['class_name'];
} else {
    $_SESSION["errorMsg"] = "Course not found";
    header("location: manageCourse.php");
    exit();
}

// Retrieve chapters for the course
$queryChapters = "SELECT c.id, c.chapter_name, c.chapter_number, c.description, GROUP_CONCAT(t.topic_name SEPARATOR ', ') AS topics
                  FROM chapters c
                  LEFT JOIN topics t ON c.id = t.chapter_id
                  WHERE c.course_id = ?
                  GROUP BY c.id";
$stmtChapters = $conn->prepare($queryChapters);
$stmtChapters->bind_param("i", $courseId);
$stmtChapters->execute();
$resultChapters = $stmtChapters->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../font.css">
    <link rel="stylesheet" href="../style/dashboard.css">
    <style>
        /* Style to make the button look visually disabled */
        .btn-light-darker {
            background-color: #d6d8d9;
            color: #495057;
            border-color: #c8cbcf;
        }

        .btn-light-darker:hover {
            background-color: #c8cbcf;
            color: #495057;
            border-color: #b9bcc0;
        }
    </style>
</head>
<body>
<main>
    <div class="container">
        <h2 class="text-center mt-5">Manage Assigned Course</h2>
        <div class="border mt-5 pl-5">
            <div class="row mt-3">
                <div class="col-md-6">
                    <p>Course Name: <?php echo $courseName; ?></p>
                    <p>Department: <?php echo $departmentName; ?></p>
                </div>
                <div class="col-md-6">
                    <p>Status: &nbsp;&nbsp;<span class="btn btn-success"><?php echo $status; ?></span></p> 
                    <p>Grade: <?php echo $className; ?></p> 
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-6 border">
                <h5 class="text-center">Actions</h5>
                <div class="container">
                    <button class="btn btn-success">Create</button>
                    <button class="btn btn-primary">Update</button>
                    <button class="btn btn-danger">Delete</button>
                    <button class="btn btn-dark"><a href="../../Admin/serverSide/pdf.php?courseId=<?php echo $courseId; ?>" class="text-white">Download Course Outline</a></button>
                </div>
                <div class="container mt-5">
                    <h5 class="text-center">Course Anlytics</h5>
                </div>
            </div>
            <div class="col-md-6 border">
                <h5 class="text-center text-success">Course Outline</h5>
                <div id="existingChapters">
                    <?php
                    if ($resultChapters->num_rows > 0) {
                        while ($rowChapters = $resultChapters->fetch_assoc()) {
                            echo "<ul>";
                            echo "<li><b>Chapter {$rowChapters['chapter_number']}:</b> {$rowChapters['chapter_name']} - {$rowChapters['description']}<br>";
                            echo "<ul>";
                            if (!empty($rowChapters['topics'])) {
                                $topics = explode(', ', $rowChapters['topics']);
                                foreach ($topics as $topic) {
                                    echo "<li>$topic</li>";
                                }
                            } else {
                                echo "<li>No topics available</li>";
                            }
                            echo "</ul>";
                            echo "</li>";
                            echo "</ul>";
                        }
                    } else {
                        echo "<p>No chapters available</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div id="notificationtext" class="notificationtext"></div>   
</body>
</html>
