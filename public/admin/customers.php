<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

require_once '../../includes/db.php';
require_once '../../includes/functions.php';

$page_title = "Customer Management";

// Initialize variables
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$customers = [];
$total_pages = 1;
$total_customers = 0;
$error_message = '';

// Only proceed if database is available
if (!can_query() || $conn === null) {
    $error_message = "Database connection error. Please check your database configuration.";
} else {
    // Pagination settings
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $per_page = 20;
    $offset = ($page - 1) * $per_page;

    // Get total count for pagination
    $count_sql = "SELECT COUNT(DISTINCT u.id) as total
                  FROM users u
                  WHERE u.role = 'customer'";

    $params = [];
    $types = '';

    if (!empty($search)) {
        $count_sql .= " AND (u.full_name LIKE ? OR u.username LIKE ? OR u.email LIKE ?)";
        $search_param = "%{$search}%";
        $params[] = $search_param;
        $params[] = $search_param;
        $params[] = $search_param;
        $types .= 'sss';
    }

    $stmt = $conn->prepare($count_sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $total_customers = $stmt->get_result()->fetch_assoc()['total'];
    $stmt->close();

    $total_pages = ceil($total_customers / $per_page);

    // Get customers from database
    $sql = "SELECT
                u.id,
                COALESCE(u.full_name, u.username) as name,
                u.email,
                u.created_at,
                COUNT(o.id) as total_orders,
                COALESCE(SUM(o.total), 0) as total_spent
            FROM users u
            LEFT JOIN orders o ON u.id = o.user_id
            WHERE u.role = 'customer'";

    $params = [];
    $types = '';

    if (!empty($search)) {
        $sql .= " AND (u.full_name LIKE ? OR u.username LIKE ? OR u.email LIKE ?)";
        $search_param = "%{$search}%";
        $params[] = $search_param;
        $params[] = $search_param;
        $params[] = $search_param;
        $types .= 'sss';
    }

    $sql .= " GROUP BY u.id
              ORDER BY u.created_at DESC
              LIMIT ? OFFSET ?";

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
        $customers[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Aura Vibe Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-body">
<div class="admin-layout">
    <?php include 'includes/sidebar.php'; ?>

    <div class="admin-main">
        <header class="admin-header">
            <h1><?php echo $page_title; ?></h1>
            <div class="admin-user">
                <span><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <i class="fas fa-user-circle"></i>
            </div>
        </header>

        <div class="admin-content">
            <div style="margin-bottom: 20px;">
                <p style="color: #ccc; font-size: 14px;">Manage and view customer information</p>
            </div>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <div class="stats-row">
            <div class="stat-card">
                <h3><?php echo $total_customers; ?></h3>
                <p>Total Customers</p>
            </div>
            <div class="stat-card">
                <h3><?php echo array_sum(array_column($customers, 'total_orders')); ?></h3>
                <p>Orders (Page)</p>
            </div>
            <div class="stat-card">
                <h3>$<?php echo number_format(array_sum(array_column($customers, 'total_spent')), 2); ?></h3>
                <p>Revenue (Page)</p>
            </div>
            <div class="stat-card">
                <h3>$<?php echo $total_customers > 0 ? number_format(array_sum(array_column($customers, 'total_spent')) / max(count($customers), 1), 2) : '0.00'; ?></h3>
                <p>Avg. Value (Page)</p>
            </div>
        </div>

        <div class="search-section">
            <form method="GET" action="" class="search-form">
                <input type="text" name="search" placeholder="Search by name or email..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-gold">Search</button>
                <?php if (!empty($search)): ?>
                    <a href="customers.php" class="btn btn-secondary">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="customers-table">
            <?php if (count($customers) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Orders</th>
                            <th>Total Spent</th>
                            <th>Join Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers as $customer): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($customer['id']); ?></td>
                                <td class="customer-name"><?php echo htmlspecialchars($customer['name']); ?></td>
                                <td class="customer-email"><?php echo htmlspecialchars($customer['email']); ?></td>
                                <td>
                                    <span class="badge badge-orders">
                                        <?php echo $customer['total_orders']; ?> orders
                                    </span>
                                </td>
                                <td class="amount-spent">
                                    $<?php echo number_format($customer['total_spent'], 2); ?>
                                </td>
                                <td class="date-text">
                                    <?php echo date('M d, Y', strtotime($customer['created_at'])); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div style="padding: 20px; text-align: center; background: #1a1a1a; border-top: 1px solid #D4AF37;">
                        <div style="display: flex; justify-content: center; align-items: center; gap: 15px;">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?php echo ($page - 1); ?>&search=<?php echo urlencode($search); ?>"
                                   class="btn btn-secondary">Previous</a>
                            <?php endif; ?>

                            <span style="color: #D4AF37; font-weight: 600;">
                                Page <?php echo $page; ?> of <?php echo $total_pages; ?> (<?php echo $total_customers; ?> total)
                            </span>

                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?php echo ($page + 1); ?>&search=<?php echo urlencode($search); ?>"
                                   class="btn btn-secondary">Next</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-results">
                    <?php if (!empty($search)): ?>
                        No customers found matching "<?php echo htmlspecialchars($search); ?>"
                    <?php else: ?>
                        No customers yet
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        </div>
    </div>
</div>
</body>
</html>
