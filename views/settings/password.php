<?php include APP_PATH . '/includes/header.php'; ?>
<?php include APP_PATH . '/includes/sidebar.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Cambiare la password</h5>
                </div>
                <div class="col-sm-6 text-right">
                    <a onclick="window.history.back();" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Torna indietro
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (isset($message)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <div class="row">
                <!-- Form column (reduced to half width) -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Aggiorna la tua password</h3>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="index.php?action=settings&do=password">
                                <div class="form-group">
                                    <label for="current_password">Password attuale</label>
                                    <input type="password" class="form-control" id="current_password"
                                        name="current_password" required>
                                </div>

                                <div class="form-group">
                                    <label for="new_password">Nuova password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password"
                                        required>
                                    <small class="form-text text-muted">La password deve essere lunga almeno 8
                                        caratteri.</small>
                                </div>

                                <div class="form-group">
                                    <label for="confirm_password">Conferma la nuova password</label>
                                    <input type="password" class="form-control" id="confirm_password"
                                        name="confirm_password" required>
                                </div>

                                <button type="submit" class="btn btn-primary">Cambiare la password</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Image column (other half) -->
                <div class="col-md-6 d-flex align-items-center">
                    <div class="text-center w-100">
                        <img src="assets/images/password-security.gif" alt="Password Security" class="img-fluid"
                            style="max-height: 400px;">
                        <h4 class="mt-3">Sicurezza della Password</h4>
                        <p class="text-muted">Utilizza una password complessa per proteggere il tuo account.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<?php include APP_PATH . '/includes/footer.php'; ?>