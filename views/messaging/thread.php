<?php include APP_PATH . '/includes/header.php'; ?>
<?php include APP_PATH . '/includes/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= htmlspecialchars($thread['subject'] ?? 'Message Thread') ?></h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card direct-chat direct-chat-primary">
                <div class="card-body" style="height: 500px; overflow-y: auto;">
                    <?php foreach ($messages as $message): ?>
                    <div class="direct-chat-msg <?= $message['sender_id'] == $_SESSION['user_id'] ? 'right' : '' ?>">
                        <div class="direct-chat-infos clearfix">
                            <span
                                class="direct-chat-name float-<?= $message['sender_id'] == $_SESSION['user_id'] ? 'right' : 'left' ?>">
                                <?= htmlspecialchars($message['username']) ?>
                            </span>
                            <span
                                class="direct-chat-timestamp float-<?= $message['sender_id'] == $_SESSION['user_id'] ? 'left' : 'right' ?>">
                                <?= date('M j, H:i', strtotime($message['created_at'])) ?>
                            </span>
                        </div>
                        <div class="direct-chat-text">
                            <?= nl2br(htmlspecialchars($message['content'])) ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="card-footer">
                    <form action="?action=messaging&do=reply" method="post">
                        <input type="hidden" name="thread_id" value="<?= $threadId ?>">
                        <div class="input-group">
                            <input type="text" name="content" placeholder="Type your message..." class="form-control">
                            <span class="input-group-append">
                                <button type="submit" class="btn btn-primary">Send</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include APP_PATH . '/includes/footer.php'; ?>