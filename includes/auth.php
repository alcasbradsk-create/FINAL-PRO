<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['uid']) || empty($_SESSION['uid'])) {
    header('Location: login.php?error=unauthorized');
    exit();
}
