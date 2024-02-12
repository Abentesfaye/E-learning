<?php
session_start();
include("../includes/conn.php");
include("../includes/notification.php");

if (!isset($_SESSION['adminID'])) {
    header('location: ../index.php');
    exit();
}

if (!isset($_GET['course_id']) || !isset($_GET['mentor_id'])) {
    $_SESSION['errorMsg'] = "Invalid request. Please select a course request.";
    header("location: ./mentorRequest.php");
    exit();
}

$courseRequestId = $_GET['course_id'];
$mentorId = $_GET['mentor_id'];

// Retrieve course request details
$query = "SELECT cr.*, m.first_name AS mentor_first_name, m.last_name AS mentor_last_name, 
                 c.course_name, d.department_name, cl.class_name
          FROM course_requests cr
          JOIN mentors m ON cr.mentor_id = m.mentor_id
          JOIN course c ON cr.course_id = c.id
          JOIN class cl ON c.class_id = cl.id
          JOIN department d ON cl.department_id = d.id
          WHERE cr.course_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $courseRequestId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['errorMsg'] = "Course request not found.";
    header("location: course_requests.php");
    exit();
}

$row = $result->fetch_assoc();
$courseName = $row['course_name'];
$mentorFullName = $row['mentor_first_name'] . ' ' . $row['mentor_last_name'];
$departmentName = $row['department_name'];
$className = $row['class_name'];

$stmt->close();

// Retrieve status from assigned course table
$queryStatus = "SELECT status FROM assignedcourse WHERE course_id = ? AND mentor_id = ?";
$stmtStatus = $conn->prepare($queryStatus);
$stmtStatus->bind_param("ii", $courseRequestId, $mentorId);
$stmtStatus->execute();
$stmtStatus->store_result();

if ($stmtStatus->num_rows === 0) {
    $status = "Not Reviewed";
} else {
    $stmtStatus->bind_result($status);
    $stmtStatus->fetch();
}


$stmtStatus->close();

$isSubmitted = $status === 'submited';

// Check if the course has been reviewed before and if there is a comment
$hasComment = false;
$comment = "";

if ($status === "reviewed") {
    $queryComment = "SELECT comment FROM course_requests WHERE course_id = ?";
    $stmtComment = $conn->prepare($queryComment);
    $stmtComment->bind_param("i", $courseRequestId);
    $stmtComment->execute();
    $stmtComment->store_result();

    if ($stmtComment->num_rows > 0) {
        $stmtComment->bind_result($comment);
        $stmtComment->fetch();
        $hasComment = true;
    }
    $stmtComment->close();
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
    <h2>Manage Course Request</h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?php echo $courseName; ?></h5>
            <p class="card-text">Requested by: <?php echo $mentorFullName; ?></p>
            <p class="card-text">Department: <?php echo $departmentName; ?></p>
            <p class="card-text">Class: <?php echo $className; ?></p>
            <p class="card-text">Status: <?php echo $status; ?></p>
            <?php if ($isSubmitted): ?>
                <a target="_blank" href="review.php?course_id=<?php echo $courseRequestId; ?>&mentor_id=<?php echo $mentorId; ?>" class="btn btn-primary">
                    <?php echo ($hasComment) ? "Continue Reviewing" : "Start Reviewing"; ?>
                </a>
                <?php elseif ($status == "rejected"): ?>
                <p class="text-danger"> Rejected can not reversed</p> 
                <?php elseif ($status == "confirmed"): ?>
                <p class="text-success">Confired Before No need review!</p> 
            <?php else: ?>
                <a target="_blank" href="review.php?course_id=<?php echo $courseRequestId; ?>&mentor_id=<?php echo $mentorId; ?>" class="btn btn-primary">
                    <?php echo ($hasComment) ? "Review Again with Comment" : "Continue Reviewing"; ?>
                </a>
            <?php endif; ?>
        </div>
        <?php if ($hasComment): ?>
            <div class="card-footer">
                <p><strong>Comment:</strong> <?php echo $comment; ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

    </main>
</body>
</html>