<?php include APP_PATH . '/includes/header.php'; ?>
<?php include APP_PATH . '/includes/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Aggiungi servizio per <?= htmlspecialchars($hostingPlan['server_name']) ?></h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="index.php?action=hosting&do=services&id=<?= $hostingPlan['id'] ?>"
                        class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Torna ai servizi
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form method="POST"
                        action="index.php?action=hosting&do=service_create&id=<?= $hostingPlan['id'] ?>">
                        <input type="hidden" name="hosting_id" value="<?= $hostingPlan['id'] ?>">
                        <input type="hidden" name="assigned_email"
                            value="<?= htmlspecialchars($hostingPlan['email_address'] ?? '') ?>">

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="domain">DETTAGLIO SERVIZI</label>
                                <input type="text" class="form-control" id="domain" name="domain"
                                    value="<?= htmlspecialchars($website['domain'] ?? '') ?>"
                                    placeholder="nome del servizio ex. fullmidia.it" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="name">TIPOLOGIA DI SERVIZI</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="<?= htmlspecialchars($website['name'] ?? '') ?>"
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
                                    value="<?= htmlspecialchars($website['email_server'] ?? '') ?>"
                                    placeholder="ex. Serverplan, Vhosting">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="expiry_date">SCADENZA</label>
                                <input type="date" class="form-control" id="expiry_date" name="expiry_date"
                                    value="<?= htmlspecialchars($website['expiry_date'] ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-2">
                                <label for="status">Costo Server</label>
                                <input type="text" class="form-control" id="status" name="status"
                                    value="<?= htmlspecialchars($website['status'] ?? '') ?>"
                                    placeholder="Costo Server (iva exclusa)">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="vendita">Prezzo di vendita</label>
                                <input type="text" class="form-control" id="vendita" name="vendita"
                                    value="<?= htmlspecialchars($website['vendita'] ?? '') ?>"
                                    placeholder="Prezzo di vendita">
                            </div>
                            <div class="form-group col-md-2">
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
                                <label for="notes">Bug? Aggiungi un report</label>
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
                                Aggiungi servizio
                            </button>
                            <a href="index.php?action=hosting&do=services&id=<?= $hostingPlan['id'] ?>"
                                class="btn btn-secondary">
                                Cancellare
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include APP_PATH . '/includes/footer.php'; ?>