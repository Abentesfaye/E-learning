<?php
include("../includes/conn.php");

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

$message = "
<html>
<head>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f4f4f4;
      color: #333;
      padding: 20px;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
      color: #4CAF50;
    }
    p {
      line-height: 1.6;
    }
    .credentials {
      background-color: #4CAF50;
      color: #fff;
      padding: 10px;
      border-radius: 4px;
    }
  </style>
</head>
<body>
  <div class='container'>
    <h1>Congratulations, {$mentorDetails['first_name']} {$mentorDetails['last_name']}!</h1>
    <p>Your mentorship application has been approved. We are excited to welcome you to our mentorship program.</p>
    <p>Your login credentials:</p>
    <div class='credentials'>
      <p><strong>Username:</strong> $username</p>
      <p><strong>Password:</strong> $password</p>
    </div>
    <p>Please use these credentials to log in and explore the opportunities awaiting you.</p>
    <p>Thank you for joining us!</p>
    <p>Mettu University E-learning!</p>
  </div>
</body>
</html>
";

$headers = "From: abentesfaye112@gmail.com\r\n";
$headers .= "Content-type: text/html\r\n";

mail($to, $subject, $message, $headers);

    } elseif (isset($_POST['reject'])) {
        // Update the database with the rejected status
        $updateSql = "UPDATE mentors SET status = 'rejected' WHERE mentor_id = $mentorId";
        $conn->query($updateSql);

       // Send rejection email
$to = $mentorDetails['email_address'];
$subject = "Mentorship Application Status";

$message = "
<html>
<head>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f4f4f4;
      color: #333;
      padding: 20px;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
      color: #e74c3c;
    }
    p {
      line-height: 1.6;
    }
    .regret {
      color: #e74c3c;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class='container'>
    <h1>Dear {$mentorDetails['first_name']} {$mentorDetails['last_name']},</h1>
    <p>We regret to inform you that your mentorship application has been rejected.</p>
    <p class='regret'>Unfortunately, we are unable to proceed with your application at this time.</p>
    <p>Thank you for your interest in our mentorship program. We appreciate your efforts and hope you consider applying again in the future.</p>
    <p>Best regards,</p>
    <p>The Mentorship Program Team</p>
  </div>
</body>
</html>
";

$headers = "From: abentesfaye112@gmail.com\r\n";
$headers .= "Content-type: text/html\r\n";

mail($to, $subject, $message, $headers);

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
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Additional styling for better spacing and buttons */
        .mentor-details {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .mentor-details > div {
            flex: 1;
            margin-right: 20px;
        }

        .mentor-details img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .mentor-info p {
            margin-bottom: 10px;
        }

        .mentor-doc {
            position: relative;
        }

        .mentor-doc img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .download-button {
            bottom: 0;
            margin-left: 100px;
            transform: translateX(-50%);
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .mentor-details,
        .mentor-analytics {
            border-top: 1px solid #ddd;
            padding-top: 20px;
            margin-top: 20px;
        }
    </style>
    <title>Mentor Detail</title>
</head>
<body>

    <section id="content">
        <main>
            <div class="mentor-details">
                <div class="mentor-photo">
                    <img src="<?php echo $mentorDetails['photo']; ?>" alt="Mentor Photo">
                </div>
                <div class="mentor-id">
                    <img src="<?php echo $mentorDetails['id_photo']; ?>" alt="Mentor ID">
                </div>
                <div class="mentor-doc">
                    <button class="download-button" onclick="downloadDocument('<?php echo $mentorDetails['document_path']; ?>')">Download Document</button>
                </div>
                <div class="mentor-info">
                    <h1><?php echo $mentorDetails['first_name'] . ' ' . $mentorDetails['last_name']; ?></h1>
                    <p>Email: <?php echo $mentorDetails['email_address']; ?></p>
                    <p>Phone: <?php echo $mentorDetails['phone_number']; ?></p>
                    <p>Account Code: <?php echo $mentorDetails['account_code']; ?></p>
                    <p>Essay: <?php echo $mentorDetails['why_mentor']; ?></p>
                    <p>Sex: <?php echo $mentorDetails['gender']; ?></p>
                    <p>Status: <?php echo $mentorDetails['status']; ?></p>

                    <?php if ($mentorDetails['status'] === 'active'): ?>
                        <p>This mentor is approved.</p>
                    <?php elseif ($mentorDetails['status'] === 'rejected'): ?>
                        <p>This mentor is rejected.</p>
                    <?php elseif ($mentorDetails['status'] === 'pending'): ?>
                        <div class="action-buttons">
                            <form method="post">
                                <button type="submit" name="approve" class="approve-button">Approve</button>
                                <button type="submit" name="reject" class="reject-button">Reject</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mentor-analytics">
                <h2>Analytics</h2>
                <p>Placeholder for analytics content</p>
            </div>
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
</body>
</html>
