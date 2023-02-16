<?php
session_start();
$level = $_SESSION['level'];
session_destroy();

if ($level === 'admin') {
    header('location:http://localhost/php/attendance-app/loginadmin.php');
} else {
    header('location:http://localhost/php/attendance-app');
}