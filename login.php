<?php
/**
 * Login Page
 * Handles user authentication against the database using password_verify() and session initialization.
 */
require_once __DIR__ . '/includes/bootstrap.php';

// If user is already logged in, redirect to dashboard
if (is_logged_in()) {
    redirect('dashboard.php');
}

require_once __DIR__ . '/config/db.php';

// Brute-force protection: after 5 wrong passwords in 15 minutes this browser
// session must wait before trying again.
const MAX_LOGIN_FAILURES = 5;
const LOCKOUT_SECONDS = 15 * 60;

$error_msg = '';
$login_input = '';

// Handle redirect notices
$info_msg = '';
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    $info_msg = "You have successfully logged out.";
} elseif (isset($_GET['error']) && $_GET['error'] === 'unauthorized') {
    $error_msg = "Please log in first to access the dashboard.";
}

// Process Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_input = trim((string) ($_POST['login_input'] ?? ''));
    $password    = (string) ($_POST['password'] ?? '');
    $failures    = array_filter($_SESSION['login_failures'] ?? [], fn($time) => $time > time() - LOCKOUT_SECONDS);

    if (!csrf_valid()) {
        $error_msg = "Your session expired. Please try logging in again.";
    } elseif ($login_input === '' || $password === '') {
        $error_msg = "Please enter your username/email and password.";
    } elseif (count($failures) >= MAX_LOGIN_FAILURES) {
        $error_msg = "Too many failed login attempts. Please wait 15 minutes and try again.";
    } else {
        try {
            // Find the user by username OR email (prepared statement)
            $stmt = $pdo->prepare("
                SELECT id, username, password
                FROM users
                WHERE username = :username_input OR email = :email_input
                LIMIT 1
            ");
            $stmt->execute([
                ':username_input' => $login_input,
                ':email_input'    => $login_input
            ]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Prevent session fixation and start a fresh session
                session_regenerate_id(true);
                $_SESSION = [
                    'uid'      => $user['id'],
                    'username' => $user['username'],
                ];
                redirect('dashboard.php');
            }

            $failures[] = time();
            $_SESSION['login_failures'] = array_values($failures);
            $error_msg = "Invalid username/email or password. Please try again.";
        } catch (PDOException $e) {
            error_log('[faculty-system] login failed: ' . $e->getMessage());
            $error_msg = "Login is temporarily unavailable. Please try again later.";
        }
    }
}

$pageTitle = "Log In";
$layout = 'auth';
require_once __DIR__ . '/includes/header.php';
?>

<div class="auth__panel">
    <h1 class="auth__title">Welcome Back</h1>
    <p class="auth__lead">Log in to access your dashboard.</p>

    <?php if (!empty($info_msg)): ?>
        <div class="alert alert-success" role="status"><i class="bi bi-check-circle"></i><div><?= htmlspecialchars($info_msg) ?></div></div>
    <?php endif; ?>
    <?php if (!empty($error_msg)): ?>
        <div class="alert alert-danger" role="alert"><i class="bi bi-exclamation-circle"></i><div><?= htmlspecialchars($error_msg) ?></div></div>
    <?php endif; ?>

    <form action="login.php" method="POST" novalidate>
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="login_input" class="form-label">Username or Email</label>
            <input type="text" class="form-control" id="login_input" name="login_input" value="<?= htmlspecialchars($login_input) ?>" autocomplete="username" required autofocus>
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" autocomplete="current-password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-lg w-100">Log In</button>
    </form>

    <p class="auth__switch">Don't have an account? <a href="register.php" class="fw-semibold">Create one here</a></p>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
