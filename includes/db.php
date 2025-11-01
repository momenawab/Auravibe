<?php
// Database configuration for Hostinger
// IMPORTANT: Update the password below with your actual Hostinger database password
define('DB_HOST', 'localhost');
define('DB_USER', 'u446437128_auravibe');  // Hostinger database username
define('DB_PASS', 'rI$n5W9:!4');  // Database password
define('DB_NAME', 'u446437128_auravibe');  // Hostinger database name

// Initialize connection variable
$conn = null;

// Try to connect to database (graceful failure)
try {
    // Try to connect with error reporting for debugging
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn) {
        mysqli_set_charset($conn, "utf8mb4");
        define('DB_CONNECTED', true);
    } else {
        // Log the connection error
        error_log("Database connection failed: " . mysqli_connect_error());
        define('DB_CONNECTED', false);
        $conn = null;
    }
} catch (Exception $e) {
    // Database not available, continue without it
    error_log("Database connection exception: " . $e->getMessage());
    define('DB_CONNECTED', false);
    $conn = null;
}

// Helper function to check if we can query
// NOTE: This function is defined in config/database.php (loaded via bootstrap.php)
// Keeping this for backward compatibility with old includes
if (!function_exists('can_query')) {
    function can_query() {
        global $conn;
        return defined('DB_CONNECTED') && DB_CONNECTED && $conn !== null;
    }
}
?>
