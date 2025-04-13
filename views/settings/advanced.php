<?php include APP_PATH . '/includes/header.php'; ?>
<?php include APP_PATH . '/includes/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Impostazioni Avanzate</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_SESSION['message']) ?></div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gestione Cron Job</h3>
                </div>
                <form method="post" action="index.php?action=settings&do=advanced">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="cronActive" name="cron_active"
                                    value="1" <?= $cronStatus ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="cronActive">
                                    Attiva Cron Job
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                Quando attivo, il sistema invierà automaticamente notifiche di scadenza
                            </small>
                        </div>

                        <?php if ($lastRun): ?>
                            <div class="form-group">
                                <label>Ultima esecuzione:</label>
                                <p><?= htmlspecialchars($lastRun) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Salva Impostazioni</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<?php include APP_PATH . '/includes/footer.php'; ?>