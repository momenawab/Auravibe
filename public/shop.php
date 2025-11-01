<?php require_once __DIR__ . '/config-paths.php';
$page_title = __('shop');
include_view('header.php');
include_view('navbar.php');

// Get filter parameters
$category = isset($_GET['category']) ? $_GET['category'] : '';
$brand = isset($_GET['brand']) ? $_GET['brand'] : '';
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 100000;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Fetch all categories for filter
$categories = [];
if (can_query()) {
    $cat_result = mysqli_query($conn, "SELECT id, name, name_ar, slug FROM categories ORDER BY name ASC");
    while ($cat_row = mysqli_fetch_assoc($cat_result)) {
        $categories[] = $cat_row;
    }
}

// Fetch all brands for filter (from products table)
$brands = [];
if (can_query()) {
    $brand_result = mysqli_query($conn, "SELECT DISTINCT brand FROM products WHERE brand IS NOT NULL AND brand != '' ORDER BY brand ASC");
    while ($brand_row = mysqli_fetch_assoc($brand_result)) {
        $brands[] = ['name' => $brand_row['brand'], 'slug' => strtolower(str_replace(' ', '-', $brand_row['brand']))];
    }
}

// Build query with prepared statements
$result = false;
$total_products = 0;

if (can_query()) {
    // Build WHERE clause
    $where_conditions = ["1=1"];
    $params = [];
    $types = "";

    if ($category) {
        $where_conditions[] = "c.slug = ?";
        $params[] = $category;
        $types .= "s";
    }
    if ($brand) {
        $where_conditions[] = "LOWER(REPLACE(p.brand, ' ', '-')) = ?";
        $params[] = $brand;
        $types .= "s";
    }
    if ($search) {
        $where_conditions[] = "(p.name LIKE ? OR p.description LIKE ? OR p.name_ar LIKE ?)";
        $search_param = "%{$search}%";
        $params[] = $search_param;
        $params[] = $search_param;
        $params[] = $search_param;
        $types .= "sss";
    }
    $where_conditions[] = "p.price BETWEEN ? AND ?";
    $params[] = $min_price;
    $params[] = $max_price;
    $types .= "dd";

    $where_clause = implode(" AND ", $where_conditions);

    // Sorting
    $order_by = "p.created_at DESC";
    switch ($sort) {
        case 'price_low':
            $order_by = "p.price ASC";
            break;
        case 'price_high':
            $order_by = "p.price DESC";
            break;
        case 'name':
            $order_by = "p.name ASC";
            break;
    }

    $query = "SELECT p.*, c.name as category_name, c.name_ar as category_name_ar, p.brand
              FROM products p
              LEFT JOIN categories c ON p.category_id = c.id

              WHERE {$where_clause}
              ORDER BY {$order_by}";

    $stmt = mysqli_prepare($conn, $query);
    if ($types) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $total_products = mysqli_num_rows($result);
}
?>

<!-- Shop Hero Section -->
<section class="shop-hero">
    <div class="container">
        <h1><?php echo __('all_watches'); ?></h1>
        <p><?php echo is_rtl() ? 'اكتشف الأناقة الخالدة والحرفية الدقيقة' : 'Discover timeless elegance and precision craftsmanship'; ?></p>
    </div>
</section>

<!-- Shop Section -->
<section class="shop-section">
    <div class="container">
        <div class="shop-layout">
            <!-- Sidebar Filters -->
            <aside class="shop-sidebar">
                <div class="filter-section">
                    <h3><?php echo __('filter'); ?></h3>

                    <form method="GET" action="shop.php" class="filter-form">
                        <!-- Search -->
                        <div class="filter-group">
                            <label><?php echo __('search'); ?></label>
                            <input type="text" name="search" placeholder="<?php echo __('search_products'); ?>..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>

                        <!-- Category Filter -->
                        <div class="filter-group">
                            <label><?php echo __('category'); ?></label>
                            <select name="category">
                                <option value=""><?php echo __('all_categories'); ?></option>
                                <?php foreach ($categories as $cat):
                                    $cat_display = is_rtl() && !empty($cat['name_ar']) ? $cat['name_ar'] : $cat['name'];
                                ?>
                                    <option value="<?php echo htmlspecialchars($cat['slug']); ?>" <?php echo $category === $cat['slug'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat_display); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Brand Filter -->
                        <div class="filter-group">
                            <label><?php echo __('brand'); ?></label>
                            <select name="brand">
                                <option value=""><?php echo __('all_brands'); ?></option>
                                <?php foreach ($brands as $b): ?>
                                    <option value="<?php echo htmlspecialchars($b['slug']); ?>" <?php echo $brand === $b['slug'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($b['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div class="filter-group">
                            <label><?php echo __('price_range'); ?></label>
                            <div class="price-inputs">
                                <input type="number" name="min_price" placeholder="<?php echo __('min_price'); ?>" value="<?php echo $min_price; ?>">
                                <span>-</span>
                                <input type="number" name="max_price" placeholder="<?php echo __('max_price'); ?>" value="<?php echo $max_price > 100000 ? '' : $max_price; ?>">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block"><?php echo __('apply_filters'); ?></button>
                        <a href="shop.php" class="btn btn-outline btn-block"><?php echo __('clear_filters'); ?></a>
                    </form>
                </div>
            </aside>

            <!-- Products Grid -->
            <div class="shop-main">
                <!-- Toolbar -->
                <div class="shop-toolbar">
                    <div class="results-count">
                        <?php echo __('showing'); ?> <?php echo $total_products; ?> <?php echo __('products_count'); ?>
                    </div>
                    <div class="sort-options">
                        <label><?php echo __('sort_by'); ?>:</label>
                        <select onchange="window.location.href='shop.php?sort=' + this.value">
                            <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>><?php echo __('newest'); ?></option>
                            <option value="price_low" <?php echo $sort === 'price_low' ? 'selected' : ''; ?>><?php echo __('price_low_high'); ?></option>
                            <option value="price_high" <?php echo $sort === 'price_high' ? 'selected' : ''; ?>><?php echo __('price_high_low'); ?></option>
                            <option value="name" <?php echo $sort === 'name' ? 'selected' : ''; ?>><?php echo is_rtl() ? 'الاسم' : 'Name'; ?></option>
                        </select>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="products-grid">
                    <?php if ($result && $total_products > 0): ?>
                        <?php while($product = mysqli_fetch_assoc($result)):
                            $prod_name = is_rtl() && !empty($product['name_ar']) ? $product['name_ar'] : $product['name'];
                            $cat_name = is_rtl() && !empty($product['category_name_ar']) ? $product['category_name_ar'] : $product['category_name'];
                        ?>
                            <div class="product-card">
                                <div class="product-image">
                                    <img src="<?php echo base_url($product['image'] ?? 'uploads/products/placeholder.jpg'); ?>"
                                         alt="<?php echo htmlspecialchars($prod_name); ?>">
                                    <div class="product-overlay">
                                        <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary"><?php echo __('view_details'); ?></a>
                                    </div>
                                    <button class="wishlist-btn" title="<?php echo __('add_to_wishlist'); ?>">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </div>
                                <div class="product-info">
                                    <div class="product-category"><?php echo htmlspecialchars($cat_name); ?></div>
                                    <h3><?php echo htmlspecialchars($prod_name); ?></h3>
                                    <div class="product-brand"><?php echo htmlspecialchars($product['brand']); ?></div>
                                    <div class="product-price number"><?php echo __('egp'); ?> <?php echo number_format($product['price'], 2); ?></div>
                                    <button class="btn btn-primary btn-block add-to-cart" data-id="<?php echo $product['id']; ?>">
                                        <i class="fas fa-shopping-cart"></i> <?php echo __('add_to_cart'); ?>
                                    </button>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="no-products" style="grid-column: 1/-1; text-align: center; padding: 80px 20px; background: linear-gradient(135deg, #1a1a1a, #2d2d2d); border-radius: 12px;">
                            <i class="fas fa-search" style="font-size: 5rem; color: #D4AF37; opacity: 0.3; margin-bottom: 25px;"></i>
                            <h3 style="color: #D4AF37; font-size: 24px; margin-bottom: 15px;"><?php echo __('no_products_found'); ?></h3>
                            <p style="color: #999; font-size: 16px; margin-bottom: 30px;"><?php echo __('try_different_filters'); ?></p>
                            <a href="shop.php" class="btn btn-primary"><?php echo __('clear_filters'); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_view('footer.php'); ?>
