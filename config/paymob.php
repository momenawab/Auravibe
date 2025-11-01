<?php
/**
 * Paymob Payment Gateway Configuration
 *
 * Uses environment variables from .env file
 */

require_once __DIR__ . '/env.php';

// Paymob API Configuration (Old API - for backward compatibility)
define('PAYMOB_API_KEY', env('PAYMOB_API_KEY', ''));

// NEW Unified Checkout API Keys
define('PAYMOB_SECRET_KEY', env('PAYMOB_SECRET_KEY', ''));
define('PAYMOB_PUBLIC_KEY', env('PAYMOB_PUBLIC_KEY', ''));

// Integration IDs for different payment methods (cast to integers)
define('PAYMOB_INTEGRATION_ONLINE_CARD', (int)env('PAYMOB_INTEGRATION_ONLINE_CARD', '5362354'));
define('PAYMOB_INTEGRATION_TAP_ON_PHONE', (int)env('PAYMOB_INTEGRATION_TAP_ON_PHONE', '5362355'));
define('PAYMOB_INTEGRATION_MOBILE_WALLET', (int)env('PAYMOB_INTEGRATION_MOBILE_WALLET', '5362356'));

// Paymob API URLs
define('PAYMOB_API_URL', env('PAYMOB_API_URL', 'https://accept.paymob.com/api'));
define('PAYMOB_AUTH_URL', PAYMOB_API_URL . '/auth/tokens');
define('PAYMOB_ORDER_URL', PAYMOB_API_URL . '/ecommerce/orders');
define('PAYMOB_PAYMENT_KEY_URL', PAYMOB_API_URL . '/acceptance/payment_keys');
define('PAYMOB_IFRAME_URL', 'https://accept.paymob.com/api/acceptance/iframes/');

// Your iFrame ID
define('PAYMOB_IFRAME_ID', env('PAYMOB_IFRAME_ID', ''));

// HMAC Secret for validating callbacks
define('PAYMOB_HMAC_SECRET', env('PAYMOB_HMAC_SECRET', ''));

// Currency
define('PAYMOB_CURRENCY', env('PAYMOB_CURRENCY', 'EGP'));
