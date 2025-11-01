<?php
/**
 * Paymob Payment Gateway Configuration
 *
 * Add your Paymob credentials here
 */

// Paymob API Configuration
define('PAYMOB_API_KEY', 'YOUR_API_KEY_HERE');

// Integration IDs for different payment methods
define('PAYMOB_INTEGRATION_ONLINE_CARD', '5362354');      // MIGS-online
define('PAYMOB_INTEGRATION_TAP_ON_PHONE', '5362355');     // MIGS-tap_on_phone
define('PAYMOB_INTEGRATION_MOBILE_WALLET', '5362356');    // UIG-in_store

// Paymob API URLs
define('PAYMOB_API_URL', 'https://accept.paymob.com/api');
define('PAYMOB_AUTH_URL', PAYMOB_API_URL . '/auth/tokens');
define('PAYMOB_ORDER_URL', PAYMOB_API_URL . '/ecommerce/orders');
define('PAYMOB_PAYMENT_KEY_URL', PAYMOB_API_URL . '/acceptance/payment_keys');
define('PAYMOB_IFRAME_URL', 'https://accept.paymob.com/api/acceptance/iframes/');

// Your iFrame ID (get this from Paymob dashboard)
define('PAYMOB_IFRAME_ID', 'YOUR_IFRAME_ID_HERE');

// HMAC Secret for validating callbacks (get this from Paymob dashboard)
define('PAYMOB_HMAC_SECRET', 'YOUR_HMAC_SECRET_HERE');

// Currency
define('PAYMOB_CURRENCY', 'EGP');
