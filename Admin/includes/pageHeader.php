<?php
// Set the active page variable
$activePage = isset($_SESSION['activePage']) ? $_SESSION['activePage'] : '';
$nextPage = isset($_SESSION['next']) ? $_SESSION['next'] : '';
?>
<div class="head-title">
    <div class="left">
        <h1>Dashboard</h1>
        <ul class="breadcrumb">
            <li>
                <a href="./dashboard.php">Dashboard</a>
            </li>
            <li><i class='bx bx-chevron-right' ></i></li>
            <li>
                <a class="active text-decoration-underline" href="<?php echo $activePage . '.php'; ?>" ><?php echo $activePage; ?></a>
            </li>
            <li>
                <a class="active text-success" href="<?php echo $nextPage . '.php'; ?>"><?php echo $nextPage; ?></a>
            </li>
        </ul>
    </div>
    <a href="#" class="btn-download">
        <i class='bx bxs-cloud-download' ></i>
        <span class="text">Download PDF</span>
    </a>
</div>
