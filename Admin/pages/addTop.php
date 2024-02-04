<?php
$courseId = $_GET['course_id'];

// Include your connection and other necessary code here

// Example content, adjust as needed
echo "<h2>Add Topic</h2>";
echo "<p>Course ID: $courseId</p>";
echo "<label for='topic_name'>Topic Name:</label>";
echo "<input type='text' name='topic_name' id='topic_name'>";
echo "<br>";
echo "<button type='submit'>Submit Topic</button>";
?>
