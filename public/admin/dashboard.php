<?php
session_start();
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get statistics from database
$stats = [
    'total_products' => 0,
    'total_orders' => 0,
    'total_customers' => 0,
    'total_revenue' => 0
];

$recent_orders = [];

if (can_query() && $conn !== null) {
    // Total products
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM products");
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $stats['total_products'] = $row['count'];
    }

    // Total orders
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders");
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $stats['total_orders'] = $row['count'];
    }

    // Total customers
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role = 'customer'");
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $stats['total_customers'] = $row['count'];
    }

    // Total revenue
    $result = mysqli_query($conn, "SELECT SUM(total) as revenue FROM orders WHERE status = 'completed' OR status = 'confirmed'");
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $stats['total_revenue'] = $row['revenue'] ?? 0;
    }

    // Recent orders (customer data is stored in orders table)
    $query = "SELECT o.*,
              CONCAT(o.first_name, ' ', o.last_name) as customer_name,
              o.email as customer_email
              FROM orders o
              ORDER BY o.created_at DESC LIMIT 10";
    $result = mysqli_query($conn, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $recent_orders[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Aura Vibe Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-body">

<div class="admin-layout">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="admin-main">
        <!-- Header -->
        <header class="admin-header">
            <h1>Dashboard</h1>
            <div class="admin-user">
                <span><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <i class="fas fa-user-circle"></i>
            </div>
        </header>

        <!-- Content -->
        <div class="admin-content">
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon gold">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Products</h3>
                        <p><?php echo number_format($stats['total_products']); ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon blue">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Orders</h3>
                        <p><?php echo number_format($stats['total_orders']); ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon green">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Revenue</h3>
                        <p>$<?php echo number_format($stats['total_revenue'], 2); ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon purple">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Customers</h3>
                        <p><?php echo number_format($stats['total_customers']); ?></p>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Recent Orders</h2>
                    <a href="orders.php" class="btn btn-outline btn-sm">View All</a>
                </div>
                <div class="card-body">
                    <?php if (count($recent_orders) > 0): ?>
                        <div class="admin-table-container">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_orders as $order): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($order['order_id']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($order['customer_name'] ?? $order['customer_email']); ?></td>
                                            <td>$<?php echo number_format($order['total'], 2); ?></td>
                                            <td>
                                                <span class="status-badge status-<?php echo $order['status']; ?>">
                                                    <?php echo ucfirst($order['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                            <td>
                                                <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-shopping-bag"></i>
                            <h3>No Orders Yet</h3>
                            <p>Orders will appear here once customers make purchases</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
