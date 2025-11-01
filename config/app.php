<?php
/**
 * Application Configuration
 *
 * Uses environment variables from .env file
 */

require_once __DIR__ . '/env.php';

// Application Settings
define('APP_NAME', env('APP_NAME', 'AuraVibe'));
define('APP_ENV', env('APP_ENV', 'local'));
define('APP_DEBUG', env('APP_DEBUG', false));
define('APP_URL', env('APP_URL', 'http://localhost'));

// Paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOAD_PATH', env('UPLOAD_PATH', 'uploads/'));
define('ASSETS_PATH', 'assets/');

// Session Configuration
define('SESSION_LIFETIME', env('SESSION_LIFETIME', 120));
define('SESSION_SECURE', env('SESSION_SECURE', false));

// Security
define('SECURE_COOKIES', env('SECURE_COOKIES', false));
define('HTTPS_ONLY', env('HTTPS_ONLY', false));

// File Upload
define('MAX_UPLOAD_SIZE', env('MAX_UPLOAD_SIZE', 5242880)); // 5MB default

// Error Reporting
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Timezone
date_default_timezone_set('Africa/Cairo');
