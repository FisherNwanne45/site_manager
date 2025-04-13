<?php include APP_PATH . '/includes/header.php'; ?>
<?php include APP_PATH . '/includes/sidebar.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Gestisci i servizi</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Confirmation Modal -->
            <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-dark">
                            <h5 class="modal-title">Conferma azione</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="confirmationMessage">Sei sicuro?</div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Annulla</button>
                            <button type="button" class="btn btn-success" id="confirmActionBtn">Conferma</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Toolbar -->
            <div class="mb-3 d-flex flex-wrap justify-content-between align-items-center">
                <div>
                    <a href="index.php?action=websites&do=create" class="btn btn-primary btn-sm mr-2">
                        <i class="fas fa-plus"></i> Aggiungi servizio
                    </a>
                    <form method="post" action="index.php?action=websites&do=export" class="d-inline">
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-file-export"></i> Esporta file Excel
                        </button>
                    </form>
                </div>
                <form method="post" action="index.php?action=websites&do=import" enctype="multipart/form-data"
                    class="d-flex"
                    onsubmit="return confirm('Questo aggiornerà i siti web esistenti con i domini corrispondenti. Continuare?')">
                    <div class="input-group input-group-sm">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="import_file" name="import_file" required
                                accept=".xls,.xlsx" onchange="updateFileName(this)">
                            <label class="custom-file-label" for="import_file">Scegli il file Excel</label>
                        </div>
                        <div class="input-group-append">
                            <button class="btn btn-info" type="submit">
                                <i class="fas fa-file-import"></i> Importare
                            </button>
                        </div>
                    </div>
                </form>
                <script>
                    function updateFileName(input) {
                        if (input.files && input.files[0]) {
                            // Get the file name and update the label
                            const fileName = input.files[0].name;
                            const label = input.nextElementSibling; // The label is the next sibling
                            label.textContent = fileName;
                        }
                    }
                </script>
            </div>

            <!-- Session messages -->
            <?php if (isset($_SESSION['import_result'])): ?>
                <div class="alert alert-info">
                    <h5>Importa risultati:</h5>
                    <p>Importato: <?= $_SESSION['import_result']['imported'] ?></p>
                    <p>Aggiornato: <?= $_SESSION['import_result']['updated'] ?></p>
                    <p>Saltato: <?= $_SESSION['import_result']['skipped'] ?></p>

                    <?php if (!empty($_SESSION['import_result']['errors'])): ?>
                        <details class="mt-3">
                            <summary>Dettagli dell'errore</summary>
                            <ul class="small">
                                <?php foreach ($_SESSION['import_result']['errors'] as $error): ?>
                                    <li>Row <?= $error['row'] ?> (<?= $error['domain'] ?>): <?= $error['message'] ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </details>
                    <?php endif; ?>
                </div>
                <?php unset($_SESSION['import_result']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success"><?= $_SESSION['message'] ?></div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <!-- Search and filter controls -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form method="get" action="index.php" class="form-inline">
                        <input type="hidden" name="action" value="websites">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" name="search" placeholder="Cerca..."
                                value="<?= htmlspecialchars($search) ?>">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit"><i
                                        class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-right">
                    <form method="get" action="index.php" class="form-inline float-right">
                        <input type="hidden" name="action" value="websites">
                        <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                        <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
                        <input type="hidden" name="order" value="<?= htmlspecialchars($order) ?>">
                        <label class="mr-2">Mostra:</label>
                        <select name="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10 risultati</option>
                            <option value="30" <?= $perPage == 30 ? 'selected' : '' ?>>30 risultati</option>
                            <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50 risultati</option>
                        </select>
                    </form>
                </div>
            </div>
            <style>
                .table-sm-text {
                    font-size: 0.90rem;
                }

                .table-sm-text th,
                .table-sm-text td {
                    padding: 0.5rem 0.8rem;
                }

                /* Custom Tooltip Styles */
                [data-custom-tooltip] {
                    position: relative;
                    cursor: pointer;
                }

                [data-custom-tooltip]::after {
                    content: attr(data-custom-tooltip);
                    position: absolute;
                    bottom: 100%;
                    left: 50%;
                    transform: translateX(-50%);
                    background: #333;
                    color: white;
                    padding: 4px 8px;
                    border-radius: 4px;
                    font-size: 14px;
                    font-weight: bold;
                    white-space: nowrap;
                    opacity: 0;
                    visibility: hidden;
                    transition: opacity 0.2s, visibility 0.2s;
                    z-index: 1000;
                    pointer-events: none;
                    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
                }

                [data-custom-tooltip]:hover::after {
                    opacity: 1;
                    visibility: visible;
                }

                /* Remove default tooltips */
                [title] {
                    position: relative;
                }

                [title]:hover::before,
                [title]:hover::after {
                    display: none !important;
                }
            </style>
            <!-- Website Table (Wrapped in a card) -->
            <div class="card">
                <div class="card-header">
                    <h5>Tutti i servizi</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0 table-sm-text">
                            <thead class="thead-dark">
                                <tr>
                                    <!-- Hosting Server Column -->
                                    <th>
                                        <a
                                            href="?action=websites&sort=hosting_server&order=<?= ($sort == 'hosting_server' && $order == 'asc') ? 'desc' : 'asc' ?>&search=<?= urlencode($search) ?>&per_page=<?= $perPage ?>">
                                            Cliente
                                            <?php if ($sort == 'hosting_server'): ?>
                                                <i class="fas fa-sort-<?= $order == 'asc' ? 'up' : 'down' ?>"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>

                                    <!-- Domain Column -->
                                    <th>
                                        <a
                                            href="?action=websites&sort=domain&order=<?= ($sort == 'domain' && $order == 'asc') ? 'desc' : 'asc' ?>&search=<?= urlencode($search) ?>&per_page=<?= $perPage ?>">
                                            Dettaglio Servizi
                                            <?php if ($sort == 'domain'): ?>
                                                <i class="fas fa-sort-<?= $order == 'asc' ? 'up' : 'down' ?>"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>

                                    <!-- Service Type Column -->
                                    <th>
                                        <a
                                            href="?action=websites&sort=name&order=<?= ($sort == 'name' && $order == 'asc') ? 'desc' : 'asc' ?>&search=<?= urlencode($search) ?>&per_page=<?= $perPage ?>">
                                            Tipologia
                                            <?php if ($sort == 'name'): ?>
                                                <i class="fas fa-sort-<?= $order == 'asc' ? 'up' : 'down' ?>"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>

                                    <!-- Registrar Column -->
                                    <th>
                                        <a
                                            href="?action=websites&sort=email_server&order=<?= ($sort == 'email_server' && $order == 'asc') ? 'desc' : 'asc' ?>&search=<?= urlencode($search) ?>&per_page=<?= $perPage ?>">
                                            Registrante
                                            <?php if ($sort == 'email_server'): ?>
                                                <i class="fas fa-sort-<?= $order == 'asc' ? 'up' : 'down' ?>"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>

                                    <!-- Expiry Date Column -->
                                    <th>
                                        <a
                                            href="?action=websites&sort=expiry_date&order=<?= ($sort == 'expiry_date' && $order == 'asc') ? 'desc' : 'asc' ?>&search=<?= urlencode($search) ?>&per_page=<?= $perPage ?>">
                                            Scadenza
                                            <?php if ($sort == 'expiry_date'): ?>
                                                <i class="fas fa-sort-<?= $order == 'asc' ? 'up' : 'down' ?>"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>

                                    <th>Bug?</th>
                                    <th>Stato</th>
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($websites as $website): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($website['hosting_server'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($website['domain']) ?></td>
                                        <td><?= htmlspecialchars($website['name'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($website['email_server']) ?></td>
                                        <td><?= htmlspecialchars($website['expiry_date']) ?></td>
                                        <td>
                                            <?php
                                            $notes = strtolower(trim($website['notes'] ?? ''));
                                            $hasIssues = !empty($notes) && !in_array($notes, ['none', 'nessuno', '']);
                                            ?>
                                            <span class="badge badge-<?= $hasIssues ? 'warning' : 'success' ?>">
                                                <?= $hasIssues ? 'Si' : 'No' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            $status = $website['dynamic_status'] ?? 'attivo';
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
                                            <a href="index.php?action=websites&do=view&id=<?= $website['id'] ?>"
                                                class="btn btn-sm btn-success" data-custom-tooltip="Visualizzare">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="index.php?action=websites&do=edit&id=<?= $website['id'] ?>"
                                                class="btn btn-sm btn-primary" data-custom-tooltip="Modificare">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="index.php?action=email&do=expiry&id=<?= $website['id'] ?>"
                                                class="btn btn-sm btn-info confirmable" data-type="email"
                                                data-name="<?= htmlspecialchars($website['domain']) ?>"
                                                data-custom-tooltip="Invia email di scadenza">
                                                <i class="fas fa-envelope"></i>
                                            </a>
                                            <a href="index.php?action=email&do=status&id=<?= $website['id'] ?>"
                                                class="btn btn-sm btn-secondary confirmable" data-type="email"
                                                data-name="<?= htmlspecialchars($website['domain']) ?>"
                                                data-custom-tooltip="E-mail rapporto stato">
                                                <i class="fas fa-bell"></i>
                                            </a>
                                            <form method="post"
                                                action="index.php?action=websites&do=delete&id=<?= $website['id'] ?>"
                                                class="d-inline confirmable" data-type="delete"
                                                data-name="<?= htmlspecialchars($website['domain']) ?>">
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    data-custom-tooltip="Eliminare">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link"
                                    href="?action=websites&page=<?= $page - 1 ?>&sort=<?= $sort ?>&order=<?= $order ?>&search=<?= urlencode($search) ?>&per_page=<?= $perPage ?>">Precedente</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link"
                                    href="?action=websites&page=<?= $i ?>&sort=<?= $sort ?>&order=<?= $order ?>&search=<?= urlencode($search) ?>&per_page=<?= $perPage ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link"
                                    href="?action=websites&page=<?= $page + 1 ?>&sort=<?= $sort ?>&order=<?= $order ?>&search=<?= urlencode($search) ?>&per_page=<?= $perPage ?>">Successivo</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>

        </div>
    </section>
</div>
<!-- /.content-wrapper -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let pendingAction = null;

        // Handle confirmable actions (email and delete)
        document.querySelectorAll('.confirmable').forEach(el => {
            if (el.tagName === 'A') {
                // For email links
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    const type = this.dataset.type;
                    const name = this.dataset.name;
                    const message =
                        `Sei sicuro di voler inviare una email per il servizio: <strong>${name}</strong>?`;
                    document.getElementById('confirmationMessage').innerHTML = message;
                    $('#confirmationModal').modal('show');

                    pendingAction = () => {
                        window.location.href = this.href;
                    };
                });
            } else if (el.tagName === 'FORM') {
                // For delete forms
                const button = el.querySelector('button[type="submit"]');
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const type = el.dataset.type;
                    const name = el.dataset.name;
                    const message =
                        `Sei sicuro di voler eliminare il servizio: <strong>${name}</strong>?`;
                    document.getElementById('confirmationMessage').innerHTML = message;
                    $('#confirmationModal').modal('show');

                    pendingAction = () => {
                        el.submit();
                    };
                });
            }
        });

        // Handle confirmation button click
        document.getElementById('confirmActionBtn').addEventListener('click', function() {
            if (pendingAction) {
                $('#confirmationModal').modal('hide');
                setTimeout(pendingAction, 300);
            }
        });

        // File name update function
        function updateFileName(input) {
            if (input.files && input.files[0]) {
                const fileName = input.files[0].name;
                const label = input.nextElementSibling;
                label.textContent = fileName;
            }
        }
    });
</script>

<?php include APP_PATH . '/includes/footer.php'; ?>