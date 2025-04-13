<?php include APP_PATH . '/includes/header.php'; ?>
<?php include APP_PATH . '/includes/sidebar.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dashboard</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <!-- All Websites Small Box -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?= $totalWebsites ?></h3>
                            <p>Tutti i servizi</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <a href="index.php?action=websites" class="small-box-footer">
                            Più info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Expiring Websites Small Box -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?= $expiringWebsitesCount ?></h3>
                            <p>Servizi in scadenza</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <a href="index.php?action=websites&sort=expiry_date&order=asc&search=&per_page=10"
                            class="small-box-footer">
                            Più info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- All Hosting Small Box -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?= $totalHosting ?></h3>
                            <p>Tutti i clienti</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="index.php?action=hosting" class="small-box-footer">
                            Più info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Expiring Hosting Small Box -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?= $buggyWebsitesCount ?></h3>
                            <p> Servizi con problemi</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <a href="#scaduti" class="small-box-footer">
                            Vedi sotto <i class="fas fa-arrow-circle-down"></i>
                        </a>
                    </div>
                </div>

            </div>

            <style>
                .view-more-link {
                    color: #6c757d;
                    text-decoration: none;
                    font-style: italic;
                    transition: all 0.3s ease;
                }

                .view-more-link:hover {
                    color: #007bff;
                    text-decoration: underline;
                }

                .view-more-link i {
                    margin-right: 5px;
                }

                .table-sm-text {
                    font-size: 0.85rem;
                }

                .table-sm-text th,
                .table-sm-text td {
                    padding: 0.5rem 0.8rem;
                }

                .badge-scaduto {
                    background-color: #dc3545;
                    color: white;
                }

                .badge-in-scadenza {
                    background-color: #ffc107;
                    color: #212529;
                }

                .badge-info {
                    min-width: 25px;
                    display: inline-block;
                }

                .text-center {
                    text-align: center;
                }
            </style>

            <!-- First Row of Tables -->
            <div class="row mt-4">
                <!-- Expiring Services Table -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-dark">
                            <h3 class="card-title text-white mb-0">
                                Scadenza entro 30 giorni
                                <span class="badge badge-warning"><?= $expiringWebsitesCount ?></span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <?php if (empty($expiringWebsites)): ?>
                                <p>Nessun servizio in scadenza nei prossimi 30 giorni.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-sm-text">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Servizi</th>
                                                <th>In scadenza</th>
                                            </tr>
                                        </thead>
                                        <tbody id="expiringTableBody">
                                            <?php foreach (array_slice($expiringWebsites, 0, 2) as $website): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($website['name']) ?></td>
                                                    <td>
                                                        <a href="index.php?action=websites&do=view&id=<?= $website['id'] ?>">
                                                            <?= htmlspecialchars($website['domain']) ?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-in-scadenza">
                                                            <?= htmlspecialchars($website['expiry_date']) ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>

                                            <!-- Hidden rows -->
                                            <?php foreach (array_slice($expiringWebsites, 2) as $website): ?>
                                                <tr class="d-none more-expiring-rows">
                                                    <td><?= htmlspecialchars($website['name']) ?></td>
                                                    <td>
                                                        <a href="index.php?action=websites&do=view&id=<?= $website['id'] ?>">
                                                            <?= htmlspecialchars($website['domain']) ?>
                                                        </a>
                                                    </td>

                                                    <td>
                                                        <span class="badge badge-in-scadenza">
                                                            <?= htmlspecialchars($website['expiry_date']) ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php if (count($expiringWebsites) > 2): ?>
                                    <div class="text-center mt-2">
                                        <a href="#" class="view-more-link" data-target="expiring">
                                            <i class="fas fa-chevron-down"></i> Visualizza tutto
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Buggy Service Table -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-dark">
                            <h3 class="card-title text-white mb-0">
                                Servizi con problemi
                                <span class="badge badge-danger"><?= $buggyWebsitesCount ?></span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <?php if (empty($buggyWebsites)): ?>
                                <p>Nessun servizio con problemi.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-sm-text">
                                        <thead>
                                            <tr>
                                                <th>Cliente</th>
                                                <th>Servizio</th>
                                                <th>Bugs?</th>
                                            </tr>
                                        </thead>
                                        <tbody id="buggyTableBody">
                                            <?php foreach (array_slice($buggyWebsites, 0, 2) as $website): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($website['hosting_server'] ?? 'N/A') ?></td>
                                                    <td>
                                                        <a href="index.php?action=websites&do=view&id=<?= $website['id'] ?>">
                                                            <?= htmlspecialchars($website['domain']) ?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-warning">
                                                            Si
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>

                                            <!-- Hidden rows -->
                                            <?php foreach (array_slice($buggyWebsites, 2) as $website): ?>
                                                <tr class="d-none more-buggy-rows">
                                                    <td><?= htmlspecialchars($website['hosting_server'] ?? 'N/A') ?></td>
                                                    <td>
                                                        <a href="index.php?action=websites&do=view&id=<?= $website['id'] ?>">
                                                            <?= htmlspecialchars($website['domain']) ?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-warning">
                                                            Si
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php if (count($buggyWebsites) > 2): ?>
                                    <div class="text-center mt-2">
                                        <a href="#" class="view-more-link" data-target="buggy">
                                            <i class="fas fa-chevron-down"></i> Visualizza tutto
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Second Row of Tables -->
            <div class="row mt-4">
                <!-- Expired Services Table -->
                <div class="col-md-6" id="scaduti">
                    <div class="card">
                        <div class="card-header bg-danger">
                            <h3 class="card-title text-white mb-0">
                                Servizi scaduti
                                <span class="badge badge-light"><?= $expiredWebsitesCount ?></span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <?php if (empty($expiredWebsites)): ?>
                                <p>Nessun servizio scaduto.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-sm-text">
                                        <thead>
                                            <tr>
                                                <th>Cliente</th>
                                                <th>Servizio</th>
                                                <th>Scaduto</th>
                                            </tr>
                                        </thead>
                                        <tbody id="expiredTableBody">
                                            <?php foreach (array_slice($expiredWebsites, 0, 2) as $website): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($website['hosting_server'] ?? 'N/A') ?></td>
                                                    <td>
                                                        <a href="index.php?action=websites&do=view&id=<?= $website['id'] ?>">
                                                            <?= htmlspecialchars($website['domain']) ?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-scaduto">
                                                            <?= htmlspecialchars($website['expiry_date']) ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>

                                            <!-- Hidden rows -->
                                            <?php foreach (array_slice($expiredWebsites, 2) as $website): ?>
                                                <tr class="d-none more-expired-rows">
                                                    <td><?= htmlspecialchars($website['hosting_server'] ?? 'N/A') ?></td>
                                                    <td>
                                                        <a href="index.php?action=websites&do=view&id=<?= $website['id'] ?>">
                                                            <?= htmlspecialchars($website['domain']) ?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-scaduto">
                                                            <?= htmlspecialchars($website['expiry_date']) ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php if (count($expiredWebsites) > 2): ?>
                                    <div class="text-center mt-2">
                                        <a href="#" class="view-more-link" data-target="expired">
                                            <i class="fas fa-chevron-down"></i> Visualizza tutto
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Hosting Summary Table -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title text-white mb-0">
                                Riepilogo Clienti e Servizi
                                <span class="badge badge-light"><?= $totalHosting ?></span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <?php if (empty($hostingWithCounts)): ?>
                                <p>Nessun cliente trovato.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-sm-text">
                                        <thead>
                                            <tr>
                                                <th>Cliente</th>
                                                <th>P. Iva</th>
                                                <th>Servizi Associati</th>
                                            </tr>
                                        </thead>
                                        <tbody id="hostingSummaryTableBody">
                                            <?php foreach (array_slice($hostingWithCounts, 0, 2) as $hosting): ?>
                                                <tr>
                                                    <td>
                                                        <a href="index.php?action=hosting&do=view&id=<?= $hosting['id'] ?>">
                                                            <?= htmlspecialchars($hosting['server_name']) ?>
                                                        </a>
                                                    </td>
                                                    <td><?= htmlspecialchars($hosting['provider'] ?? 'N/A') ?></td>
                                                    <td class="text-center">
                                                        <a href="index.php?action=hosting&do=services&id=<?= $hosting['id'] ?>">
                                                            <span class="badge badge-info">

                                                                <?= $hosting['service_count'] ?>

                                                            </span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>

                                            <!-- Hidden rows -->
                                            <?php foreach (array_slice($hostingWithCounts, 2) as $hosting): ?>
                                                <tr class="d-none more-hosting-rows">
                                                    <td>
                                                        <a href="index.php?action=hosting&do=view&id=<?= $hosting['id'] ?>">
                                                            <?= htmlspecialchars($hosting['server_name']) ?>
                                                        </a>
                                                    </td>
                                                    <td><?= htmlspecialchars($hosting['provider'] ?? 'N/A') ?></td>
                                                    <td class="text-center">
                                                        <a href="index.php?action=hosting&do=services&id=<?= $hosting['id'] ?>">
                                                            <span class="badge badge-info">

                                                                <?= $hosting['service_count'] ?>

                                                            </span>
                                                        </a>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php if (count($hostingWithCounts) > 2): ?>
                                    <div class="text-center mt-2">
                                        <a href="#" class="view-more-link" data-target="hosting">
                                            <i class="fas fa-chevron-down"></i> Visualizza tutto
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
<!-- /.content-wrapper -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const viewMoreLinks = document.querySelectorAll('.view-more-link');

        viewMoreLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const target = this.getAttribute('data-target');
                const icon = this.querySelector('i');
                const rows = document.querySelectorAll(`.more-${target}-rows`);

                if (this.classList.contains('expanded')) {
                    // Collapse the table
                    rows.forEach(row => row.classList.add('d-none'));
                    icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
                    this.innerHTML = '<i class="fas fa-chevron-down"></i> Visualizza tutto';
                    this.classList.remove('expanded');
                } else {
                    // Expand the table
                    rows.forEach(row => row.classList.remove('d-none'));
                    icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
                    this.innerHTML = '<i class="fas fa-chevron-up"></i> Mostra meno';
                    this.classList.add('expanded');
                }
            });
        });
    });
</script>
<?php include APP_PATH . '/includes/footer.php'; ?>