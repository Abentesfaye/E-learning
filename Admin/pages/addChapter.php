<?php
$courseId = $_GET['course_id'];

// Include your connection and other necessary code here

// Example content, adjust as needed
echo "<h2>Create Chapter</h2>";
echo "<p>Course ID: $courseId</p>";
echo "<label for='chapter_name'>Chapter Name:</label>";
echo "<input type='text' name='chapter_name' id='chapter_name'>";
echo "<br>";
echo "<button type='submit'>Submit Chapter</button>";
?>
