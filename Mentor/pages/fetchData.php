<?php
// Assume you have already included necessary files and started the session
session_start();
include("../includes/conn.php");
// Include necessary files and st
// Fetch chapters data
$queryChapters = "SELECT id, chapter_name FROM chapters WHERE course_id = ?";
$stmtChapters = $conn->prepare($queryChapters);
$stmtChapters->bind_param("i", $courseId);
$stmtChapters->execute();
$resultChapters = $stmtChapters->get_result();

// Fetch topics data
$queryTopics = "SELECT id, topic_name FROM topics WHERE chapter_id = ?";
$stmtTopics = $conn->prepare($queryTopics);

$data = array(
    'chapters' => '',
    'topics' => ''
);

// Prepare chapters dropdown options
if ($resultChapters->num_rows > 0) {
    while ($row = $resultChapters->fetch_assoc()) {
        $data['chapters'] .= "<option value='{$row['id']}'>{$row['chapter_name']}</option>";
    }
}

// If you have a default "Select Chapter" option, you can include it here
$data['chapters'] = "<option value=''>Select Chapter</option>" . $data['chapters'];

// Close prepared statement
$stmtChapters->close();

// Prepare topics dropdown options
$data['topics'] = "<option value=''>Select Topic</option>"; // Assuming you want a default "Select Topic" option

if (isset($_GET['chapter_id'])) {
    $chapterId = $_GET['chapter_id'];
    $stmtTopics->bind_param("i", $chapterId);
    $stmtTopics->execute();
    $resultTopics = $stmtTopics->get_result();

    if ($resultTopics->num_rows > 0) {
        while ($row = $resultTopics->fetch_assoc()) {
            $data['topics'] .= "<option value='{$row['id']}'>{$row['topic_name']}</option>";
        }
    }
}

// Close prepared statement
$stmtTopics->close();

// Encode data as JSON and output
echo json_encode($data);
?>
