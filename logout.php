<?php
/**
 * Logout Script
 * Destroys the user session and redirects back to the login page.
 * Only accepts a POST with a valid CSRF token, so other sites cannot log users out.
 */
require_once __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valid()) {
    redirect(is_logged_in() ? 'dashboard.php' : 'login.php');
}

// Unset all session variables
$_SESSION = [];

// Destroy session cookie if set
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Completely destroy session
session_destroy();

// Redirect to login page with feedback
redirect('login.php?logout=success');
