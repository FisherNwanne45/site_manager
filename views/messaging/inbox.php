<?php include APP_PATH . '/includes/header.php'; ?>
<?php include APP_PATH . '/includes/sidebar.php'; ?>

<?php if (isset($debug)): ?>
<div class="alert alert-info">
    Debug: User ID <?= $debug['user_id'] ?> | Threads: <?= $debug['thread_count'] ?>
</div>
<?php endif; ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Messages</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="?action=messaging&do=compose" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Message
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-hover">
                        <tbody>
                            <?php foreach ($threads as $thread): ?>
                            <tr onclick="window.location='?action=messaging&do=view&id=<?= $thread['id'] ?>'">
                                <td>
                                    <div class="font-weight-bold"><?= htmlspecialchars($thread['subject']) ?></div>
                                    <div class="text-muted"><?= substr($thread['last_message'], 0, 50) ?>...</div>
                                </td>
                                <td class="text-right">
                                    <?php if ($thread['unread']): ?>
                                    <span class="badge bg-danger"><?= $thread['unread'] ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include APP_PATH . '/includes/footer.php'; ?>