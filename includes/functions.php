<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get base URL
function base_url($path = '') {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];

    // Get the script directory
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);

    // Remove /public/ from the path since .htaccess handles routing
    // This ensures clean URLs: auravibe.site/assets/ instead of auravibe.site/public/assets/
    if (basename($scriptDir) === 'public') {
        // Remove /public from the path
        $scriptDir = dirname($scriptDir);
    }

    $base = $protocol . "://" . $host . $scriptDir;

    // Return the URL with the path
    $path = $path ?? '';
    return rtrim($base, '/') . '/' . ltrim($path, '/');
}

// Sanitize input
function sanitize($data) {
    global $conn;
    $clean_data = htmlspecialchars(strip_tags(trim($data)));

    // Only use mysqli_real_escape_string if database is connected
    if (can_query() && $conn !== null) {
        return mysqli_real_escape_string($conn, $clean_data);
    }

    return $clean_data;
}

// Format price
function format_price($price) {
    return '$' . number_format($price, 2);
}

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['customer_id']);
}

// Check if admin is logged in
function is_admin_logged_in() {
    return isset($_SESSION['admin_id']);
}

// Redirect function
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Get cart count
function get_cart_count() {
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        $count = 0;
        foreach ($_SESSION['cart'] as $item) {
            if (isset($item['quantity'])) {
                $count += $item['quantity'];
            }
        }
        return $count;
    }
    return 0;
}

// Success message
function set_message($message, $type = 'success') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
}

// Display message
function display_message() {
    if (isset($_SESSION['message'])) {
        $type = $_SESSION['message_type'] ?? 'success';
        echo '<div class="alert alert-' . $type . '">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
}
?>
