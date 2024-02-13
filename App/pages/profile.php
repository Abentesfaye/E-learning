<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userID'])){
    header('Location:  ../index.html');
    exit;
}

require_once '../includes/header.php'; 
include("../includes/conn.php");
include("../includes/notification.php");

// Check if there is an error message in the session
if (isset($_SESSION['errorMsg'])) {
    $errorMsg = $_SESSION['errorMsg'];
    showNotification($errorMsg);
    // Clear the error message from the session to prevent displaying it multiple times
    unset($_SESSION['errorMsg']);
}
if (isset($_SESSION['successMsg'])) {
    $successMsg = $_SESSION['successMsg'];
    showgoodNotification($successMsg);
    // Clear the error message from the session to prevent displaying it multiple times
    unset($_SESSION['successMsg']);
}

$userID = $_SESSION['userID'];

// Fetch user information
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle profile update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update personal information
    if (isset($_POST['updatePersonalInfo'])) {
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $phoneNumber = $_POST['phoneNumber'];

       // Validate Ethiopian phone number format
$phoneRegex = '/^(?:\+251|09)\d{8}$/';
if (!preg_match($phoneRegex, $phoneNumber)) {
    $_SESSION['errorMsg'] = "Please enter a valid Ethiopian phone number.";
    header("Location: profile.php");
    exit;
}

// Check if the phone number already exists in the database
$query = "SELECT id FROM users WHERE phone = ? AND id != ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $phoneNumber, $userID);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $_SESSION['errorMsg'] = "This phone number is already associated with another account.";
    header("Location: profile.php");
    exit;
}

// Update user information in the database
$query = "UPDATE users SET first_name = ?, last_name = ?, phone = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssi", $firstName, $lastName, $phoneNumber, $userID);
if ($stmt->execute()) {
    $_SESSION['successMsg'] = "Personal information updated successfully!";
    header("Location: profile.php");
    exit;
} else {
    $_SESSION['errorMsg'] = "Error updating personal information. Please try again.";
    header("Location: profile.php");
    exit;
}
$stmt->close();

    }

    // Handle password change
    if (isset($_POST['changePassword'])) {
        $oldPassword = $_POST['oldPassword'];
        $newPassword = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];

        // Check if old password matches
        if (password_verify($oldPassword, $user['password'])) {
            // Check if new password meets requirements
            if (strlen($newPassword) >= 6 && preg_match('/[A-Za-z]/', $newPassword) && preg_match('/\d/', $newPassword)) {
                // Check if new password matches confirm password
                if ($newPassword === $confirmPassword) {
                    // Hash the new password
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    // Update password in the database
                    $query = "UPDATE users SET password = ? WHERE id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("si", $hashedPassword, $userID);
                    if ($stmt->execute()) {
                        $_SESSION['successMsg'] = "Password changed successfully!";
                        header("Location: profile.php");
                        exit;
                    } else {
                        $_SESSION['errorMsg'] = "Error changing password. Please try again.";
                        header("Location: profile.php");
                        exit;
                    }
                    $stmt->close();
                } else {
                    $_SESSION['errorMsg'] = "New password and confirm password do not match.";
                    header("Location: profile.php");
                    exit;
                }
            } else {
                $_SESSION['errorMsg'] = "New password must be at least 6 characters long and include both letters and numbers.";
                header("Location: profile.php");
                exit;
            }
        } else {
            $_SESSION['errorMsg'] = "Incorrect old password.";
            header("Location: profile.php");
            exit;
        }
    }

   
if (isset($_POST['uploadProfilePicture'])) {

    if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
       
        $targetDir = "../../profilePicture";
  
        $targetFile = $targetDir . uniqid() . "_" . basename($_FILES['profilePicture']['name']);
      
        if (move_uploaded_file($_FILES['profilePicture']['tmp_name'], $targetFile)) {
           
            $query = "UPDATE users SET profile_picture = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $targetFile, $userID);
            if ($stmt->execute()) {
                $_SESSION['successMsg'] = "Profile picture uploaded successfully!";
                header("Location: profile.php");
                exit;
            } else {
                $_SESSION['errorMsg'] = "Error updating profile picture. Please try again.";
                header("Location: profile.php");
                exit;
            }
            $stmt->close();
        } else {
            $_SESSION['errorMsg'] = "Error uploading profile picture. Please try again.";
            header("Location: profile.php");
            exit;
        }
    } else {
        $_SESSION['errorMsg'] = "No file uploaded or an error occurred during upload.";
        header("Location: profile.php");
        exit;
    }
}

}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <!-- Include Bootstrap CSS from local folder -->
    <link rel="stylesheet" href="path/to/bootstrap/css/bootstrap.min.css">
</head>
<body>
<div id="notificationtext" class="notificationtext"></div>  
<div class="container mt-5">
    <h2>User Profile</h2>
    <div class="row">
        <div class="col-md-6">
            <!-- Update Personal Information Card -->
            <div class="card mb-3">
                <div class="card-header">Update Personal Information</div>
                <div class="card-body">
                    <!-- Personal information update form -->
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="firstName">First Name:</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo $user['first_name']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name:</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo $user['last_name']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="phoneNumber">Phone Number:</label>
                            <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" value="<?php echo $user['phone']; ?>">
                        </div>
                        <button type="submit" class="btn btn-primary mt-3" name="updatePersonalInfo">Update Personal Information</button>
                    </form>
                </div>
            </div>
            <!-- Change Password Card -->
            <div class="card mb-3">
                <div class="card-header">Change Password</div>
                <div class="card-body">
                    <!-- Password change form -->
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="oldPassword">Old Password:</label>
                            <input type="password" class="form-control" id="oldPassword" name="oldPassword" required>
                        </div>
                        <div class="form-group">
                            <label for="newPassword">New Password:</label>
                            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Confirm New Password:</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3" name="changePassword">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <!-- Upload Profile Picture Card -->
            <div class="card mb-3">
                <div class="card-header">Upload Profile Picture</div>
                <div class="card-body">
                    <!-- Profile picture upload form -->
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="profilePicture">Choose Profile Picture:</label>
                            <input type="file" class="form-control-file" id="profilePicture" name="profilePicture" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3" name="uploadProfilePicture">Upload Profile Picture</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Bootstrap JS from local folder -->
<script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>
