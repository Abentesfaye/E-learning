<?php
// Include TCPDF library
require_once('../tcpdf/tcpdf.php');

// Include database connection
include("../includes/conn.php");

// Get course ID from the URL
$courseId = $_GET['courseId'];

// Fetch course information from the database
$queryCourse = "SELECT c.course_name, d.department_name, cl.class_name
                FROM course c
                INNER JOIN class cl ON c.class_id = cl.id
                INNER JOIN department d ON cl.department_id = d.id
                WHERE c.id = ?";
$stmtCourse = $conn->prepare($queryCourse);
$stmtCourse->bind_param("i", $courseId);
$stmtCourse->execute();
$resultCourse = $stmtCourse->get_result();

// Check if course exists
if ($resultCourse->num_rows > 0) {
    // Fetch course data
    $rowCourse = $resultCourse->fetch_assoc();
    $courseName = $rowCourse['course_name'];
    $departmentName = $rowCourse['department_name'];
    $className = $rowCourse['class_name'];

    // Create new PDF document
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Mettu University');
    $pdf->SetTitle('Course Outline');
    $pdf->SetSubject('Course Outline');
    $pdf->SetKeywords('Course, Outline, Mettu University');

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('helvetica', '', 10); 

    // Generate PDF content
    $html = '
    <div style="text-align: center;">
        <img src="../../assets/logo/logo.jpg" alt="Mettu University Logo" style="width: 100px; height: auto;">
        <h1 style="color: #008000;">Mettu University E-learning</h1>
    </div>
    <div style="margin-top: -30px;">
        <h2 style="text-align: center;">Course Outline</h2>
        <p><strong>Course Title:</strong> ' . $courseName . '</p>
        <p><strong>Department:</strong> ' . $departmentName . '</p>
        <p><strong>Class:</strong> ' . $className . '</p>
    ';

    // Fetch chapters for the course
    $queryChapters = "SELECT id, chapter_name, chapter_number, description FROM chapters WHERE course_id = ?";
    $stmtChapters = $conn->prepare($queryChapters);
    $stmtChapters->bind_param("i", $courseId);
    $stmtChapters->execute();
    $resultChapters = $stmtChapters->get_result();

    // Check if chapters exist
    if ($resultChapters->num_rows > 0) {
        // Loop through each chapter
        while ($rowChapter = $resultChapters->fetch_assoc()) {
            $chapterId = $rowChapter['id'];
            $chapterName = $rowChapter['chapter_name'];
            $chapterNumber = $rowChapter['chapter_number'];
            $chapterDescription = $rowChapter['description'];

            // Add chapter details
            $html .= '<div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 20px;">';
            $html .= '<h3 style="color: #008000;">Chapter ' . $chapterNumber . ': ' . $chapterName . '</h3>';
            $html .= '<p>' . $chapterDescription . '</p>';

            // Fetch topics for the current chapter
            $queryTopics = "SELECT topic_name, description FROM topics WHERE chapter_id = ?";
            $stmtTopics = $conn->prepare($queryTopics);
            $stmtTopics->bind_param("i", $chapterId);
            $stmtTopics->execute();
            $resultTopics = $stmtTopics->get_result();

            // Check if topics exist
            if ($resultTopics->num_rows > 0) {
                // Add topics as sublist
                $html .= '<ul>';
                // Loop through each topic
                while ($rowTopic = $resultTopics->fetch_assoc()) {
                    $topicName = $rowTopic['topic_name'];
                    $topicDescription = $rowTopic['description'];

                    // Add topic details as list item
                    $html .= '<li><strong style="color: #008000;">' . $topicName . '</strong>: ' . $topicDescription . '</li>';
                }
                $html .= '</ul>';
            } else {
                $html .= '<p>No topics found for this chapter</p>';
            }

            $html .= '</div>'; // Close chapter div
        }
    } else {
        $html .= '<p>No chapters found for this course</p>';
    }

    // Close the HTML content
    $html .= '</div>';

    // Write HTML content to PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Close and output PDF document
    $pdf->Output($courseName . '_course_outline.pdf', 'D');

} else {
    echo "Course not found";
}
?>
