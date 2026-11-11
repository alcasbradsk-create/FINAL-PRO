<?php
/**
 * Landing page
 * ISPSC Faculty Profiling System
 */
$pageTitle = 'Home';
$layout = 'public';
require_once __DIR__ . '/includes/header.php';
?>

<main>
    <section class="hero">
        <div class="container">
            <div class="row align-items-center gy-5">
                <div class="col-lg-6">
                    <span class="eyebrow">Ilocos Sur Polytechnic State College</span>
                    <h1 class="hero__title">Faculty records, <em>kept in order.</em></h1>
                    <p class="hero__lead">Register, view and manage the faculty profiles of Ilocos Sur Polytechnic State College in one secure place.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <?php if (is_logged_in()): ?>
                            <a href="dashboard.php" class="btn btn-primary btn-lg">Go to Dashboard <i class="bi bi-arrow-right"></i></a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-primary btn-lg"><i class="bi bi-box-arrow-in-right"></i> Log In</a>
                            <a href="register.php" class="btn btn-secondary btn-lg"><i class="bi bi-person-plus"></i> Create Account</a>
                        <?php endif; ?>
                    </div>
                    <p class="hero__note"><i class="bi bi-shield-lock me-1"></i> Faculty records can only be viewed and managed after logging in.</p>
                </div>

                <div class="col-lg-5 offset-lg-1">
                    <div class="specimen-stack">
                        <article class="specimen" aria-label="Example of a faculty record">
                            <div class="specimen__bar"><span>Faculty record</span><span>Sample</span></div>
                            <div class="specimen__head">
                                <span class="avatar avatar-lg" aria-hidden="true">ER</span>
                                <div>
                                    <p class="specimen__name">Elena M. Rivera</p>
                                    <p class="specimen__role">Associate Professor II · College of Computing Studies</p>
                                    <span class="badge-soft is-green mt-2">Active</span>
                                </div>
                            </div>
                            <dl class="specimen__grid">
                                <div><dt>Employment</dt><dd>Permanent</dd></div>
                                <div><dt>Date hired</dt><dd>June 1, 2012</dd></div>
                                <div><dt>Specialization</dt><dd>Database Systems</dd></div>
                                <div><dt>Department</dt><dd>Information Technology</dd></div>
                                <div><dt>Email</dt><dd>e.rivera@example.edu</dd></div>
                                <div><dt>Contact</dt><dd>+63 900 000 0000</dd></div>
                            </dl>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <div class="row gy-4">
                <div class="col-md-4 feature">
                    <span class="feature__icon"><i class="bi bi-person-vcard"></i></span>
                    <h3>Complete faculty profiles</h3>
                    <p>Add, view, edit and delete faculty records: position, department, employment status, specialization and service dates.</p>
                </div>
                <div class="col-md-4 feature">
                    <span class="feature__icon"><i class="bi bi-shield-lock"></i></span>
                    <h3>Secure access</h3>
                    <p>Only logged-in users can open Faculty Management, and every database query uses prepared statements.</p>
                </div>
                <div class="col-md-4 feature">
                    <span class="feature__icon"><i class="bi bi-search"></i></span>
                    <h3>Find anyone quickly</h3>
                    <p>Search across names, departments, positions, specializations and email addresses.</p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
