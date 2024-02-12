<?php

$activePage = isset($_SESSION['activePage']) ? $_SESSION['activePage'] : '';
?>

    <section id='sidebar'>
        <a href='#' class='brand'>
            <i class='bx bxs-smile'></i>
            <span class='text'>AdminHub</span>
        </a>
        <ul class='side-menu top'>
            <li class='<?php echo ($activePage === 'dashboard') ? 'active' : ''; ?>'>
                <a href='./dashboard.php' >
                    <i class='bx bxs-dashboard' ></i>
                    <span class='text'>Dashboard</span>
                </a>
            </li>
            <li class='<?php echo ($activePage === 'manageMentor') ? 'active' : ''; ?>'>
                <a href='./manageMentor.php' >
                    <i class='bx bxs-shopping-bag-alt' ></i>
                    <span class='text'>Manage Mentor</span>
                </a>
            </li>
            <li class='<?php echo ($activePage === 'createDpt') ? 'active' : ''; ?>'>
                <a href='./createDpt.php' >
                    <i class='bx bxs-shopping-bag-alt' ></i>
                    <span class='text'>Create Department</span>
                </a>
            </li>
            <li class='<?php echo ($activePage === 'createClass') ? 'active' : ''; ?>'>
                <a href='./createClass.php' >
                    <i class='bx bxs-shopping-bag-alt' ></i>
                    <span class='text'>Create Class</span>
                </a>
            </li>
            <li class='<?php echo ($activePage === 'createCourse') ? 'active' : ''; ?>'>
                <a href='./createCourse.php' >
                    <i class='bx bxs-shopping-bag-alt' ></i>
                    <span class='text'>Create Course</span>
                </a>
            </li>
    
        </ul>
        <ul class='side-menu'>
            <li>
                <a href='#'>
                    <i class='bx bxs-cog' ></i>
                    <span class='text'>Settings</span>
                </a>
            </li>
            <li>
                <a href='../pages/logout.php' class='logout'>
                    <i class='bx bxs-log-out-circle' ></i>
                    <span class='text'>Logout</span>
                </a>
            </li>
        </ul>
    </section>
    <section id='content'>
    <nav>
        <i class='bx bx-menu' ></i>
    
        <form action='#'>
            <div class='form-input'>
                <input type='search' placeholder='Search...'>
                <button type='submit' class='search-btn'><i class='bx bx-search' ></i></button>
            </div>
        </form>
        <input type='checkbox' id='switch-mode' hidden>
        <label for='switch-mode' class='switch-mode'></label>
        <a href='#' class='notification'>
            <i class='bx bxs-bell' ></i>
            <span class='num'>8</span>
        </a>
        <a href='#' class='profile'>
            <img src='img/people.png'>
        </a>
    </nav>

