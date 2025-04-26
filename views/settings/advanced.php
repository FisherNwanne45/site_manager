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

            <?php if (isset($_SESSION['google_sync_result'])): ?>
            <div class="mt-4 p-3 border rounded alert-primary">
                <h5>Ultimi Risultati della Sincronizzazione:</h5>
                <ul>
                    <li>Record esportati: <?= $_SESSION['google_sync_result']['exported'] ?? 0 ?></li>
                    <li>Record importati: <?= $_SESSION['google_sync_result']['imported'] ?? 0 ?></li>
                    <li>Record aggiornati: <?= $_SESSION['google_sync_result']['updated'] ?? 0 ?></li>
                    <li>Conflitti risolti: <?= $_SESSION['google_sync_result']['conflicts'] ?? 0 ?></li>
                    <?php if (!empty($_SESSION['google_sync_result']['errors'])): ?>
                    <li>Errori:
                        <ul>
                            <?php foreach ($_SESSION['google_sync_result']['errors'] as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php unset($_SESSION['google_sync_result']); ?>
            <?php endif; ?>

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

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-primary">
                        <i class="fas fa-clock text-primary mr-1"></i>
                        <b>Gestione Cron Job</b>
                    </h3>
                </div>
                <form method="post" action="index.php?action=settings&do=advanced">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="cronActive" name="cron_active"
                                    value="1" <?= $cronStatus ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="cronActive">Attiva Cron Job</label>
                            </div>
                            <small class="form-text text-muted">Quando attivo, il sistema invierà automaticamente
                                notifiche di scadenza</small>
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

            <?php if (isset($_SESSION['google_error'])): ?>
            <div class="alert alert-danger">
                <strong>Google Sheets Error:</strong> <?= htmlspecialchars($_SESSION['google_error']) ?>
                <?php unset($_SESSION['google_error']); ?>
            </div>
            <?php endif; ?>

            <!-- Google Sheets Integration Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title text-success">
                        <i class="fas fa-file-excel text-success mr-1"></i>
                        <b>Integrazione con Google Sheet</b>
                    </h3>
                </div>
                <div class="card-body">
                    <form method="post" action="index.php?action=settings&do=google_sheets" id="googleSettingsForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="google_sheet_id">ID del Google Sheet</label>
                                    <input type="text" class="form-control" id="google_sheet_id" name="google_sheet_id"
                                        value="<?= htmlspecialchars($googleSheetSettings['sheet_id'] ?? '') ?>"
                                        placeholder="Inserisci l'ID del Google Sheet">
                                    <small class="form-text text-muted">Trovato nell'URL del tuo Google Sheet (tra /d/ e
                                        /edit)</small>
                                </div>

                                <div class="form-group">
                                    <label for="google_sheet_name">Nome del Foglio</label>
                                    <input type="text" class="form-control" id="google_sheet_name"
                                        name="google_sheet_name"
                                        value="<?= htmlspecialchars($googleSheetSettings['sheet_name'] ?? '') ?>"
                                        placeholder="Inserisci il nome del foglio (nome della tab)">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="google_credentials">Credenziali del Account di Servizio (JSON)</label>
                                    <textarea class="form-control" id="google_credentials" name="google_credentials"
                                        rows="7"
                                        placeholder="Incolla le tue credenziali JSON dell'account di servizio"><?= htmlspecialchars($googleSheetSettings['credentials'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="google_sync_enabled"
                                    name="google_sync_enabled" value="1"
                                    <?= ($googleSheetSettings['enabled'] ?? false) ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="google_sync_enabled">Abilita la
                                    sincronizzazione con Google Sheets</label>
                            </div>
                        </div>

                        <div class="btn-group mt-3">
                            <button type="submit" name="save_google_settings" class="btn btn-primary">Salva le
                                impostazioni di Google Sheets</button>
                            <button type="button" name="export_to_google" id="exportBtn"
                                class="btn btn-success ml-2">Esporta su Google Sheet</button>
                            <button type="button" name="import_from_google" id="importBtn"
                                class="btn btn-info ml-2">Importa da Google Sheet</button>
                        </div>
                    </form>

                    <!-- Hidden forms for import/export actions -->
                    <form id="exportForm" method="post" action="index.php?action=settings&do=google_sheets"
                        style="display:none;">
                        <input type="hidden" name="export_to_google" value="1">
                        <input type="hidden" name="google_sheet_id"
                            value="<?= htmlspecialchars($googleSheetSettings['sheet_id'] ?? '') ?>">
                        <input type="hidden" name="google_sheet_name"
                            value="<?= htmlspecialchars($googleSheetSettings['sheet_name'] ?? '') ?>">
                        <input type="hidden" name="google_credentials"
                            value="<?= htmlspecialchars($googleSheetSettings['credentials'] ?? '') ?>">
                    </form>

                    <form id="importForm" method="post" action="index.php?action=settings&do=google_sheets"
                        style="display:none;">
                        <input type="hidden" name="import_from_google" value="1">
                        <input type="hidden" name="google_sheet_id"
                            value="<?= htmlspecialchars($googleSheetSettings['sheet_id'] ?? '') ?>">
                        <input type="hidden" name="google_sheet_name"
                            value="<?= htmlspecialchars($googleSheetSettings['sheet_name'] ?? '') ?>">
                        <input type="hidden" name="google_credentials"
                            value="<?= htmlspecialchars($googleSheetSettings['credentials'] ?? '') ?>">
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const exportBtn = document.getElementById('exportBtn');
    const importBtn = document.getElementById('importBtn');
    const googleSyncToggle = document.getElementById('google_sync_enabled');

    function updateButtonState() {
        const syncOn = googleSyncToggle.checked;
        exportBtn.disabled = !syncOn;
        importBtn.disabled = !syncOn;
    }


    updateButtonState();

    googleSyncToggle.addEventListener('change', updateButtonState);

    exportBtn.addEventListener('click', function() {
        document.getElementById('confirmationMessage').innerHTML =
            'Sei sicuro di voler esportare i dati su Google Sheet?';
        $('#confirmationModal').modal('show');
        document.getElementById('confirmActionBtn').onclick = function() {
            $('#confirmationModal').modal('hide');
            document.getElementById('exportForm').submit();
        };
    });

    importBtn.addEventListener('click', function() {
        document.getElementById('confirmationMessage').innerHTML =
            'Sei sicuro di voler importare i dati da Google Sheet?<br><small>Questa operazione sovrascriverà i dati esistenti.</small>';
        $('#confirmationModal').modal('show');
        document.getElementById('confirmActionBtn').onclick = function() {
            $('#confirmationModal').modal('hide');
            document.getElementById('importForm').submit();
        };
    });
});
</script>

<?php include APP_PATH . '/includes/footer.php'; ?>