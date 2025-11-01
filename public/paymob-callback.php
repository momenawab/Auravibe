<?php require_once __DIR__ . '/config-paths.php';

/**
 * Paymob Payment Callback Handler
 *
 * This file handles callbacks from Paymob after payment processing
 * You need to register this URL in your Paymob dashboard as the callback URL
 * URL format: https://yourdomain.com/paymob-callback.php
 */

session_start();

// Include Paymob class
require_once ROOT . '/app/classes/Paymob.php';

// Log the callback for debugging
error_log("Paymob Callback Received: " . json_encode($_GET));

// Get the transaction data from callback
// Paymob sends data as GET parameters
$transactionId = $_GET['id'] ?? null;
$success = $_GET['success'] ?? 'false';
$orderId = $_GET['order'] ?? null;
$amountCents = $_GET['amount_cents'] ?? 0;
$currency = $_GET['currency'] ?? '';
$hmac = $_GET['hmac'] ?? '';

// Verify HMAC to ensure callback is from Paymob
$paymob = new Paymob();

if (!$paymob->verifyCallback($_GET)) {
    error_log("Paymob Callback: HMAC verification failed");
    http_response_code(403);
    die('Invalid callback');
}

// Get merchant order ID from the order object
// The order parameter is a JSON object
$merchantOrderId = null;
if (isset($_GET['obj'])) {
    $obj = json_decode($_GET['obj'], true);
    if (isset($obj['order']['merchant_order_id'])) {
        $merchantOrderId = $obj['order']['merchant_order_id'];
    }
}

// Alternative way to get merchant order ID
if (!$merchantOrderId && $orderId && can_query() && $conn !== null) {
    // Query database to get merchant order ID
    $stmt = mysqli_prepare($conn, "SELECT order_id FROM orders WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $orderId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && $order = mysqli_fetch_assoc($result)) {
        $merchantOrderId = $order['order_id'];
    }
}

// Update order status in database
if ($merchantOrderId && can_query() && $conn !== null) {
    $paymentStatus = ($success === 'true') ? 'paid' : 'failed';
    $orderStatus = ($success === 'true') ? 'confirmed' : 'payment_failed';

    $stmt = mysqli_prepare($conn, "
        UPDATE orders
        SET
            status = ?,
            payment_status = ?,
            transaction_id = ?,
            payment_response = ?,
            updated_at = NOW()
        WHERE order_id = ?
    ");

    $paymentResponse = json_encode($_GET);

    mysqli_stmt_bind_param($stmt, "sssss",
        $orderStatus,
        $paymentStatus,
        $transactionId,
        $paymentResponse,
        $merchantOrderId
    );

    if (mysqli_stmt_execute($stmt)) {
        error_log("Order {$merchantOrderId} updated with status: {$orderStatus}");
    } else {
        error_log("Database error updating order: " . mysqli_error($conn));
    }
}

// Redirect user to appropriate page
if ($success === 'true') {
    $_SESSION['success'] = 'Payment successful! Your order has been confirmed.';
    header('Location: order-success.php?order_id=' . urlencode($merchantOrderId));
} else {
    $_SESSION['error'] = 'Payment failed. Please try again or choose a different payment method.';
    header('Location: checkout.php');
}
exit;
