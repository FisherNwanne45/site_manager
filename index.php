<?php
require __DIR__ . '/config/bootstrap.php';

// Initialize controllers
$authController = new AuthController($GLOBALS['pdo']);
$dashboardController = new DashboardController($GLOBALS['pdo']);
$websiteController = new WebsiteController($GLOBALS['pdo']);
$hostingController = new HostingController($GLOBALS['pdo']);
$emailController = new EmailController($GLOBALS['pdo']);
$settingsController = new SettingsController($GLOBALS['pdo']);

// Get action from request
$action = $_GET['action'] ?? 'login';
$do = $_GET['do'] ?? '';

// Route the request
switch ($action) {
    case 'login':
        $authController->login();
        break;
    case 'logout':
        $authController->logout();
        break;
    case 'dashboard':
        $dashboardController->index();
        break;
    case 'websites':
        switch ($do) {
            case 'create':
                $websiteController->create();
                break;
            case 'edit':
                $id = $_GET['id'] ?? 0;
                $websiteController->edit($id);
                break;
            case 'view':
                $id = $_GET['id'] ?? 0;
                $websiteController->view($id);
                break;
            case 'delete':
                $id = $_GET['id'] ?? 0;
                $websiteController->delete($id);
                break;
            case 'import':
                $websiteController->import();
                break;
            case 'export':
                $websiteController->export();
                break;
            case 'renew':
                $id = $_GET['id'] ?? 0;
                $websiteController->renew($id);
                break;
            default:
                $websiteController->index();
        }
        break;
    case 'hosting':
        switch ($do) {
            case 'create':
                $hostingController->create();
                break;
            case 'view':
                $id = $_GET['id'] ?? 0;
                $hostingController->view($id);
                break;
            case 'edit':
                $id = $_GET['id'] ?? 0;
                $hostingController->edit($id);
                break;
            case 'delete':
                $id = $_GET['id'] ?? 0;
                $hostingController->delete($id);
                break;
            case 'services':
                $id = $_GET['id'] ?? 0;
                $hostingController->services($id);
                break;
            default:
                $hostingController->index();
        }
        break;
    case 'email':
        $id = $_GET['id'] ?? 0;
        switch ($do) {
            case 'expiry':
                $emailController->sendExpiryNotification($id);
                break;
            case 'status':
                $emailController->sendStatusNotification($id);
                break;
            case 'logs':
                $emailController->showEmailLogs();
                break;
            default:
                header('Location: index.php?action=dashboard');
        }
        break;
    case 'settings':
        switch ($do) {
            case 'smtp':
                $settingsController->smtp();
                break;
            case 'test_smtp':
                $settingsController->testSmtp();
                break;
            case 'password':
                $authController->changePassword();
                break;
            default:
                header('Location: index.php?action=settings&do=smtp');
        }
        break;
    case 'getHostingEmail':
        $id = $_GET['id'] ?? 0;
        $websiteController->getHostingEmail($id);
        break;
    default:
        header('Location: index.php?action=login');
}
