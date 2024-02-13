<?php
include("../includes/conn.php");

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    header('Location: ../index.html');
    exit();
}

// Fetch user information from the database
$userID = $_SESSION['userID'];
$query = "SELECT first_name, profile_picture FROM users WHERE id = $userID";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $firstName = $row['first_name'];
    $profilePic = $row['profile_picture'];
} else {
    // Handle the case where user information is not found
    $firstName = ''; // Default to empty first name
    $profilePic = ''; // Default to empty profile picture
}

// If profile picture is not set, use default image
if (empty($profilePic)) {
    $profilePic = '../../assets/icon.jpg'; // Path to default profile picture
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../font.css">
    <style>
        /* Custom CSS */
        .navbar-brand {
            color: #fff;
            font-size: 24px;
            font-weight: bold;
        }

        .navbar-toggler-icon {
            background-color: #fff;
            border: 2px solid #fff;
            border-radius: 4px;
        }

        .navbar-toggler-icon span {
            background-color: #fff;
            display: block;
            width: 20px;
            height: 2px;
            margin-bottom: 4px;
        }

        .nav-link {
            color: #fff;
            font-size: 18px;
            font-weight: bold;
        }

        .nav-link:hover {
            color: #fff;
        }

        .form-control {
            border-radius: 20px;
        }

        .rounded-circle span {
            color: #fff;
            font-size: 20px;
            line-height: 50px;
            display: inline-block;
            width: 50px;
            height: 50px;
            text-align: center;
        }

        .bg-success {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
        }

        .navbar-collapse {
            justify-content: flex-end;
        }

        .navbar-nav {
            margin-right: 20px;
        }

        .search-form {
            margin-right: 20px;
        }

        .profile-pic {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-success">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">E-Learning</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse text-center" id="navbarNav">
                <form class="form-inline mx-auto search-form" role="search">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Search" aria-label="Search"
                            aria-describedby="button-addon2">
                        <button class="btn btn-secondary" type="submit" id="button-addon2">Search</button>
                    </div>
                </form>
            </div>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/Enrolled.php">Enrolled Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/profile.php">
                            <?php if (!empty($firstName)) echo "Welcome back, $firstName"; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/logout.php">Logout</a>
                    </li>
                </ul>
                <?php if (!empty($profilePic)) : ?>
                <img src="<?php echo $profilePic; ?>" alt="Profile Picture" class="profile-pic">
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
