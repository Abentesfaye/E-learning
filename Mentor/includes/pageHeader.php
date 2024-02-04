<?php
// Set the active page variable
$activePage = isset($_SESSION['activePage']) ? $_SESSION['activePage'] : '';
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
                <a class="active" href="<?php echo $activePage . '.php'; ?>"><?php echo $activePage; ?></a>
            </li>
        </ul>
    </div>
</div>
