<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= APP_NAME ?> - <?= isset($title) ? $title : 'Dashboard' ?></title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
        <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/styles.css">
        <link rel="icon" href="assets/images/logo.png" type="image/png">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
        <style>
        /* Session Timeout Modal */
        #sessionTimeoutModal {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #fff3cd;
            border: 1px solid #ffeeba;
            padding: 15px;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            max-width: 300px;
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        </style>

    </head>

    <body class="hold-transition sidebar-mini">
        <div class="wrapper">

            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <!-- Toggle for sidebar -->
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                                class="fas fa-bars"></i></a>
                    </li>
                </ul>

                <!-- Right navbar links -->

                <ul class="navbar-nav ml-auto">

                    <!-- Settings Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#">
                            <i class="fas fa-cogs"></i> <!-- Settings Cog Icon -->
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <a href="index.php?action=settings&do=smtp" class="dropdown-item">
                                <i class="fas fa-cogs mr-2"></i> Impostazioni SMTP
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="index.php?action=settings&do=password" class="dropdown-item">
                                <i class="fas fa-key mr-2"></i> Cambiare la password
                            </a>
                            <a href="index.php?action=logout" class="dropdown-item">
                                <i class="fas fa-sign-out-alt mr-2"></i> Esci
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <!-- /.navbar -->