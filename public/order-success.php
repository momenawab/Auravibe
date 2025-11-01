<?php require_once __DIR__ . '/config-paths.php';
// Session already started in bootstrap.php
// Database connection already available via bootstrap.php
$page_title = 'Order Success';
include_view('header.php');
include_view('navbar.php');

$orderId = $_GET['order_id'] ?? null;
$order = null;
$orderItems = [];

if ($orderId && can_query() && $conn !== null) {
    // Get order details
    $stmt = mysqli_prepare($conn, "SELECT * FROM orders WHERE order_id = ?");
    mysqli_stmt_bind_param($stmt, "s", $orderId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $order = mysqli_fetch_assoc($result);

    // Get order items
    if ($order) {
        $stmt = mysqli_prepare($conn, "SELECT * FROM order_items WHERE order_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $order['id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($item = mysqli_fetch_assoc($result)) {
            $orderItems[] = $item;
        }
    }
}
?>

<!-- Order Success Section -->
<section class="order-success-section">
    <div class="container">
        <?php if ($order): ?>
            <div class="success-message">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1>Order Placed Successfully!</h1>
                <p>Thank you for your purchase. Your order has been received and is being processed.</p>
            </div>

            <div class="order-details">
                <div class="order-header">
                    <h2>Order Details</h2>
                    <div class="order-id">Order #<?php echo htmlspecialchars($order['order_id']); ?></div>
                </div>

                <div class="order-info-grid">
                    <div class="info-card">
                        <h3>Order Information</h3>
                        <div class="info-row">
                            <span class="label">Order Date:</span>
                            <span class="value"><?php echo date('F j, Y', strtotime($order['created_at'])); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Payment Method:</span>
                            <span class="value"><?php echo ucwords(str_replace('_', ' ', $order['payment_method'])); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Status:</span>
                            <span class="value status-<?php echo $order['status']; ?>">
                                <?php echo ucwords(str_replace('_', ' ', $order['status'])); ?>
                            </span>
                        </div>
                    </div>

                    <div class="info-card">
                        <h3>Shipping Address</h3>
                        <address>
                            <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?><br>
                            <?php echo htmlspecialchars($order['address']); ?><br>
                            <?php if ($order['address2']): ?>
                                <?php echo htmlspecialchars($order['address2']); ?><br>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($order['city'] . ', ' . ($order['governorate'] ?? $order['state']) . ' ' . $order['zip']); ?><br>
                            Egypt
                        </address>
                    </div>

                    <div class="info-card">
                        <h3>Contact Information</h3>
                        <div class="info-row">
                            <span class="label">Email:</span>
                            <span class="value"><?php echo htmlspecialchars($order['email']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Phone:</span>
                            <span class="value"><?php echo htmlspecialchars($order['phone']); ?></span>
                        </div>
                    </div>
                </div>

                <?php if (isset($orderItems) && count($orderItems) > 0): ?>
                    <div class="order-items">
                        <h3>Order Items</h3>
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Brand</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orderItems as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                        <td><?php echo htmlspecialchars($item['brand']); ?></td>
                                        <td><?php echo number_format($item['price'], 2); ?> EGP</td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?> EGP</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <div class="order-summary">
                    <h3>Order Summary</h3>
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span><?php echo number_format($order['subtotal'], 2); ?> EGP</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span><?php echo $order['shipping'] > 0 ? number_format($order['shipping'], 2) . ' EGP' : '<span style="color: #28a745; font-weight: 600;">FREE</span>'; ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Tax (14% VAT):</span>
                        <span><?php echo number_format($order['tax'], 2); ?> EGP</span>
                    </div>
                    <div class="summary-divider"></div>
                    <div class="summary-row summary-total">
                        <span>Total:</span>
                        <span><?php echo number_format($order['total'], 2); ?> EGP</span>
                    </div>
                </div>

                <div class="order-actions">
                    <a href="index.php" class="btn btn-primary">
                        <i class="fas fa-home"></i> Continue Shopping
                    </a>
                    <a href="account.php" class="btn btn-outline">
                        <i class="fas fa-user"></i> View Orders
                    </a>
                </div>
            </div>

        <?php else: ?>
            <div class="error-message">
                <div class="error-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h1>Order Not Found</h1>
                <p>We couldn't find the order you're looking for.</p>
                <a href="index.php" class="btn btn-primary">Return to Home</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.order-success-section {
    padding: 60px 0;
    min-height: 70vh;
}

.success-message,
.error-message {
    text-align: center;
    margin-bottom: 40px;
}

.success-icon {
    font-size: 80px;
    color: #28a745;
    margin-bottom: 20px;
}

.error-icon {
    font-size: 80px;
    color: #dc3545;
    margin-bottom: 20px;
}

.success-message h1,
.error-message h1 {
    font-size: 32px;
    margin-bottom: 10px;
}

.order-details {
    max-width: 900px;
    margin: 0 auto;
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #e9ecef;
}

.order-id {
    font-size: 18px;
    font-weight: 600;
    color: #D4AF37;
}

.order-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.info-card {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.info-card h3 {
    font-size: 18px;
    margin-bottom: 15px;
    color: #1a1a1a;
}

.info-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.info-row .label {
    color: #6c757d;
}

.info-row .value {
    font-weight: 600;
}

address {
    font-style: normal;
    line-height: 1.6;
}

.order-items {
    margin: 30px 0;
}

.items-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.items-table th,
.items-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e9ecef;
}

.items-table th {
    background: #f8f9fa;
    font-weight: 600;
}

.order-summary {
    max-width: 400px;
    margin: 30px 0 30px auto;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.summary-divider {
    height: 1px;
    background: #dee2e6;
    margin: 15px 0;
}

.summary-total {
    font-size: 20px;
    font-weight: 700;
    color: #1a1a1a;
}

.order-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 30px;
}

.status-pending { color: #ffc107; }
.status-confirmed { color: #28a745; }
.status-payment_failed { color: #dc3545; }
</style>

<?php include_view('footer.php'); ?>
