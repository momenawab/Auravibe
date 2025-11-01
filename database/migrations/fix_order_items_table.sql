-- ===================================
-- Fix order_items table structure
-- Add brand column if it doesn't exist
-- ===================================

-- Check if brand column exists, if not add it
ALTER TABLE order_items 
ADD COLUMN IF NOT EXISTS brand VARCHAR(100) NULL AFTER product_name;

-- If you're getting "Unknown column 'subtotal'" error, 
-- your table might have been created with different column names.
-- This will standardize the table structure.

-- Note: MySQL doesn't support "IF NOT EXISTS" for ADD COLUMN in older versions
-- If you get an error, the column already exists - you can ignore it.

-- Alternative: Drop and recreate (USE WITH CAUTION - WILL DELETE DATA!)
-- Uncomment the lines below ONLY if you want to recreate the table from scratch

/*
DROP TABLE IF EXISTS order_items;

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL COMMENT 'Foreign key to orders.id',
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    brand VARCHAR(100) NULL,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    subtotal DECIMAL(10, 2) NOT NULL COMMENT 'Calculated as price * quantity',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    
    INDEX idx_order_id (order_id),
    INDEX idx_product_id (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
*/
