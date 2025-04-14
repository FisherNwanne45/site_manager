<?php
// Determine active states
$currentAction = $_GET['action'] ?? 'dashboard';
$currentDo = $_GET['do'] ?? '';
$isSettingsActive = ($currentAction === 'settings');
$userRole = $_SESSION['user_role'] ?? 'viewer'; // Make sure you set this during login
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php?action=dashboard" class="brand-link">
        <img src="assets/images/logo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><?= APP_NAME ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Dashboard - All Roles -->
                <li class="nav-item">
                    <a href="index.php?action=dashboard"
                        class="nav-link <?= ($currentAction === 'dashboard') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Websites - All Roles -->
                <li class="nav-item">
                    <a href="index.php?action=websites"
                        class="nav-link <?= ($currentAction === 'websites') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-globe"></i>
                        <p>Servizi</p>
                    </a>
                </li>

                <!-- Hosting - All Roles -->
                <li class="nav-item">
                    <a href="index.php?action=hosting"
                        class="nav-link <?= ($currentAction === 'hosting') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-server"></i>
                        <p>Clienti</p>
                    </a>
                </li>

                <!-- Settings (Super Admin Only) -->
                <?php if ($userRole === 'super_admin'): ?>
                    <li
                        class="nav-item has-treeview <?= ($isSettingsActive || $currentAction === 'users') ? 'menu-open' : '' ?>">
                        <a href="#"
                            class="nav-link <?= ($isSettingsActive || $currentAction === 'users') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Impostazioni
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview"
                            style="<?= ($isSettingsActive || $currentAction === 'users') ? 'display: block;' : '' ?>">
                            <li class="nav-item">
                                <a href="index.php?action=settings&do=smtp"
                                    class="nav-link <?= ($isSettingsActive && $currentDo === 'smtp') ? 'active' : '' ?>">
                                    <i class="nav-icon fas fa-envelope"></i>
                                    <p>SMTP</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="index.php?action=settings&do=advanced"
                                    class="nav-link <?= ($isSettingsActive && $currentDo === 'advanced') ? 'active' : '' ?>">
                                    <i class="nav-icon fas fa-sliders-h"></i>
                                    <p>Avanzate</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="index.php?action=users"
                                    class="nav-link <?= ($currentAction === 'users') ? 'active' : '' ?>">
                                    <i class="nav-icon fas fa-users-cog"></i>
                                    <p>Gestione Utenti</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- Password Change - All Logged In Users (now outside settings) -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a href="index.php?action=settings&do=password"
                            class="nav-link <?= ($isSettingsActive && $currentDo === 'password') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-key"></i>
                            <p>Cambia Password</p>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Logout -->
                <li class="nav-item">
                    <a href="index.php?action=logout" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Esci</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>


<style>
    /* Enhanced hover effects */
    .nav-sidebar .nav-item>.nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    /* Active item styling */
    .nav-sidebar .nav-item>.nav-link.active {
        background-color: rgba(255, 255, 255, 0.2);
        border-left: 3px solid #3c8dbc;
    }

    /* Submenu active item */
    .nav-treeview .nav-item>.nav-link.active {
        background-color: rgba(255, 255, 255, 0.15);
        font-weight: 600;
    }

    /* Keep submenu open when active */
    .nav-item.has-treeview.menu-open>.nav-treeview {
        display: block;
    }
</style>