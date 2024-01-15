<?php
session_start();
include("../includes/conn.php");
if (isset($_SESSION['accountNumber'])) {
    $accountNumber= $_SESSION['accountNumber'];
    // Clear the error message from the session to prevent displaying it multiple times
    unset($_SESSION['mentorID']);

    echo "<html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                
                <style>
                    body {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        height: 100vh;
                        margin: 0;
                        background-color: #f4f4f4; /* Set your desired background color */
                    }
                    .message-container {
                        text-align: center;
                        padding: 20px;
                        background-color: #ffffff; /* Set your desired background color */
                        border-radius: 10px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    }
                    .success-message {
                        color: #008000; /* Set your brand color */
                        font-size: 18px;
                    }
                    a {
                        color: #008000; /* Set your brand color */
                        text-decoration: none;
                        font-weight: bold;
                    }
                </style>
                <link rel='stylesheet' href='../../font.css'>
            </head>
            <body>
                <div class='message-container'>
                    <p class='success-message'>
                        Registration completed!<br>
                        Your information has been sent to admins for review.<br>
                        Your account number is: $accountNumber<br>
                        You can check your account status <a href='checkStatus.php'>here</a>.
                        or checkStatus.php
                    </p>
                </div>
            </body>
          </html>";

    // Redirect to a success page or stay on the same page
    // header("Location: success.php");
    // exit();
}else {
    // Log the error
    error_log("Error: " . $conn->error);
    echo "Registration failed. Please try again later.";
}

// Close the connection
$conn->close();
?>
