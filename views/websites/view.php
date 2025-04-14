<?php include APP_PATH . '/includes/header.php'; ?>
<?php include APP_PATH . '/includes/sidebar.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Dettagli Servizio: <b><?= htmlspecialchars($website['domain']) ?></b> </h5>
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
            <div class="card">
                <!--<div class="card-header">
                    <h3 class="card-title"><?= htmlspecialchars($website['domain']) ?></h3>
                </div>-->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informazioni Generali</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">Cliente</th>
                                    <td><?= htmlspecialchars($website['hosting_server'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>Tipologia</th>
                                    <td><?= htmlspecialchars($website['name'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>Registrante</th>
                                    <td><?= htmlspecialchars($website['email_server']) ?></td>
                                </tr>
                                <tr>
                                    <th>Scadenza</th>
                                    <td><?= htmlspecialchars($website['expiry_date']) ?></td>
                                </tr>
                                <tr>
                                    <th>Stato</th>
                                    <td>
                                        <span class="badge badge-<?=
                                                                    $website['dynamic_status'] === 'attivo' ? 'success' : ($website['dynamic_status'] === 'scade_presto' ? 'warning' : 'danger')
                                                                    ?>">
                                            <?= ucwords(str_replace('_', ' ', $website['dynamic_status'])) ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Dettagli Aggiuntivi</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">Email Assegnata</th>
                                    <td><?= htmlspecialchars($website['assigned_email'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>Proprietario</th>
                                    <td><?= htmlspecialchars($website['proprietario'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>DNS</th>
                                    <td><?= htmlspecialchars($website['dns'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>cPanel</th>
                                    <td><?= htmlspecialchars($website['cpanel'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>ePanel</th>
                                    <td><?= htmlspecialchars($website['epanel'] ?? 'N/A') ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6>Bug Report</h6>
                            <div class="card">
                                <div class="card-body">
                                    <p><?= !empty($website['notes']) ? nl2br(htmlspecialchars($website['notes'])) : 'Nessuna bug' ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Note</h6>
                            <div class="card">
                                <div class="card-body">
                                    <p><?= !empty($website['remark']) ? nl2br(htmlspecialchars($website['remark'])) : 'Nessun note' ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($userRole === 'manager' || $userRole === 'super_admin'): ?>
                    <div class="card-footer text-right">
                        <a href="index.php?action=websites&do=edit&id=<?= $website['id'] ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Modifica
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<?php include APP_PATH . '/includes/footer.php'; ?>