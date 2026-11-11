<?php
/**
 * Registration Page
 * Handles new user account registration with validation and password hashing.
 */
require_once __DIR__ . '/includes/bootstrap.php';

// If user is already logged in, redirect to dashboard
if (is_logged_in()) {
    redirect('dashboard.php');
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
    $firstname        = trim((string) ($_POST['firstname'] ?? ''));
    $middlename       = trim((string) ($_POST['middlename'] ?? ''));
    $lastname         = trim((string) ($_POST['lastname'] ?? ''));
    $username         = trim((string) ($_POST['username'] ?? ''));
    $email            = trim((string) ($_POST['email'] ?? ''));
    $password         = (string) ($_POST['password'] ?? '');
    $confirm_password = (string) ($_POST['confirm_password'] ?? '');

    if (!csrf_valid()) {
        $errors[] = "Your session expired. Please submit the form again.";
    }

    // 1. Basic empty field validation (middle name is optional)
    if ($firstname === '' || $lastname === '' || $username === '' || $email === '' || $password === '' || $confirm_password === '') {
        $errors[] = "Please fill in all required fields.";
    }

    // 2. Validate lengths and email format
    foreach (['First name' => $firstname, 'Middle name' => $middlename, 'Last name' => $lastname] as $label => $value) {
        if (mb_strlen($value) > 100) {
            $errors[] = "{$label} must be 100 characters or fewer.";
        }
    }
    if ($email !== '' && (mb_strlen($email) > 150 || !filter_var($email, FILTER_VALIDATE_EMAIL))) {
        $errors[] = "Please provide a valid email address.";
    }

    // 3. Validate username format (alphanumeric and underscores)
    if (!empty($username) && !preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username)) {
        $errors[] = "Username must be between 3 and 30 characters and contain only letters, numbers, or underscores.";
    }

    // 4. Password validation
    if ($password !== '') {
        if (mb_strlen($password) < PASSWORD_MIN_LENGTH) {
            $errors[] = "Password must be at least " . PASSWORD_MIN_LENGTH . " characters long.";
        }
        if (strlen($password) > 72) {
            $errors[] = "Password must be 72 bytes or fewer.";
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
                    ':middlename' => $middlename === '' ? null : $middlename,
                    ':lastname'  => $lastname,
                    ':username'  => $username,
                    ':email'     => $email,
                    ':password'  => $hashed_password,
                ]);

                $success_msg = "Account created successfully! You can now log in.";
                // Clear fields on success
                $firstname = $middlename = $lastname = $username = $email = '';
            }
        } catch (PDOException $e) {
            error_log('[faculty-system] registration failed: ' . $e->getMessage());
            $errors[] = "Registration is temporarily unavailable. Please try again later.";
        }
    }
}

$pageTitle = "Create Account";
$layout = 'auth';
require_once __DIR__ . '/includes/header.php';
?>

<div class="auth__panel is-wide">
    <h1 class="auth__title">Create Account</h1>
    <p class="auth__lead">Fill out the form below to register your account.</p>



    <?php if (!empty($success_msg)): ?>
        <div class="alert alert-success" role="status">
            <i class="bi bi-check-circle"></i>
            <div>
                <?= htmlspecialchars($success_msg) ?>
                <div class="mt-2"><a href="login.php" class="fw-semibold">Proceed to Log In <i class="bi bi-arrow-right"></i></a></div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" role="alert">
            <i class="bi bi-exclamation-circle"></i>
            <div>
                <strong class="d-block mb-1">Please fix the following:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <form action="register.php" method="POST" novalidate>
        <?= csrf_field() ?>
        <div class="row g-3 mb-3">
            <div class="col-sm-4">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstname" name="firstname" value="<?= htmlspecialchars($firstname) ?>" autocomplete="given-name" maxlength="100" required>
            </div>
            <div class="col-sm-4">
                <label for="middlename" class="form-label">Middle Name <span class="optional">Optional</span></label>
                <input type="text" class="form-control" id="middlename" name="middlename" value="<?= htmlspecialchars($middlename) ?>" autocomplete="additional-name" maxlength="100">
            </div>
            <div class="col-sm-4">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastname" name="lastname" value="<?= htmlspecialchars($lastname) ?>" autocomplete="family-name" maxlength="100" required>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-sm-6">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($username) ?>" autocomplete="username" maxlength="30" required aria-describedby="usernameHelp">
                <div id="usernameHelp" class="form-text">3–30 letters, numbers or underscores.</div>
            </div>
            <div class="col-sm-6">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" autocomplete="email" maxlength="150" required>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-sm-6">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" autocomplete="new-password" minlength="8" required aria-describedby="passwordHelp">
                <div id="passwordHelp" class="form-text">At least 8 characters.</div>
            </div>
            <div class="col-sm-6">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" autocomplete="new-password" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100">Register Account</button>
    </form>

    <p class="auth__switch">Already have an account? <a href="login.php" class="fw-semibold">Log in here</a></p>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
