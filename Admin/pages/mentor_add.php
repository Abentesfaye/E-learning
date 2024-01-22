    <?php
    session_start();
    include("../includes/conn.php");
    include("../includes/notification.php");
    $errorMsg = "";
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
    $departmentsQuery = "SELECT id, department_name FROM department";
    $departmentsResult = $conn->query($departmentsQuery);
    // Get mentor ID from the query parameters
    if (isset($_GET['mentorId'])) {
        $mentorId = $_GET['mentorId'];
        $_SESSION['mentorID'] = $mentorId;
        // Fetch mentor details from the mentors table based on the mentorId
        $mentorSql = "SELECT * FROM mentors WHERE mentor_id = $mentorId";
        $mentorResult = $conn->query($mentorSql);

        if ($mentorResult->num_rows > 0) {
            $mentor = $mentorResult->fetch_assoc();
            if ($mentor["status"] == "rejected" || $mentor["status"] == "pending") {
    
                $errorMsg = "This mentor is not allowed to create a course!";
                $_SESSION['errorMsg'] = $errorMsg;
                header('Location: manageMentor.php');
                exit();
            }else {
    // Fetch department, class, and course details from their respective tables
    $departmentSql = "SELECT * FROM department";
    $classSql = "SELECT * FROM class";
    $courseSql = "SELECT * FROM course";

    $departmentResult = $conn->query($departmentSql);
    $classResult = $conn->query($classSql);
    $courseResult = $conn->query($courseSql);
            }
        
        } else {
            // Handle the case where the mentor ID is not valid
            $errorMsg = "Invalid Mentor ID";
                $_SESSION['errorMsg'] = $errorMsg;
                header('location: manageMentor.php');
                exit;
    
        }
    } else {
        // Handle the case where mentorId is not provided in the URL
                 $errorMsg = "Mentor ID not provided";
                $_SESSION['errorMsg'] = $errorMsg;
                header('location: manageMentor.php');
                exit;
    }

    // Close the database connection
    $conn->close();
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="../../font.css">
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>

            
        <!-- MAIN -->
        <main>
            
        <!-- ... (previous code) ... -->

        <div class="container mt-5 border p-4 center">
            <h1 class="text-center">Assign Mentor</h1>
            <p>Assign <h1 class=" color-primary"><?php echo $mentor['first_name'] . ' ' . $mentor['last_name']; ?></h1> to a course:</p>

            <form id="assignForm" action="../serverSide/process_assignment.php" method="post">
                <div class="mb-3">
                    <label for="department" class="form-label">Select Department</label>
                    <select class="form-select" id="department" name="department" required>
                        <option value="" disabled selected>Select Department</option>
                        <?php
                        while ($row = $departmentsResult->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['department_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="class" class="form-label">Select Class</label>
                    <select class="form-select" id="class" name="class" required>
                        <option value="" disabled selected>Select Class</option>
                        <!-- Class options will be dynamically populated here -->
                    </select>
                </div>
                <div class="mb-3">
                    <label for="course" class="form-label">Select Course</label>
                    <select class="form-select" id="course" name="course" required>
                        <option value="" disabled selected>Select Course</option>
                        <!-- Course options will be dynamically populated here -->
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Assign</button>
            </form>
        </div>
        </main>
                    </section>
                    <div id="notificationtext" class="notificationtext"></div>
        <!-- Include Bootstrap JS -->
        <script src="../../bootstrap/js/bootstrap.min.js"></script>
        <script src="../js/script.js"></script>
        <script src="../js/fetchClass.js"></script>
        <script src="../js/fetchCourse.js"></script>
    </body>
    </html>
