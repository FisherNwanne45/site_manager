<?php include APP_PATH . '/includes/header.php'; ?>
<?php include APP_PATH . '/includes/sidebar.php'; ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Groups</h1>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-body">
                <?php if (empty($groups)): ?>
                <p>No groups found</p>
                <?php else: ?>
                <ul>
                    <?php foreach ($groups as $group): ?>
                    <li><?= htmlspecialchars($group['name']) ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<?php include APP_PATH . '/includes/footer.php'; ?>