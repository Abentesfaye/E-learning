<?php
session_start();
if (!isset($_SESSION['userID'])){
    header('Location:  ../index.html');
}
echo $_SESSION['userID'];
djjdjdj