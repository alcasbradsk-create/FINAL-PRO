<?php
/**
 * Main Page / Home
 * ISPSC Faculty Profiling System
 */
$pageTitle = "Home";
require_once __DIR__ . './includes/header.php';
?>

<main class="landing-page">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="eyebrow text-primary">THE SHOGUN ARCHIVE</span>
                <h1 class="landing-title">Order for every record.<br><em>Honor in every name.</em></h1>
                <p class="landing-copy">A composed digital archive for the people who shape ISPSC—securely register, preserve, and manage every faculty profile.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="login.php" class="btn btn-primary btn-lg px-4"><i class="bi bi-box-arrow-in-right me-2"></i>Sign In</a>
                    <a href="register.php" class="btn btn-outline-primary btn-lg px-4"><i class="bi bi-person-plus me-2"></i>Create Account</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="landing-panel">
                    <div class="dashboard-icon mb-3"><i class="bi bi-people-fill"></i></div>
                    <h2 class="h4 mb-3">The Faculty Registry</h2>
                    <p class="text-muted mb-4">A complete record of status, position, department, contact details, specialization, and service history.</p>
                    <div class="row g-3 small"><div class="col-6"><i class="bi bi-check-circle-fill text-success me-2"></i>CRUD operations</div><div class="col-6"><i class="bi bi-check-circle-fill text-success me-2"></i>Protected access</div><div class="col-6"><i class="bi bi-check-circle-fill text-success me-2"></i>MySQL storage</div><div class="col-6"><i class="bi bi-check-circle-fill text-success me-2"></i>Full profiles</div></div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
