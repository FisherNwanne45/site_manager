<?php include APP_PATH . '/includes/header.php'; ?>
<?php include APP_PATH . '/includes/sidebar.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">



    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['message']) ?></div>
            <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">Configurazione SMTP</h3>
                </div>

                <div class="card-body">
                    <form method="POST" action="index.php?action=settings&do=smtp">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="host">Host SMTP</label>
                                <input type="text" class="form-control" id="host" name="host"
                                    value="<?= isset($smtpSettings['host']) ? htmlspecialchars($smtpSettings['host']) : '' ?>"
                                    required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="port">Porta SMTP</label>
                                <input type="number" class="form-control" id="port" name="port"
                                    value="<?= isset($smtpSettings['port']) ? htmlspecialchars($smtpSettings['port']) : '587' ?>"
                                    required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="username">SMTP Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    value="<?= isset($smtpSettings['username']) ? htmlspecialchars($smtpSettings['username']) : '' ?>"
                                    required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password">SMTP Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    value="<?= isset($smtpSettings['password']) ? htmlspecialchars($smtpSettings['password']) : '' ?>"
                                    required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="encryption">Encryption</label>
                                <select class="form-control" id="encryption" name="encryption" required>
                                    <option value="tls"
                                        <?= (isset($smtpSettings['encryption']) && $smtpSettings['encryption'] == 'tls') ? 'selected' : '' ?>>
                                        TLS (Implicit)</option>
                                    <option value="starttls"
                                        <?= (isset($smtpSettings['encryption']) && $smtpSettings['encryption'] == 'starttls') ? 'selected' : '' ?>>
                                        STARTTLS (Explicit)</option>
                                    <option value="ssl"
                                        <?= (isset($smtpSettings['encryption']) && $smtpSettings['encryption'] == 'ssl') ? 'selected' : '' ?>>
                                        SSL</option>
                                    <option value=""
                                        <?= (isset($smtpSettings['encryption']) && empty($smtpSettings['encryption'])) ? 'selected' : '' ?>>
                                        None</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="from_name">Da Nome</label>
                                <input type="text" class="form-control" id="from_name" name="from_name"
                                    value="<?= isset($smtpSettings['from_name']) ? htmlspecialchars($smtpSettings['from_name']) : '' ?>"
                                    required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="from_email">Da e-mail</label>
                                <input type="email" class="form-control" id="from_email" name="from_email"
                                    value="<?= isset($smtpSettings['from_email']) ? htmlspecialchars($smtpSettings['from_email']) : '' ?>"
                                    required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="cc_email">CC / Indirizzo e-mail di risposta</label>
                                <input type="email" class="form-control" id="cc_email" name="cc_email"
                                    value="<?= isset($smtpSettings['cc_email']) ? htmlspecialchars($smtpSettings['cc_email']) : '' ?>"
                                    placeholder="Optional: Email to receive copies of all emails">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="test_email">Indirizzo e-mail di prova</label>
                            <input type="email" class="form-control" id="test_email" name="test_email"
                                placeholder="Enter email address to test with"
                                value="<?= $smtpSettings['from_email'] ?? '' ?>">
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Salva impostazioni</button>
                            <button type="button" id="testSmtp" class="btn btn-info">Prova la connessione SMTP</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>
</div>

<script>
document.getElementById('testSmtp').addEventListener('click', function() {
    const btn = this;
    const testEmail = document.getElementById('test_email').value;
    const originalText = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML =
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Test in corso...';

    // Clear previous alerts
    document.querySelectorAll('.alert').forEach(el => el.remove());

    fetch('index.php?action=settings&do=test_smtp', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'test_email=' + encodeURIComponent(testEmail)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('La risposta della rete non è stata buona');
            }
            return response.json();
        })
        .then(data => {
            const alertType = data.success ? 'alert-success' : 'alert-danger';
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert ${alertType} mt-3`;
            alertDiv.textContent = data.message;
            btn.parentNode.insertBefore(alertDiv, btn.nextSibling);
        })
        .catch(error => {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger mt-3';
            alertDiv.textContent = 'Errore durante il test SMTP: ' + error.message;
            btn.parentNode.insertBefore(alertDiv, btn.nextSibling);
            console.error('Error:', error);
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
});
</script>

<?php include APP_PATH . '/includes/footer.php'; ?>