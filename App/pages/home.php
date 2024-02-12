<?php
session_start();
if (!isset($_SESSION['userID'])){
    header('Location:  ../index.html');
}

require_once '../includes/header.php'; 