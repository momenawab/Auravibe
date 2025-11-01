<?php
/**
 * COMPLETE Database Migration for Admin Panel
 * Ensures ALL admin panel tables are properly structured and populated
 * DELETE THIS FILE after use!
 */

// Database credentials
$DB_PASSWORD = 'YOUR_DATABASE_PASSWORD_HERE';  // ← UPDATE THIS!

$host = 'localhost';
$user = 'u446437128_auravibe';
$pass = $DB_PASSWORD;
$dbname = 'u446437128_auravibe';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Complete Admin Panel Migration</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 30px auto; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: green; background: #d4edda; padding: 10px; margin: 10px 0; border-left: 4px solid #28a745; }
        .error { color: red; background: #f8d7da; padding: 10px; margin: 10px 0; border-left: 4px solid #dc3545; }
        .warning { background: #fff3cd; padding: 10px; margin: 10px 0; border-left: 4px solid #ffc107; }
        .info { background: #d1ecf1; padding: 10px; margin: 10px 0; border-left: 4px solid #17a2b8; }
        h1 { color: #333; border-bottom: 3px solid #007bff; padding-bottom: 10px; }
        h2 { color: #555; margin-top: 30px; border-bottom: 2px solid #ddd; padding-bottom: 8px; }
        h3 { color: #666; margin-top: 20px; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th { background: #007bff; color: white; padding: 12px; text-align: left; }
        td { padding: 10px; border: 1px solid #ddd; }
        tr:nth-child(even) { background: #f9f9f9; }
        .btn { display: inline-block; padding: 12px 24px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; font-weight: bold; }
        .btn-success { background: #28a745; }
        .btn-warning { background: #ffc107; color: #333; }
        .progress { font-size: 24px; }
    </style>
</head>
<body>

<div class="container">
<h1>🚀 Complete Admin Panel Database Migration</h1>

<?php
// Check password
if ($DB_PASSWORD === 'YOUR_DATABASE_PASSWORD_HERE') {
    echo '<div class="error"><strong>❌ ERROR:</strong> Please update the database password on line 10 of this file.</div>';
    exit;
}

// Connect
$conn = @mysqli_connect($host, $user, $pass, $dbname);
if (!$conn) {
    echo '<div class="error">❌ Database Connection Failed: ' . mysqli_connect_error() . '</div>';
    exit;
}

mysqli_set_charset($conn, 'utf8mb4');
echo '<div class="success">✅ Database connected successfully!</div>';

// ==================================================
// STEP 1: CREATE/VERIFY ALL ADMIN PANEL TABLES
// ==================================================
echo '<h2>📋 Step 1: Verifying Table Structure</h2>';

$tables = [
    'admins' => 'Admin users',
    'categories' => 'Product categories',
    'products' => 'Products catalog',
    'product_images' => 'Product image gallery',
    'customers' => 'Customer accounts',
    'users' => 'Website users',
    'addresses' => 'Customer addresses',
    'orders' => 'Customer orders',
    'order_items' => 'Order line items',
    'shipping_costs' => 'Shipping fees',
    'settings' => 'Site settings',
    'wishlist' => 'Customer wishlists',
    'reviews' => 'Product reviews',
    'newsletter_subscribers' => 'Newsletter emails',
    'paymob_transactions' => 'Payment records'
];

foreach ($tables as $table => $description) {
    $result = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
    if (mysqli_num_rows($result) > 0) {
        $count_result = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM $table");
        $count = mysqli_fetch_assoc($count_result)['cnt'];
        echo '<div class="info">✅ Table exists: <strong>' . $table . '</strong> (' . $description . ') - ' . $count . ' rows</div>';
    } else {
        echo '<div class="error">❌ Table missing: <strong>' . $table . '</strong> - Please import database.sql first!</div>';
    }
}

// ==================================================
// STEP 2: ADD MISSING COLUMNS FOR MULTILINGUAL
// ==================================================
echo '<h2>🌍 Step 2: Adding Multilingual Support Columns</h2>';

// Add Arabic columns to categories
$columns_to_add = [
    "ALTER TABLE categories ADD COLUMN IF NOT EXISTS name_ar VARCHAR(100) AFTER name",
    "ALTER TABLE categories ADD COLUMN IF NOT EXISTS description_ar TEXT AFTER description",
    "ALTER TABLE products ADD COLUMN IF NOT EXISTS name_ar VARCHAR(255) AFTER name",
    "ALTER TABLE products ADD COLUMN IF NOT EXISTS description_ar TEXT AFTER description",
];

foreach ($columns_to_add as $sql) {
    if (@mysqli_query($conn, $sql)) {
        echo '<div class="success">✅ Column added/verified</div>';
    } else {
        // Try alternative method for MySQL versions that don't support IF NOT EXISTS
        $error = mysqli_error($conn);
        if (strpos($error, 'Duplicate column') !== false) {
            echo '<div class="info">ℹ️ Column already exists</div>';
        } else {
            echo '<div class="warning">⚠️ ' . htmlspecialchars($error) . '</div>';
        }
    }
}

// ==================================================
// STEP 3: SEED CATEGORIES
// ==================================================
echo '<h2>📁 Step 3: Creating Product Categories</h2>';

$categories = [
    [
        'name' => 'Luxury Watches',
        'name_ar' => 'ساعات فاخرة',
        'slug' => 'luxury-watches',
        'description' => 'Premium luxury watches crafted with finest materials',
        'description_ar' => 'ساعات فاخرة مصنوعة من أجود المواد'
    ],
    [
        'name' => 'Sport Watches',
        'name_ar' => 'ساعات رياضية',
        'slug' => 'sport-watches',
        'description' => 'High-performance sports and diving watches',
        'description_ar' => 'ساعات رياضية وغوص عالية الأداء'
    ],
    [
        'name' => 'Classic Watches',
        'name_ar' => 'ساعات كلاسيكية',
        'slug' => 'classic-watches',
        'description' => 'Timeless elegant designs for every occasion',
        'description_ar' => 'تصاميم أنيقة خالدة لكل مناسبة'
    ],
    [
        'name' => 'Smart Watches',
        'name_ar' => 'ساعات ذكية',
        'slug' => 'smart-watches',
        'description' => 'Modern smartwatches with advanced technology',
        'description_ar' => 'ساعات ذكية حديثة بتقنية متقدمة'
    ],
    [
        'name' => 'Limited Edition',
        'name_ar' => 'إصدار محدود',
        'slug' => 'limited-edition',
        'description' => 'Exclusive limited edition timepieces',
        'description_ar' => 'ساعات حصرية بإصدار محدود'
    ]
];

foreach ($categories as $cat) {
    $check = mysqli_prepare($conn, "SELECT id FROM categories WHERE slug = ?");
    mysqli_stmt_bind_param($check, "s", $cat['slug']);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($result) == 0) {
        $stmt = mysqli_prepare($conn, "INSERT INTO categories (name, name_ar, slug, description, description_ar) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssss", $cat['name'], $cat['name_ar'], $cat['slug'], $cat['description'], $cat['description_ar']);

        if (mysqli_stmt_execute($stmt)) {
            echo '<div class="success">✅ Created: ' . htmlspecialchars($cat['name']) . ' / ' . $cat['name_ar'] . '</div>';
        } else {
            echo '<div class="error">❌ Failed: ' . htmlspecialchars($cat['name']) . '</div>';
        }
    } else {
        echo '<div class="info">ℹ️ Exists: ' . htmlspecialchars($cat['name']) . '</div>';
    }
}

// ==================================================
// STEP 4: SEED PRODUCTS
// ==================================================
echo '<h2>🛍️ Step 4: Creating Sample Products</h2>';

$products = [
    [
        'name' => 'Titanium Sport Pro',
        'name_ar' => 'تيتانيوم سبورت برو',
        'slug' => 'titanium-sport-pro',
        'brand' => 'SPORT MAX',
        'description' => 'Professional titanium sports watch with advanced features, shock resistance, and 200m water resistance. Perfect for athletes and active lifestyles.',
        'description_ar' => 'ساعة رياضية تيتانيوم احترافية بميزات متقدمة، مقاومة للصدمات ومقاومة للماء حتى 200 متر. مثالية للرياضيين والأنماط النشطة.',
        'price' => 2299.00,
        'stock' => 15,
        'category' => 'sport-watches',
        'sku' => 'TSP-001',
        'is_featured' => 1
    ],
    [
        'name' => 'Royal Gold Chronograph',
        'name_ar' => 'كرونوغراف الذهب الملكي',
        'slug' => 'royal-gold-chronograph',
        'brand' => 'ETERNITY',
        'description' => 'Luxurious 18k gold chronograph with Swiss automatic movement. A statement piece for distinguished individuals.',
        'description_ar' => 'كرونوغراف فاخر من الذهب عيار 18 قيراط بحركة سويسرية أوتوماتيكية. قطعة مميزة للأفراد المتميزين.',
        'price' => 4599.00,
        'stock' => 8,
        'category' => 'luxury-watches',
        'sku' => 'RGC-002',
        'is_featured' => 1
    ],
    [
        'name' => 'Carbon Fiber Racing',
        'name_ar' => 'سباق ألياف الكربون',
        'slug' => 'carbon-fiber-racing',
        'brand' => 'SPORT MAX',
        'description' => 'Ultra-lightweight carbon fiber racing chronograph. Precision timing for motorsport enthusiasts.',
        'description_ar' => 'كرونوغراف سباق خفيف الوزن من ألياف الكربون. توقيت دقيق لعشاق رياضة السيارات.',
        'price' => 2799.00,
        'stock' => 12,
        'category' => 'sport-watches',
        'sku' => 'CFR-003',
        'is_featured' => 1
    ],
    [
        'name' => 'Vintage Elegance',
        'name_ar' => 'الأناقة الكلاسيكية',
        'slug' => 'vintage-elegance',
        'brand' => 'HERITAGE',
        'description' => 'Classic vintage-inspired timepiece with manual winding mechanism. Timeless elegance meets traditional craftsmanship.',
        'description_ar' => 'ساعة كلاسيكية مستوحاة من الطراز القديم بآلية تعبئة يدوية. أناقة خالدة تلتقي بالحرفية التقليدية.',
        'price' => 1899.00,
        'stock' => 20,
        'category' => 'classic-watches',
        'sku' => 'VE-004',
        'is_featured' => 0
    ],
    [
        'name' => 'Ocean Diver Pro',
        'name_ar' => 'محترف الغوص البحري',
        'slug' => 'ocean-diver-pro',
        'brand' => 'AQUAMASTER',
        'description' => 'Professional diving watch certified to 300m depth. Luminous markers and unidirectional bezel for safety.',
        'description_ar' => 'ساعة غوص احترافية معتمدة لعمق 300 متر. علامات مضيئة وحافة أحادية الاتجاه للأمان.',
        'price' => 3299.00,
        'stock' => 10,
        'category' => 'sport-watches',
        'sku' => 'ODP-005',
        'is_featured' => 1
    ],
    [
        'name' => 'Rose Gold Automatic',
        'name_ar' => 'ذهب وردي أوتوماتيكي',
        'slug' => 'rose-gold-automatic',
        'brand' => 'ETERNITY',
        'description' => 'Elegant rose gold automatic watch with exhibition caseback. See the intricate movement at work.',
        'description_ar' => 'ساعة أوتوماتيكية أنيقة من الذهب الوردي بظهر شفاف. شاهد الحركة المعقدة أثناء العمل.',
        'price' => 3899.00,
        'stock' => 6,
        'category' => 'luxury-watches',
        'sku' => 'RGA-006',
        'is_featured' => 1
    ],
    [
        'name' => 'Smart Fitness Tracker',
        'name_ar' => 'متتبع اللياقة الذكي',
        'slug' => 'smart-fitness-tracker',
        'brand' => 'TECHWATCH',
        'description' => 'Advanced smartwatch with heart rate monitoring, GPS, and 7-day battery life. Track your health goals.',
        'description_ar' => 'ساعة ذكية متقدمة مع مراقبة معدل ضربات القلب وGPS وبطارية تدوم 7 أيام. تتبع أهداف صحتك.',
        'price' => 1299.00,
        'stock' => 25,
        'category' => 'smart-watches',
        'sku' => 'SFT-007',
        'is_featured' => 0
    ],
    [
        'name' => 'Moonphase Limited Edition',
        'name_ar' => 'طور القمر إصدار محدود',
        'slug' => 'moonphase-limited',
        'brand' => 'CELESTIAL',
        'description' => 'Limited edition moonphase complication. Only 100 pieces worldwide. Collector\'s masterpiece.',
        'description_ar' => 'إصدار محدود بتعقيد طور القمر. 100 قطعة فقط حول العالم. تحفة للمقتنين.',
        'price' => 8999.00,
        'stock' => 3,
        'category' => 'limited-edition',
        'sku' => 'MPL-008',
        'is_featured' => 1
    ]
];

foreach ($products as $prod) {
    // Get category_id
    $cat_stmt = mysqli_prepare($conn, "SELECT id FROM categories WHERE slug = ?");
    mysqli_stmt_bind_param($cat_stmt, "s", $prod['category']);
    mysqli_stmt_execute($cat_stmt);
    $cat_result = mysqli_stmt_get_result($cat_stmt);
    $category = mysqli_fetch_assoc($cat_result);
    $category_id = $category['id'] ?? null;

    if (!$category_id) {
        echo '<div class="warning">⚠️ Category not found for: ' . htmlspecialchars($prod['name']) . '</div>';
        continue;
    }

    // Check if product exists
    $check = mysqli_prepare($conn, "SELECT id FROM products WHERE slug = ? OR sku = ?");
    mysqli_stmt_bind_param($check, "ss", $prod['slug'], $prod['sku']);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($result) == 0) {
        $stmt = mysqli_prepare($conn, "INSERT INTO products (category_id, name, name_ar, slug, brand, description, description_ar, price, stock, sku, is_featured, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
        mysqli_stmt_bind_param($stmt, "issssssdssi",
            $category_id,
            $prod['name'],
            $prod['name_ar'],
            $prod['slug'],
            $prod['brand'],
            $prod['description'],
            $prod['description_ar'],
            $prod['price'],
            $prod['stock'],
            $prod['sku'],
            $prod['is_featured']
        );

        if (mysqli_stmt_execute($stmt)) {
            $product_id = mysqli_insert_id($conn);
            echo '<div class="success">✅ Created: ' . htmlspecialchars($prod['name']) . ' - EGP ' . number_format($prod['price'], 2) . ' (ID: ' . $product_id . ')</div>';
        } else {
            echo '<div class="error">❌ Failed: ' . htmlspecialchars($prod['name']) . ' - ' . mysqli_error($conn) . '</div>';
        }
    } else {
        echo '<div class="info">ℹ️ Exists: ' . htmlspecialchars($prod['name']) . '</div>';
    }
}

// ==================================================
// STEP 5: SHIPPING COSTS
// ==================================================
echo '<h2>🚚 Step 5: Configuring Shipping Costs</h2>';

$shipping_data = [
    ['governorate' => 'Cairo', 'cost' => 0.00],
    ['governorate' => 'Giza', 'cost' => 0.00],
    ['governorate' => 'Alexandria', 'cost' => 50.00],
    ['governorate' => 'Qalyubia', 'cost' => 30.00],
    ['governorate' => 'Sharqia', 'cost' => 40.00],
    ['governorate' => 'Dakahlia', 'cost' => 45.00],
    ['governorate' => 'Beheira', 'cost' => 50.00],
    ['governorate' => 'Gharbia', 'cost' => 45.00],
    ['governorate' => 'Monufia', 'cost' => 40.00],
    ['governorate' => 'Kafr El Sheikh', 'cost' => 50.00],
    ['governorate' => 'Damietta', 'cost' => 55.00],
    ['governorate' => 'Port Said', 'cost' => 60.00],
    ['governorate' => 'Ismailia', 'cost' => 55.00],
    ['governorate' => 'Suez', 'cost' => 60.00],
    ['governorate' => 'Other', 'cost' => 100.00]
];

$result = mysqli_query($conn, "SHOW TABLES LIKE 'shipping_costs'");
if (mysqli_num_rows($result) > 0) {
    foreach ($shipping_data as $ship) {
        $check = mysqli_prepare($conn, "SELECT id FROM shipping_costs WHERE governorate = ?");
        mysqli_stmt_bind_param($check, "s", $ship['governorate']);
        mysqli_stmt_execute($check);
        $result = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($result) == 0) {
            $stmt = mysqli_prepare($conn, "INSERT INTO shipping_costs (governorate, cost) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, "sd", $ship['governorate'], $ship['cost']);

            if (mysqli_stmt_execute($stmt)) {
                echo '<div class="success">✅ ' . htmlspecialchars($ship['governorate']) . ': EGP ' . number_format($ship['cost'], 2) . '</div>';
            }
        } else {
            echo '<div class="info">ℹ️ ' . htmlspecialchars($ship['governorate']) . ': Already configured</div>';
        }
    }
} else {
    echo '<div class="warning">⚠️ shipping_costs table not found</div>';
}

// ==================================================
// STEP 6: SETTINGS
// ==================================================
echo '<h2>⚙️ Step 6: Configuring Site Settings</h2>';

$settings = [
    ['key' => 'site_name', 'value' => 'AuraVibe', 'type' => 'text'],
    ['key' => 'site_tagline', 'value' => 'Luxury Watches Collection', 'type' => 'text'],
    ['key' => 'site_email', 'value' => 'info@auravibe.site', 'type' => 'text'],
    ['key' => 'site_phone', 'value' => '+20 XXX XXX XXXX', 'type' => 'text'],
    ['key' => 'currency', 'value' => 'EGP', 'type' => 'text'],
    ['key' => 'currency_symbol', 'value' => 'EGP', 'type' => 'text'],
    ['key' => 'tax_rate', 'value' => '14', 'type' => 'number'],
    ['key' => 'free_shipping_threshold', 'value' => '100', 'type' => 'number'],
    ['key' => 'items_per_page', 'value' => '12', 'type' => 'number'],
    ['key' => 'enable_reviews', 'value' => '1', 'type' => 'boolean'],
    ['key' => 'enable_wishlist', 'value' => '1', 'type' => 'boolean'],
    ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean']
];

foreach ($settings as $setting) {
    $check = mysqli_prepare($conn, "SELECT id FROM settings WHERE setting_key = ?");
    mysqli_stmt_bind_param($check, "s", $setting['key']);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($result) == 0) {
        $stmt = mysqli_prepare($conn, "INSERT INTO settings (setting_key, setting_value, setting_type) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $setting['key'], $setting['value'], $setting['type']);

        if (mysqli_stmt_execute($stmt)) {
            echo '<div class="success">✅ ' . htmlspecialchars($setting['key']) . ' = ' . htmlspecialchars($setting['value']) . '</div>';
        }
    } else {
        echo '<div class="info">ℹ️ ' . htmlspecialchars($setting['key']) . ': Already configured</div>';
    }
}

// ==================================================
// STEP 7: SUMMARY
// ==================================================
echo '<h2>📊 Step 7: Database Summary</h2>';

echo '<table>';
echo '<tr><th>Table Name</th><th>Description</th><th>Row Count</th><th>Status</th></tr>';

$admin_tables = [
    'admins' => 'Admin Users',
    'categories' => 'Product Categories',
    'products' => 'Product Catalog',
    'customers' => 'Customer Accounts',
    'users' => 'Website Users',
    'orders' => 'Customer Orders',
    'order_items' => 'Order Line Items',
    'shipping_costs' => 'Shipping Configuration',
    'settings' => 'Site Settings',
    'wishlist' => 'Customer Wishlists',
    'reviews' => 'Product Reviews',
    'newsletter_subscribers' => 'Newsletter Subscriptions',
    'paymob_transactions' => 'Payment Transactions'
];

foreach ($admin_tables as $table => $description) {
    $result = @mysqli_query($conn, "SELECT COUNT(*) as count FROM $table");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $count = $row['count'];
        $status = $count > 0 ? '✅ Active' : '⚠️ Empty';
        $color = $count > 0 ? '#28a745' : '#ffc107';

        echo '<tr>';
        echo '<td><strong>' . $table . '</strong></td>';
        echo '<td>' . $description . '</td>';
        echo '<td style="text-align: center; font-weight: bold;">' . $count . '</td>';
        echo '<td style="color: ' . $color . '; font-weight: bold;">' . $status . '</td>';
        echo '</tr>';
    }
}

echo '</table>';

// Count totals
$total_categories = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM categories"))['c'];
$total_products = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM products"))['c'];
$total_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM orders"))['c'];
$total_customers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM customers"))['c'];

mysqli_close($conn);

// ==================================================
// SUCCESS MESSAGE
// ==================================================
echo '<div class="success" style="margin-top: 30px; padding: 20px;">';
echo '<h2 class="progress">🎉 Migration Completed Successfully!</h2>';
echo '<h3>Database Statistics:</h3>';
echo '<ul style="font-size: 16px; line-height: 2;">';
echo '<li>✅ <strong>' . $total_categories . '</strong> Product Categories</li>';
echo '<li>✅ <strong>' . $total_products . '</strong> Products in Catalog</li>';
echo '<li>✅ <strong>' . $total_orders . '</strong> Orders</li>';
echo '<li>✅ <strong>' . $total_customers . '</strong> Customers</li>';
echo '</ul>';
echo '<p style="font-size: 18px; margin-top: 20px;">Your admin panel is now <strong>fully configured and ready to use!</strong></p>';
echo '</div>';

echo '<div style="text-align: center; margin: 30px 0;">';
echo '<a href="admin/" class="btn btn-success" style="font-size: 18px; padding: 15px 30px;">🎛️ Open Admin Panel</a>';
echo '<a href="../" class="btn" style="font-size: 18px; padding: 15px 30px;">🏠 View Website</a>';
echo '</div>';

echo '<div class="warning" style="margin-top: 30px; padding: 20px;">';
echo '<h3>🔒 SECURITY: Clean Up Required</h3>';
echo '<p><strong>DELETE these test files immediately:</strong></p>';
echo '<ol style="font-size: 16px; line-height: 2;">';
echo '<li>full-migration.php (this file)</li>';
echo '<li>migrate-data.php</li>';
echo '<li>setup-admin.php</li>';
echo '<li>create-admin.php</li>';
echo '<li>test-db.php</li>';
echo '</ol>';
echo '<p style="color: red; font-weight: bold; margin-top: 15px;">⚠️ These files contain sensitive database information!</p>';
echo '</div>';
?>

</div>
</body>
</html>
