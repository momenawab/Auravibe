<?php
/**
 * Database Migration & Seeding Script
 * Populates database with initial data
 * DELETE THIS FILE after use!
 */

// Database credentials (same as setup-admin.php)
$DB_PASSWORD = 'YOUR_DATABASE_PASSWORD_HERE';  // ← UPDATE THIS!

$host = 'localhost';
$user = 'u446437128_auravibe';
$pass = $DB_PASSWORD;
$dbname = 'u446437128_auravibe';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Migration</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 50px auto; padding: 20px; }
        .success { color: green; background: #d4edda; padding: 10px; margin: 10px 0; border: 1px solid #c3e6cb; border-radius: 5px; }
        .error { color: red; background: #f8d7da; padding: 10px; margin: 10px 0; border: 1px solid #f5c6cb; border-radius: 5px; }
        .warning { background: #fff3cd; padding: 10px; margin: 10px 0; border: 1px solid #ffeaa7; border-radius: 5px; }
        .info { background: #d1ecf1; padding: 10px; margin: 10px 0; border: 1px solid #bee5eb; border-radius: 5px; }
        h1 { color: #333; }
        h2 { color: #666; border-bottom: 2px solid #ddd; padding-bottom: 10px; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
    </style>
</head>
<body>

<h1>📦 Database Migration & Seeding</h1>

<?php
// Check if password is updated
if ($DB_PASSWORD === 'YOUR_DATABASE_PASSWORD_HERE') {
    echo '<div class="error"><strong>❌ ERROR:</strong> Please update the database password in this file (line 10).</div>';
    exit;
}

// Connect to database
$conn = @mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    echo '<div class="error">❌ Database Connection Failed: ' . mysqli_connect_error() . '</div>';
    exit;
}

mysqli_set_charset($conn, 'utf8mb4');
echo '<div class="success">✅ Database connected successfully!</div>';

// Track results
$results = [];

// ============================================
// 1. ADD MISSING COLUMNS TO TABLES
// ============================================
echo '<h2>1️⃣ Checking Table Structure</h2>';

// Check and add name_ar to categories if missing
$result = mysqli_query($conn, "SHOW COLUMNS FROM categories LIKE 'name_ar'");
if (mysqli_num_rows($result) == 0) {
    mysqli_query($conn, "ALTER TABLE categories ADD COLUMN name_ar VARCHAR(100) AFTER name");
    echo '<div class="info">➕ Added column: categories.name_ar</div>';
} else {
    echo '<div class="success">✅ Column exists: categories.name_ar</div>';
}

// Check and add name_ar to products if missing
$result = mysqli_query($conn, "SHOW COLUMNS FROM products LIKE 'name_ar'");
if (mysqli_num_rows($result) == 0) {
    mysqli_query($conn, "ALTER TABLE products ADD COLUMN name_ar VARCHAR(255) AFTER name");
    echo '<div class="info">➕ Added column: products.name_ar</div>';
} else {
    echo '<div class="success">✅ Column exists: products.name_ar</div>';
}

// Check and add description_ar to products if missing
$result = mysqli_query($conn, "SHOW COLUMNS FROM products LIKE 'description_ar'");
if (mysqli_num_rows($result) == 0) {
    mysqli_query($conn, "ALTER TABLE products ADD COLUMN description_ar TEXT AFTER description");
    echo '<div class="info">➕ Added column: products.description_ar</div>';
} else {
    echo '<div class="success">✅ Column exists: products.description_ar</div>';
}

// ============================================
// 2. SEED CATEGORIES
// ============================================
echo '<h2>2️⃣ Seeding Categories</h2>';

$categories = [
    ['name' => 'Luxury Watches', 'name_ar' => 'ساعات فاخرة', 'slug' => 'luxury-watches', 'description' => 'Premium luxury watches for the discerning connoisseur'],
    ['name' => 'Sport Watches', 'name_ar' => 'ساعات رياضية', 'slug' => 'sport-watches', 'description' => 'High-performance sports and diving watches'],
    ['name' => 'Classic Watches', 'name_ar' => 'ساعات كلاسيكية', 'slug' => 'classic-watches', 'description' => 'Timeless designs that never go out of style'],
    ['name' => 'Smart Watches', 'name_ar' => 'ساعات ذكية', 'slug' => 'smart-watches', 'description' => 'Modern smartwatches with advanced features']
];

foreach ($categories as $cat) {
    // Check if category exists
    $check = mysqli_prepare($conn, "SELECT id FROM categories WHERE slug = ?");
    mysqli_stmt_bind_param($check, "s", $cat['slug']);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($result) == 0) {
        // Insert new category
        $stmt = mysqli_prepare($conn, "INSERT INTO categories (name, name_ar, slug, description) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $cat['name'], $cat['name_ar'], $cat['slug'], $cat['description']);

        if (mysqli_stmt_execute($stmt)) {
            echo '<div class="success">✅ Created category: ' . htmlspecialchars($cat['name']) . '</div>';
        } else {
            echo '<div class="error">❌ Failed to create category: ' . htmlspecialchars($cat['name']) . '</div>';
        }
    } else {
        echo '<div class="info">ℹ️ Category already exists: ' . htmlspecialchars($cat['name']) . '</div>';
    }
}

// ============================================
// 3. SEED SAMPLE PRODUCTS
// ============================================
echo '<h2>3️⃣ Seeding Sample Products</h2>';

$products = [
    [
        'name' => 'Titanium Sport Pro',
        'name_ar' => 'تيتانيوم سبورت برو',
        'slug' => 'titanium-sport-pro',
        'brand' => 'SPORT MAX',
        'description' => 'Professional titanium sports watch with advanced features and durability',
        'price' => 2299.00,
        'stock' => 15,
        'category' => 'sport-watches',
        'sku' => 'TSP-001'
    ],
    [
        'name' => 'Royal Gold Chronograph',
        'name_ar' => 'كرونوغراف الذهب الملكي',
        'slug' => 'royal-gold-chronograph',
        'brand' => 'ETERNITY',
        'description' => 'Luxurious gold chronograph with Swiss movement',
        'price' => 4599.00,
        'stock' => 8,
        'category' => 'luxury-watches',
        'sku' => 'RGC-002'
    ],
    [
        'name' => 'Carbon Fiber Racing',
        'name_ar' => 'سباق ألياف الكربون',
        'slug' => 'carbon-fiber-racing',
        'brand' => 'SPORT MAX',
        'description' => 'Lightweight carbon fiber racing watch for athletes',
        'price' => 2799.00,
        'stock' => 12,
        'category' => 'sport-watches',
        'sku' => 'CFR-003'
    ],
    [
        'name' => 'Vintage Elegance',
        'name_ar' => 'الأناقة القديمة',
        'slug' => 'vintage-elegance',
        'brand' => 'HERITAGE',
        'description' => 'Classic vintage-inspired watch with timeless design',
        'price' => 1899.00,
        'stock' => 20,
        'category' => 'classic-watches',
        'sku' => 'VE-004'
    ],
    [
        'name' => 'Ocean Diver Pro',
        'name_ar' => 'محترف الغوص البحري',
        'slug' => 'ocean-diver-pro',
        'brand' => 'AQUAMASTER',
        'description' => 'Professional diving watch with 300m water resistance',
        'price' => 3299.00,
        'stock' => 10,
        'category' => 'sport-watches',
        'sku' => 'ODP-005'
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

    // Check if product exists
    $check = mysqli_prepare($conn, "SELECT id FROM products WHERE slug = ? OR sku = ?");
    mysqli_stmt_bind_param($check, "ss", $prod['slug'], $prod['sku']);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($result) == 0) {
        // Insert product
        $stmt = mysqli_prepare($conn, "INSERT INTO products (category_id, name, name_ar, slug, brand, description, price, stock, sku, is_featured, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 1)");
        mysqli_stmt_bind_param($stmt, "isssssdis",
            $category_id,
            $prod['name'],
            $prod['name_ar'],
            $prod['slug'],
            $prod['brand'],
            $prod['description'],
            $prod['price'],
            $prod['stock'],
            $prod['sku']
        );

        if (mysqli_stmt_execute($stmt)) {
            echo '<div class="success">✅ Created product: ' . htmlspecialchars($prod['name']) . ' - ' . number_format($prod['price'], 2) . ' EGP</div>';
        } else {
            echo '<div class="error">❌ Failed to create product: ' . htmlspecialchars($prod['name']) . ' - ' . mysqli_error($conn) . '</div>';
        }
    } else {
        echo '<div class="info">ℹ️ Product already exists: ' . htmlspecialchars($prod['name']) . '</div>';
    }
}

// ============================================
// 4. SEED SHIPPING COSTS
// ============================================
echo '<h2>4️⃣ Seeding Shipping Costs</h2>';

// Check if shipping_costs table exists
$result = mysqli_query($conn, "SHOW TABLES LIKE 'shipping_costs'");
if (mysqli_num_rows($result) > 0) {
    $shipping_costs = [
        ['governorate' => 'Cairo', 'cost' => 0.00],
        ['governorate' => 'Giza', 'cost' => 0.00],
        ['governorate' => 'Alexandria', 'cost' => 50.00],
        ['governorate' => 'Qalyubia', 'cost' => 30.00],
        ['governorate' => 'Sharqia', 'cost' => 40.00],
        ['governorate' => 'Other', 'cost' => 100.00]
    ];

    foreach ($shipping_costs as $shipping) {
        $check = mysqli_prepare($conn, "SELECT id FROM shipping_costs WHERE governorate = ?");
        mysqli_stmt_bind_param($check, "s", $shipping['governorate']);
        mysqli_stmt_execute($check);
        $result = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($result) == 0) {
            $stmt = mysqli_prepare($conn, "INSERT INTO shipping_costs (governorate, cost) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, "sd", $shipping['governorate'], $shipping['cost']);

            if (mysqli_stmt_execute($stmt)) {
                echo '<div class="success">✅ Added shipping cost: ' . htmlspecialchars($shipping['governorate']) . ' - ' . number_format($shipping['cost'], 2) . ' EGP</div>';
            }
        } else {
            echo '<div class="info">ℹ️ Shipping cost exists: ' . htmlspecialchars($shipping['governorate']) . '</div>';
        }
    }
} else {
    echo '<div class="warning">⚠️ Table "shipping_costs" does not exist - skipping</div>';
}

// ============================================
// 5. UPDATE SETTINGS
// ============================================
echo '<h2>5️⃣ Updating Settings</h2>';

$settings = [
    ['key' => 'site_name', 'value' => 'AuraVibe', 'type' => 'text'],
    ['key' => 'site_email', 'value' => 'info@auravibe.site', 'type' => 'text'],
    ['key' => 'site_phone', 'value' => '+20 XXX XXX XXXX', 'type' => 'text'],
    ['key' => 'currency', 'value' => 'EGP', 'type' => 'text'],
    ['key' => 'currency_symbol', 'value' => 'EGP', 'type' => 'text'],
    ['key' => 'tax_rate', 'value' => '14', 'type' => 'number'],
    ['key' => 'free_shipping_threshold', 'value' => '100', 'type' => 'number']
];

foreach ($settings as $setting) {
    $check = mysqli_prepare($conn, "SELECT id FROM settings WHERE setting_key = ?");
    mysqli_stmt_bind_param($check, "s", $setting['key']);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($result) == 0) {
        $stmt = mysqli_prepare($conn, "INSERT INTO settings (setting_key, setting_value, setting_type) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $setting['key'], $setting['value'], $setting['type']);
        mysqli_stmt_execute($stmt);
        echo '<div class="success">✅ Added setting: ' . htmlspecialchars($setting['key']) . '</div>';
    } else {
        echo '<div class="info">ℹ️ Setting exists: ' . htmlspecialchars($setting['key']) . '</div>';
    }
}

// ============================================
// SUMMARY
// ============================================
echo '<h2>📊 Summary</h2>';

$tables = ['categories', 'products', 'admins', 'customers', 'orders', 'settings'];
echo '<table border="1" cellpadding="10" style="border-collapse: collapse; width: 100%;">';
echo '<tr><th>Table</th><th>Row Count</th></tr>';

foreach ($tables as $table) {
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM $table");
    $row = mysqli_fetch_assoc($result);
    echo '<tr><td>' . $table . '</td><td>' . $row['count'] . '</td></tr>';
}

echo '</table>';

mysqli_close($conn);

echo '<div class="success">';
echo '<h3>✅ Migration Completed Successfully!</h3>';
echo '<p>Your database is now ready to use.</p>';
echo '</div>';

echo '<p>';
echo '<a href="admin/" class="btn">Go to Admin Panel</a>';
echo '<a href="../" class="btn" style="background: #28a745;">View Website</a>';
echo '</p>';

echo '<div class="warning">';
echo '<h3>⚠️ IMPORTANT - Clean Up</h3>';
echo '<p>Delete these files from your server:</p>';
echo '<ul>';
echo '<li>migrate-data.php (this file)</li>';
echo '<li>setup-admin.php</li>';
echo '<li>create-admin.php</li>';
echo '<li>test-db.php</li>';
echo '</ul>';
echo '</div>';
?>

</body>
</html>
