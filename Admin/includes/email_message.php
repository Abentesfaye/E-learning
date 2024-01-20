
<?php
function getApprovalEmailMessage($mentorDetails, $username, $password)
{
    return "
    <html>
    <head>
        <link rel='stylesheet' href='../../bootstrap/css/bootstrap.min.css'>
        <style>
            body {
                background-color: #f4f4f4;
                color: #333;
            }
            .container {
                margin-top: 5%;
            }
            .card {
                padding: 4%;
                border-radius: 15px;
                background-color: #fff;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            .text-success {
                color: #4CAF50;
            }
            .lead {
                line-height: 1.6;
                font-size: 18px;
            }
            .alert-success {
                background-color: #dff0d8;
                border-color: #d6e9c6;
                color: #3c763d;
            }
        </style>
        <link rel='stylesheet' href='../../font.css'>
    </head>
    <body class='bg-light'>
        <div class='container mt-5'>
            <div class='card p-4'>
                <h1 class='text-success'>Welcome Aboard, {$mentorDetails['first_name']} {$mentorDetails['last_name']}!</h1>
                <p class='lead'>We are thrilled to inform you that your mentorship application has been approved. Congratulations on joining our mentorship program!</p>
                <div class='alert alert-success'>
                    <p class='mb-0'><strong>Your Credentials:</strong></p>
                    <p class='mb-0'><strong>Username:</strong> {$username}</p>
                    <p class='mb-0'><strong>Password:</strong> {$password}</p>
                </div>
                <p class='lead'>Use these credentials to access your account and start your journey as a mentor. We look forward to the positive impact you'll make!</p>
                <p>Thank you for being part of Mettu University E-learning!</p>
            </div>
        </div>
    </body>
    </html>
    ";
}

function getRejectionEmailMessage($mentorDetails)
{
    $message = "
    <html>
    <head>
        <link rel='stylesheet' href='../../bootstrap/css/bootstrap.min.css'>
        <link rel='stylesheet' href='../../font.css'>
    </head>
    <body class='bg-light'>
        <div class='container mt-5'>
            <div class='card p-4'>
                <h1 class='text-danger'>Dear {$mentorDetails['first_name']} {$mentorDetails['last_name']},</h1>
                <p class='lead'>We regret to inform you that your mentorship application has been rejected.</p>
                <p class='regret'>Unfortunately, we are unable to proceed with your application at this time.</p>
                <p>Thank you for your interest in our mentorship program. We appreciate your efforts and hope you consider applying again in the future.</p>
                <p>Best regards,</p>
                <p>The Mentorship Program Team</p>
            </div>
        </div>
    </body>
    </html>
    ";

    return $message;
}
?>