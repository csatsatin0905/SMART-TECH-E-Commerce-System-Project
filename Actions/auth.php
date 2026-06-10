<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // relative path back to login.php in parent folder
    header("Location: ../login-register.php");
    exit;
}
