<?php
include("../includes/conn.php");
include("../includes/notification.php");
session_start(); 
// Get course ID from the URL
$courseId = $_GET['course_id'];

// Check if error or success message exists in session and display them

if (isset($_SESSION['errorMsg'])) {
    $errorMsg = $_SESSION['errorMsg'];
    showNotification($errorMsg);
    // Clear the error message from the session to prevent displaying it multiple times
    unset($_SESSION['errorMsg']);
}
if (isset($_SESSION['successMsg'])) {
    $successMsg = $_SESSION['successMsg'];
    showGoodNotification($successMsg);
    // Clear the error message from the session to prevent displaying it multiple times
    unset($_SESSION['successMsg']);
}
if (!isset($_SESSION['adminID'])) {
    header('location: ../index.php');
}

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
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../font.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Style to make the button look visually disabled */
        .btn-light-darker {
            background-color: #d6d8d9;
            color: #495057;
            border-color: #c8cbcf;
        }

        .btn-light-darker:hover {
            background-color: #c8cbcf;
            color: #495057;
            border-color: #b9bcc0;
        }
    </style>
</head>
<body>
<?php include("../includes/nav_sidebar.php"); ?>
<main>
        <?php include("../includes/pageHeader.php"); ?>
<div class="container">
    <h2 class="text-center">Course Details</h2>
    <div class="border mt-5 pl-5">
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="">
                    <p>Course ID: <?php echo $courseId; ?></p>
                    <p>Course Name: <?php echo $courseName; ?></p>
                    <p>Class: <?php echo $className; ?></p>
                    <p>Department: <?php echo $departmentName; ?></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="">
                    <h4 class="text-center">Assigned Mentors</h4>
                    <p class="text-center">
                        <?php
                        if ($resultMentors->num_rows > 0) {
                            while ($rowMentors = $resultMentors->fetch_assoc()) {
                                $mentorFirstName = $rowMentors['first_name'];
                                $mentorLastName = $rowMentors['last_name'];
                                echo "<p class='text-center'> $mentorFirstName $mentorLastName</p>";
                            }
                        } else {
                            echo "<p>No Mentor Assigned</p>";
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-6">
            <form id="actionForm">
                <label>Select Action:</label>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary" onclick="openModal('#addChapterModal')">
                        Create Chapter
                    </button>
                    <button type="button" class="btn btn-primary" onclick="openModal('#addTopicModal')">
                        Add Topic
                    </button>
                </div>
                <br>
               
        </div>
        <div class="col-md-6 border">
        <label for="chapter_id" class="mt-2">Existing Chapters:</label>
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
    </div>
</div>
</main>
                </section>
                <div id="notificationtext" class="notificationtext"></div>   

<!-- Add Chapter Modal -->
<div class="modal fade" id="addChapterModal" tabindex="-1" role="dialog" aria-labelledby="addChapterModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addChapterModalLabel">Add Chapter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../serverSide/addchapter.php" method="post">
                    <input type="hidden" name="course_id" value="<?php echo $courseId; ?>">
                    <div class="form-group">
                        <label for="chapterNumber">Chapter Number:</label>
                        <input type="number" class="form-control" name="chapterNumber" required>
                    </div>
                    <div class="form-group">
                        <label for="chapterName">Chapter Name:</label>
                        <input type="text" class="form-control" name="chapterName" required>
                    </div>
                    <div class="form-group">
                        <label for="chapterDescription">Chapter Description:</label>
                        <textarea class="form-control" name="chapterDescription" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Add Chapter</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Topic Modal -->
<div class="modal fade" id="addTopicModal" tabindex="-1" role="dialog" aria-labelledby="addTopicModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTopicModalLabel">Add Topic</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../serverSide/addtopic.php" method="post">
                    <input type="hidden" name="course_id" value="<?php echo $courseId; ?>">
                    <div class="form-group">
                        <label for="topicName">Topic Name:</label>
                        <input type="text" class="form-control" name="topicName" required>
                    </div>
                    <div class="form-group">
                        <label for="topicDescription">Topic Description:</label>
                        <textarea class="form-control" name="topicDescription" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="chapterId">Select Chapter:</label>
                        <select class="form-control" name="chapterId" required>
                            <?php
                            // Fetch chapters for dropdown
                            $resultChapters->data_seek(0);
                            while ($rowChapters = $resultChapters->fetch_assoc()) {
                                $chapterId = $rowChapters['id'];
                                $chapterName = $rowChapters['chapter_name'];
                                echo "<option value='$chapterId'>$chapterName</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Add Topic</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Ensure jQuery, Popper.js, and Bootstrap JS are included -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function openModal(modalId) {
        $(modalId).modal('show');
    }
</script>

</body>
</html>
