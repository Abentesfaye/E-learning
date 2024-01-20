<?php
session_start(); // Ensure session is started
// Set the active page variable
$activePage = "manageMentor";
$_SESSION['activePage'] = $activePage;

include("../includes/conn.php");
include("../includes/notification.php");

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
                            $mentorId = $row['mentor_id'];
                            $status = $row['status'];

                            // Set the button attributes based on the status
                            $buttonClass = '';
                            $modalMessage = '';

                            switch ($status) {
                                case 'pending':
                                    $buttonClass = 'btn-light-darker';
                                    $modalMessage = 'This Mentor is not Approved create Lecture!';
                                    break;
                                case 'rejected':
                                    $buttonClass = 'btn-light-darker';
                                    $modalMessage = 'This Mentor is Rejected Can not create Course!';
                                    break;

                                case 'active':
                                    $buttonClass = 'btn-success';
                                    $modalMessage = '';
                                    break;

                                default:
                                    break;
                            }
                            ?>
                            <tr>
                                <td><?php echo $row['mentor_id']; ?></td>
                                <td><?php echo $row['first_name']; ?></td>
                                <td><?php echo $row['last_name']; ?></td>
                                <td><?php echo $row['phone_number']; ?></td>
                                <td><?php echo $row['email_address']; ?></td>
                                <td><?php echo $row['department']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                                <td>
                                    <a href='mentorDetail.php?mentorId=<?php echo $mentorId; ?>' target='_blank' class='btn btn-primary'>Manage</a>
                                    <!-- Assign button with data-toggle for the modal -->
                                    <button type="button" class="btn <?php echo $buttonClass; ?> mt-1"
                                            data-toggle="modal" data-target="#assignModal<?php echo $mentorId; ?>"
                                            onclick="handleAssignClick('<?php echo $status; ?>', '<?php echo $modalMessage; ?>')">
                                        Assign
                                    </button>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='8'>No mentors found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Include Bootstrap JS -->
    <script src="../../bootstrap/js/bootstrap.min.js"></script>
    <script src="../js/script.js"></script>
  
    
    <script>
        function handleAssignClick(status, message) {
            if(status !== 'active'){
                alert('Error: ' + message);
               
            }
            

            if (status === 'active') {
                // Add logic to open a new window or perform other actions
                window.open('mentor_add.php?mentorId=<?php echo $mentorId; ?>');
            }
        }
    </script>
</body>
</html>
