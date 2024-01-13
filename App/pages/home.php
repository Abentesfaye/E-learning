<?php
session_start();
if (!isset($_SESSION['userID'])){
    header('../index.html');
}
echo $_SESSION['userID'];