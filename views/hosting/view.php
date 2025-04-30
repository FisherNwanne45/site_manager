<?php include APP_PATH . '/includes/header.php'; ?>
<?php include APP_PATH . '/includes/sidebar.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dettagli Cliente</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a onclick="window.history.back();" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Torna indietro
                    </a>
                    <a href="index.php?action=hosting&do=service_create&id=<?= $hostingPlan['id'] ?>"
                        class="btn btn-primary ml-2">
                        <i class="fas fa-plus"></i> Aggiungi servizio
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= htmlspecialchars($hostingPlan['server_name']) ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Informazioni Cliente</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">Nome Cliente</th>
                                    <td><?= htmlspecialchars($hostingPlan['server_name']) ?></td>
                                </tr>
                                <tr>
                                    <th>P.IVA</th>
                                    <td><?= htmlspecialchars($hostingPlan['provider'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?= htmlspecialchars($hostingPlan['email_address'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>Indirizzo IP</th>
                                    <td><?= htmlspecialchars($hostingPlan['ip_address'] ?? 'N/A') ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4>Statistiche</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">Totale Servizi</th>
                                    <td>
                                        <?php
                                        $serviceCount = $hostingPlan['service_count'] ?? 0;
                                        if ($serviceCount > 0): ?>
                                            <a href="index.php?action=hosting&do=services&id=<?= $hostingPlan['id'] ?>"
                                                class="btn btn-sm btn-info">
                                                Vedi <?= $serviceCount ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">Nessun servizio</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Data Creazione</th>
                                    <td><?= htmlspecialchars($hostingPlan['created_at'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>Ultimo Aggiornamento</th>
                                    <td><?= htmlspecialchars($hostingPlan['updated_at'] ?? 'N/A') ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php if (!empty($hostingPlan['notes'])): ?>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h4>Note</h4>
                                <div class="card">
                                    <div class="card-body">
                                        <p><?= nl2br(htmlspecialchars($hostingPlan['notes'])) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if ($userRole === 'manager' || $userRole === 'super_admin'): ?>
                    <div class="card-footer text-right">
                        <a href="index.php?action=hosting&do=edit&id=<?= $hostingPlan['id'] ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Modifica
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<?php include APP_PATH . '/includes/footer.php'; ?>