<?php
/**
 * Paymob Payment Gateway Integration Class
 *
 * This class handles all interactions with the Paymob API
 */

require_once 'paymob-config.php';

class Paymob {

    private $apiKey;
    private $authToken;

    /**
     * Constructor
     */
    public function __construct() {
        $this->apiKey = PAYMOB_API_KEY;
    }

    /**
     * Authenticate with Paymob API and get auth token
     *
     * @return string|false Auth token or false on failure
     */
    public function authenticate() {
        $data = [
            'api_key' => $this->apiKey
        ];

        $response = $this->makeRequest(PAYMOB_AUTH_URL, $data);

        if ($response && isset($response['token'])) {
            $this->authToken = $response['token'];
            return $this->authToken;
        }

        return false;
    }

    /**
     * Register an order with Paymob
     *
     * @param array $orderData Order details
     * @return array|false Order response or false on failure
     */
    public function registerOrder($orderData) {
        if (!$this->authToken) {
            $this->authenticate();
        }

        $data = [
            'auth_token' => $this->authToken,
            'delivery_needed' => $orderData['delivery_needed'] ?? 'false',
            'amount_cents' => $orderData['amount_cents'], // Amount in cents
            'currency' => PAYMOB_CURRENCY,
            'merchant_order_id' => $orderData['merchant_order_id'] ?? time(),
            'items' => $orderData['items'] ?? []
        ];

        $response = $this->makeRequest(PAYMOB_ORDER_URL, $data);

        return $response;
    }

    /**
     * Get payment key for processing payment
     *
     * @param array $paymentData Payment details
     * @return string|false Payment token or false on failure
     */
    public function getPaymentKey($paymentData) {
        if (!$this->authToken) {
            $this->authenticate();
        }

        $data = [
            'auth_token' => $this->authToken,
            'amount_cents' => $paymentData['amount_cents'],
            'expiration' => 3600, // Token expires in 1 hour
            'order_id' => $paymentData['order_id'],
            'billing_data' => $paymentData['billing_data'],
            'currency' => PAYMOB_CURRENCY,
            'integration_id' => $paymentData['integration_id']
        ];

        $response = $this->makeRequest(PAYMOB_PAYMENT_KEY_URL, $data);

        if ($response && isset($response['token'])) {
            return $response['token'];
        }

        return false;
    }

    /**
     * Create complete payment process and get iframe URL
     *
     * @param array $orderData Order details
     * @param array $billingData Customer billing information
     * @param int $integrationId Integration ID for payment method
     * @return string|false Iframe URL or false on failure
     */
    public function createPayment($orderData, $billingData, $integrationId) {
        // Step 1: Authenticate
        if (!$this->authenticate()) {
            return false;
        }

        // Step 2: Register Order
        $order = $this->registerOrder($orderData);
        if (!$order || !isset($order['id'])) {
            return false;
        }

        // Step 3: Get Payment Key
        $paymentData = [
            'amount_cents' => $orderData['amount_cents'],
            'order_id' => $order['id'],
            'billing_data' => $billingData,
            'integration_id' => $integrationId
        ];

        $paymentToken = $this->getPaymentKey($paymentData);
        if (!$paymentToken) {
            return false;
        }

        // Step 4: Generate iframe URL
        $iframeUrl = PAYMOB_IFRAME_URL . PAYMOB_IFRAME_ID . '?payment_token=' . $paymentToken;

        return $iframeUrl;
    }

    /**
     * Verify callback HMAC to ensure it's from Paymob
     *
     * @param array $data Callback data from Paymob
     * @return bool True if valid, false otherwise
     */
    public function verifyCallback($data) {
        if (!isset($data['hmac'])) {
            return false;
        }

        $hmac = $data['hmac'];

        // Concatenate the values in the specific order required by Paymob
        $concatenatedString =
            $data['amount_cents'] .
            $data['created_at'] .
            $data['currency'] .
            $data['error_occured'] .
            $data['has_parent_transaction'] .
            $data['id'] .
            $data['integration_id'] .
            $data['is_3d_secure'] .
            $data['is_auth'] .
            $data['is_capture'] .
            $data['is_refunded'] .
            $data['is_standalone_payment'] .
            $data['is_voided'] .
            $data['order'] .
            $data['owner'] .
            $data['pending'] .
            $data['source_data_pan'] .
            $data['source_data_sub_type'] .
            $data['source_data_type'] .
            $data['success'];

        // Generate HMAC
        $generatedHmac = hash_hmac('sha512', $concatenatedString, PAYMOB_HMAC_SECRET);

        return hash_equals($generatedHmac, $hmac);
    }

    /**
     * Make HTTP request to Paymob API
     *
     * @param string $url API endpoint URL
     * @param array $data Data to send
     * @return array|false Response data or false on failure
     */
    private function makeRequest($url, $data) {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($httpCode == 200 || $httpCode == 201) {
            return json_decode($response, true);
        }

        // Log error for debugging
        error_log("Paymob API Error: " . $response);

        return false;
    }

    /**
     * Format billing data for Paymob
     *
     * @param array $customerData Customer information
     * @return array Formatted billing data
     */
    public static function formatBillingData($customerData) {
        return [
            'apartment' => $customerData['apartment'] ?? 'NA',
            'email' => $customerData['email'],
            'floor' => $customerData['floor'] ?? 'NA',
            'first_name' => $customerData['first_name'],
            'street' => $customerData['street'] ?? $customerData['address'] ?? 'NA',
            'building' => $customerData['building'] ?? 'NA',
            'phone_number' => $customerData['phone'],
            'shipping_method' => $customerData['shipping_method'] ?? 'NA',
            'postal_code' => $customerData['postal_code'] ?? $customerData['zip'] ?? 'NA',
            'city' => $customerData['city'],
            'country' => $customerData['country'] ?? 'EG',
            'last_name' => $customerData['last_name'],
            'state' => $customerData['state'] ?? 'NA'
        ];
    }

    /**
     * Format cart items for Paymob order
     *
     * @param array $cartItems Cart items
     * @return array Formatted items
     */
    public static function formatOrderItems($cartItems) {
        $items = [];

        foreach ($cartItems as $item) {
            $items[] = [
                'name' => $item['name'],
                'amount_cents' => (int)($item['price'] * 100), // Convert to cents
                'description' => $item['brand'] ?? '',
                'quantity' => $item['quantity']
            ];
        }

        return $items;
    }

    /**
     * Convert amount to cents (Paymob requires amounts in cents)
     *
     * @param float $amount Amount in currency units
     * @return int Amount in cents
     */
    public static function toCents($amount) {
        return (int)($amount * 100);
    }
}
