<?php
/**
 * Paymob Unified Checkout Integration Class
 * 
 * Uses the NEW Paymob Unified Checkout API (v1/intention)
 * This replaces the old iFrame method
 */

class PaymobUnified {
    
    private $secretKey;
    private $publicKey;
    private $apiUrl = 'https://accept.paymob.com/v1/intention/';
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->secretKey = PAYMOB_SECRET_KEY;
        $this->publicKey = PAYMOB_PUBLIC_KEY;
    }
    
    /**
     * Create payment intention (single API call)
     * 
     * @param array $orderData Order details
     * @param array $billingData Customer billing information
     * @param array $paymentMethods Payment method Integration IDs (e.g., [5362354] for Card)
     * @return array|false Response with client_secret and payment_url
     */
    public function createIntention($orderData, $billingData, $paymentMethods = [1]) {
        
        // Prepare the request data according to official Paymob documentation
        $data = [
            'amount' => (int)$orderData['amount_cents'], // Amount in cents as INTEGER (required)
            'currency' => 'EGP', // Required
            'payment_methods' => $paymentMethods, // Integration IDs (required) - e.g., [5362354] for Card
            'items' => $this->formatItems($orderData['items']), // Optional but recommended
            'billing_data' => $this->formatBillingData($billingData), // Required
            'customer' => [ // Optional
                'first_name' => $billingData['first_name'],
                'last_name' => $billingData['last_name'],
                'email' => $billingData['email']
            ]
        ];
        
        // Add optional fields if provided
        if (isset($orderData['merchant_order_id'])) {
            $data['special_reference'] = $orderData['merchant_order_id']; // Optional - used for tracking
        }
        
        // Add extras for custom data (optional)
        if (isset($orderData['extras'])) {
            $data['extras'] = $orderData['extras'];
        } else if (isset($orderData['merchant_order_id'])) {
            $data['extras'] = [
                'order_id' => $orderData['merchant_order_id']
            ];
        }
        
        // Add callback URLs if provided (optional but recommended)
        if (isset($orderData['notification_url'])) {
            $data['notification_url'] = $orderData['notification_url'];
        }
        
        if (isset($orderData['redirection_url'])) {
            $data['redirection_url'] = $orderData['redirection_url'];
        }
        
        // Log complete request for Paymob support
        error_log("=== PAYMOB INTENTION REQUEST - START ===");
        error_log("Endpoint: " . $this->apiUrl);
        error_log("Method: POST");
        error_log("Secret Key (first 30 chars): " . substr($this->secretKey, 0, 30) . "...");
        error_log("Public Key (first 30 chars): " . substr($this->publicKey, 0, 30) . "...");
        error_log("Request Body (JSON): " . json_encode($data, JSON_PRETTY_PRINT));
        error_log("Request Body (Raw): " . json_encode($data));
        error_log("=== PAYMOB INTENTION REQUEST - END ===");
        
        // Make API request
        $response = $this->makeRequest($this->apiUrl, $data);
        
        if ($response && isset($response['client_secret'])) {
            error_log("Paymob Unified - Intention Created Successfully");
            
            // Generate checkout URL according to documentation
            $checkoutUrl = "https://accept.paymob.com/unifiedcheckout/?publicKey=" . 
                          urlencode($this->publicKey) . 
                          "&clientSecret=" . urlencode($response['client_secret']);
            
            return [
                'success' => true,
                'client_secret' => $response['client_secret'],
                'payment_url' => $checkoutUrl,
                'intention_id' => $response['id'] ?? null,
                'intention_order_id' => $response['intention_order_id'] ?? null
            ];
        }
        
        error_log("Paymob Unified - Intention Failed: " . json_encode($response));
        return false;
    }
    
    /**
     * Format items for Paymob
     */
    private function formatItems($items) {
        $formatted = [];
        foreach ($items as $item) {
            $formatted[] = [
                'name' => $item['name'],
                'amount' => (int)$item['amount_cents'], // Amount in cents as INTEGER
                'description' => $item['description'] ?? $item['name'],
                'quantity' => $item['quantity'] ?? 1
            ];
        }
        return $formatted;
    }
    
    /**
     * Format billing data for Paymob
     * According to official documentation, all fields should be provided
     */
    private function formatBillingData($data) {
        return [
            'apartment' => !empty($data['apartment']) ? $data['apartment'] : 'NA',
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'street' => !empty($data['street']) ? $data['street'] : $data['address'],
            'building' => !empty($data['building']) ? $data['building'] : 'NA',
            'phone_number' => $data['phone_number'] ?? $data['phone'],
            'city' => !empty($data['city']) ? $data['city'] : 'NA',
            'country' => !empty($data['country']) ? $data['country'] : 'EG',
            'email' => $data['email'],
            'floor' => !empty($data['floor']) ? $data['floor'] : 'NA',
            'state' => $data['state'] ?? $data['governorate'] ?? $data['city'] ?? 'NA'
        ];
    }
    
    /**
     * Make HTTP request to Paymob API
     */
    private function makeRequest($url, $data) {
        $ch = curl_init($url);
        
        $headers = [
            'Authorization: Token ' . $this->secretKey,
            'Content-Type: application/json'
        ];
        
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            error_log("Paymob Unified - cURL Error: " . curl_error($ch));
            curl_close($ch);
            return false;
        }
        
        curl_close($ch);
        
        $decoded = json_decode($response, true);
        
        // Log complete response for debugging
        error_log("=== PAYMOB INTENTION RESPONSE - START ===");
        error_log("HTTP Code: " . $httpCode);
        error_log("Response Body (Raw): " . $response);
        error_log("Response Body (JSON): " . json_encode($decoded, JSON_PRETTY_PRINT));
        error_log("=== PAYMOB INTENTION RESPONSE - END ===");
        
        // Documentation shows 201 as success code for intention creation
        if ($httpCode !== 201 && $httpCode !== 200) {
            error_log("Paymob Unified - Error HTTP $httpCode: " . $response);
        }
        
        return $decoded;
    }
    
    /**
     * Verify HMAC signature from callback
     */
    public function verifyCallback($data) {
        if (!isset($data['hmac'])) {
            return false;
        }
        
        $receivedHmac = $data['hmac'];
        
        // Concatenate values according to Paymob documentation
        $string = $data['amount_cents'] .
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
        
        $calculatedHmac = hash_hmac('sha512', $string, PAYMOB_HMAC_SECRET);
        
        return hash_equals($calculatedHmac, $receivedHmac);
    }
}
?>
