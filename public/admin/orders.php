<?php
session_start();
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Initialize variables
$orders = [];
$total_pages = 1;
$total_orders = 0;
$error_message = '';

// Only proceed if database is available
if (!can_query() || $conn === null) {
    $error_message = "Database connection error. Please check your database configuration.";
} else {
    // Get status filter
    $status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

    // Get search query
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    // Pagination settings
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $per_page = 20;
    $offset = ($page - 1) * $per_page;

    // Get total count for pagination
    $count_sql = "SELECT COUNT(*) as total FROM orders o WHERE 1=1";

    $params = [];
    $types = '';

    if ($status_filter !== 'all') {
        $count_sql .= " AND o.status = ?";
        $params[] = $status_filter;
        $types .= 's';
    }

    if (!empty($search)) {
        $count_sql .= " AND (o.order_id LIKE ? OR o.first_name LIKE ? OR o.last_name LIKE ? OR o.email LIKE ?)";
        $search_param = "%{$search}%";
        $params[] = $search_param;
        $params[] = $search_param;
        $params[] = $search_param;
        $params[] = $search_param;
        $types .= 'ssss';
    }

    $stmt = $conn->prepare($count_sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $total_orders = $stmt->get_result()->fetch_assoc()['total'];
    $stmt->close();

    $total_pages = ceil($total_orders / $per_page);

    // Get orders from database (customer data is in orders table)
    $sql = "SELECT o.*,
            CONCAT(o.first_name, ' ', o.last_name) as customer_name,
            o.email as customer_email
            FROM orders o
            WHERE 1=1";

    $params = [];
    $types = '';

    if ($status_filter !== 'all') {
        $sql .= " AND o.status = ?";
        $params[] = $status_filter;
        $types .= 's';
    }

    if (!empty($search)) {
        $sql .= " AND (o.order_id LIKE ? OR o.first_name LIKE ? OR o.last_name LIKE ? OR o.email LIKE ?)";
        $search_param = "%{$search}%";
        $params[] = $search_param;
        $params[] = $search_param;
        $params[] = $search_param;
        $params[] = $search_param;
        $types .= 'ssss';
    }

    $sql .= " ORDER BY o.created_at DESC LIMIT ? OFFSET ?";
    $params[] = $per_page;
    $params[] = $offset;
    $types .= 'ii';

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Aura Vibe Admin</title>
    
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
                <h1>Manage Orders</h1>
                <div class="admin-user">
                    <span><?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></span>
                    <i class="fas fa-user-circle"></i>
                </div>
            </header>

            <!-- Content -->
            <div class="admin-content">
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <!-- Toolbar -->
                <div class="admin-toolbar">
                    <div class="filter-group">
                        <span class="filter-label">Filter by Status:</span>
                        <select class="filter-select" onchange="filterOrders(this.value)">
                            <option value="all" <?php echo (isset($status_filter) && $status_filter === 'all') ? 'selected' : ''; ?>>All Orders</option>
                            <option value="pending" <?php echo (isset($status_filter) && $status_filter === 'pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="processing" <?php echo (isset($status_filter) && $status_filter === 'processing') ? 'selected' : ''; ?>>Processing</option>
                            <option value="completed" <?php echo (isset($status_filter) && $status_filter === 'completed') ? 'selected' : ''; ?>>Completed</option>
                            <option value="cancelled" <?php echo (isset($status_filter) && $status_filter === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <form method="GET" action="" style="display: flex; gap: 0.5rem;">
                            <?php if (isset($status_filter) && $status_filter !== 'all'): ?>
                                <input type="hidden" name="status" value="<?php echo htmlspecialchars($status_filter); ?>">
                            <?php endif; ?>
                            <input type="text" name="search" placeholder="Search orders..."
                                   value="<?php echo isset($search) ? htmlspecialchars($search) : ''; ?>"
                                   style="padding: 0.6rem 1rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.95rem;">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <?php if (isset($search) && !empty($search)): ?>
                                <a href="orders.php?status=<?php echo isset($status_filter) ? $status_filter : 'all'; ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <!-- Orders Table -->
                <?php if (count($orders) > 0): ?>
                    <div class="admin-table-container">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer Name</th>
                                    <th>Email</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($order['order_id']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($order['customer_name'] ?? 'Guest'); ?></td>
                                        <td><?php echo htmlspecialchars($order['customer_email'] ?? 'N/A'); ?></td>
                                        <td><strong>$<?php echo number_format($order['total'], 2); ?></strong></td>
                                        <td>
                                            <span class="status-badge status-<?php echo $order['status']; ?>">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                        <td>
                                            <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div style="padding: 1.5rem; display: flex; justify-content: center; align-items: center; gap: 0.5rem;">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?php echo ($page - 1); ?>&status=<?php echo $status_filter; ?>&search=<?php echo urlencode($search); ?>"
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </a>
                            <?php endif; ?>

                            <span style="padding: 0 1rem; color: #666;">
                                Page <?php echo $page; ?> of <?php echo $total_pages; ?> (<?php echo $total_orders; ?> total orders)
                            </span>

                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?php echo ($page + 1); ?>&status=<?php echo $status_filter; ?>&search=<?php echo urlencode($search); ?>"
                                   class="btn btn-primary btn-sm">
                                    Next <i class="fas fa-chevron-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="admin-table-container">
                        <div class="empty-state">
                            <i class="fas fa-shopping-cart"></i>
                            <p><?php echo !empty($search) ? 'No orders found matching your search' : 'No orders yet'; ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function filterOrders(status) {
            const search = '<?php echo addslashes($search); ?>';
            let url = 'orders.php?status=' + status;
            if (search) {
                url += '&search=' + encodeURIComponent(search);
            }
            window.location.href = url;
        }
    </script>
</body>
</html>
