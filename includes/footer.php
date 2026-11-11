    <!-- Auth Modal (Triggered by clicking the ISPSC Logo) -->
    <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                
                <div class="modal-header border-0 bg-primary text-white p-4">
                    <div class="d-flex align-items-center gap-3">
                        <img src="ispsc_logo_small.png" alt="ISPSC Logo" style="height: 48px; width: auto;" onerror="this.style.display='none'">
                        <div>
                            <h5 class="modal-title fw-bold mb-0" id="authModalLabel">ISPSC Faculty Profiling</h5>
                            <small class="text-white-50">Sign In or Register an Account</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 bg-white">
                    <!-- Nav Tabs for Login and Register -->
                    <ul class="nav nav-pills nav-fill mb-4 p-1 bg-light rounded-3" id="authTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-semibold" id="login-tab" data-bs-toggle="pill" data-bs-target="#login-tab-pane" type="button" role="tab" aria-controls="login-tab-pane" aria-selected="true">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Log In
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold" id="register-tab" data-bs-toggle="pill" data-bs-target="#register-tab-pane" type="button" role="tab" aria-controls="register-tab-pane" aria-selected="false">
                                <i class="bi bi-person-plus me-1"></i> Register
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="authTabContent">
                        <!-- Login Tab Pane -->
                        <div class="tab-pane fade show active" id="login-tab-pane" role="tabpanel" aria-labelledby="login-tab" tabindex="0">
                            <form action="login.php" method="POST">
                                <div class="mb-3">
                                    <label for="modal_login_input" class="form-label small fw-bold">Username or Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control" id="modal_login_input" name="login_input" placeholder="Enter username or email" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="modal_password" class="form-label small fw-bold">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-key"></i></span>
                                        <input type="password" class="form-control" id="modal_password" name="password" placeholder="Enter password" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold rounded-3 mb-2">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                                </button>
                                <div class="text-center">
                                    <a href="login.php" class="small text-decoration-none text-muted">Open Full Login Page</a>
                                </div>
                            </form>
                        </div>

                        <!-- Register Tab Pane -->
                        <div class="tab-pane fade" id="register-tab-pane" role="tabpanel" aria-labelledby="register-tab" tabindex="0">
                            <form action="register.php" method="POST">
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label for="modal_firstname" class="form-label small fw-bold">First Name</label>
                                        <input type="text" class="form-control form-control-sm" id="modal_firstname" name="firstname" placeholder="First Name" required>
                                    </div>
                                    <div class="col-6">
                                        <label for="modal_middlename" class="form-label small fw-bold">Middle Name</label>
                                        <input type="text" class="form-control form-control-sm" id="modal_middlename" name="middlename" placeholder="Middle Name" required>
                                    </div>
                                    <div class="col-6">
                                        <label for="modal_lastname" class="form-label small fw-bold">Last Name</label>
                                        <input type="text" class="form-control form-control-sm" id="modal_lastname" name="lastname" placeholder="Last Name" required>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label for="modal_username" class="form-label small fw-bold">Username</label>
                                    <input type="text" class="form-control form-control-sm" id="modal_username" name="username" placeholder="Choose a username" required>
                                </div>
                                <div class="mb-2">
                                    <label for="modal_email" class="form-label small fw-bold">Email Address</label>
                                    <input type="email" class="form-control form-control-sm" id="modal_email" name="email" placeholder="name@example.com" required>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label for="modal_reg_password" class="form-label small fw-bold">Password</label>
                                        <input type="password" class="form-control form-control-sm" id="modal_reg_password" name="password" placeholder="Min. 6 chars" required>
                                    </div>
                                    <div class="col-6">
                                        <label for="modal_confirm_password" class="form-label small fw-bold">Confirm</label>
                                        <input type="password" class="form-control form-control-sm" id="modal_confirm_password" name="confirm_password" placeholder="Repeat" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success w-100 py-2 fw-semibold rounded-3 mb-2">
                                    <i class="bi bi-person-plus me-1"></i> Register Account
                                </button>
                                <div class="text-center">
                                    <a href="register.php" class="small text-decoration-none text-muted">Open Full Registration Page</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- About Us Modal -->
    <div class="modal fade" id="aboutModal" tabindex="-1" aria-labelledby="aboutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="aboutModalLabel">About Us</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <img src="ispsc_logo_small.png" alt="ISPSC" style="height: 70px;" class="mb-3" onerror="this.style.display='none'">
                    <h5 class="fw-bold text-dark">ISPSC Faculty Profiling System</h5>
                    <p class="text-muted small mb-3">Ilocos Sur Polytechnic State College</p>
                    <p class="text-secondary small">
                        This system is designed and implemented to provide a centralized faculty profiling, authentication, and record management solution.
                    </p>
                    <hr>
                    <div class="small text-muted text-start">
                        <div><strong>Project Developers:</strong> Brandon S Onga-ong, Louie Garcia</div>
                        <div><strong>Academic Year:</strong> 2026 &ndash; 2027</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contacts Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="contactModalLabel">Contact Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <i class="bi bi-geo-alt-fill text-primary fs-3"></i>
                        <div>
                            <strong>Location</strong>
                            <p class="text-muted small mb-0">Ilocos Sur Polytechnic State College, Philippines</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <i class="bi bi-envelope-fill text-primary fs-3"></i>
                        <div>
                            <strong>Official Email</strong>
                            <p class="text-muted small mb-0">info@ispsc.edu.ph</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-people-fill text-primary fs-3"></i>
                        <div>
                            <strong>Lead Developers</strong>
                            <p class="text-muted small mb-0">Brandon S Onga-ong &amp; Louie Garcia</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Site Footer Component -->
    <footer class="footer-custom mt-auto">
        <div class="container text-center">
            <p class="mb-0 footer-text">
                <span class="footer-mark">SHOGUN ARCHIVE</span> Ilocos Sur Polytechnic State College · All rights reserved · 2026
            </p>
        </div>
    </footer>

    <!-- Bootstrap 5 JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (() => {
            const transition = document.createElement('div');
            transition.className = 'page-transition';
            document.body.appendChild(transition);

            const revealTargets = document.querySelectorAll('main > .container, .landing-page .col-lg-7, .landing-page .landing-panel, .dashboard-card, .auth-card, .table-card, .page-heading');
            revealTargets.forEach((element, index) => {
                element.classList.add('reveal');
                element.style.setProperty('--reveal-delay', `${Math.min(index * 70, 350)}ms`);
            });
            requestAnimationFrame(() => document.body.classList.add('is-ready'));

            document.querySelectorAll('a[href]').forEach(link => {
                link.addEventListener('click', event => {
                    const url = new URL(link.href, window.location.href);
                    const samePage = url.origin === window.location.origin;
                    const isAction = link.hasAttribute('data-bs-toggle') || url.hash || link.target === '_blank';
                    if (!samePage || isAction || event.metaKey || event.ctrlKey || event.shiftKey) return;
                    event.preventDefault();
                    transition.classList.add('is-active');
                    window.setTimeout(() => { window.location.href = url.href; }, 240);
                });
            });
        })();
    </script>
</body>
</html>
