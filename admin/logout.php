<?php
session_start();
unset($_SESSION['ADMIN_LOGIN']);
header('location:login.php');
die();
?>