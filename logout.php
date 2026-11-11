<?php
/**
 * Logout Script
 * Destroys user session and redirects back to the login page.
 */
session_start();

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
header("Location: login.php?logout=success");
exit();
?>