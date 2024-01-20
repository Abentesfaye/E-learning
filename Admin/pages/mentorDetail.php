<?php
include("../includes/conn.php");
include("../includes/email_message.php");
include("../includes/notification.php");
// Check if mentorId is provided in the URL
if (isset($_GET['mentorId'])) {
    $mentorId = $_GET['mentorId'];

    // SQL query to select mentor details based on mentorId
    $sql = "SELECT * FROM mentors WHERE mentor_id = $mentorId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $mentorDetails = $result->fetch_assoc();
    } else {
        // Redirect to a page indicating mentor not found
        header("Location: mentor_not_found.php");
        exit();
    }
} else {
    // Redirect to a page indicating mentorId is not provided
    header("Location: mentor_id_not_provided.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve'])) {
        // Generate username and password
        $username = strtolower(substr($mentorDetails['first_name'], 0, 3) . substr($mentorDetails['last_name'], 0, 3));
        $password = bin2hex(random_bytes(4)); // Generate a strong 8-character password

        // Update the database with the generated username, password, and status
        $updateSql = "UPDATE mentors SET username = '$username', password = '$password', status = 'active' WHERE mentor_id = $mentorId";
        $conn->query($updateSql);

        // Send approval email
        $to = $mentorDetails['email_address'];
        $subject = "Congratulations! Your mentorship has been approved";
        $message = getApprovalEmailMessage($mentorDetails, $username, $password);
        $headers = "From: abentesfaye11@gmail.com\r\n";
        $headers .= "Content-type: text/html\r\n";
        mail($to, $subject, $message, $headers);

    } elseif (isset($_POST['reject'])) {
        // Update the database with the rejected status
        $updateSql = "UPDATE mentors SET status = 'rejected' WHERE mentor_id = $mentorId";
        $conn->query($updateSql);
        // Send rejection email
        $to = $mentorDetails['email_address'];
        $subject = "Mentorship Application Rejected";
        $message = getRejectionEmailMessage($mentorDetails);
        $headers = "From: abentesfaye11@gmail.com\r\n";
        $headers .= "Content-type: text/html\r\n";
        mail($to, $subject, $message, $headers);
        showNotification("Application Rejected!");
    }

    // Redirect to prevent form resubmission on page refresh
    header("Location: {$_SERVER['REQUEST_URI']}");
    exit();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../font.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    
    <style>
        body {
          background-color: #F9F9F9;
        }
    </style>
    <title>Mentor Detail</title>
</head>
<body>

    <section id="content">
        <main>
            <?php include("../includes/mentor_details.php"); ?>
            <?php include("../includes/mentor_analytics.php"); ?>
            <div id="notification" class="notification"></div>
        </main>
    </section>
    
    <script src="../js/script.js"></script>
    <script>
        function downloadDocument(documentPath) {
            // You may need to implement actual download logic based on the documentPath
            console.log('Downloading document:', documentPath);
            window.location.href = documentPath;
        }
    </script>
    <script src="../../bootstrap/js/jquery.slim.min.js"></script>
    <script src="../../bootstrap/js/popper.min.js"></script>
    <script src="../../bootstrap/js/bootstrap.min.js"></script>
    <!-- Include jQuery from CDN -->
</body>
</html>
