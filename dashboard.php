<?php
// Only signed-in users can access the dashboard.
session_start();

if (!isset($_SESSION['uid']) || empty($_SESSION['uid'])) {
    header("Location: login.php?error=unauthorized");
    exit();
}

$username = $_SESSION['username'] ?? 'User';

$pageTitle = "Dashboard";
require_once __DIR__ . '/includes/header.php';
?>

<main class="dashboard-wrapper">
    <div class="container py-5">
        <div class="welcome-banner fade-in">
            <div>
                <span class="eyebrow">Faculty information system</span>
                <h1 class="mb-2">Welcome, <?= htmlspecialchars($username) ?></h1>
                <p class="mb-0 text-white-50">Manage faculty records securely from one place.</p>
            </div>
            <i class="bi bi-mortarboard-fill welcome-icon"></i>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-xl-4">
                <a class="dashboard-card dashboard-link d-block text-decoration-none" href="faculty.php">
                    <div class="dashboard-icon"><i class="bi bi-people-fill"></i></div>
                    <h2 class="h5 mt-4 mb-2">Faculty Management</h2>
                    <p class="text-muted mb-3">Create, view, update, and delete faculty information.</p>
                    <span class="text-primary fw-semibold">Open module <i class="bi bi-arrow-right"></i></span>
                </a>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="dashboard-card h-100">
                    <div class="dashboard-icon muted"><i class="bi bi-shield-check"></i></div>
                    <h2 class="h5 mt-4 mb-2">Account security</h2>
                    <p class="text-muted mb-0">Your authenticated session protects access to faculty records.</p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
