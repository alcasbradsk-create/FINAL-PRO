<?php
/**
 * Page guard for the dashboard and every Faculty Management page.
 * Visitors who are not logged in are sent to the login page.
 * The account is reloaded on every request, so a deleted account is signed out at once.
 */
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/../config/db.php';

if (!is_logged_in()) {
    redirect('login.php?error=unauthorized');
}

$stmt = $pdo->prepare('SELECT id, firstname, middlename, lastname, username, email FROM users WHERE id = :id');
$stmt->execute([':id' => $_SESSION['uid']]);
$currentUser = $stmt->fetch();

if (!$currentUser) {
    $_SESSION = [];
    session_destroy();
    redirect('login.php?error=unauthorized');
}
