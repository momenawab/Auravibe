<?php
session_start();
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get order ID
$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($order_id <= 0) {
    header('Location: orders.php');
    exit;
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $new_status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);

    if ($stmt->execute()) {
        $success_message = "Order status updated successfully!";
    } else {
        $error_message = "Failed to update order status.";
    }
    $stmt->close();
}

// Get order details from database (customer data is in orders table)
$stmt = $conn->prepare("SELECT o.*,
                        CONCAT(o.first_name, ' ', o.last_name) as customer_name,
                        o.email as customer_email,
                        o.phone as customer_phone
                        FROM orders o
                        WHERE o.id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    // Show 404 error
    http_response_code(404);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Order Not Found - Aura Vibe Admin</title>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="../assets/css/admin.css">
    </head>
    <body>
        <div class="error-container">
            <i class="fas fa-exclamation-triangle"></i>
            <h1>Order Not Found</h1>
            <p>The order you are looking for does not exist or has been removed.</p>
            <a href="orders.php" class="btn">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$order = $result->fetch_assoc();
$stmt->close();

// Get order items
$stmt = $conn->prepare("SELECT oi.*, p.name as product_name
                        FROM order_items oi
                        LEFT JOIN products p ON oi.product_id = p.id
                        WHERE oi.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

$order_items = [];
while ($row = $result->fetch_assoc()) {
    $order_items[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Aura Vibe Admin</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <h1>Order Details</h1>
                <div class="admin-user">
                    <span><?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></span>
                    <i class="fas fa-user-circle"></i>
                </div>
            </header>

            <!-- Content -->
            <div class="admin-content">
                <a href="orders.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Orders
                </a>

                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <!-- Order Information Grid -->
                <div class="card-grid">
                    <!-- Order Information -->
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-shopping-cart"></i>
                            <h3>Order Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="info-row">
                                <span class="info-label">Order Number:</span>
                                <span class="info-value"><?php echo htmlspecialchars($order['order_id']); ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Order Date:</span>
                                <span class="info-value"><?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Payment Method:</span>
                                <span class="info-value"><?php echo htmlspecialchars($order['payment_method']); ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Payment Status:</span>
                                <span class="info-value">
                                    <span class="status-badge status-<?php echo $order['payment_status']; ?>">
                                        <?php echo ucfirst($order['payment_status']); ?>
                                    </span>
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Order Status:</span>
                                <span class="info-value">
                                    <span class="status-badge status-<?php echo $order['status']; ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-user"></i>
                            <h3>Customer Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="info-row">
                                <span class="info-label">Name:</span>
                                <span class="info-value"><?php echo htmlspecialchars($order['customer_name'] ?? 'Guest'); ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Email:</span>
                                <span class="info-value"><?php echo htmlspecialchars($order['customer_email'] ?? 'N/A'); ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Phone:</span>
                                <span class="info-value"><?php echo htmlspecialchars($order['customer_phone'] ?? 'N/A'); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-truck"></i>
                            <h3>Shipping Address</h3>
                        </div>
                        <div class="card-body">
                            <p style="line-height: 1.8; color: #333;">
                                <?php echo htmlspecialchars($order['shipping_address']); ?><br>
                                <?php echo htmlspecialchars($order['shipping_city']); ?>, <?php echo htmlspecialchars($order['shipping_state']); ?> <?php echo htmlspecialchars($order['shipping_zip']); ?><br>
                                <?php echo htmlspecialchars($order['shipping_country']); ?>
                            </p>
                        </div>
                    </div>

                    <!-- Update Order Status -->
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-edit"></i>
                            <h3>Update Status</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <div class="form-group">
                                    <label class="form-label">Order Status:</label>
                                    <select name="status" class="form-select">
                                        <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                        <option value="completed" <?php echo $order['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                        <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                </div>
                                <button type="submit" name="update_status" class="btn btn-primary btn-full">
                                    <i class="fas fa-check"></i> Update Status
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-box"></i>
                        <h3>Order Items</h3>
                    </div>
                    <div class="card-body">
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_items as $item): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($item['product_name']); ?></strong></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                                        <td><strong>$<?php echo number_format($item['subtotal'], 2); ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <!-- Order Summary -->
                        <div class="order-summary">
                            <div class="summary-row">
                                <span>Subtotal:</span>
                                <span>$<?php echo number_format($order['subtotal'], 2); ?></span>
                            </div>
                            <div class="summary-row">
                                <span>Tax:</span>
                                <span>$<?php echo number_format($order['tax'], 2); ?></span>
                            </div>
                            <div class="summary-row">
                                <span>Shipping:</span>
                                <span>$<?php echo number_format($order['shipping'], 2); ?></span>
                            </div>
                            <div class="summary-row total">
                                <span>Grand Total:</span>
                                <span>$<?php echo number_format($order['total'], 2); ?></span>
                            </div>
                        </div>

                        <?php if (!empty($order['notes'])): ?>
                            <div style="margin-top: 1.5rem; padding: 1rem; background: #FFF3CD; border-left: 4px solid #D4AF37; border-radius: 4px;">
                                <strong style="color: #856404; display: block; margin-bottom: 0.5rem;">
                                    <i class="fas fa-sticky-note"></i> Order Notes:
                                </strong>
                                <p style="color: #856404; margin: 0;">
                                    <?php echo nl2br(htmlspecialchars($order['notes'])); ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
