<?php
require_once __DIR__ . '/config-paths.php';

// Session already started in bootstrap.php
// Include Paymob class
require_once ROOT . '/app/classes/Paymob.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php');
    exit;
}

// Get form data
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$firstName = $_POST['first_name'] ?? '';
$lastName = $_POST['last_name'] ?? '';
$address = $_POST['address'] ?? '';
$address2 = $_POST['address2'] ?? '';
$city = $_POST['city'] ?? '';
$governorate = $_POST['governorate'] ?? '';
$zip = $_POST['zip'] ?? '';
$shippingCost = floatval($_POST['shipping_cost'] ?? 0);
$paymentMethod = $_POST['payment_method'] ?? 'cash_on_delivery';
$orderNotes = $_POST['order_notes'] ?? '';

// Validate required fields
if (empty($email) || empty($phone) || empty($firstName) || empty($lastName) || empty($address) || empty($city) || empty($governorate)) {
    $_SESSION['error'] = 'Please fill in all required fields including governorate';
    header('Location: checkout.php');
    exit;
}

// Get cart items
$demo_cart = [
    ['id' => 1, 'name' => 'Royal Gold Chronograph', 'brand' => 'PRESTIGE', 'price' => 2499.00, 'quantity' => 1],
    ['id' => 3, 'name' => 'Platinum Diver', 'brand' => 'OCEANIC', 'price' => 3799.00, 'quantity' => 1],
];

$cart_items = isset($_SESSION['cart']) && count($_SESSION['cart']) > 0 ? $_SESSION['cart'] : $demo_cart;

// Calculate totals
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

// Use shipping cost from form (calculated based on governorate)
$shipping = $shippingCost;

// 14% VAT in Egypt
$tax = $subtotal * 0.14;
$total = $subtotal + $shipping + $tax;

// Generate unique order ID
$orderId = 'ORD-' . time() . '-' . rand(1000, 9999);

// Store order in database (using mysqli)
if (can_query() && $conn !== null) {
    $stmt = mysqli_prepare($conn, "
        INSERT INTO orders (
            order_id, user_id, email, phone, first_name, last_name,
            address, address2, city, governorate, zip,
            payment_method, subtotal, shipping, tax, total,
            order_notes, status, created_at
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW()
        )
    ");

    $userId = $_SESSION['user_id'] ?? null;

    mysqli_stmt_bind_param($stmt, "sissssssssssdddds",
        $orderId, $userId, $email, $phone, $firstName, $lastName,
        $address, $address2, $city, $governorate, $zip,
        $paymentMethod, $subtotal, $shipping, $tax, $total,
        $orderNotes
    );

    if (mysqli_stmt_execute($stmt)) {
        $dbOrderId = mysqli_insert_id($conn);

        // Store order items
        $stmt = mysqli_prepare($conn, "
            INSERT INTO order_items (order_id, product_id, product_name, brand, price, quantity, subtotal)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        foreach ($cart_items as $item) {
            $brandName = $item['brand'] ?? '';
            $itemSubtotal = $item['price'] * $item['quantity'];
            mysqli_stmt_bind_param($stmt, "iissdid",
                $dbOrderId,
                $item['id'],
                $item['name'],
                $brandName,
                $item['price'],
                $item['quantity'],
                $itemSubtotal
            );
            mysqli_stmt_execute($stmt);
        }
    } else {
        error_log("Database error: " . mysqli_error($conn));
        $_SESSION['error'] = 'An error occurred while processing your order';
        header('Location: checkout.php');
        exit;
    }
} else {
    // If database is not available, log error but continue with payment
    error_log("Database not available, order not saved to database: " . $orderId);
}

// Handle payment based on selected method
if ($paymentMethod === 'cash_on_delivery') {
    // For cash on delivery, just redirect to success page
    $_SESSION['success'] = 'Order placed successfully! Order ID: ' . $orderId;
    header('Location: order-success.php?order_id=' . $orderId);
    exit;

} else {
    // Process Paymob payment using NEW Unified Checkout API
    require_once ROOT . '/app/classes/PaymobUnified.php';
    $paymob = new PaymobUnified();

    // Determine payment method using YOUR Integration IDs from .env
    // Now using TEST Integration IDs (5376291 for Card, 5374707 for Wallet)
    
    // Debug: Log the constants
    error_log("DEBUG - PAYMOB_INTEGRATION_ONLINE_CARD constant value: " . var_export(PAYMOB_INTEGRATION_ONLINE_CARD, true));
    error_log("DEBUG - PAYMOB_SECRET_KEY: " . substr(PAYMOB_SECRET_KEY, 0, 20) . "...");
    
    $paymentMethods = [PAYMOB_INTEGRATION_ONLINE_CARD]; // Use constant from .env (5376291)
    
    if ($paymentMethod === 'mobile_wallet') {
        $paymentMethods = [PAYMOB_INTEGRATION_MOBILE_WALLET]; // Mobile wallet (5374707)
    } elseif ($paymentMethod === 'card_payment' || $paymentMethod === 'online_payment') {
        $paymentMethods = [PAYMOB_INTEGRATION_ONLINE_CARD]; // Card payment (5376291)
    }
    
    error_log("Paymob - Using payment methods from .env: " . json_encode($paymentMethods));

    // Format items for Paymob Unified API
    $items = [];
    foreach ($cart_items as $item) {
        $items[] = [
            'name' => $item['name'],
            'amount_cents' => (int)($item['price'] * 100), // Convert to cents
            'description' => ($item['brand'] ?? '') . ' - ' . $item['name'],
            'quantity' => $item['quantity']
        ];
    }
    
    // Add shipping as an item (if > 0)
    if ($shipping > 0) {
        $items[] = [
            'name' => 'Shipping Fee',
            'amount_cents' => (int)($shipping * 100),
            'description' => 'Delivery to ' . $governorate,
            'quantity' => 1
        ];
    }
    
    // Add tax as an item (if > 0)
    if ($tax > 0) {
        $items[] = [
            'name' => 'VAT (14%)',
            'amount_cents' => (int)($tax * 100),
            'description' => 'Egyptian VAT 14%',
            'quantity' => 1
        ];
    }

    // Prepare order data for Paymob Unified
    $orderData = [
        'amount_cents' => (int)($total * 100), // Convert to cents
        'merchant_order_id' => $orderId,
        'items' => $items
    ];

    // Prepare billing data
    $billingData = [
        'email' => $email,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'phone' => $phone,
        'phone_number' => $phone, // Phone number (international or domestic format accepted)
        'address' => $address,
        'apartment' => $address2 ?? 'NA',
        'street' => $address,
        'building' => $address2 ?? 'NA',
        'floor' => 'NA',
        'city' => $city,
        'state' => $governorate,
        'governorate' => $governorate,
        'zip' => $zip ?? '',
        'country' => 'EG' // ISO country code
    ];

    // Create payment intention and get checkout URL
    $result = $paymob->createIntention($orderData, $billingData, $paymentMethods);

    if ($result && $result['success']) {
        // Store order ID in session for callback verification
        $_SESSION['pending_order_id'] = $orderId;
        $_SESSION['paymob_client_secret'] = $result['client_secret'];

        // Redirect to Paymob Unified Checkout page
        header('Location: ' . $result['payment_url']);
        exit;
    } else {
        // Payment initiation failed
        error_log("Paymob Unified payment initiation failed for order: " . $orderId);
        
        // Check if it's a configuration issue
        if (empty(PAYMOB_SECRET_KEY) || PAYMOB_SECRET_KEY === 'your_secret_key_here') {
            $_SESSION['error'] = 'Payment system not configured. Please contact administrator.';
            error_log("PAYMOB ERROR: Secret Key not configured in .env file");
        } else {
            $_SESSION['error'] = 'Payment initialization failed. Please try again or choose Cash on Delivery.';
        }
        
        header('Location: checkout.php');
        exit;
    }
}
