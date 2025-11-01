<?php
/**
 * Language System for AuraVibe
 * Supports Arabic (ar) and English (en)
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set default language
if (!defined('DEFAULT_LANGUAGE')) {
    define('DEFAULT_LANGUAGE', 'ar'); // Arabic as default
}

// Get current language from session or default
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'en'])) {
    $_SESSION['language'] = $_GET['lang'];
}

if (!isset($_SESSION['language'])) {
    $_SESSION['language'] = DEFAULT_LANGUAGE;
}

$current_language = $_SESSION['language'];

// Load language file
$lang_file = __DIR__ . '/languages/' . $current_language . '.php';
if (file_exists($lang_file)) {
    require_once $lang_file;
} else {
    // Fallback to Arabic
    require_once __DIR__ . '/languages/ar.php';
}

/**
 * Get translation for a key
 */
function __($key) {
    global $translations;
    return $translations[$key] ?? $key;
}

/**
 * Get current language
 */
function get_current_language() {
    global $current_language;
    return $current_language;
}

/**
 * Check if current language is RTL
 */
function is_rtl() {
    return get_current_language() === 'ar';
}

/**
 * Get language direction
 */
function get_language_direction() {
    return is_rtl() ? 'rtl' : 'ltr';
}

/**
 * Get opposite language for switcher
 */
function get_opposite_language() {
    return get_current_language() === 'ar' ? 'en' : 'ar';
}

/**
 * Get language name
 */
function get_language_name($lang = null) {
    $lang = $lang ?? get_current_language();
    return $lang === 'ar' ? 'العربية' : 'English';
}
