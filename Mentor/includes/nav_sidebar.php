<?php
include("conn.php");
// Set the active page variable
$activePage = isset($_SESSION['activePage']) ? $_SESSION['activePage'] : '';
$mentorID = $_SESSION['mentorID'];

// Retrieve mentor information
$checkQuery = "SELECT * FROM mentors WHERE mentor_id = ?";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param("i", $mentorID);
$checkStmt->execute();
$result = $checkStmt->get_result();
if ($result->num_rows > 0) {
    $mentorData = $result->fetch_assoc();
} else {
    header('location: ../index.php');
    exit();
}
?>

    <section id='sidebar'>
        <a href='#' class='brand'>
            <i class='bx bxs-smile'></i>
            <span class='text'><?php echo $mentorData['first_name']?> <php</span>
        </a>
        <ul class='side-menu top'>
            <li class='<?php echo ($activePage === 'dashboard') ? 'active' : ''; ?>'>
                <a href='./dashboard.php' >
                    <i class='bx bxs-dashboard' ></i>
                    <span class='text'>Dashboard</span>
                </a>
            </li>
            <li class='<?php echo ($activePage === 'Course') ? 'active' : ''; ?>'>
                <a href='./courses.php' >
                    <i class='bx bxs-shopping-bag-alt' ></i>
                    <span class='text'>Course</span>
                </a>
            </li>
            <li class='<?php echo ($activePage === 'ManageCourse') ? 'active' : ''; ?>'>
                <a href='./createDpt.php' >
                    <i class='bx bxs-shopping-bag-alt' ></i>
                    <span class='text'>Manage Course</span>
                </a>
            </li>
            <li class='<?php echo ($activePage === 'createClass') ? 'active' : ''; ?>'>
                <a href='./createClass.php' >
                    <i class='bx bxs-shopping-bag-alt' ></i>
                    <span class='text'>Create Class</span>
                </a>
            </li>
            <li class='<?php echo ($activePage === 'createCourse') ? 'active' : ''; ?>'>
                <a href='./createCourse.php' >
                    <i class='bx bxs-shopping-bag-alt' ></i>
                    <span class='text'>Create Course</span>
                </a>
            </li>
            <!-- Add other navigation links as needed -->
        </ul>
        <ul class='side-menu'>
            <li>
                <a href='#'>
                    <i class='bx bxs-cog' ></i>
                    <span class='text'>Settings</span>
                </a>
            </li>
            <li>
                <a href='../pages/logout.php' class='logout'>
                    <i class='bx bxs-log-out-circle' ></i>
                    <span class='text'>Logout</span>
                </a>
            </li>
        </ul>
    </section>
    <section id='content'>
    <nav>
        <i class='bx bx-menu' ></i>
        <a href='#' class='notification'>
            <i class='bx bxs-bell' ></i>
            <span class='num'>8</span>
        </a>
        <a href='#' class='profile'>
            <img src='<?php echo $mentorData['photo']?>'>
        </a>
    </nav>

