<aside class="admin-sidebar">
    <div class="admin-logo">
        <h2>Aura Vibe</h2>
        <p>Admin Panel</p>
    </div>

    <nav class="admin-nav">
        <a href="dashboard.php" class="admin-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="products.php" class="admin-nav-item <?php echo in_array(basename($_SERVER['PHP_SELF']), ['products.php', 'add-product.php', 'edit-product.php']) ? 'active' : ''; ?>">
            <i class="fas fa-box"></i> Products
        </a>
        <a href="categories.php" class="admin-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>">
            <i class="fas fa-tags"></i> Categories
        </a>
        <a href="brands.php" class="admin-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'brands.php' ? 'active' : ''; ?>">
            <i class="fas fa-certificate"></i> Brands
        </a>
        <a href="orders.php" class="admin-nav-item <?php echo in_array(basename($_SERVER['PHP_SELF']), ['orders.php', 'order-details.php']) ? 'active' : ''; ?>">
            <i class="fas fa-shopping-cart"></i> Orders
        </a>
        <a href="customers.php" class="admin-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'customers.php' ? 'active' : ''; ?>">
            <i class="fas fa-users"></i> Customers
        </a>
        <a href="shipping-costs.php" class="admin-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'shipping-costs.php' ? 'active' : ''; ?>">
            <i class="fas fa-shipping-fast"></i> Shipping Costs
        </a>
        <a href="settings.php" class="admin-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
            <i class="fas fa-cog"></i> Settings
        </a>
        <a href="logout.php" class="admin-nav-item">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </nav>
</aside>
