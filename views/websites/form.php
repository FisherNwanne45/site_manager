<?php include APP_PATH . '/includes/header.php'; ?>
<?php include APP_PATH . '/includes/sidebar.php'; ?>

<?php
// Initialize default website array with all required keys
$website = $website ?? [];
// Change this part:
$defaultWebsite = [
    'id' => null,
    'domain' => '',
    'name' => '',
    'hosting_id' => null,
    'email_server' => '',
    'expiry_date' => '',
    'status' => '',
    'assigned_email' => '',
    'proprietario' => '',
    'dns' => '',
    'cpanel' => '',
    'epanel' => '',
    'notes' => '',
    'dynamic_status' => 'attivo'
];
$website = array_merge($defaultWebsite, $website);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h6 class="card-title mb-0">
                        <?php if ($website['id']) : ?>
                        <b><?= htmlspecialchars($website['domain']) ?> </b>
                        <?php
                            $badgeClass = [
                                'attivo' => 'success',
                                'scade_presto' => 'warning',
                                'scaduto' => 'danger'
                            ][$website['dynamic_status']];
                            ?>
                        <span class="badge badge-<?= $badgeClass ?>">
                            <?= ucwords(str_replace('_', ' ', $website['dynamic_status'])) ?>
                        </span>
                        <?php else : ?>
                        Dettagli del nuovo servizio
                        <?php endif; ?>
                    </h6>

                </div>
                <div class="col-sm-6 text-right">
                    <?php if ($website['id'] && $website['dynamic_status'] === 'scade_presto'): ?>
                    <form method="POST" action="index.php?action=websites&do=renew&id=<?= $website['id'] ?>"
                        class="d-inline">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-sync-alt"></i> Segna come rinnovato (+1 anno)
                        </button>
                    </form>
                    <?php endif; ?>
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

            <div class="card">

                <div class="card-body">
                    <form method="POST"
                        action="index.php?action=websites&do=<?= $website['id'] ? 'edit&id=' . $website['id'] : 'create' ?>">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="hosting_id">CLIENTE</label>
                                <select class="form-control" id="hosting_id" name="hosting_id">
                                    <option value="">-- Nessun cliente selezionato --</option>
                                    <?php foreach ($hostingPlans as $plan): ?>
                                    <option value="<?= $plan['id'] ?>"
                                        <?= (isset($website['hosting_id']) && $website['hosting_id'] == $plan['id']) ? 'selected' : '' ?>
                                        data-email="<?= htmlspecialchars($plan['email_address'] ?? '') ?>">
                                        <?= htmlspecialchars($plan['server_name']) ?>
                                        (<?= htmlspecialchars($plan['provider'] ?? 'N/A') ?>)
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="assigned_email">EMAIL ASSEGNATA</label>
                                <input type="email" class="form-control" id="assigned_email" name="assigned_email"
                                    value="<?= htmlspecialchars($website['assigned_email'] ?? '') ?>" readonly>

                            </div>
                        </div>

                        <div class="row">

                            <div class="form-group col-md-6">
                                <label for="domain">DETTAGLIO SERVIZI</label>
                                <input type="text" class="form-control" id="domain" name="domain"
                                    value="<?= htmlspecialchars($website['domain']) ?>"
                                    placeholder="nome del servizio ex. fullmidia.it" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="name">TIPOLOGIA DI SERVIZI</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="<?= htmlspecialchars($website['name']) ?>"
                                    placeholder="ex. dominio, server, e-mail" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="proprietario">PROPRIETARIO</label>
                                <input type="text" class="form-control" id="proprietario" name="proprietario"
                                    value="<?= htmlspecialchars($website['proprietario'] ?? '') ?>"
                                    placeholder="ex. Fullmidia">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="email_server">REGISTRANTE</label>
                                <input type="text" class="form-control" id="email_server" name="email_server"
                                    value="<?= htmlspecialchars($website['email_server']) ?>"
                                    placeholder="ex. Serverplan, Vhosting">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="expiry_date">SCADENZA</label>
                                <input type="date" class="form-control" id="expiry_date" name="expiry_date"
                                    value="<?= htmlspecialchars($website['expiry_date']) ?>" required>
                            </div>
                        </div>
                        <div class="row">

                            <div class="form-group col-md-3">
                                <label for="status">Costo Server </label>
                                <input type="text" class="form-control" id="status" name="status"
                                    value="<?= htmlspecialchars($website['status']) ?>"
                                    placeholder="Costo Server (iva exclusa)">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="dns">Direct DNS A</label>
                                <input type="text" class="form-control" id="dns" name="dns"
                                    value="<?= htmlspecialchars($website['dns'] ?? '') ?>" placeholder="Direct DNS A">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="cpanel">Username Cpanel</label>
                                <input type="text" class="form-control" id="cpanel" name="cpanel"
                                    value="<?= htmlspecialchars($website['cpanel'] ?? '') ?>"
                                    placeholder="Username Cpanel">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="epanel">Email panel</label>
                                <input type="text" class="form-control" id="epanel" name="epanel"
                                    value="<?= htmlspecialchars($website['epanel'] ?? '') ?>" placeholder="Email panel">
                            </div>


                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="notes">Bug? Aggiungi un report </label>
                                <textarea class="form-control" id="notes" name="notes" rows="2"
                                    placeholder="Lascia vuoto se assente"><?= htmlspecialchars($website['notes'] ?? '') ?></textarea>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="remark">Notes</label>
                                <textarea class="form-control" id="remark" name="remark" rows="2"
                                    placeholder="Inserisci le note aziendali"><?= htmlspecialchars($website['remark'] ?? '') ?></textarea>
                            </div>
                        </div>



                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <?= $website['id'] ? 'Aggiorna servizio' : 'Aggiungi servizio' ?>
                            </button>
                            <a onclick="window.history.back();" class="btn btn-secondary">
                                Cancellare
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>
</div>
<script>
document.getElementById('hosting_id').addEventListener('change', function() {
    const hostingId = this.value;
    const emailField = document.getElementById('assigned_email');

    if (hostingId) {
        fetch(`index.php?action=getHostingEmail&id=${hostingId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('La risposta della rete non è stata buona');
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                    emailField.value = '';
                } else {
                    emailField.value = data.email;
                }
            })
            .catch(error => {
                console.error('Errore durante il recupero del client:', error);
                emailField.value = '';
            });
    } else {
        emailField.value = '';
    }
});

// Trigger change event on page load if hosting is preselected
document.addEventListener('DOMContentLoaded', function() {
    const hostingSelect = document.getElementById('hosting_id');
    if (hostingSelect.value) {
        hostingSelect.dispatchEvent(new Event('change'));
    }
});
</script>
<?php include APP_PATH . '/includes/footer.php'; ?>