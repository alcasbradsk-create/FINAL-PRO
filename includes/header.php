<?php
/**
 * Page header. Each page sets:
 *   $pageTitle  browser tab title
 *   $layout     'public' (landing), 'auth' (sign-in / register) or 'app' (signed-in pages)
 * App pages must include includes/auth.php first so $currentUser and $pdo exist.
 */
require_once __DIR__ . '/bootstrap.php';

$layout = $layout ?? 'public';
$current_page = basename($_SERVER['PHP_SELF']);

$navItems = [
    ['dashboard.php', 'bi-grid-1x2', 'Dashboard', ['dashboard.php']],
    ['faculty.php', 'bi-person-vcard', 'Faculty Management', ['faculty.php', 'faculty_form.php', 'faculty_view.php']],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' · ' : '' ?>ISPSC Faculty Profiling System</title>
    <link rel="icon" type="image/png" href="img/favicon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&family=Source+Serif+4:ital,opsz,wght@0,8..60,500;0,8..60,600;0,8..60,700;1,8..60,500&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" integrity="sha384-XGjxtQfXaH2tnPFa9x+ruJTuLE3Aa6LhHSWRr1XeTyhezb4abCG4ccI5AkVDxqC+" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="layout-<?= htmlspecialchars($layout) ?>">

<?php if ($layout === 'public'): ?>
    <header class="site-header">
        <div class="container site-header__inner">
            <div class="brand">
                <?php if (is_logged_in()): ?>
                    <a href="dashboard.php" class="brand__seal" title="Go to Dashboard"><img src="img/ispsc-seal.png" alt="ISPSC logo" width="40" height="40"></a>
                <?php else: ?>
                    <a href="#authModal" class="brand__seal" data-bs-toggle="modal" title="Click to Log In or Create an Account"><img src="img/ispsc-seal.png" alt="ISPSC logo: Log In or Create Account" width="40" height="40"></a>
                <?php endif; ?>
                <a href="index.php" class="brand__text text-decoration-none">
                    <span class="brand__name">ISPSC</span>
                    <span class="brand__product">Faculty Profiling System</span>
                </a>
            </div>
            <nav class="site-nav" aria-label="Main">
                <a class="site-nav__link is-optional <?= $current_page === 'index.php' ? 'is-active' : '' ?>" href="index.php">Home</a>
                <a class="site-nav__link is-optional" href="#aboutModal" data-bs-toggle="modal">About Us</a>
                <a class="site-nav__link is-optional" href="#contactModal" data-bs-toggle="modal">Contacts</a>
                <?php if (is_logged_in()): ?>
                    <a class="btn btn-primary btn-sm ms-2" href="dashboard.php">Go to Dashboard <i class="bi bi-arrow-right"></i></a>
                <?php else: ?>
                    <a class="site-nav__link" href="login.php">Log In</a>
                    <a class="btn btn-primary btn-sm ms-1" href="register.php">Create Account</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

<?php elseif ($layout === 'auth'): ?>
    <div class="auth">
        <aside class="auth__aside">
            <a class="brand" href="index.php">
                <span class="brand__text">
                    <span class="brand__name">ISPSC</span>
                    <span class="brand__product">Faculty Profiling System</span>
                </span>
            </a>
            <div>
                <img class="auth__seal" src="img/ispsc-seal.png" alt="Ilocos Sur Polytechnic State College seal" width="120" height="120">
                <p class="auth__quote">ISPSC Faculty Profiling System</p>
                <p class="auth__sub">Register, view and manage faculty records of Ilocos Sur Polytechnic State College.</p>
            </div>
            <div class="auth__meta">Research · Extension · Production · Instruction</div>
        </aside>
        <div class="auth__main">
            <div class="auth__top">
                <a class="brand" href="index.php">
                    <img src="img/ispsc-seal.png" alt="" width="40" height="40">
                    <span class="brand__text">
                        <span class="brand__name">ISPSC</span>
                        <span class="brand__product">Faculty Profiling System</span>
                    </span>
                </a>
                <a class="back-link" href="index.php"><i class="bi bi-arrow-left"></i> Back to home</a>
            </div>

<?php else: /* app */ ?>
    <div class="app">
        <aside class="sidebar">
            <div class="offcanvas-lg offcanvas-start sidebar__panel" id="sidebar" tabindex="-1" aria-label="Navigation">
                <div class="offcanvas-body">
                    <a class="brand" href="dashboard.php">
                        <img src="img/ispsc-seal.png" alt="" width="36" height="36">
                        <span class="brand__text">
                            <span class="brand__name">ISPSC</span>
                            <span class="brand__product">Faculty Profiling</span>
                        </span>
                    </a>

                    <div class="sidebar__label">Menu</div>
                    <nav class="sidebar__nav" aria-label="Main">
                        <?php foreach ($navItems as [$navHref, $navIcon, $navLabel, $navPages]): $navActive = in_array($current_page, $navPages, true) && !($current_page === 'faculty_form.php' && empty($_GET['id'])); ?>
                            <a class="sidebar__link <?= $navActive ? 'is-active' : '' ?>" href="<?= $navHref ?>" <?= $navActive ? 'aria-current="page"' : '' ?>>
                                <i class="bi <?= $navIcon ?>"></i><?= $navLabel ?>
                            </a>
                        <?php endforeach; ?>
                    </nav>

                    <div class="sidebar__label mt-4">Quick Actions</div>
                        <nav class="sidebar__nav" aria-label="Quick actions">
                            <a class="sidebar__link <?= $current_page === 'faculty_form.php' && empty($_GET['id']) ? 'is-active' : '' ?>" href="faculty_form.php"><i class="bi bi-plus-lg"></i>Add Faculty</a>
                        </nav>

                    <div class="sidebar__user">
                        <?= avatar($currentUser) ?>
                        <div class="min-w-0">
                            <div class="sidebar__user-name text-truncate"><?= htmlspecialchars(full_name($currentUser)) ?></div>
                            <div class="sidebar__user-role">@<?= htmlspecialchars($currentUser['username']) ?></div>
                        </div>
                        <form action="logout.php" method="POST" class="ms-auto">
                            <?= csrf_field() ?>
                            <button type="submit" class="sidebar__signout" title="Logout"><i class="bi bi-box-arrow-right"></i> Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <div class="app__main">
            <div class="topbar d-lg-none">
                <a class="brand" href="dashboard.php">
                    <img src="img/ispsc-seal.png" alt="" width="32" height="32">
                    <span class="brand__text">
                        <span class="brand__name">ISPSC</span>
                        <span class="brand__product">Faculty Profiling</span>
                    </span>
                </a>
                <button class="topbar__toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar" aria-label="Open navigation">
                    <i class="bi bi-list"></i>
                </button>
            </div>
            <main class="app__content">
<?php endif; ?>
