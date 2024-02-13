<?php
session_start();
include("../includes/conn.php");
include("../includes/notification.php");

$secondPage = "createCourse";
$activePage = "ManageCourse";
$_SESSION['next'] = $secondPage;
$_SESSION['activePage'] = $activePage;

if (!isset($_SESSION['adminID'])) {
    header('location: ../index.php');
    exit();
}

// Retrieve data from the database
$query = "SELECT c.id, c.course_name, d.department_name, cl.class_name 
          FROM course c
          INNER JOIN class cl ON c.class_id = cl.id
          INNER JOIN department d ON cl.department_id = d.id";

$result = $conn->query($query);

// Check if there is an error message in the session
if (isset($_SESSION['errorMsg'])) {
    $errorMsg = $_SESSION['errorMsg'];
    showNotification($errorMsg);
    unset($_SESSION['errorMsg']);
}

if (isset($_SESSION['successMsg'])) {
    $successMsg = $_SESSION['successMsg'];
    showGoodNotification($successMsg);
    unset($_SESSION['successMsg']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../font.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <?php include("../includes/nav_sidebar.php"); ?>

    <!-- MAIN -->
    <main>
        <?php include("../includes/pageHeader.php"); ?>
        <div class="container mt-5">

            <?php if ($result->num_rows > 0): ?>
                <!-- Display the list of courses with associated department and class information -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Course Name</th>
                            <th>Department</th>
                            <th>Class</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['course_name']; ?></td>
                                <td><?php echo $row['department_name']; ?></td>
                                <td><?php echo $row['class_name']; ?></td>
                                <td>
                                 
                                    <a href="#" title="Update Course" data-toggle="tooltip" data-placement="top" data-original-title="Edit">
                                        <i class="bx bxs-edit"></i>
                                    </a>
                                    <a href="#" title="Delete Course" data-toggle="tooltip" data-placement="top" data-original-title="Delete" class="delete-course" data-course-id="<?php echo $row['id']; ?>">
                                        <i class="bx bxs-trash"></i>
                                    </a>
                                    <a href="createCourseOutline.php?course_id=<?php echo $row['id']; ?>" title="Add Course Outline" data-toggle="tooltip" data-placement="top" data-original-title="Add Outline">
                                        <i class="bx bx-plus"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">No courses found. please Create Course First.</p>
            <?php endif; ?>

        </div>
        <div id="notificationtext" class="notificationtext"></div> 
        <script src="../../bootstrap/js/jquery.slim.min.js"></script>
        <script src="../../bootstrap/js/popper.min.js"></script>
        <script src="../../bootstrap/js/bootstrap.min.js"></script>
        <script src="../js/script.js"></script>
        <script src="../js/fetchClass.js"></script>

        <script>
         
            $(document).ready(function () {
                // Show delete modal when the delete icon is clicked
                $('body').on('click', '.delete-course', function () {
                    var courseId = $(this).data('course-id');
                    $('#confirmDelete').attr('href', 'delete_course.php?course_id=' + courseId);
                    $('#deleteModal').modal('show');
                });
            });
        </script>
    </main>
</body>
</html>
