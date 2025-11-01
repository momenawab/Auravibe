<?php
/**
 * Fix Product Image Paths
 * This script updates old image paths to store only filenames
 */

require_once '../../includes/db.php';

if (!can_query() || $conn === null) {
    die("Database connection error. Please check your database configuration.");
}

echo "<h2>Fixing Product Image Paths</h2>";
echo "<p>Updating database to store only filenames instead of full paths...</p>";

// Get all products
$query = "SELECT id, name, image FROM products WHERE image IS NOT NULL AND image != ''";
$result = mysqli_query($conn, $query);

$updated = 0;
$skipped = 0;

if ($result) {
    while ($product = mysqli_fetch_assoc($result)) {
        $current_image = $product['image'];

        // Check if it has the old format (contains uploads/products/)
        if (strpos($current_image, 'uploads/products/') === 0) {
            // Extract just the filename
            $new_image = str_replace('uploads/products/', '', $current_image);

            // Update the database
            $update_query = "UPDATE products SET image = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($stmt, "si", $new_image, $product['id']);

            if (mysqli_stmt_execute($stmt)) {
                echo "✓ Updated: " . htmlspecialchars($product['name']) . " (ID: {$product['id']})<br>";
                echo "&nbsp;&nbsp;Old: {$current_image}<br>";
                echo "&nbsp;&nbsp;New: {$new_image}<br><br>";
                $updated++;
            } else {
                echo "✗ Failed to update: " . htmlspecialchars($product['name']) . "<br><br>";
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "⊘ Skipped (already correct): " . htmlspecialchars($product['name']) . " - {$current_image}<br>";
            $skipped++;
        }
    }

    echo "<hr>";
    echo "<h3>Summary:</h3>";
    echo "<p><strong>Updated:</strong> {$updated} products</p>";
    echo "<p><strong>Skipped:</strong> {$skipped} products (already correct)</p>";
    echo "<p><strong>Total:</strong> " . ($updated + $skipped) . " products processed</p>";

    echo "<br><p><a href='products.php' style='display: inline-block; padding: 10px 20px; background: #D4AF37; color: white; text-decoration: none; border-radius: 4px;'>Back to Products</a></p>";
} else {
    echo "<p style='color: red;'>Error querying database: " . mysqli_error($conn) . "</p>";
}
?>
