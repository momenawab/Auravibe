<?php
session_start();
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Check if database is available
if (!can_query() || $conn === null) {
    $_SESSION['error'] = "Database connection not available.";
    header('Location: products.php');
    exit;
}

// Get product ID
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    $_SESSION['error'] = "Invalid product ID.";
    header('Location: products.php');
    exit;
}

// Get product details (to delete associated image)
$query = "SELECT image FROM products WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    $_SESSION['error'] = "Product not found.";
    header('Location: products.php');
    exit;
}

// Delete product from database
$delete_query = "DELETE FROM products WHERE id = ?";
$delete_stmt = mysqli_prepare($conn, $delete_query);
mysqli_stmt_bind_param($delete_stmt, "i", $product_id);

if (mysqli_stmt_execute($delete_stmt)) {
    // Delete associated image file if it exists
    if (!empty($product['image'])) {
        $image_path = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($product['image'], '/');
        if (file_exists($image_path)) {
            @unlink($image_path);
        }
    }

    $_SESSION['success'] = "Product deleted successfully.";
} else {
    $_SESSION['error'] = "Failed to delete product: " . mysqli_error($conn);
}

header('Location: products.php');
exit;
?>
