<?php
/**
 * Path Configuration for Public Files
 *
 * Include this at the top of every public page to set correct paths
 */

// Define the root path (one level up from public)
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__));
}

// Load bootstrap from root
require_once ROOT . '/bootstrap.php';

// Define paths for includes
if (!defined('INCLUDES_PATH')) {
    define('INCLUDES_PATH', ROOT . '/includes/');
}

// Load functions.php for helper functions (base_url, etc.)
if (!function_exists('base_url')) {
    require_once INCLUDES_PATH . 'functions.php';
}

// Load language system
require_once INCLUDES_PATH . 'language.php';

// Helper function to include files
function include_view($file) {
    $path = INCLUDES_PATH . $file;
    if (file_exists($path)) {
        include $path;
    } else {
        die("View file not found: " . $file);
    }
}
