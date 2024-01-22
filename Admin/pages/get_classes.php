<?php
// fetch_courses.php

// Check if the class ID is set in the request
if (isset($_GET['class_id'])) {
    $classId = $_GET['class_id'];

    // Include database connection code
    include("../includes/conn.php");

    // Prepare SQL query to fetch courses based on the class ID
    $coursesQuery = "SELECT id, course_name FROM course WHERE class_id = ?";
    $coursesStmt = $conn->prepare($coursesQuery);
    $coursesStmt->bind_param("s", $classId);
    $coursesStmt->execute();
    $coursesResult = $coursesStmt->get_result();

    // Fetch and return course data as JSON
    $courseData = array();
    while ($row = $coursesResult->fetch_assoc()) {
        $courseData[] = $row;
    }

    echo json_encode($courseData);

    // Close database connections
    $coursesStmt->close();
    $conn->close();
} else {
    // Return an error message if class ID is not provided
    echo json_encode(array('error' => 'Class ID not provided'));
}
?>
