<?php
session_start();

// Include necessary files and start the session
include("../includes/conn.php");
include("../includes/notification.php");
include("../includes/calculateProgress.php");
// Prevent direct access
if (!isset($_SESSION["mentorID"])) {
    header('location: ../index.php');
    exit();
}

// Initialize variables
$courseId = null;
$mentorId = $_SESSION['mentorID'];

// Check if course_id is set in the URL
if (!isset($_GET['course_id'])) {
    $_SESSION["errorMsg"] = "Course not found";
    header("location: manageCourse.php");
    exit();
}

// Assign the course_id value
$courseId = $_GET['course_id'];
$_SESSION['course_id'] = $courseId;
// Check if error or success message exists in session and display them
if (isset($_SESSION['errorMsg'])) {
    showNotification($_SESSION['errorMsg']);
    unset($_SESSION['errorMsg']);
}
if (isset($_SESSION['successMsg'])) {
    showGoodNotification($_SESSION['successMsg']);
    unset($_SESSION['successMsg']);
}

// Retrieve assigned course details
$query = "SELECT ac.status, c.course_name, d.department_name, cl.class_name
          FROM assignedcourse ac
          INNER JOIN course c ON ac.course_id = c.id
          INNER JOIN class cl ON c.class_id = cl.id
          INNER JOIN department d ON cl.department_id = d.id
          WHERE ac.course_id = ? AND ac.mentor_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $courseId, $mentorId);
$stmt->execute();
$result = $stmt->get_result();

// Check if a row was found
if (!$row = $result->fetch_assoc()) {
    $_SESSION["errorMsg"] = "Course not found";
    header("location: manageCourse.php");
    exit();
}

$status = $row['status'];
$courseName = $row['course_name'];
$departmentName = $row['department_name'];
$className = $row['class_name'];

// Retrieve all chapters for the course
$queryChapters = "SELECT id, chapter_number, chapter_name FROM chapters WHERE course_id = ?";
$stmtChapters = $conn->prepare($queryChapters);
$stmtChapters->bind_param("i", $courseId);
$stmtChapters->execute();
$resultChapters = $stmtChapters->get_result();
// Retrieve status from assigned course table
$queryStatus = "SELECT status FROM assignedcourse WHERE course_id = ? AND mentor_id = ?";
$stmtStatus = $conn->prepare($queryStatus);
$stmtStatus->bind_param("ii", $courseId, $mentorId);
$stmtStatus->execute();
$stmtStatus->store_result();

if ($stmtStatus->num_rows === 0) {
   exit("");
} else {
    $stmtStatus->bind_result($status);
    $stmtStatus->fetch();
}
$stmtStatus->close();

$hasComment = false;
$comment = "";

if ($status === "reviewed") {
    $queryComment = "SELECT comment FROM course_requests WHERE course_id = ?";
    $stmtComment = $conn->prepare($queryComment);
    $stmtComment->bind_param("i", $courseId);
    $stmtComment->execute();
    $stmtComment->store_result();

    if ($stmtComment->num_rows > 0) {
        $stmtComment->bind_result($comment);
        $stmtComment->fetch();
        $hasComment = true;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../font.css">

    <style>
       /* Custom CSS for Progress Bars */
        .progress-bar-custom {
            background-color: #28a745; /* Green color */
            border-color: #28a745; /* Border color same as background color */
            color: #fff; /* Text color */
        }

        /* Custom JS for Progress Bars remains the same */

        /* Custom background colors for different status */
        .status-not_prepared { background-color: #f8d7da; color: #721c24; }
        .status-preparing { background-color: #ffeeba; color: #856404; }
        .status-submited { background-color: #d4edda; color: #155724; }
        .status-reviewing { background-color: #cce5ff; color: #004085; }
        .status-reviewed { background-color: #d6d8d9; color: #383d41; }
        .status-confirmed { background-color: #d4edda; color: #155724; }
        .status-rejected { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
<main>
      <div id="notificationtext" class="notificationtext"></div>  
    <div class="container">
        <h2 class="text-center mt-5">Manage Assigned Course</h2>
        
        <!-- Course Analytics Section -->
        <div class="row mt-5">
    <div class="col-md-12 border">
        <h5 class="text-center">Course Analytics</h5>
        <div class="row">
            <div class="col-md-12">
                <p><strong>Overall Progress:</strong></p>
                <div class="progress">
                    <div id="overallProgress" class="progress-bar progress-bar-striped progress-bar-custom" role="progressbar" style="width: <?php echo calculateOverallProgress($courseId); ?>%;" aria-valuenow="<?php echo calculateOverallProgress($courseId); ?>" aria-valuemin="0" aria-valuemax="100"><?php echo calculateOverallProgress($courseId); ?>%</div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <p><strong>Chapter-wise Progress:</strong></p>
                <div class="accordion" id="chapterAccordion">
                    <?php
                    if ($resultChapters->num_rows > 0) {
                        while ($rowChapters = $resultChapters->fetch_assoc()) {
                            // Calculate progress for the current chapter
                            $chapterProgress = calculateChapterProgress($rowChapters['id']);
                            ?>
                            <div class="card">
                                <div class="card-header" id="heading<?php echo $rowChapters['id']; ?>">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse<?php echo $rowChapters['id']; ?>" aria-expanded="true" aria-controls="collapse<?php echo $rowChapters['id']; ?>">
                                            Chapter <?php echo $rowChapters['chapter_number']; ?>: <?php echo $rowChapters['chapter_name']; ?>
                                        </button>
                                    </h2>
                                </div>

                                <div id="collapse<?php echo $rowChapters['id']; ?>" class="collapse" aria-labelledby="heading<?php echo $rowChapters['id']; ?>" data-parent="#chapterAccordion">
                                    <div class="card-body">
                                        <p><strong>Progress:</strong></p>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-custom" role="progressbar" style="width: <?php echo $chapterProgress; ?>%;" aria-valuenow="<?php echo $chapterProgress; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $chapterProgress; ?>%</div>
                                        </div>
                                        <p><strong>Topic-wise Progress:</strong></p>
                                        <ul>
                                            <?php
                                            // Fetch topics for the current chapter
                                            $queryTopics = "SELECT topic_name, id FROM topics WHERE chapter_id = ?";
                                            $stmtTopics = $conn->prepare($queryTopics);
                                            $stmtTopics->bind_param("i", $rowChapters['id']);
                                            $stmtTopics->execute();
                                            $resultTopics = $stmtTopics->get_result();
                                            if ($resultTopics->num_rows > 0) {
                                                while ($rowTopics = $resultTopics->fetch_assoc()) {
                                                    // Calculate progress for the current topic
                                                    $topicProgress = calculateTopicProgress($rowChapters['id'], $rowTopics['id']);
                                                    ?>
                                                    <li>
                                                        <?php echo $rowTopics['topic_name']; ?>
                                                        <span class="progress-text"><?php echo $topicProgress; ?>%</span>
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-striped progress-bar-custom" role="progressbar" style="width: <?php echo $topicProgress; ?>%;" aria-valuenow="<?php echo $topicProgress; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </li>
                                                    <?php
                                                }
                                            } else {
                                                echo "<li>No topics available</li>";
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p>No chapters available</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Actions Section -->
        <div class="row mt-5 border">
    <div class="col-md-12 mb-5 ">
        <?php 
            if ($status === "reviewing") {
                echo "<p class='text-center text-danger'><strong>Sorry, you cannot perform any actions while the Admin is reviewing the course.</strong></p>";
            } elseif ($status === "rejected") {
                echo "<p class='text-center text-danger'><strong>Sorry, the Admin has rejected your proposal. Thank you for your effort. The Admin will assign you to another course.</strong></p>";
            } elseif ($status === "confirmed") {
                echo "<p class='text-center text-success'><strong>Congratulations! The course is approved. No need to update anything.</strong></p>";
            } else {
        ?>
        <h5 class="text-center">Actions</h5>
        <div class="container">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addUpdateVideoModal"><i class='bx bx-video'></i> Add/Update Video</button>
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addUpdateNoteModal"><i class='bx bx-note'></i> Add/Update Note</button>
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addUpdateReferenceModal"><i class='bx bx-book-open'></i> Add/Update Reference</button>
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addcoverModal"><i class='bx bx-book-open'></i> Add/Update Course cover image</button>
            <button type="button" class="btn btn-success btn-sm"><a href="../serverSide/submitReq.php" class="text-white">Send to admin for review</a></button>
            <button type="button" class="btn btn-success btn-sm mt-3"><a href="./publicview.php" class="text-white">Public view</a></button>
            <button class="btn btn-primary btn-sm mt-3"><a href="../../Admin/serverSide/pdf.php?courseId=<?php echo $courseId; ?>" class="text-white"><i class='bx bxs-file-pdf'></i> Download Course Outline</a></button>
        </div>
        <?php } ?>
    </div>
</div>

        <!-- Course Details Section -->
        <div class="row mt-5">
            <div class="col-md-6 border">
                <h5 class="text-center">Course Details</h5>
                <div class="container">
                    <p>Course Name: <?php echo $courseName; ?></p>
                    <p>Department: <?php echo $departmentName; ?></p>
                    <p>Status: &nbsp;&nbsp;<span class="btn status-<?php echo $status; ?>"><?php echo $status; ?></span></p>
                    <p>Grade: <?php echo $className; ?></p>
                    <?php if ($hasComment): ?>
            <div class="card-footer">
                <p><strong>Comment:</strong> <?php echo $comment; ?></p>
            </div>
        <?php endif; ?>
                </div>
              
            </div>
            <div class="col-md-6 border">
                <h5 class="text-center text-success">Course Outline</h5>
                <div id="existingChapters">
                    <?php
                    // Reset resultChapters pointer to beginning
                    $resultChapters->data_seek(0);

                    if ($resultChapters->num_rows > 0) {
                        while ($rowChapters = $resultChapters->fetch_assoc()) {
                            // Calculate progress for the current chapter
                            $chapterProgress = calculateChapterProgress($rowChapters['id']);
                            ?>
                            <div class="card">
                                <div class="card-header" id="heading<?php echo $rowChapters['id']; ?>">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse<?php echo $rowChapters['id']; ?>" aria-expanded="true" aria-controls="collapse<?php echo $rowChapters['id']; ?>">
                                            Chapter <?php echo $rowChapters['chapter_number']; ?>: <?php echo $rowChapters['chapter_name']; ?>
                                        </button>
                                    </h2>
                                </div>
                    
                                <div id="collapse<?php echo $rowChapters['id']; ?>" class="collapse" aria-labelledby="heading<?php echo $rowChapters['id']; ?>" data-parent="#chapterAccordion">
                                    <div class="card-body">
                                        <p><strong>Progress:</strong></p>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-custom" role="progressbar" style="width: <?php echo $chapterProgress; ?>%;" aria-valuenow="<?php echo $chapterProgress; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $chapterProgress; ?>%</div>
                                        </div>
                                    </div>
                    
                                    <!-- Topic list with progress -->
                                    <div class="card-body">
                                        <p><strong>Topic-wise Progress:</strong></p>
                                        <ul>
                                            <?php
                                            // Fetch topics for the current chapter
                                            $queryTopics = "SELECT topic_name, id FROM topics WHERE chapter_id = ?";
                                            $stmtTopics = $conn->prepare($queryTopics);
                                            $stmtTopics->bind_param("i", $rowChapters['id']);
                                            $stmtTopics->execute();
                                            $resultTopics = $stmtTopics->get_result();
                                            if ($resultTopics->num_rows > 0) {
                                                while ($rowTopics = $resultTopics->fetch_assoc()) {
                                                    // Calculate progress for the current topic
                                                    $topicProgress = calculateTopicProgress($rowChapters['id'], $rowTopics['id']);
                                                    ?>
                                                    <li>
                                                        <?php echo $rowTopics['topic_name']; ?>
                                                        <?php echo $topicProgress; ?>%
                                                    </li>
                                                    <?php
                                                }
                                            } else {
                                                echo "<li>No topics available</li>";
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p>No chapters available</p>";
                    }
                    ?>
                </div>                    
    </div>

    <div class="modal fade" id="addUpdateVideoModal" tabindex="-1" role="dialog" aria-labelledby="addUpdateVideoModalLabel" aria-hidden="true">
    <!-- Modal content -->
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUpdateVideoModalLabel">Add/Update Video</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Dropdown for selecting chapters -->
                <form action="../serverSide/addVideo.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="chapterDropdown">Chapter:</label>
                    <select id="chapterDropdown" name="chapterDropdown" class="form-control mb-2">
                        <?php
                        // Fetch chapters for the course from the database
                        $queryChapters = "SELECT id, chapter_number, chapter_name FROM chapters WHERE course_id = ?";
                        $stmtChapters = $conn->prepare($queryChapters);
                        $stmtChapters->bind_param("i", $courseId);
                        $stmtChapters->execute();
                        $resultChapters = $stmtChapters->get_result();

                        // Check if chapters are available
                        if ($resultChapters->num_rows > 0) {
                            // Loop through each chapter and create options for the dropdown
                            while ($rowChapter = $resultChapters->fetch_assoc()) {
                                echo "<option value='" . $rowChapter['id'] . "'>Chapter " . $rowChapter['chapter_number'] . ": " . $rowChapter['chapter_name'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>No chapters available</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="topicDropdown">Topic:</label>
                    <select id="topicDropdown" name="topicDropdown" class="form-control">
                        <!-- Topics Dropdown - Filled by JavaScript -->
                    </select>
                </div>
                <!-- Input fields for video title, URL, and description -->
                <div class="form-group">
                    <label for="videoTitle">Video Title:</label>
                    <input type="text" class="form-control" id="videoTitle" name="videoTitle" placeholder="Enter video title" required>
                </div>
                <div class="form-group mb-2 mt-1">
                    <label for="videoFile">Upload Video:</label>
                    <input type="file" class="form-control-file border" name="videoFile" id="videoFile" required>

                </div>
                <div class="form-group">
                    <label for="thumbnailFile">Upload Thumbnail:</label>
                    <input type="file" class="form-control-file border" id="thumbnailFile" name="thumbnailFile" accept="image/*" required>
                </div>
                <div class="form-group">
                    <label for="videoDescription">Video Description:</label>
                    <textarea class="form-control" id="videoDescription" name="videoDescription" placeholder="Enter video description"></textarea>
                </div>
                <!-- Dropdown for selecting topics (will be filled by JavaScript) -->
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="addUpdateNoteModal" tabindex="-1" role="dialog" aria-labelledby="addUpdateNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUpdateNoteModalLabel">Add/Update Note</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Dropdown for selecting chapters -->
                <form action="../serverSide/addNote.php" method="post">
                <div class="form-group">
                    <label for="chapterDropdownNote">Chapter:</label>
                    <select id="chapterDropdownNote" name="chapterDropdown" class="form-control">
                        <?php
                        // Reset resultChapters pointer to beginning
                        $resultChapters->data_seek(0);

                        // Check if chapters are available
                        if ($resultChapters->num_rows > 0) {
                            // Loop through each chapter and create options for the dropdown
                            while ($rowChapter = $resultChapters->fetch_assoc()) {
                                echo "<option value='" . $rowChapter['id'] . "'>Chapter " . $rowChapter['chapter_number'] . ": " . $rowChapter['chapter_name'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>No chapters available</option>";
                        }
                        ?>
                    </select>
                </div>
                <!-- Dropdown for selecting topics -->
                <div class="form-group">
                    <label for="topicDropdown">Topic:</label>
                    <select id="topicDropdown" name="topicDropdown" class="form-control" required>
                        <!-- Topics Dropdown - Filled by JavaScript -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="noteDescription">Note:</label>
                    <textarea class="form-control" id="noteDescription" name="note" placeholder="Enter note here"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="saveNoteBtn">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
        </form>
    </div>
</div>

<div class="modal fade" id="addUpdateReferenceModal" tabindex="-1" role="dialog" aria-labelledby="addUpdateReferenceModalLabel" aria-hidden="true">
    <!-- Modal content -->
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUpdateReferenceModalLabel">Add/Update Reference</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="../serverSide/addFile.php" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- Dropdown for selecting chapters (same as in Add/Update Video Modal) -->
                    <div class="form-group">
                        <label for="chapterDropdownRefenance">Chapter:</label>
                        <select id="chapterDropdownRefenance" name="chapterDropdown" class="form-control">
                            <?php
                            // Reset resultChapters pointer to beginning
                            $resultChapters->data_seek(0);

                            // Check if chapters are available
                            if ($resultChapters->num_rows > 0) {
                                // Loop through each chapter and create options for the dropdown
                                while ($rowChapter = $resultChapters->fetch_assoc()) {
                                    echo "<option value='" . $rowChapter['id'] . "'>Chapter " . $rowChapter['chapter_number'] . ": " . $rowChapter['chapter_name'] . "</option>";
                                }
                            } else {
                                echo "<option value=''>No chapters available</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="topicDropdown">Topic:</label>
                        <select id="topicDropdown" name="topicDropdown" class="form-control" required>
                            <!-- Topics Dropdown - Filled by JavaScript -->
                        </select>
                    </div>
                    <!-- Input fields for reference title, file upload, and description -->
                    <div class="form-group">
                        <label for="referenceTitle">Reference Title:</label>
                        <input type="text" class="form-control" id="referenceTitle" name="file_title" placeholder="Enter reference title">
                    </div>
                    <div class="form-group">
                        <label for="referenceFile">Reference File:</label>
                        <input type="file" class="form-control-file border" id="referenceFile" name="referenceFile" accept=".pdf,.doc,.docx,.ppt,.pptx">
                    </div>
                    <div class="form-group">
                        <label for="referenceDescription">Reference Description:</label>
                        <textarea class="form-control" id="referenceDescription" name="fileDescription" placeholder="Enter reference description"></textarea>
                    </div>
            
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="addcoverModal" tabindex="-1" role="dialog" aria-labelledby="addcoverModalLabel" aria-hidden="true">
    <!-- Modal content -->
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUpdateVideoModalLabel">Add/Update Course Cover</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../serverSide/addcover.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="coverImage">Upload Cover Image:</label>
                    <input type="file" class="form-control-file border" id="courseCover" name="coverImage" accept="image/*" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
// Function to fetch topics for a selected chapter
function fetchTopicsForChapter(chapterId, modalId) {
    // Fetch topics for the selected chapter from the server using AJAX
    $.ajax({
        url: 'fetchTopics.php', // Replace with the actual URL to fetch topics
        method: 'GET',
        data: { chapterId: chapterId }, // Pass the chapter ID here
        success: function(response) {
            // Log response to check if data is fetched correctly
            console.log('Fetched topics:', response);
            // Populate topics dropdown in the modal with fetched data
            $(modalId + ' #topicDropdown').html(response);
        },
        error: function(xhr, status, error) {
            // Handle error
            console.error('Error fetching topics:', error);
        }
    });
}

// Call fetchTopicsForChapter function when a chapter is selected
$('#chapterDropdown').change(function() {
    // Get the selected chapter ID (which corresponds to the 'id' field in the database)
    var selectedChapterId = $(this).val(); // Use the value of the selected option
    // Get the modal ID of the Add/Update Video modal
    var modalId = '#addUpdateVideoModal';
    // Call fetchTopicsForChapter function to fetch topics for the selected chapter
    fetchTopicsForChapter(selectedChapterId, modalId);
});
    // Call fetchTopicsForChapter function when a chapter is selected
    $('#chapterDropdownNote').change(function() {
        // Get the selected chapter ID (which corresponds to the 'id' field in the database)
        var selectedChapterId = $(this).val(); // Use the value of the selected option
        // Get the modal ID of the Add/Update Note modal
        var modalId = '#addUpdateNoteModal';
        // Call fetchTopicsForChapter function to fetch topics for the selected chapter
        fetchTopicsForChapter(selectedChapterId, modalId);
    });
       // Call fetchTopicsForChapter function when a chapter is selected
       $('#chapterDropdownRefenance').change(function() {
        // Get the selected chapter ID (which corresponds to the 'id' field in the database)
        var selectedChapterId = $(this).val(); // Use the value of the selected option
        // Get the modal ID of the Add/Update Note modal
        var modalId = '#addUpdateReferenceModal';
        // Call fetchTopicsForChapter function to fetch topics for the selected chapter
        fetchTopicsForChapter(selectedChapterId, modalId);
    });
    addUpdateReferenceModal
    </script>
</body>
</html>
