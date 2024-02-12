<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Header Component</title>
    <!-- Include Bootstrap CSS from local folder -->
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <style>
        /* Custom CSS */
        .navbar-brand {
            color: #fff; /* Change navbar brand color */
        }
        .navbar-toggler-icon {
            background-color: #fff; /* Change color of the toggler icon */
        }
        .nav-link {
            color: #fff; /* Change color of nav links */
        }
        .nav-link:hover {
            color: #fff; /* Change color of nav links on hover */
        }
        .form-control {
            border-radius: 20px; /* Rounded search input */
        }
        .rounded-circle span {
            color: #fff; /* Color of the icon inside the circle */
            font-size: 20px; /* Icon size */
            line-height: 50px; /* Center the icon vertically */
            display: inline-block; /* Ensure the span takes up space */
            width: 50px; /* Width of the span */
            height: 50px; /* Height of the span */
            text-align: center; /* Center the icon horizontally */
        }
        .bg-info {
            background-color: #17a2b8 !important; /* Change background color of the circle */
            border-color: #17a2b8 !important; /* Change border color of the circle */
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Enroaled</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Profile</a>
        </li>
      </ul>
      <form class="d-flex mr-5" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-" type="submit">Search</button>
      </form>
      
      <a href='#' class='profile'>
            <img src='../../assets/ceritificate.svg'>
        </a>
    </div>
  </div>
</nav>
<script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
