<?php
/**
 * Registration Page
 * Handles new user account registration with validation and password hashing.
 */
session_start();

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['uid']) && !empty($_SESSION['uid'])) {
    header("Location: dashboard.php");
    exit();
}

require_once __DIR__ . '/config/db.php';

$errors = [];
$success_msg = '';

$firstname = '';
$middlename = '';
$lastname  = '';
$username  = '';
$email     = '';

// Process Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname        = trim($_POST['firstname'] ?? '');
    $middlename       = trim($_POST['middlename'] ?? '');
    $lastname         = trim($_POST['lastname'] ?? '');
    $username         = trim($_POST['username'] ?? '');
    $email            = trim($_POST['email'] ?? '');
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // 1. Basic empty field validation
    if (empty($firstname) || empty($middlename) || empty($lastname) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required. Please fill in all details.";
    }

    // 2. Validate email format
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please provide a valid email address.";
    }

    // 3. Validate username format (alphanumeric and underscores)
    if (!empty($username) && !preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username)) {
        $errors[] = "Username must be between 3 and 30 characters and contain only letters, numbers, or underscores.";
    }

    // 4. Password validation
    if (!empty($password)) {
        if (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters long.";
        }
        if ($password !== $confirm_password) {
            $errors[] = "Passwords do not match.";
        }
    }

    // 5. Database duplicate check (Username and Email)
    if (empty($errors)) {
        try {
            // Check username
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username LIMIT 1");
            $stmt->execute([':username' => $username]);
            if ($stmt->fetch()) {
                $errors[] = "The username '{$username}' is already taken. Please choose another.";
            }

            // Check email
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            if ($stmt->fetch()) {
                $errors[] = "The email '{$email}' is already registered. Please log in or use a different email.";
            }

            // 6. Insert into database if no errors
            if (empty($errors)) {
                // Securely hash password using PASSWORD_DEFAULT (Bcrypt)
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $insert_stmt = $pdo->prepare("
                    INSERT INTO users (firstname, middlename, lastname, username, email, password)
                    VALUES (:firstname, :middlename, :lastname, :username, :email, :password)
                ");

                $insert_stmt->execute([
                    ':firstname' => $firstname,
                    ':middlename' => $middlename,
                    ':lastname'  => $lastname,
                    ':username'  => $username,
                    ':email'     => $email,
                    ':password'  => $hashed_password
                ]);

                $success_msg = "Account created successfully! You can now log in.";
                // Clear fields on success
                $firstname = $middlename = $lastname = $username = $email = '';
            }
        } catch (PDOException $e) {
            $errors[] = "A database error occurred: " . htmlspecialchars($e->getMessage());
        }
    }
}

$pageTitle = "Register";
require_once __DIR__ . '/includes/header.php';
?>

<div class="main-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                
                <div class="auth-card fade-in">
                    <div class="auth-header">
                        <i class="bi bi-person-plus-fill fs-1 text-warning mb-2 d-inline-block"></i>
                        <h3>Create Account</h3>
                        <p>Fill out the form below to register your account</p>
                    </div>

                    <div class="auth-body">
                        <!-- Success Message Alert -->
                        <?php if (!empty($success_msg)): ?>
                            <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                                <div>
                                    <?= htmlspecialchars($success_msg) ?>
                                    <div class="mt-2">
                                        <a href="login.php" class="btn btn-sm btn-success fw-bold">Proceed to Login &rarr;</a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Error Messages Alert -->
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger mb-4" role="alert">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                                    <strong>Registration failed:</strong>
                                </div>
                                <ul class="mb-0 ps-3 small">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <!-- Registration Form -->
                        <form action="register.php" method="POST" novalidate>
                            <div class="row g-2 mb-3">
                                <div class="col-sm-6">
                                    <label for="firstname" class="form-label">First Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control" id="firstname" name="firstname" value="<?= htmlspecialchars($firstname) ?>" placeholder="e.g. Juan" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="lastname" class="form-label">Last Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control" id="lastname" name="lastname" value="<?= htmlspecialchars($lastname) ?>" placeholder="e.g. Dela Cruz" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="middlename" class="form-label">Middle Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control" id="middlename" name="middlename" value="<?= htmlspecialchars($middlename) ?>" placeholder="e.g. Santos" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-at"></i></span>
                                    <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($username) ?>" placeholder="e.g. juandelacruz" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="e.g. juan@example.com" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="At least 6 characters" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Re-type your password" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary-custom mb-3">
                                <i class="bi bi-person-check me-1"></i> Register Account
                            </button>

                            <div class="text-center text-muted small">
                                Already have an account? <a href="login.php" class="text-primary fw-semibold text-decoration-none">Log in here</a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
