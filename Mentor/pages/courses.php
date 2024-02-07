<?php
include("../includes/conn.php"); 
include("../includes/notification.php");

session_start(); // Ensure session is started
// Set the active page variable
$activePage = "Course";
$_SESSION['activePage'] = $activePage;
if (isset($_SESSION['errorMsg'])) {
    $errorMsg = $_SESSION['errorMsg'];
    showNotification($errorMsg);
    // Clear the error message from the session to prevent displaying it multiple times
    unset($_SESSION['errorMsg']);
}
if (isset($_SESSION['successMsg'])) {
    $successMsg = $_SESSION['successMsg'];
    showGoodNotification($successMsg);
    // Clear the error message from the session to prevent displaying it multiple times
    unset($_SESSION['successMsg']);
}
if (!isset($_SESSION['mentorID'])) {
    header('location: ../index.php');
}

// Fetch assigned course IDs for the mentor
$mentorID = $_SESSION['mentorID'];
$queryAssignedCourses = "SELECT course_id FROM assignedcourse WHERE mentor_id = ?";
$stmtAssignedCourses = $conn->prepare($queryAssignedCourses);
$stmtAssignedCourses->bind_param("i", $mentorID);
$stmtAssignedCourses->execute();
$resultAssignedCourses = $stmtAssignedCourses->get_result();

// Array to store assigned courses
$assignedCourses = array();

// Check if assigned courses exist
if ($resultAssignedCourses->num_rows > 0) {
    // Loop through assigned courses and store course IDs
    while ($rowAssignedCourse = $resultAssignedCourses->fetch_assoc()) {
        $assignedCourses[] = $rowAssignedCourse['course_id'];
    }
}

// Fetch course details for assigned courses
$assignedCoursesDetails = array();

// Check if there are assigned courses
if (!empty($assignedCourses)) {
    // Construct comma-separated list of course IDs
    $courseIDs = implode(",", $assignedCourses);
    
    // Query to fetch course details for assigned courses
    $queryCourses = "SELECT c.id, c.course_name, d.department_name, cl.class_name
                     FROM course c
                     INNER JOIN class cl ON c.class_id = cl.id
                     INNER JOIN department d ON cl.department_id = d.id
                     WHERE c.id IN ($courseIDs)";
    
    // Execute query
    $resultCourses = $conn->query($queryCourses);
    
    // Check if courses are found
    if ($resultCourses->num_rows > 0) {
        // Fetch course details and store in array
        while ($rowCourse = $resultCourses->fetch_assoc()) {
            $assignedCoursesDetails[] = $rowCourse;
        }
    }
}

// Close the database connection
$conn->close();
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

    <?php include("../includes/nav_sidebar.php"); ?>
        
    <!-- MAIN -->
    <main>
        <?php include("../includes/pageHeader.php"); ?>
        
        <div class="container mt-5">
            <!-- List assigned courses -->
          <!-- List assigned courses -->
<h2>Assigned Courses</h2>
<?php if (!empty($assignedCoursesDetails)) : ?>
    <table class="table">
        <thead>
            <tr>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Department</th>
                <th>Class</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($assignedCoursesDetails as $course) : ?>
                <tr style="cursor: pointer;">
    <a href="other_page.php?course_id=<?php echo $course['id']; ?>" style="display: block; width: 100%; height: 100%;">
        <!-- Content of the row -->
        <td><?php echo $course['id']; ?></td>
        <td><?php echo $course['course_name']; ?></td>
        <td><?php echo $course['department_name']; ?></td>
        <td><?php echo $course['class_name']; ?></td>
    </a>
</tr>

            <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p>You are currently not assigned to any courses. Please stay tuned until the admin assigns you to a course.</p>
<?php endif; ?>

                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
