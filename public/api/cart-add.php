<?php
/**
 * Add to Cart API Endpoint
 */

require_once __DIR__ . '/../config-paths.php';

header('Content-Type: application/json');

// Get POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$product_id = isset($data['product_id']) ? intval($data['product_id']) : 0;
$quantity = isset($data['quantity']) ? intval($data['quantity']) : 1;

// Validate product ID
if ($product_id <= 0) {
    echo json_encode([
        'success' => false,
        'message' => is_rtl() ? 'معرف المنتج غير صحيح' : 'Invalid product ID'
    ]);
    exit;
}

// Get product details from database
if (can_query()) {
    $stmt = mysqli_prepare($conn, "SELECT id, name, name_ar, price, stock, image, brand FROM products WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);

    if (!$product) {
        echo json_encode([
            'success' => false,
            'message' => is_rtl() ? 'المنتج غير موجود' : 'Product not found'
        ]);
        exit;
    }

    // Check stock
    if ($product['stock'] <= 0) {
        echo json_encode([
            'success' => false,
            'message' => is_rtl() ? 'المنتج غير متوفر في المخزون' : 'Product out of stock'
        ]);
        exit;
    }

    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if product already in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // Check if adding more doesn't exceed stock
            if ($item['quantity'] + $quantity <= $product['stock']) {
                $item['quantity'] += $quantity;
                $found = true;
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => is_rtl() ? 'الكمية المطلوبة تتجاوز المتوفر في المخزون' : 'Requested quantity exceeds available stock'
                ]);
                exit;
            }
            break;
        }
    }

    // If not found, add new item
    if (!$found) {
        if ($quantity <= $product['stock']) {
            $_SESSION['cart'][] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'name_ar' => $product['name_ar'] ?? null,
                'brand' => $product['brand'] ?? '',
                'price' => $product['price'],
                'quantity' => $quantity,
                'image' => $product['image']
            ];
        } else {
            echo json_encode([
                'success' => false,
                'message' => is_rtl() ? 'الكمية المطلوبة تتجاوز المتوفر في المخزون' : 'Requested quantity exceeds available stock'
            ]);
            exit;
        }
    }

    // Calculate cart count
    $cart_count = 0;
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['quantity'];
    }

    $product_name = is_rtl() && !empty($product['name_ar']) ? $product['name_ar'] : $product['name'];

    echo json_encode([
        'success' => true,
        'message' => is_rtl() ? "تم إضافة {$product_name} إلى السلة" : "{$product_name} added to cart",
        'cart_count' => $cart_count,
        'cart' => $_SESSION['cart']
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => is_rtl() ? 'خطأ في الاتصال بقاعدة البيانات' : 'Database connection error'
    ]);
}
