<?php include APP_PATH . '/includes/header.php'; ?>
<?php include APP_PATH . '/includes/sidebar.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Crea nuovo utente</h5>
                </div>
                <div class="col-sm-6 text-right">
                    <a onclick="window.history.back();" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Indietro
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

            <div class="row">
                <!-- Form column -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Informazioni utente</h3>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="index.php?action=users&do=store">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>

                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <small class="form-text text-muted">La password deve contenere almeno 8
                                        caratteri.</small>
                                </div>

                                <div class="form-group">
                                    <label for="role">Ruolo</label>
                                    <select class="form-control" id="role" name="role" required>
                                        <option value="viewer">Spettatore</option>
                                        <option value="manager">Manager</option>
                                        <option value="super_admin">Super Admin</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Crea utente
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Image column -->
                <div class="col-md-6 d-flex align-items-center">
                    <div class="text-center w-100">
                        <img src="assets/images/password-security.gif" alt="User Creation" class="img-fluid"
                            style="max-height: 400px;">
                        <h4 class="mt-3">Gestione degli utenti</h4>
                        <p class="text-muted">Crea nuovi utenti con livelli di accesso appropriati.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<?php include APP_PATH . '/includes/footer.php'; ?>

<style>
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-control {
        border-radius: 0.25rem;
    }

    .card {
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }

    .btn-primary {
        padding: 0.375rem 1.5rem;
    }
</style>