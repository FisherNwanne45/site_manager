<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php?action=dashboard" class="brand-link">
        <!-- Display logo when expanded -->
        <img src="assets/images/logo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <!-- Display text when collapsed -->
        <span class="brand-text "><?= APP_NAME ?></span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">
                <li class="nav-item">
                    <a href="index.php?action=dashboard"
                        class="nav-link <?= $activePage == 'dashboard' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?action=websites"
                        class="nav-link <?= $activePage == 'websites' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-server"></i>
                        <p>Servizi</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?action=hosting" class="nav-link <?= $activePage == 'hosting' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Clienti</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?action=settings&do=smtp"
                        class="nav-link <?= $activePage == 'settings' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Impostazioni</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?action=settings&do=password"
                        class="nav-link <?= $activePage == 'password' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-key"></i>
                        <p>Cambiare la password</p>
                    </a>
                </li>
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