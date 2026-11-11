<?php
/**
 * Login Page
 * Handles user authentication against the database using password_verify() and session initialization.
 */
session_start();

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['uid']) && !empty($_SESSION['uid'])) {
    header("Location: dashboard.php");
    exit();
}

require_once __DIR__ . '/config/db.php';

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
    $login_input = trim($_POST['login_input'] ?? '');
    $password    = $_POST['password'] ?? '';

    if (empty($login_input) || empty($password)) {
        $error_msg = "Please enter your username/email and password.";
    } else {
        try {
            // Find user by either username OR email using prepared statement
            $stmt = $pdo->prepare("
                SELECT id AS uid, lastname, firstname, middlename, username, email, password, created_at 
                FROM users 
                WHERE username = :username_input OR email = :email_input 
                LIMIT 1
            ");
            $stmt->execute([
                ':username_input' => $login_input,
                ':email_input'    => $login_input
            ]);
            $user = $stmt->fetch();

            // Verify user existence and password hash
            if ($user && password_verify($password, $user['password'])) {
                // Prevent session fixation
                session_regenerate_id(true);

                // Set session data
                $_SESSION['uid']        = $user['uid'];
                $_SESSION['firstname']  = $user['firstname'];
                $_SESSION['lastname']   = $user['lastname'];
                $_SESSION['username']   = $user['username'];
                $_SESSION['email']      = $user['email'];
                $_SESSION['created_at'] = $user['created_at'];
                $_SESSION['logged_in']  = true;

                // Redirect to Dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                $error_msg = "Invalid username/email or password. Please try again.";
            }
        } catch (PDOException $e) {
            $error_msg = "Database error: " . htmlspecialchars($e->getMessage());
        }
    }
}

$pageTitle = "Login";
require_once __DIR__ . '/includes/header.php';
?>

<div class="main-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5 col-xl-4">
                
                <div class="auth-card fade-in">
                    <div class="auth-header">
                        <i class="bi bi-shield-lock-fill fs-1 text-warning mb-2 d-inline-block"></i>
                        <h3>Welcome Back</h3>
                        <p>Sign in to access your dashboard</p>
                    </div>

                    <div class="auth-body">
                        <!-- Info / Notice Alert -->
                        <?php if (!empty($info_msg)): ?>
                            <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                                <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                                <div><?= htmlspecialchars($info_msg) ?></div>
                            </div>
                        <?php endif; ?>

                        <!-- Error Message Alert -->
                        <?php if (!empty($error_msg)): ?>
                            <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-5 flex-shrink-0"></i>
                                <div><?= htmlspecialchars($error_msg) ?></div>
                            </div>
                        <?php endif; ?>

                        <!-- Login Form -->
                        <form action="login.php" method="POST" novalidate>
                            <div class="mb-3">
                                <label for="login_input" class="form-label">Username or Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="login_input" 
                                        name="login_input" 
                                        value="<?= htmlspecialchars($login_input) ?>" 
                                        placeholder="Enter your username or email" 
                                        required 
                                        autofocus
                                    >
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                                    <input 
                                        type="password" 
                                        class="form-control" 
                                        id="password" 
                                        name="password" 
                                        placeholder="Enter your password" 
                                        required
                                    >
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary-custom mb-3">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                            </button>

                            <div class="text-center text-muted small">
                                Don't have an account? <a href="register.php" class="text-primary fw-semibold text-decoration-none">Register here</a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
