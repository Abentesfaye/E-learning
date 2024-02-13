<?php

include("../includes/conn.php");

if (isset($_POST['class_id'])) {
    $classId = $_POST['class_id'];

    // Fetch courses based on the selected class
    $courseSql = "SELECT * FROM courses WHERE class_id = $classId";
    $courseResult = $conn->query($courseSql);

    $options = "";
    while ($course = $courseResult->fetch_assoc()) {
        $options .= "<option value='" . $course['course_id'] . "'>" . $course['course_name'] . "</option>";
    }

    echo $options;
}


$conn->close();
?>
