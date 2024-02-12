<?php
session_start();
include("../includes/conn.php");

// Redirect if mentorID is not set
if (!isset($_SESSION["mentorID"])) {
    header('location: ../index.php');
    exit();
}


$mentorId = $_SESSION['mentorID'];
$courseId = $_SESSION['course_id'];


$queryCourse = "SELECT c.course_name, c.class_id, cl.class_name, cl.department_id, cc.cover
                FROM course c
                INNER JOIN class cl ON c.class_id = cl.id
                LEFT JOIN coursecover cc ON c.id = cc.course_id
                WHERE c.id = ?";
$stmtCourse = $conn->prepare($queryCourse);
$stmtCourse->bind_param("i", $courseId);
$stmtCourse->execute();
$stmtCourse->store_result();

if ($stmtCourse->num_rows > 0) {
    $stmtCourse->bind_result($courseName, $class_id, $className, $dpt_id, $coverImage);
    $stmtCourse->fetch();
} else {
    $_SESSION['errorMsg'] = "No course found.";
    header("location: ../pages/manageCourse.php?course_id=$courseId");
    exit(); 
}
$stmtCourse->close();


$queryDepartment = "SELECT department_name FROM department WHERE id = ?";
$stmtDepartment = $conn->prepare($queryDepartment);
$stmtDepartment->bind_param("i", $dpt_id);
$stmtDepartment->execute();
$stmtDepartment->store_result();

if ($stmtDepartment->num_rows > 0) {
    $stmtDepartment->bind_result($dptName);
    $stmtDepartment->fetch();
} else {
    $_SESSION['errorMsg'] = "No department found.";
    header("location: ../pages/manageCourse.php?course_id=$courseId");
    exit(); 
}
$stmtDepartment->close();

$queryMentor = "SELECT first_name, last_name FROM mentors WHERE mentor_id = ?";
$stmtMentor = $conn->prepare($queryMentor);
$stmtMentor->bind_param("i", $mentorId);
$stmtMentor->execute();
$stmtMentor->store_result();

if ($stmtMentor->num_rows > 0) {
    $stmtMentor->bind_result($firstName, $lastName);
    $stmtMentor->fetch();
} else {
    $_SESSION['errorMsg'] = "No mentor found.";
    header("location: ../pages/manageCourse.php?course_id=$courseId");
    exit(); 
}
$stmtMentor->close();

$queryContent = "SELECT ch.chapter_number, ch.chapter_name, ch.description AS chapter_description, 
                        t.id AS topic_id, t.topic_name, t.description AS topic_description, 
                        ec.thumbnail AS video_thumbnail, ec.video, ec.video_title, ec.video_description,
                        ec.note, ec.file_title, ec.file_, ec.file_description
                FROM educationContent ec
                INNER JOIN chapters ch ON ec.chapter_id = ch.id
                INNER JOIN topics t ON ec.topic_id = t.id
                WHERE ec.course_id = ?";
$stmtContent = $conn->prepare($queryContent);
$stmtContent->bind_param("i", $courseId);
$stmtContent->execute();
$resultContent = $stmtContent->get_result();

// Structure data into an array
$contentData = array();
while ($row = $resultContent->fetch_assoc()) {
    $chapterNumber = $row['chapter_number'];
    if (!isset($contentData[$chapterNumber])) {
        $contentData[$chapterNumber] = array(
            'chapter_name' => $row['chapter_name'],
            'chapter_description' => $row['chapter_description'],
            'topics' => array()
        );
    }
    $contentData[$chapterNumber]['topics'][] = array(
        'topic_id' => $row['topic_id'],
        'topic_name' => $row['topic_name'],
        'topic_description' => $row['topic_description'],
        'video_thumbnail' => $row['video_thumbnail'],
        'video' => $row['video'],
        'video_title' => $row['video_title'],
        'video_description' => $row['video_description'],
        'note' => $row['note'],
        'file_title' => $row['file_title'],
        'file_' => $row['file_'],
        'file_description' => $row['file_description']
    );
}
$stmtContent->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../font.css">
    <style>
        .banner {
            position: relative;
            overflow: hidden;
            height: 300px;
        }
        .banner img {
            width: 100%;
            height: auto;
        }
        .banner .content {
            position: absolute;
            top: 80%;
            transform: translateY(-50%);
            width: 100%;
            text-align: center;
            color: white;
        }
        .subject {
            font-size: 4em;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .content p {
            font-size: 1.5em;
            margin-top: -30px;
        }
        .note-container {
    background-color: #f8f9fa; /* Light gray background */
    border: 1px solid #ced4da; /* Gray border */
    border-radius: 5px; /* Rounded corners */
    padding: 10px; /* Spacing inside the container */
    margin-top: 10px; /* Top margin */
}

.note-content {
    font-family: Arial, sans-serif; /* Use a common font */
    font-size: 16px; /* Adjust font size as needed */
    line-height: 1.5; /* Spacing between lines */
    color: #333; /* Dark text color */
}

/* Add any additional styles to enhance the appearance of the note container */

    </style>
</head>
<body>

<div class="banner">
    <img src="<?php echo $coverImage; ?>" class="img-fluid" alt="Course Cover Image">
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-left">
                    <h1 class="subject text-primary"><?php echo $courseName; ?></h1>
                    <p class="text-primary">Grade: <?php echo $className; ?></p>
                    <p class="text-primary text-bold"><?php echo $dptName; ?></p>
                </div>
                <div class="col-md-6 text-center">
                    <p>Prepared by Instructor <?php echo $firstName . ' ' . $lastName; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Section -->
<!-- Content Section -->
<div class="container mt-5">
    <div id="accordion">
        <?php foreach ($contentData as $chapterNumber => $chapter): ?>
            <div class="card mt-3">
                <div class="card-header" id="heading<?php echo $chapterNumber; ?>">
                    <h2 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse<?php echo $chapterNumber; ?>" aria-expanded="true" aria-controls="collapse<?php echo $chapterNumber; ?>">
                            Chapter <?php echo $chapterNumber; ?>: <?php echo $chapter['chapter_name']; ?>
                        </button>
                    </h2>
                </div>
                <div id="collapse<?php echo $chapterNumber; ?>" class="collapse" aria-labelledby="heading<?php echo $chapterNumber; ?>" data-parent="#accordion">
                    <div class="card-body">
                        <p>Chapter Description: <?php echo $chapter['chapter_description']; ?></p>
                        <?php foreach ($chapter['topics'] as $topic): ?>
                            <div class="card mt-3">
                                <div class="card-header" id="topicHeading<?php echo $topic['topic_id']; ?>">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#topicCollapse<?php echo $topic['topic_id']; ?>" aria-expanded="true" aria-controls="topicCollapse<?php echo $topic['topic_id']; ?>">
                                            Topic : <?php echo $topic['topic_name']; ?>
                                        </button>
                                    </h2>
                                </div>
                                <div id="topicCollapse<?php echo $topic['topic_id']; ?>" class="collapse" aria-labelledby="topicHeading<?php echo $topic['topic_id']; ?>" data-parent="#collapse<?php echo $chapterNumber; ?>">
    <div class="card-body">
        <h4>Topic Description:</h4>
        <p><?php echo $topic['topic_description']; ?></p>
        <div class="video-container">
            <video width="100%" height="auto" poster="<?php echo $topic['video_thumbnail']; ?>" controls>
                <source src="<?php echo $topic['video']; ?>" type="video/mp4">
                <!-- Add additional source tags for other video formats if needed -->
                Your browser does not support the video tag.
            </video>
        </div>
        <h4>Video Title:</h4>
        <p><?php echo $topic['video_title']; ?></p>
        <h4>Video Description:</h4>
        <p><?php echo $topic['video_description']; ?></p>
        <h4>Note:</h4>
        <div class="note-container">
    <div class="note-content">
        <p><?php echo $topic['note']; ?></p>
    </div>
</div>

        <h4>File:</h4>
        <div class="file-container">
            <h4 class="text-primary"><?php echo $topic['file_title']?></h4>
            <p><?php echo $topic['file_description']?></p>
            <a href="<?php echo $topic['file_']; ?>" download>Download File</a>
        </div>
    </div>
</div>

                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
