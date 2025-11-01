<?php
// Database configuration for localhost
define('DB_HOST', 'localhost');
define('DB_USER', 'auravibe_user');
define('DB_PASS', 'aura2024!');
define('DB_NAME', 'auravibe_db');

// Initialize connection variable
$conn = null;

// Try to connect to database (graceful failure)
try {
    // Suppress errors and try to connect
    $conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASS);

    if ($conn) {
        // Try to select database
        @mysqli_select_db($conn, DB_NAME);
        mysqli_set_charset($conn, "utf8mb4");
        define('DB_CONNECTED', true);
    } else {
        define('DB_CONNECTED', false);
    }
} catch (Exception $e) {
    // Database not available, continue without it
    define('DB_CONNECTED', false);
    $conn = null;
}

// Helper function to check if we can query
function can_query() {
    global $conn;
    return defined('DB_CONNECTED') && DB_CONNECTED && $conn !== null;
}
?>
