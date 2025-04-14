<?php
// Define base path FIRST
define('BASE_PATH', realpath(dirname(__DIR__)));
define('APP_PATH', BASE_PATH);
// Define default language
define('DEFAULT_LANG', 'it');

// Initialize language
$lang = $_SESSION['lang'] ?? DEFAULT_LANG;
$translations = [];

// Load language file
$langFile = APP_PATH . "/lang/{$lang}.php";
if (file_exists($langFile)) {
    $translations = require $langFile;
}

// Helper function
function __(string $key, array $params = []): string
{
    global $translations;

    $keys = explode('.', $key);
    $value = $translations;

    foreach ($keys as $k) {
        if (!isset($value[$k])) {
            return $key; // Return key if translation not found
        }
        $value = $value[$k];
    }

    // Simple parameter replacement
    foreach ($params as $k => $v) {
        $value = str_replace("{{$k}}", $v, $value);
    }

    return $value;
}

// Load configuration files
require APP_PATH . '/config/auth.php';
require APP_PATH . '/config/database.php';
require APP_PATH . '/config/mailer.php';
require APP_PATH . '/config/constants.php';

// Start session with secure settings
session_start([
    'cookie_lifetime' => SESSION_TIMEOUT,
    'cookie_secure' => isset($_SERVER['HTTPS']), // Enable for HTTPS
    'cookie_httponly' => true,
    'use_strict_mode' => true
]);

// Session timeout and security management
if (isset($_SESSION['LAST_ACTIVITY'])) {
    // Session timeout based on last activity
    if (time() - $_SESSION['LAST_ACTIVITY'] > SESSION_TIMEOUT) {
        session_unset();
        session_destroy();
        header('Location: index.php?action=login&timeout=1');
        exit();
    }

    // Session ID regeneration logic (every half of timeout period)
    if (!isset($_SESSION['CREATED'])) {
        $_SESSION['CREATED'] = time();
    } elseif (time() - $_SESSION['LAST_ACTIVITY'] > (SESSION_TIMEOUT / 2)) {
        session_regenerate_id(true);
        $_SESSION['CREATED'] = time(); // Reset creation time
    }
}

// Always update last activity time
$_SESSION['LAST_ACTIVITY'] = time();

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set default timezone
date_default_timezone_set('UTC');

// Enhanced autoloader with namespace support
spl_autoload_register(function ($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    // Try controllers first
    $file = APP_PATH . "/controllers/$class.php";
    if (file_exists($file)) {
        require $file;
        return;
    }

    // Then try models
    $file = APP_PATH . "/models/$class.php";
    if (file_exists($file)) {
        require $file;
        return;
    }

    // Optional: Log missing class for debugging
    error_log("Autoload failed: Class $class not found");
});

// Database connection with improved error handling
try {
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_PERSISTENT => false
    ];

    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], $options);
    $GLOBALS['pdo'] = $pdo;
} catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("A database error occurred. Please try again later.");
}

// Optional: CSRF token generation if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
