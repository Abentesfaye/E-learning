<?php
session_start();
if (!isset($_SESSION['userID'])){
    header('Location:  ../index.html');
}

require_once '../includes/header.php'; 
include("../includes/conn.php");
include("../includes/notification.php");

// Check if there is an error message in the session
if (isset($_SESSION['errorMsg'])) {
    $errorMsg = $_SESSION['errorMsg'];
    showNotification($errorMsg);
    // Clear the error message from the session to prevent displaying it multiple times
    unset($_SESSION['errorMsg']);
}
if (isset($_SESSION['successMsg'])) {
    $successMsg = $_SESSION['successMsg'];
    showgoodNotification($successMsg);
    // Clear the error message from the session to prevent displaying it multiple times
    unset($_SESSION['successMsg']);
}

if (!isset($_SESSION['userID'])) {
    $_SESSION['errorMsg'] = "You are not logged in.";
    exit;
}


$query = "SELECT course.id AS course_id, course.course_name, coursecover.cover, class.class_name, department.department_name
          FROM assignedcourse
          INNER JOIN course ON assignedcourse.course_id = course.id
          INNER JOIN class ON course.class_id = class.id
          INNER JOIN department ON class.department_id = department.id
          LEFT JOIN coursecover ON course.id = coursecover.course_id
          WHERE assignedcourse.status = 'confirmed'";

$result = mysqli_query($conn, $query);

$confirmedCourses = [];


if (mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_assoc($result)) {
        $confirmedCourses[] = $row;
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="../logo/logo.png" href="../../assets//logo/logo.png" />
    <title>Home</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../font.css">
    <style>
               .navbar-brand {
            color: #fff; 
            font-size: 24px; 
            font-weight: bold;
        }
        .navbar-toggler-icon {
            background-color: #fff;
            border: 2px solid #fff;
            border-radius: 4px;
        }
        .navbar-toggler-icon span {
            background-color: #fff;
            display: block;
            width: 20px;
            height: 2px;
            margin-bottom: 4px;
        }
        .nav-link {
            color: #fff; 
            font-size: 18px;
            font-weight: bold;
        }
        .nav-link:hover {
            color: #fff;
        }
        .form-control {
            border-radius: 20px; 
        }
        .rounded-circle span {
            color: #fff; 
            font-size: 20px;
            line-height: 50px; 
            display: inline-block; 
            width: 50px;
            height: 50px; 
            text-align: center;
        }
        .bg-success {
            background-color: #28a745 !important; 
            border-color: #28a745 !important; 
        }
        .navbar-collapse {
            justify-content: flex-end;
        }
        .navbar-nav {
            margin-right: 20px;
        }
        .card-img-top {
            height: 150px;
            object-fit: cover; 
        }
    </style>
</head>
<body>
 <!-- Add the notification element -->
 <div id="notificationtext" class="notificationtext"></div>  
<section class="container mt-5">
    <h2>Courses</h2>

    <div class="row">
        <?php foreach ($confirmedCourses as $course) { ?>
        <div class="col-md-3 mb-3">
            <div class="card">
                <img src="<?php echo $course['cover']; ?>" class="card-img-top" alt="Course Cover">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $course['course_name']; ?></h5>
                    <p class="card-text"><strong>Class:</strong> <?php echo $course['class_name']; ?></p>
                    <p class="card-text"><strong>Department:</strong> <?php echo $course['department_name']; ?></p>
                    <button type="button" class="btn btn-primary enroll-btn" data-course-id="<?php echo $course['course_id']; ?>">Enroll Now</button>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</section>
<div class="modal fade" id="enrollModal" tabindex="-1" role="dialog" aria-labelledby="enrollModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="enrollModalLabel">Enroll Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to enroll in this course?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <a href="onboarding.php" id="confirmEnrollBtn" class="btn btn-primary">Enroll</a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function(){
        $('.enroll-btn').click(function(){
            var courseId = $(this).data('course-id');
            $('#confirmEnrollBtn').attr('href', 'enroll.php?course_id=' + courseId);
            $('#enrollModal').modal('show');
        });
    });
</script>
</body>
</html>
