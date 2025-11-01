<?php
/**
 * Database Configuration
 *
 * Uses environment variables from .env file
 */

require_once __DIR__ . '/env.php';

// Database configuration from .env
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_PORT', env('DB_PORT', '3306'));
define('DB_USER', env('DB_USERNAME', 'root'));
define('DB_PASS', env('DB_PASSWORD', ''));
define('DB_NAME', env('DB_DATABASE', 'auravibe'));

// Initialize connection variables
$conn = null;
$pdo = null;

// Try to connect with MySQLi (for legacy compatibility)
try {
    $conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

    if ($conn) {
        mysqli_set_charset($conn, "utf8mb4");
        define('DB_CONNECTED', true);
    } else {
        define('DB_CONNECTED', false);
    }
} catch (Exception $e) {
    define('DB_CONNECTED', false);
    $conn = null;
}

// PDO Connection (recommended for new code)
try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    define('PDO_CONNECTED', true);
} catch (PDOException $e) {
    define('PDO_CONNECTED', false);
    $pdo = null;

    // Log error if debug mode is enabled
    if (env('APP_DEBUG', false)) {
        error_log("Database connection failed: " . $e->getMessage());
    }
}

// Helper function to check if we can query with MySQLi
function can_query() {
    global $conn;
    return defined('DB_CONNECTED') && DB_CONNECTED && $conn !== null;
}

// Helper function to check if PDO is available
function pdo_available() {
    global $pdo;
    return defined('PDO_CONNECTED') && PDO_CONNECTED && $pdo !== null;
}
