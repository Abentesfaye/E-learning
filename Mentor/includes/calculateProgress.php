<?php

include("../includes/conn.php");

// Function to calculate the progress of a topic
function calculateTopicProgress($chapterId, $topicId) {
    global $conn;

    // Retrieve educational content for the topic from the database
    $query = "SELECT video, note, file_ FROM educationcontent WHERE chapter_id = ? AND topic_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $chapterId, $topicId);
    $stmt->execute();
    $stmt->bind_result($video, $note, $file);
    $stmt->fetch();
    $stmt->close();

    // Assign completion percentages based on the presence of each type of content
    $completionPercentage = 0;
    if (!empty($video)) {
        $completionPercentage += 50;
    }
    if (!empty($note)) {
        $completionPercentage += 25;
    }
    if (!empty($file)) {
        $completionPercentage += 25;
    }

    return $completionPercentage;
}

// Function to calculate the progress of a chapter
function calculateChapterProgress($chapterId) {
    global $conn;

    // Retrieve topics for the chapter from the database
    $query = "SELECT id FROM topics WHERE chapter_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $chapterId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $totalTopics = $result->num_rows;
    $totalProgress = 0;

    // Calculate progress for each topic
    while ($row = $result->fetch_assoc()) {
        $topicId = $row['id'];
        $topicProgress = calculateTopicProgress($chapterId, $topicId);
        $totalProgress += $topicProgress;
    }

    // Calculate the average progress for the chapter
    if ($totalTopics > 0) {
        $chapterProgress = $totalProgress / $totalTopics;
    } else {
        $chapterProgress = 0;
    }

    return $chapterProgress;
}

// Function to calculate the overall progress of the course
function calculateOverallProgress($courseId) {
    global $conn;
    $mentorID = $_SESSION['mentorID'];

    // Retrieve chapters for the course from the database
    $query = "SELECT id FROM chapters WHERE course_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $courseId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $totalChapters = $result->num_rows;
    $totalProgress = 0;

    // Calculate progress for each chapter
    while ($row = $result->fetch_assoc()) {
        $chapterId = $row['id'];
        $chapterProgress = calculateChapterProgress($chapterId);
        $totalProgress += $chapterProgress;
    }

    // Calculate the average progress for the course
    if ($totalChapters > 0) {
        $overallProgress = $totalProgress / $totalChapters;
    } else {
        $overallProgress = 0;
    }

    // Update progress in the assignedcourse table
    $query = "UPDATE assignedcourse SET progress = ? WHERE course_id = ? AND mentor_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("dii", $overallProgress, $courseId, $mentorID);
    $stmt->execute();
    $stmt->close();

    return $overallProgress;
}



?>
