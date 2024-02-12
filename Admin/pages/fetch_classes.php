<?php

if (isset($_GET['department_id'])) {
    $departmentId = $_GET['department_id'];

    include("../includes/conn.php");


    $classesQuery = "SELECT id, class_name FROM class WHERE department_id = ?";
    $classesStmt = $conn->prepare($classesQuery);
    $classesStmt->bind_param("s", $departmentId);
    $classesStmt->execute();
    $classesResult = $classesStmt->get_result();


    $classData = array();
    while ($row = $classesResult->fetch_assoc()) {
        $classData[] = $row;
    }

    echo json_encode($classData);


    $classesStmt->close();
    $conn->close();
} else {

    echo json_encode(array('error' => 'Department ID not provided'));
}
?>
