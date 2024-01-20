<?php
session_start(); // Ensure session is started
// Set the active page variable
$activePage = "manageMentor";
$_SESSION['activePage'] = $activePage;

include("../includes/conn.php");

// SQL query to select data from mentors table and sort by status
$sql = "SELECT * FROM mentors ORDER BY FIELD(status, 'pending', 'rejected', 'active')";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- Include Bootstrap CSS -->
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
            <h2>Mentor List</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch data from the database and populate the table
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['mentor_id'] . "</td>";
                            echo "<td>" . $row['first_name'] . "</td>";
                            echo "<td>" . $row['last_name'] . "</td>";
                            echo "<td>" . $row['phone_number'] . "</td>";
                            echo "<td>" . $row['email_address'] . "</td>";
                            echo "<td>" . $row['department'] . "</td>";
                            echo "<td>" . $row['status'] . "</td>";
                            echo "<td><a href='mentorDetail.php?mentorId=" . $row['mentor_id'] . "' target='_blank' class='btn btn-primary'>Manage</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No mentors found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
    <section>
</body>
<!-- Include Bootstrap JS -->
<script src="../../bootstrap/js/jquery.slim.min.js"></script>
<script src="../../bootstrap/js/popper.min.js"></script>
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<script src="../js/script.js"></script>
</html>
