<?php include APP_PATH . '/includes/header.php'; ?>
<?php include APP_PATH . '/includes/sidebar.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Modifica utente</h5>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="index.php?action=users&do=list" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Indietro
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_SESSION['message']) ?>
                    <?php unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <!-- Form column -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Modifica utente</h3>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="index.php?action=users&do=update&id=<?= $user['id'] ?>">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" name="username" class="form-control"
                                        value="<?= htmlspecialchars($user['username']) ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Ruolo</label>
                                    <select name="role" class="form-control" required>
                                        <option value="viewer" <?= $user['role'] === 'viewer' ? 'selected' : '' ?>>
                                            Spettatore</option>
                                        <option value="manager" <?= $user['role'] === 'manager' ? 'selected' : '' ?>>
                                            Manager</option>
                                        <option value="super_admin"
                                            <?= $user['role'] === 'super_admin' ? 'selected' : '' ?>>Super Admin
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group form-check">
                                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                                        <?= $user['is_active'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="is_active">Utente attivo</label>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Salva modifiche
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Image column -->
                <div class="col-md-6 d-flex align-items-center">
                    <div class="text-center w-100">
                        <img src="assets/images/password-security.gif" alt="Gestione utenti" class="img-fluid"
                            style="max-height: 400px;">
                        <h4 class="mt-3">Gestione degli utenti</h4>
                        <p class="text-muted">Modifica i dettagli e i permessi degli utenti esistenti.</p>
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