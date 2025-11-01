<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

require_once '../../includes/db.php';
require_once '../../includes/functions.php';

$page_title = "Category Management";

// Initialize variables
$categories = [];
$success_message = '';
$error_message = '';
$edit_mode = false;
$edit_category = null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (can_query()) {
        try {
            if (isset($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'add':
                        $name = trim($_POST['category_name']);
                        $slug = trim($_POST['category_slug']);
                        $description = trim($_POST['category_description']);

                        if (!empty($name) && !empty($slug)) {
                            // Check if slug already exists
                            $check_stmt = $conn->prepare("SELECT id FROM categories WHERE slug = ?");
                            $check_stmt->bind_param("s", $slug);
                            $check_stmt->execute();
                            $check_result = $check_stmt->get_result();

                            if ($check_result->num_rows > 0) {
                                $error_message = "A category with this slug already exists.";
                                $check_stmt->close();
                            } else {
                                $check_stmt->close();

                                $stmt = $conn->prepare("INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)");
                                $stmt->bind_param("sss", $name, $slug, $description);

                                if ($stmt->execute()) {
                                    $success_message = "Category added successfully!";
                                } else {
                                    $error_message = "Failed to add category.";
                                }
                                $stmt->close();
                            }
                        } else {
                            $error_message = "Category name and slug are required.";
                        }
                        break;

                    case 'edit':
                        $id = intval($_POST['category_id']);
                        $name = trim($_POST['category_name']);
                        $slug = trim($_POST['category_slug']);
                        $description = trim($_POST['category_description']);

                        if (!empty($name) && !empty($slug) && $id > 0) {
                            // Check if slug already exists for other categories
                            $check_stmt = $conn->prepare("SELECT id FROM categories WHERE slug = ? AND id != ?");
                            $check_stmt->bind_param("si", $slug, $id);
                            $check_stmt->execute();
                            $check_result = $check_stmt->get_result();

                            if ($check_result->num_rows > 0) {
                                $error_message = "A category with this slug already exists.";
                                $check_stmt->close();
                            } else {
                                $check_stmt->close();

                                $stmt = $conn->prepare("UPDATE categories SET name = ?, slug = ?, description = ? WHERE id = ?");
                                $stmt->bind_param("sssi", $name, $slug, $description, $id);

                                if ($stmt->execute()) {
                                    $success_message = "Category updated successfully!";
                                } else {
                                    $error_message = "Failed to update category.";
                                }
                                $stmt->close();
                            }
                        } else {
                            $error_message = "Invalid category data.";
                        }
                        break;

                    case 'delete':
                        $id = intval($_POST['category_id']);

                        if ($id > 0) {
                            // Check if category has products
                            $check_stmt = $conn->prepare("SELECT COUNT(*) as count FROM products WHERE category_id = ?");
                            $check_stmt->bind_param("i", $id);
                            $check_stmt->execute();
                            $check_result = $check_stmt->get_result();
                            $check_row = $check_result->fetch_assoc();
                            $check_stmt->close();

                            if ($check_row['count'] > 0) {
                                $error_message = "Cannot delete category with existing products.";
                            } else {
                                $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
                                $stmt->bind_param("i", $id);

                                if ($stmt->execute()) {
                                    $success_message = "Category deleted successfully!";
                                } else {
                                    $error_message = "Failed to delete category.";
                                }
                                $stmt->close();
                            }
                        }
                        break;
                }
            }
        } catch (Exception $e) {
            $error_message = "Database error: " . $e->getMessage();
        }
    } else {
        $error_message = "Database not available.";
    }
}

// Handle edit request
if (isset($_GET['edit']) && can_query()) {
    $edit_id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $edit_category = $result->fetch_assoc();
        $edit_mode = true;
    }
    $stmt->close();
}

// Fetch categories
if (can_query()) {
    try {
        $sql = "SELECT
                    c.id,
                    c.name,
                    c.slug,
                    c.description,
                    COUNT(p.id) as product_count
                FROM categories c
                LEFT JOIN products p ON c.id = p.category_id
                GROUP BY c.id, c.name, c.slug, c.description
                ORDER BY c.name ASC";

        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    } catch (Exception $e) {
        $error_message = "Error loading categories: " . $e->getMessage();
    }
}
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
    <style>
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
            margin-top: 20px;
        }

        @media (max-width: 1200px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        .form-section {
            background: linear-gradient(135deg, #1a1a1a, #2d2d2d);
            padding: 30px;
            border-radius: 12px;
            border: 1px solid #333;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        .form-section h2 {
            color: #D4AF37;
            font-size: 24px;
            margin-bottom: 25px;
            font-family: 'Playfair Display', serif;
            border-bottom: 2px solid #D4AF37;
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #D4AF37;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            background: #0a0a0a;
            border: 1px solid #444;
            border-radius: 6px;
            color: #fff;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #D4AF37;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
            font-family: 'Montserrat', sans-serif;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            display: inline-block;
        }

        .btn-gold {
            background: linear-gradient(135deg, #D4AF37, #B8941F);
            color: #000;
        }

        .btn-gold:hover {
            background: linear-gradient(135deg, #F4E4B8, #D4AF37);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.4);
        }

        .btn-secondary {
            background: #333;
            color: #fff;
            margin-left: 10px;
        }

        .btn-secondary:hover {
            background: #444;
        }

        .categories-section {
            background: linear-gradient(135deg, #1a1a1a, #2d2d2d);
            border-radius: 12px;
            border: 1px solid #333;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .section-header {
            background: linear-gradient(135deg, #2d2d2d, #1a1a1a);
            padding: 20px 30px;
            border-bottom: 2px solid #D4AF37;
        }

        .section-header h2 {
            color: #D4AF37;
            font-size: 24px;
            margin: 0;
            font-family: 'Playfair Display', serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead tr {
            background: #0a0a0a;
        }

        table th {
            padding: 15px 20px;
            text-align: left;
            color: #D4AF37;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table td {
            padding: 15px 20px;
            color: #ccc;
            border-top: 1px solid #333;
        }

        table tbody tr {
            transition: background 0.2s ease;
        }

        table tbody tr:hover {
            background: rgba(212, 175, 55, 0.05);
        }

        .category-name {
            font-weight: 600;
            color: #fff;
            font-size: 15px;
            margin-bottom: 5px;
        }

        .category-description {
            color: #999;
            font-size: 13px;
            line-height: 1.5;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            background: rgba(212, 175, 55, 0.1);
            color: #D4AF37;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid rgba(212, 175, 55, 0.3);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-small {
            padding: 8px 16px;
            font-size: 12px;
        }

        .btn-edit {
            background: #2563eb;
            color: #fff;
        }

        .btn-edit:hover {
            background: #1e40af;
        }

        .btn-delete {
            background: #dc2626;
            color: #fff;
        }

        .btn-delete:hover {
            background: #b91c1c;
        }

        .no-results {
            padding: 60px 30px;
            text-align: center;
            color: #999;
            font-size: 15px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #22c55e;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }
    </style>
</head>
<body class="admin-body">
<div class="admin-layout">
    <?php include 'includes/sidebar.php'; ?>

    <div class="admin-main">
        <header class="admin-header">
            <h1><?php echo $page_title; ?></h1>
            <div class="admin-user">
                <span><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <i class="fas fa-user-circle"></i>
            </div>
        </header>

        <div class="admin-content">
            <div style="margin-bottom: 20px;">
                <p style="color: #ccc; font-size: 14px;">Manage product categories</p>
            </div>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <div class="content-grid">
            <div class="form-section">
                <h2><?php echo $edit_mode ? 'Edit Category' : 'Add New Category'; ?></h2>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="<?php echo $edit_mode ? 'edit' : 'add'; ?>">
                    <?php if ($edit_mode): ?>
                        <input type="hidden" name="category_id" value="<?php echo $edit_category['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="category_name">Category Name *</label>
                        <input
                            type="text"
                            id="category_name"
                            name="category_name"
                            required
                            value="<?php echo $edit_mode ? htmlspecialchars($edit_category['name']) : ''; ?>"
                            placeholder="e.g., Luxury Watches">
                    </div>

                    <div class="form-group">
                        <label for="category_slug">Slug *</label>
                        <input
                            type="text"
                            id="category_slug"
                            name="category_slug"
                            required
                            value="<?php echo $edit_mode ? htmlspecialchars($edit_category['slug']) : ''; ?>"
                            placeholder="e.g., luxury-watches">
                    </div>

                    <div class="form-group">
                        <label for="category_description">Description</label>
                        <textarea
                            id="category_description"
                            name="category_description"
                            placeholder="Brief description of the category..."><?php echo $edit_mode ? htmlspecialchars($edit_category['description']) : ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-gold">
                            <?php echo $edit_mode ? 'Update Category' : 'Add Category'; ?>
                        </button>
                        <?php if ($edit_mode): ?>
                            <a href="categories.php" class="btn btn-secondary">Cancel</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="categories-section">
                <div class="section-header">
                    <h2>All Categories</h2>
                </div>
                
                <?php if (count($categories) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Category</th>
                                <th>Products</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($category['id']); ?></td>
                                    <td>
                                        <div class="category-name"><?php echo htmlspecialchars($category['name']); ?></div>
                                        <?php if (!empty($category['description'])): ?>
                                            <div class="category-description">
                                                <?php echo htmlspecialchars($category['description']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge">
                                            <?php echo $category['product_count']; ?> products
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="?edit=<?php echo $category['id']; ?>" class="btn btn-edit btn-small">
                                                Edit
                                            </a>
                                            <form method="POST" action="" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                                                <button type="submit" class="btn btn-delete btn-small">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-results">
                        <?php if (can_query()): ?>
                            No categories found. Add your first category using the form.
                        <?php else: ?>
                            Database connection error. Please check your database settings.
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
