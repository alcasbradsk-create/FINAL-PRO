<?php
/**
 * Header Component
 * ISPSC Faculty Profiling System
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);
$is_logged_in = isset($_SESSION['uid']) && !empty($_SESSION['uid']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | ' : '' ?>ISPSC Faculty Profiling System</title>
    
    <!-- Editorial Japanese display face + practical UI face -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Noto+Serif+JP:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom Project CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-white">

    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container-fluid px-lg-5">
            
            <!-- Left Side: ISPSC Logo (Clicking opens Login/Register modal or page) -->
            <div class="navbar-left">
                <a href="#authModal" class="logo-btn" data-bs-toggle="modal" data-bs-target="#authModal" title="Click to Log In or Register">
                    <img src="ispsc_logo_small.png" alt="ISPSC Logo" class="ispsc-logo" onerror="this.style.display='none'">
                </a>
            </div>
            
            <!-- Center: System Title -->
            <div class="navbar-center">
                <a href="index.php" class="text-decoration-none">
                        <span class="system-nav-kicker">SHOGUN ARCHIVE · FACULTY RECORDS</span>
                        <span class="system-nav-title">ISPSC Faculty Profiling System</span>
                </a>
            </div>
            
            <!-- Mobile Toggle -->
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Right Side: Home, About Us, Contacts -->
            <div class="collapse navbar-collapse flex-grow-0" id="navbarMain">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3 py-2 py-lg-0">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom <?= ($current_page == 'index.php') ? 'active fw-bold text-primary' : '' ?>" href="index.php">
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="#aboutModal" data-bs-toggle="modal" data-bs-target="#aboutModal">
                            About Us
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="#contactModal" data-bs-toggle="modal" data-bs-target="#contactModal">
                            Contacts
                        </a>
                    </li>

                    <?php if ($is_logged_in): ?>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom <?= ($current_page == 'faculty.php') ? 'active fw-bold text-primary' : '' ?>" href="faculty.php">
                                Faculty Registry
                            </a>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <a class="nav-link nav-link-custom <?= ($current_page == 'dashboard.php') ? 'active fw-bold text-primary' : '' ?>" href="dashboard.php">
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-sm btn-outline-danger rounded-pill px-3" href="logout.php">
                                Logout
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

        </div>
    </nav>
