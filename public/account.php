<?php require_once __DIR__ . '/config-paths.php';
session_start();
$page_title = 'My Account';
include_view('header.php');
include_view('navbar.php');

// Demo user data
$user = [
    'name' => $_SESSION['user_name'] ?? 'John Doe',
    'email' => $_SESSION['user_email'] ?? 'john.doe@example.com',
    'phone' => '+1 (555) 123-4567',
    'address' => '123 Luxury Avenue, Beverly Hills, CA 90210',
    'member_since' => '2024'
];

// Demo orders
$orders = [
    [
        'id' => 'ORD-2024-001',
        'date' => '2024-10-10',
        'total' => 6298.00,
        'status' => 'Delivered',
        'items' => 2
    ],
    [
        'id' => 'ORD-2024-002',
        'date' => '2024-09-22',
        'total' => 3799.00,
        'status' => 'In Transit',
        'items' => 1
    ],
    [
        'id' => 'ORD-2024-003',
        'date' => '2024-08-15',
        'total' => 2499.00,
        'status' => 'Delivered',
        'items' => 1
    ]
];
?>

<!-- Account Section -->
<section class="account-section">
    <div class="container">
        <h1>My Account</h1>

        <div class="account-layout">
            <!-- Sidebar Navigation -->
            <aside class="account-sidebar">
                <div class="account-user">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                    </div>
                    <div class="user-info">
                        <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                        <p><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                </div>

                <nav class="account-nav">
                    <a href="#dashboard" class="nav-item active" onclick="showTab('dashboard')">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="#orders" class="nav-item" onclick="showTab('orders')">
                        <i class="fas fa-shopping-bag"></i> Orders
                    </a>
                    <a href="#wishlist" class="nav-item" onclick="showTab('wishlist')">
                        <i class="fas fa-heart"></i> Wishlist
                    </a>
                    <a href="#profile" class="nav-item" onclick="showTab('profile')">
                        <i class="fas fa-user"></i> Profile
                    </a>
                    <a href="#addresses" class="nav-item" onclick="showTab('addresses')">
                        <i class="fas fa-map-marker-alt"></i> Addresses
                    </a>
                    <a href="#settings" class="nav-item" onclick="showTab('settings')">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                    <a href="logout.php" class="nav-item text-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </nav>
            </aside>

            <!-- Main Content -->
            <div class="account-main">
                <!-- Dashboard Tab -->
                <div id="dashboard" class="account-tab active">
                    <h2>Dashboard</h2>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value"><?php echo count($orders); ?></div>
                                <div class="stat-label">Total Orders</div>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value">5</div>
                                <div class="stat-label">Wishlist Items</div>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-award"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value">Gold</div>
                                <div class="stat-label">Membership Tier</div>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value"><?php echo $user['member_since']; ?></div>
                                <div class="stat-label">Member Since</div>
                            </div>
                        </div>
                    </div>

                    <h3>Recent Orders</h3>
                    <div class="orders-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($orders, 0, 3) as $order): ?>
                                    <tr>
                                        <td><?php echo $order['id']; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($order['date'])); ?></td>
                                        <td><?php echo $order['items']; ?> item(s)</td>
                                        <td>$<?php echo number_format($order['total'], 2); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $order['status'])); ?>">
                                                <?php echo $order['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Orders Tab -->
                <div id="orders" class="account-tab">
                    <h2>Order History</h2>
                    <div class="orders-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?php echo $order['id']; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($order['date'])); ?></td>
                                        <td><?php echo $order['items']; ?> item(s)</td>
                                        <td>$<?php echo number_format($order['total'], 2); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $order['status'])); ?>">
                                                <?php echo $order['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline">View Details</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Profile Tab -->
                <div id="profile" class="account-tab">
                    <h2>Profile Information</h2>
                    <form class="account-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" value="<?php echo htmlspecialchars($user['name']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="tel" value="<?php echo htmlspecialchars($user['phone']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Date of Birth</label>
                                <input type="date" value="1990-01-01">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>

                    <h3 class="mt-4">Change Password</h3>
                    <form class="account-form">
                        <div class="form-group">
                            <label>Current Password</label>
                            <input type="password" placeholder="Enter current password">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" placeholder="Enter new password">
                            </div>
                            <div class="form-group">
                                <label>Confirm New Password</label>
                                <input type="password" placeholder="Confirm new password">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </form>
                </div>

                <!-- Wishlist Tab -->
                <div id="wishlist" class="account-tab">
                    <h2>My Wishlist</h2>
                    <div class="products-grid">
                        <?php
                        $wishlist = [
                            ['id' => 6, 'name' => 'Limited Edition Tourbillon', 'brand' => 'PRESTIGE', 'price' => 9999.00],
                            ['id' => 7, 'name' => 'Rose Gold Automatic', 'brand' => 'ETERNITY', 'price' => 3299.00],
                        ];
                        foreach($wishlist as $item):
                        ?>
                            <div class="product-card">
                                <div class="product-image">
                                    <img src="https://via.placeholder.com/400x400/1a1a1a/D4AF37?text=<?php echo urlencode($item['name']); ?>"
                                         alt="<?php echo htmlspecialchars($item['name']); ?>">
                                    <button class="wishlist-btn active">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                                <div class="product-info">
                                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                    <div class="product-brand"><?php echo htmlspecialchars($item['brand']); ?></div>
                                    <div class="product-price">$<?php echo number_format($item['price'], 2); ?></div>
                                    <a href="product.php?id=<?php echo $item['id']; ?>" class="btn btn-primary btn-block">View Details</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.account-tab').forEach(tab => {
        tab.classList.remove('active');
    });

    // Remove active from all nav items
    document.querySelectorAll('.account-nav .nav-item').forEach(item => {
        item.classList.remove('active');
    });

    // Show selected tab
    document.getElementById(tabName).classList.add('active');
    event.target.classList.add('active');
}
</script>

<?php include_view('footer.php'); ?>
