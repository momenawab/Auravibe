<?php
session_start();

// Disable debug mode in production
// error_reporting(E_ALL);
// ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../../error.log');

require_once '../../includes/db.php';
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$page_title = 'Manage Products';

// Initialize variables
$products = [];
$error_message = '';
$total_pages = 1;
$total_products = 0;

// Check if database is available
if (can_query() && $conn !== null) {
    // Get pagination parameters
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $per_page = 20;
    $offset = ($page - 1) * $per_page;

    // Get search parameter
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    // Build query with search
    $where_clause = '';
    $count_where = '';
    if (!empty($search)) {
        $search_param = '%' . $search . '%';
        $where_clause = " WHERE p.name LIKE ? OR p.description LIKE ? OR p.brand LIKE ? OR c.name LIKE ?";
        $count_where = $where_clause;
    }

    // Get total count for pagination
    $count_query = "SELECT COUNT(*) as total FROM products p
                    LEFT JOIN categories c ON p.category_id = c.id
                    " . $count_where;

    if (!empty($search)) {
        $count_stmt = mysqli_prepare($conn, $count_query);
        if (!$count_stmt) {
            $error_message = "Database error: " . mysqli_error($conn);
        } else {
            mysqli_stmt_bind_param($count_stmt, "ssss", $search_param, $search_param, $search_param, $search_param);
            if (!mysqli_stmt_execute($count_stmt)) {
                $error_message = "Database error: " . mysqli_stmt_error($count_stmt);
            }
            $count_result = mysqli_stmt_get_result($count_stmt);
        }
    } else {
        $count_result = mysqli_query($conn, $count_query);
        if (!$count_result) {
            $error_message = "Database error: " . mysqli_error($conn);
        }
    }

    $total_products = 0;
    if ($count_result) {
        $count_row = mysqli_fetch_assoc($count_result);
        $total_products = $count_row['total'];
    }

    $total_pages = ceil($total_products / $per_page);

    // Get products from database with pagination
    $query = "SELECT p.*, c.name as category_name, p.brand as brand_name
              FROM products p
              LEFT JOIN categories c ON p.category_id = c.id
              " .
              $where_clause .
              " ORDER BY p.created_at DESC LIMIT ? OFFSET ?";

    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        $error_message = "Database error preparing products query: " . mysqli_error($conn);
    } else {
        if (!empty($search)) {
            mysqli_stmt_bind_param($stmt, "ssssii", $search_param, $search_param, $search_param, $search_param, $per_page, $offset);
        } else {
            mysqli_stmt_bind_param($stmt, "ii", $per_page, $offset);
        }

        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $products[] = $row;
                }
            }
        } else {
            $error_message = "Error loading products: " . mysqli_stmt_error($stmt);
        }
    }

    // Get categories for filter
    $categories = [];
    $cat_result = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");
    if ($cat_result) {
        while ($row = mysqli_fetch_assoc($cat_result)) {
            $categories[] = $row;
        }
    }
} else {
    $error_message = "Database connection error. Please check your database configuration.";
}

// Handle success/error messages
$success = isset($_SESSION['success']) ? $_SESSION['success'] : '';
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['success'], $_SESSION['error']);
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
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="admin-logo">
            <h2>Aura Vibe</h2>
            <p>Admin Panel</p>
        </div>

        <nav class="admin-nav">
            <a href="dashboard.php" class="admin-nav-item">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="products.php" class="admin-nav-item active">
                <i class="fas fa-box"></i> Products
            </a>
            <a href="orders.php" class="admin-nav-item">
                <i class="fas fa-shopping-cart"></i> Orders
            </a>
            <a href="customers.php" class="admin-nav-item">
                <i class="fas fa-users"></i> Customers
            </a>
            <a href="categories.php" class="admin-nav-item">
                <i class="fas fa-tags"></i> Categories
            </a>
            <a href="settings.php" class="admin-nav-item">
                <i class="fas fa-cog"></i> Settings
            </a>
            <a href="logout.php" class="admin-nav-item">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="admin-main">
        <!-- Header -->
        <header class="admin-header">
            <h1>Manage Products</h1>
            <div class="admin-user">
                <span>Admin User</span>
                <i class="fas fa-user-circle"></i>
            </div>
        </header>

        <!-- Content -->
        <div class="admin-content">
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <!-- Toolbar -->
            <div class="admin-toolbar">
                <div class="toolbar-left">
                    <form method="GET" action="" style="display: flex; gap: 10px;">
                        <input type="text" name="search" class="search-input" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn btn-primary" style="white-space: nowrap;">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <?php if (!empty($search)): ?>
                            <a href="products.php" class="btn btn-outline">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
                <div class="toolbar-right">
                    <a href="add-product.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Product
                    </a>
                </div>
            </div>

            <!-- Products Table -->
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Featured</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 40px;">
                                    <i class="fas fa-box-open" style="font-size: 48px; color: #ccc; margin-bottom: 10px;"></i>
                                    <p style="color: #999; font-size: 18px;">
                                        <?php if (!empty($search)): ?>
                                            No products found matching "<?php echo htmlspecialchars($search); ?>"
                                        <?php else: ?>
                                            No products found. <a href="add-product.php" style="color: #D4AF37;">Add your first product</a>
                                        <?php endif; ?>
                                    </p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td>#<?php echo $product['id']; ?></td>
                                    <td>
                                        <div class="product-cell">
                                            <?php
                                            $image_url = !empty($product['image']) ? base_url($product['image']) : 'https://via.placeholder.com/50x50/1a1a1a/D4AF37?text=' . substr($product['name'], 0, 1);
                                            ?>
                                            <img src="<?php echo htmlspecialchars($image_url); ?>"
                                                 alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-thumb">
                                            <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($product['brand_name'] ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo strtolower($product['category_name'] ?? 'default'); ?>">
                                            <?php echo htmlspecialchars($product['category_name'] ?? 'N/A'); ?>
                                        </span>
                                    </td>
                                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                                    <td>
                                        <?php if ($product['stock'] > 10): ?>
                                            <span class="stock-badge stock-high"><?php echo $product['stock']; ?></span>
                                        <?php elseif ($product['stock'] > 0): ?>
                                            <span class="stock-badge stock-low"><?php echo $product['stock']; ?></span>
                                        <?php else: ?>
                                            <span class="stock-badge stock-out">Out of Stock</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($product['is_featured']): ?>
                                            <i class="fas fa-star" style="color: #D4AF37;"></i>
                                        <?php else: ?>
                                            <i class="far fa-star" style="color: #999;"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="edit-product.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="deleteProduct(<?php echo $product['id']; ?>)" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="admin-pagination">
                    <?php
                    $search_query = !empty($search) ? '&search=' . urlencode($search) : '';
                    ?>
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo ($page - 1) . $search_query; ?>" class="btn btn-outline">
                            <i class="fas fa-chevron-left"></i> Previous
                        </a>
                    <?php else: ?>
                        <button class="btn btn-outline" disabled>
                            <i class="fas fa-chevron-left"></i> Previous
                        </button>
                    <?php endif; ?>

                    <span>Page <?php echo $page; ?> of <?php echo $total_pages; ?> (<?php echo $total_products; ?> products)</span>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo ($page + 1) . $search_query; ?>" class="btn btn-outline">
                            Next <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php else: ?>
                        <button class="btn btn-outline" disabled>
                            Next <i class="fas fa-chevron-right"></i>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
        window.location.href = 'delete-product.php?id=' + id;
    }
}
</script>

</body>
</html>
