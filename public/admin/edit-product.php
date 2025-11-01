<?php
session_start();
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$success = '';
$error = '';
$product = null;

// Check if database is available
if (!can_query() || $conn === null) {
    $error = "Database connection error. Please check your database configuration.";
} else {
    // Validate product ID
    if ($product_id <= 0) {
        header('Location: products.php');
        exit;
    }

    // Get product data
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);

    // Show 404 if product not found
    if (!$product) {
        $_SESSION['error'] = "Product not found.";
        header('Location: products.php');
        exit;
    }

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $description = sanitize($_POST['description']);
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $brand = sanitize($_POST['brand']); // Brand is text, not integer
    $stock = intval($_POST['stock']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    // Generate slug from product name
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

    $image = $product['image']; // Keep existing image by default

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
        $file_name = $file['name'];
        $file_size = $file['size'];
        $file_tmp = $file['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Validate file type
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($file_ext, $allowed_extensions)) {
            $error = "Invalid file type. Only JPG, JPEG, PNG, and WEBP files are allowed.";
        }
        // Validate file size (5MB max)
        elseif ($file_size > 5242880) {
            $error = "File size exceeds 5MB limit.";
        }
        else {
            // Create upload directory if it doesn't exist
            $upload_dir = dirname(__DIR__) . '/uploads/products/'; // Use dirname(__DIR__) to get /public/ directory
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Generate unique filename
            $new_filename = uniqid('product_') . '_' . time() . '.' . $file_ext;
            $upload_path = $upload_dir . $new_filename;

            // Move uploaded file
            if (move_uploaded_file($file_tmp, $upload_path)) {
                // Delete old image if exists
                if (!empty($product['image'])) {
                    $old_image_path = dirname(__DIR__) . '/' . $product['image'];
                    if (file_exists($old_image_path)) {
                        @unlink($old_image_path);
                    }
                }
                $image = 'uploads/products/' . $new_filename;  // Store relative path (consistent with add-product.php)
            } else {
                $error = "Failed to upload image.";
            }
        }
    }

    // Update database if no errors
    if (empty($error)) {
        $query = "UPDATE products SET name=?, slug=?, description=?, price=?, category_id=?, brand=?, stock=?, is_featured=?, image=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssdisiisi", $name, $slug, $description, $price, $category_id, $brand, $stock, $is_featured, $image, $product_id);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Product updated successfully!";
            // Reload product data to show updated values
            $query = "SELECT * FROM products WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $product_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $product = mysqli_fetch_assoc($result);
        } else {
            $error = "Failed to update product: " . mysqli_error($conn);
        }
    }
}

// Get categories from database (only if database is available)
$categories = [];

if (can_query() && $conn !== null) {
    $cat_result = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");
    if ($cat_result) {
        while ($row = mysqli_fetch_assoc($cat_result)) {
            $categories[] = $row;
        }
    }
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Aura Vibe Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-body">

<div class="admin-layout">
    <?php include 'includes/sidebar.php'; ?>

    <div class="admin-main">
        <header class="admin-header">
            <h1>Edit Product</h1>
            <div class="admin-user">
                <span><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <i class="fas fa-user-circle"></i>
            </div>
        </header>

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

            <div class="card">
                <form method="POST" action="" enctype="multipart/form-data" class="product-form">
                    <div class="form-grid">
                        <div class="form-column">
                            <h3>Basic Information</h3>

                            <div class="form-group">
                                <label>Product Name *</label>
                                <input type="text" name="name" required value="<?php echo htmlspecialchars($product['name']); ?>">
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Category *</label>
                                    <select name="category_id" required>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $product['category_id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($cat['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Brand *</label>
                                    <input type="text" name="brand" required value="<?php echo htmlspecialchars($product['brand']); ?>" placeholder="e.g., Rolex, Omega, Prestige">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Price ($) *</label>
                                    <input type="number" name="price" step="0.01" required value="<?php echo $product['price']; ?>">
                                </div>

                                <div class="form-group">
                                    <label>Stock Quantity *</label>
                                    <input type="number" name="stock" required value="<?php echo $product['stock']; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Description *</label>
                                <textarea name="description" rows="6" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="is_featured" <?php echo $product['is_featured'] ? 'checked' : ''; ?>>
                                    <span>Featured Product</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-column">
                            <h3>Product Images</h3>

                            <div class="form-group">
                                <label>Main Image</label>
                                <?php if (!empty($product['image'])): ?>
                                    <div class="current-image" style="margin-bottom: 10px;">
                                        <img src="<?php echo base_url($product['image']); ?>" alt="Current product image" style="max-width: 200px; border: 2px solid #ddd; border-radius: 8px;">
                                        <p style="color: #666; font-size: 14px; margin-top: 5px;">Current image</p>
                                    </div>
                                <?php endif; ?>
                                <div class="image-upload">
                                    <input type="file" name="image" id="mainImage" accept="image/*">
                                    <label for="mainImage" class="upload-label">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Click to upload new image or drag and drop</span>
                                        <small>PNG, JPG, JPEG, WEBP up to 5MB</small>
                                    </label>
                                    <div id="mainPreview" class="image-preview"></div>
                                </div>
                            </div>

                            <!-- Specifications section removed - column doesn't exist in database -->
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="products.php" class="btn btn-outline">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Image preview
document.getElementById('mainImage').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('mainPreview').innerHTML =
                '<img src="' + e.target.result + '" alt="Preview">';
        };
        reader.readAsDataURL(file);
    }
});
</script>

</body>
</html>
