<?php include APP_PATH . '/includes/header.php'; ?>
<?php include APP_PATH . '/includes/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Servizi per <?= htmlspecialchars($hostingPlan['server_name']) ?></h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="index.php?action=hosting" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Torna alla lista clienti
                    </a>
                    <a href="index.php?action=hosting&do=service_create&id=<?= $hostingPlan['id'] ?>"
                        class="btn btn-primary ml-2">
                        <i class="fas fa-plus"></i> Aggiungi servizio
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php if (empty($services)): ?>
                <div class="alert alert-info">Nessun servizio associato a questo cliente.</div>
            <?php else: ?>
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['message'] ?></div>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Dettaglio Servizi
                                            <?php if ($hostingPlan['service_count'] > 0): ?>
                                                <span class="badge badge-info"> <?= $hostingPlan['service_count'] ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">0</span>
                                            <?php endif; ?>
                                        </th>
                                        <th>Tipologia</th>
                                        <th>Registrante</th>
                                        <th>Scadenza</th>
                                        <th>Stato</th>
                                        <th>Azioni</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($services as $service): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($service['domain']) ?></td>
                                            <td><?= htmlspecialchars($service['name']) ?></td>
                                            <td><?= htmlspecialchars($service['email_server']) ?></td>
                                            <td><?= htmlspecialchars($service['expiry_date']) ?></td>
                                            <td>
                                                <?php
                                                $status = $service['dynamic_status'] ?? 'attivo';
                                                $badgeClass = [
                                                    'attivo' => 'success',
                                                    'scade_presto' => 'warning',
                                                    'scaduto' => 'danger'
                                                ][$status];
                                                ?>
                                                <span class="badge badge-<?= $badgeClass ?>">
                                                    <?= ucwords(str_replace('_', ' ', $status)) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="index.php?action=websites&do=view&id=<?= $service['id'] ?>"
                                                    class="btn btn-sm btn-success" data-custom-tooltip="Visualizzare">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if ($userRole === 'manager' || $userRole === 'super_admin'): ?>
                                                    <a href="index.php?action=websites&do=edit&id=<?= $service['id'] ?>"
                                                        class="btn btn-sm btn-primary" data-custom-tooltip="Modificare">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="index.php?action=websites&do=delete&id=<?= $service['id'] ?>"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Sei sicuro di voler eliminare questo servizio?');"
                                                        data-custom-tooltip="Eliminare">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php include APP_PATH . '/includes/footer.php'; ?>