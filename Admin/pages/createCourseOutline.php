<?php
include("../includes/conn.php");

// Get course ID from the URL
$courseId = $_GET['course_id'];

// Retrieve course details
$queryCourse = "SELECT c.id, c.course_name, d.department_name, cl.class_name 
FROM course c
INNER JOIN class cl ON c.class_id = cl.id
INNER JOIN department d ON cl.department_id = d.id
WHERE c.id = ?";

$stmtCourse = $conn->prepare($queryCourse);
$stmtCourse->bind_param("i", $courseId);
$stmtCourse->execute();
$resultCourse = $stmtCourse->get_result();

// Check if there are any results
if ($resultCourse->num_rows > 0) {
    // Fetch course data
    while ($row = $resultCourse->fetch_assoc()) {
        $courseName = $row['course_name'];
        $departmentName = $row['department_name'];
        $className = $row['class_name'];
    }
} else {
    // Handle case where no course is found for the provided ID
    echo "Course not found";
}

// Retrieve assigned mentors
$queryMentors = "SELECT m.first_name, m.last_name
FROM mentors m
INNER JOIN assignedcourse a ON m.mentor_id = a.mentor_id
WHERE a.course_id = ?";

$stmtMentors = $conn->prepare($queryMentors);
$stmtMentors->bind_param("i", $courseId);
$stmtMentors->execute();
$resultMentors = $stmtMentors->get_result();

// Retrieve chapters for the course
$queryChapters = "SELECT id, chapter_name FROM chapters WHERE course_id = ?";
$stmtChapters = $conn->prepare($queryChapters);
$stmtChapters->bind_param("i", $courseId);
$stmtChapters->execute();
$resultChapters = $stmtChapters->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Details</title>
    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../../font.css">
</head>
<body>

<div class="container">
    <div class="border mt-5 pl-5">
        <h2 class="text-center">Course Details</h2>
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="">
                    <p>Course ID: <?php echo $courseId; ?></p>
                    <p>Course Name: <?php echo $courseName; ?></p>
                    <p>Class: <?php echo $className; ?></p>
                    <p>department: <?php echo  $departmentName; ?></p>
                  
                </div>
            </div>
            <div class="col-md-6">
                <div class="">
                    <h4 class="text-center">Assigned Mentors</h4>
                    <p class="text-center"> <?php
                        // Display assigned mentor information
                        if ($resultMentors->num_rows > 0) {
                            while ($rowMentors = $resultMentors->fetch_assoc()) {
                                $mentorFirstName = $rowMentors['first_name'];
                                $mentorLastName = $rowMentors['last_name'];
                                echo "<p class='text-center'> $mentorFirstName $mentorLastName</p>";
                            }
                        } else {
                            echo "<p>No Mentor Assigned</p>";
                        }
                        ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6">
            <form id="actionForm">
                <label for="action">Select Action:</label>
                <select name="action" id="action">
                    <option value="create_chapter">Create Chapter</option>
                    <option value="add_topic">Add Topic</option>
                </select>
                <br>
                <label for="chapter_id">Existing Chapters:</label>
                <div id="existingChapters">
                    <?php
                    if ($resultChapters->num_rows > 0) {
                        echo "<ul>";
                        while ($rowChapters = $resultChapters->fetch_assoc()) {
                            $chapterName = $rowChapters['chapter_name'];
                            echo "<li>$chapterName</li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "<p>No chapters available</p>";
                    }
                    ?>
                </div>
                <br>
            </form>
        </div>
        <div class="col-md-6" id="rightSideContent">
            <!-- Right Side Content will be dynamically updated here -->
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function () {
        $('#action').on('change', function () {
            updateRightSide();
        });

        // Initial load
        updateRightSide();
    });

    function updateRightSide() {
        var selectedAction = $('#action').val();
        var rightSideContent = $('#rightSideContent');
        var course_id = <?php echo $courseId; ?>;

        rightSideContent.html('');

        if (selectedAction === 'create_chapter' || selectedAction === 'add_topic') {
            var iframeSrc = selectedAction === 'create_chapter' ? 'addChapter.php' : 'addTop.php';
            iframeSrc += '?course_id=' + course_id;

            var iframe = $('<iframe>', {
                src: iframeSrc,
                id: 'dynamicIframe',
                frameborder: 0,
                scrolling: 'no',
                width: '100%',
                height: '100%'
            });

            rightSideContent.append(iframe);
        }
    }
</script>

</body>
</html>