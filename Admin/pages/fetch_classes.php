<?php
// fetch_classes.php

// Check if the department ID is set in the request
if (isset($_GET['department_id'])) {
    $departmentId = $_GET['department_id'];

    // Include database connection code
    include("../includes/conn.php");

    // Prepare SQL query to fetch classes based on the department ID
    $classesQuery = "SELECT id, class_name FROM class WHERE department_id = ?";
    $classesStmt = $conn->prepare($classesQuery);
    $classesStmt->bind_param("s", $departmentId);
    $classesStmt->execute();
    $classesResult = $classesStmt->get_result();

    // Fetch and return class data as JSON
    $classData = array();
    while ($row = $classesResult->fetch_assoc()) {
        $classData[] = $row;
    }

    echo json_encode($classData);

    // Close database connections
    $classesStmt->close();
    $conn->close();
} else {
    // Return an error message if department ID is not provided
    echo json_encode(array('error' => 'Department ID not provided'));
}
?>
