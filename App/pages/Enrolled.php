<?php
session_start();
if (!isset($_SESSION['userID'])){
    header('Location:  ../index.html');
}
require_once '../includes/header.php'; 
include("../includes/conn.php");


if (!isset($_SESSION['userID'])) {
    $_SESSION['errorMsg'] = "You are not logged in.";
    exit;
}


$userID = $_SESSION['userID'];


$query = "SELECT course.id AS course_id, course.course_name, class.class_name, department.department_name, coursecover.cover AS cover_url
          FROM enrolled
          INNER JOIN course ON enrolled.course_id = course.id
          INNER JOIN class ON course.class_id = class.id
          INNER JOIN department ON class.department_id = department.id
          INNER JOIN coursecover ON course.id = coursecover.course_id
          WHERE enrolled.user_id = $userID";

$result = mysqli_query($conn, $query);

$enrolledCourses = [];


if (mysqli_num_rows($result) > 0) {
    // Store enrolled courses in an array
    while ($row = mysqli_fetch_assoc($result)) {
        $enrolledCourses[] = $row;
    }
} else {
    // If the user is not enrolled in any courses
    $errorMessage = "You are not enrolled in any courses yet.";
}


mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrolled Courses</title>
   
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../font.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .enrolled-courses {
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }
        .course-card {
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }
        .course-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card-img-top {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card-body {
            text-align: center;
            padding: 20px;
        }
        .card-title {
            font-size: 20px;
            margin-bottom: 10px;
        }
        .continue-btn {
            width: 150px;
        }
        .no-courses {
            text-align: center;
            font-size: 18px;
            color: #6c757d;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="enrolled-courses">
        <?php if (isset($enrolledCourses) && count($enrolledCourses) > 0) { ?>
            <h2 class="text-center mb-4">Enrolled Courses</h2>
            <div class="row">
                <?php foreach ($enrolledCourses as $course) { ?>
                    <div class="col-md-6">
                        <div class="card course-card">
                            <img src="<?php echo $course['cover_url']; ?>" class="card-img-top" alt="Course Cover">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $course['course_name']; ?></h5>
                                <p class="card-text"><strong>Class:</strong> <?php echo $course['class_name']; ?></p>
                                <p class="card-text"><strong>Department:</strong> <?php echo $course['department_name']; ?></p>
                                <a href="education_content.php?course_id=<?php echo $course['course_id']; ?>" class="btn btn-primary continue-btn">Continue Learning</a>

                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <p class="no-courses"><?php echo $errorMessage; ?></p>
        <?php } ?>
    </div>
</body>
</html>
