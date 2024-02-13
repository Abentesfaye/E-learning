<?php
session_start();
include("../includes/conn.php");
$errorMsg = "";
$successMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $chapterNumber = sanitizeInput($_POST["chapterNumber"]);
    $chapterName = sanitizeInput($_POST["chapterName"]);
    $chapterDescription = sanitizeInput($_POST["chapterDescription"]);
    $courseId = sanitizeInput($_POST["course_id"]);

    // Check if chapter number already exists for the course
    $queryCheckChapterNumber = "SELECT * FROM chapters WHERE course_id = ? AND chapter_number = ?";
    $stmtCheckChapterNumber = $conn->prepare($queryCheckChapterNumber);
    $stmtCheckChapterNumber->bind_param("ii", $courseId, $chapterNumber);
    $stmtCheckChapterNumber->execute();
    $resultCheckChapterNumber = $stmtCheckChapterNumber->get_result();

    if ($resultCheckChapterNumber->num_rows > 0) {
        $errorMsg = "Error: Chapter  $chapterNumber already exists for this course.";
        $_SESSION["errorMsg"] = $errorMsg;
        header("location: ../pages/createCourseOutline.php?course_id=$courseId");
        
        exit; 
    } else {
        // Check if chapter name already exists for the course
        $queryCheckChapterName = "SELECT * FROM chapters WHERE course_id = ? AND chapter_name = ?";
        $stmtCheckChapterName = $conn->prepare($queryCheckChapterName);
        $stmtCheckChapterName->bind_param("is", $courseId, $chapterName);
        $stmtCheckChapterName->execute();
        $resultCheckChapterName = $stmtCheckChapterName->get_result();

        if ($resultCheckChapterName->num_rows > 0) {
            $errorMsg = "Error: Chapter with name $chapterName already exists for this course.";
            $_SESSION["errorMsg"] = $errorMsg;
            header("location: ../pages/createCourseOutline.php?course_id=$courseId");
           
            exit; 
        } else {
            // Insert new chapter
            $queryInsertChapter = "INSERT INTO chapters (course_id, chapter_number, chapter_name, description) VALUES (?, ?, ?, ?)";
            $stmtInsertChapter = $conn->prepare($queryInsertChapter);
            $stmtInsertChapter->bind_param("iiss", $courseId, $chapterNumber, $chapterName, $chapterDescription);
            if ($stmtInsertChapter->execute()) {
                $successMsg = "Chapter created successfully.";
                $_SESSION["successMsg"] = $successMsg;
                header("location: ../pages/createCourseOutline.php?course_id=$courseId");
               
                exit; 
            } else {
                $errorMsg = "Error creating chapter.";
                $_SESSION["errorMsg"] = $errorMsg;
                header("location: ../pages/createCourseOutline.php?course_id=$courseId");
                echo '<script>window.location.reload();</script>';
                exit; 
            }
        }
    }
}

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>
