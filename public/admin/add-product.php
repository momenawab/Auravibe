<?php
session_start();
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$page_title = 'Manage Products';

// Initialize variables
$success = '';
$error = '';
$categories = [];
$brands = [];

// Check if database is available
if (!can_query() || $conn === null) {
    $error = "Database connection error. Please check your database configuration.";
} else {
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $description = sanitize($_POST['description']);
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    // Generate slug from product name
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

    $image = '';

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
                $image = 'uploads/products/' . $new_filename;  // Store relative path
            } else {
                $error = "Failed to upload image.";
            }
        }
    }

    // Insert into database if no errors
    if (empty($error)) {
        // Note: Using actual database columns: brand (not brand_id), stock (not stock_quantity), image (not main_image)
        // Removed specifications as it doesn't exist in schema
        $brand = isset($_POST['brand']) ? sanitize($_POST['brand']) : '';
        $stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;

        $query = "INSERT INTO products (name, slug, brand, description, price, category_id, stock, is_featured, image, created_at)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssssdiiis", $name, $slug, $brand, $description, $price, $category_id, $stock, $is_featured, $image);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Product added successfully!";
            header('Location: products.php');
            exit;
        } else {
            $error = "Failed to add product: " . mysqli_error($conn);
            // Delete uploaded image if database insert failed
            if (!empty($image) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $image)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $image);
            }
        }
    }
}

// Get categories from database (only if database is available)
if (can_query() && $conn !== null) {
    $cat_result = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");
    if ($cat_result) {
        while ($row = mysqli_fetch_assoc($cat_result)) {
            $categories[] = $row;
        }
    }
    // Note: brands is a text field in products table, not a separate table
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Aura Vibe Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-body">

<div class="admin-layout">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="admin-main">
        <!-- Header -->
        <header class="admin-header">
            <h1>Add New Product</h1>
            <div class="admin-user">
                <span><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <i class="fas fa-user-circle"></i>
            </div>
        </header>

        <!-- Content -->
        <div class="admin-content">
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <form method="POST" action="" enctype="multipart/form-data" class="product-form">
                    <div class="form-grid">
                        <!-- Left Column -->
                        <div class="form-column">
                            <h3>Basic Information</h3>

                            <div class="form-group">
                                <label>Product Name *</label>
                                <input type="text" name="name" required placeholder="e.g., Royal Gold Chronograph">
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Category *</label>
                                    <select name="category_id" required>
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Brand *</label>
                                    <input type="text" name="brand" required placeholder="e.g., Rolex, Omega, Prestige">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Price ($) *</label>
                                    <input type="number" name="price" step="0.01" required placeholder="0.00">
                                </div>

                                <div class="form-group">
                                    <label>Stock Quantity *</label>
                                    <input type="number" name="stock" required placeholder="0" min="0">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Description *</label>
                                <textarea name="description" rows="6" required placeholder="Enter product description..."></textarea>
                            </div>

                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="is_featured">
                                    <span>Featured Product</span>
                                </label>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="form-column">
                            <h3>Product Images</h3>

                            <div class="form-group">
                                <label>Main Image</label>
                                <div class="image-upload">
                                    <input type="file" name="image" id="mainImage" accept="image/*">
                                    <label for="mainImage" class="upload-label">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Click to upload or drag and drop</span>
                                        <small>PNG, JPG up to 5MB</small>
                                    </label>
                                    <div id="mainPreview" class="image-preview"></div>
                                </div>
                            </div>

                            <!-- Specifications removed - column doesn't exist in database -->
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="products.php" class="btn btn-outline">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Product
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
