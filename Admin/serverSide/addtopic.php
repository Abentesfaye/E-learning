<?php
session_start();
include("../includes/conn.php");
$errorMsg = "";
$successMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $topicName = sanitizeInput($_POST["topicName"]);
    $chapterId = sanitizeInput($_POST["chapterId"]);
    $topicDescription = sanitizeInput($_POST["topicDescription"]);
    $courseId = sanitizeInput($_POST["course_id"]);

   
    // Insert the topic into the database
    $query = "INSERT INTO topics (chapter_id, topic_name, description) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $chapterId, $topicName, $description);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the page where the topic was added
        $_SESSION["successMsg"] = "Topic added to the chapter succesfully!";
        header("location: ../pages/createCourseOutline.php?course_id=$courseId");
        exit();
    } else {
        // Handle the error if the insertion fails
        $_SESSION["errorMsg"] = "Error: Unable to add the topic.";
        header("location: ../pages/createCourseOutline.php?course_id=$courseId");
        exit();
    }
}

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>
