<?php
/** Page footer: closes whichever layout includes/header.php opened. */
$layout = $layout ?? 'public';
?>
<?php if ($layout === 'public'): ?>
    <footer class="site-footer">
        <div class="container d-flex flex-wrap justify-content-between gap-2">
            <span>&copy; <?= date('Y') ?> Ilocos Sur Polytechnic State College</span>
            <span>Faculty Profiling System · <a href="#aboutModal" data-bs-toggle="modal">About Us</a> · <a href="#contactModal" data-bs-toggle="modal">Contacts</a></span>
        </div>
    </footer>

    <?php if (!is_logged_in()): ?>
    <!-- Log In / Create Account pop-up, opened by clicking the ISPSC logo -->
    <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-3">
                        <img src="img/ispsc-seal.png" alt="" width="44" height="44">
                        <div>
                            <h2 class="modal-title" id="authModalLabel">ISPSC Faculty Profiling</h2>
                            <div class="text-muted small">Log in or create an account</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="segmented" role="tablist">
                        <button class="segmented__btn active" id="login-tab" data-bs-toggle="pill" data-bs-target="#login-pane" type="button" role="tab" aria-controls="login-pane" aria-selected="true">Log In</button>
                        <button class="segmented__btn" id="register-tab" data-bs-toggle="pill" data-bs-target="#register-pane" type="button" role="tab" aria-controls="register-pane" aria-selected="false">Create Account</button>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="login-pane" role="tabpanel" aria-labelledby="login-tab" tabindex="0">
                            <form action="login.php" method="POST">
                                <?= csrf_field() ?>
                                <div class="mb-3">
                                    <label for="modal_login_input" class="form-label">Username or Email</label>
                                    <input type="text" class="form-control" id="modal_login_input" name="login_input" autocomplete="username" required>
                                </div>
                                <div class="mb-4">
                                    <label for="modal_password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="modal_password" name="password" autocomplete="current-password" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Log In</button>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="register-pane" role="tabpanel" aria-labelledby="register-tab" tabindex="0">
                            <form action="register.php" method="POST">
                                <?= csrf_field() ?>
                                <div class="row g-2 mb-2">
                                    <div class="col-sm-4">
                                        <label for="modal_firstname" class="form-label">First Name</label>
                                        <input type="text" class="form-control" id="modal_firstname" name="firstname" maxlength="100" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="modal_middlename" class="form-label">Middle Name <span class="optional">Optional</span></label>
                                        <input type="text" class="form-control" id="modal_middlename" name="middlename" maxlength="100">
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="modal_lastname" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="modal_lastname" name="lastname" maxlength="100" required>
                                    </div>
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-sm-6">
                                        <label for="modal_username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="modal_username" name="username" maxlength="30" autocomplete="username" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="modal_email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="modal_email" name="email" maxlength="150" autocomplete="email" required>
                                    </div>
                                </div>
                                <div class="row g-2 mb-4">
                                    <div class="col-sm-6">
                                        <label for="modal_reg_password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="modal_reg_password" name="password" minlength="8" placeholder="At least 8 characters" autocomplete="new-password" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="modal_confirm_password" class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" id="modal_confirm_password" name="confirm_password" autocomplete="new-password" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Register Account</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="modal fade" id="aboutModal" tabindex="-1" aria-labelledby="aboutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="aboutModalLabel">About Us</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>The ISPSC Faculty Profiling System keeps one authoritative record for each faculty member of Ilocos Sur Polytechnic State College, available only to logged-in users.</p>
                    <dl class="details mt-4">
                        <div><dt>Developed by</dt><dd>Brandon S Onga-ong<br>Louie Garcia</dd></div>
                        <div><dt>Academic year</dt><dd>2026&ndash;2027</dd></div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="contactModalLabel">Contact Information</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="contact-list">
                        <li><i class="bi bi-geo-alt"></i><div><strong>Location</strong><span>Ilocos Sur Polytechnic State College, Philippines</span></div></li>
                        <li><i class="bi bi-envelope"></i><div><strong>Email</strong><span><a href="mailto:info@ispsc.edu.ph">info@ispsc.edu.ph</a></span></div></li>
                        <li><i class="bi bi-code-slash"></i><div><strong>Developers</strong><span>Brandon S Onga-ong &amp; Louie Garcia</span></div></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

<?php elseif ($layout === 'auth'): ?>
        </div><!-- /.auth__main -->
    </div><!-- /.auth -->

<?php else: ?>
            </main>
            <footer class="app__footer">
                &copy; <?= date('Y') ?> Ilocos Sur Polytechnic State College · Faculty Profiling System
            </footer>
        </div><!-- /.app__main -->
    </div><!-- /.app -->

    <!-- Delete confirmation, opened by any button with data-bs-target="#deleteModal" -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="post" action="<?= htmlspecialchars($deleteAction ?? 'faculty.php') ?>">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="deleteModalLabel">Delete faculty record?</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to delete the record of <strong data-delete-name>this faculty member</strong>? This cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="faculty_id" value="" data-delete-id>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash3"></i> Delete</button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        // Fill the delete confirmation with the record that was clicked.
        document.getElementById('deleteModal')?.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;
            this.querySelector('[data-delete-id]').value = trigger.dataset.id;
            this.querySelector('[data-delete-name]').textContent = trigger.dataset.name;
        });
    </script>
</body>
</html>
