-- Create Orders and Order Items Tables
-- Run this to set up the complete order system

USE u446437128_auravibe;

-- Drop existing tables if they exist (be careful with this in production!)
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;

-- Create orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(50) NOT NULL UNIQUE COMMENT 'Unique order identifier like ORD-123456-7890',
    user_id INT NULL COMMENT 'User ID if customer is logged in',

    -- Customer Information
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,

    -- Shipping Address
    address VARCHAR(255) NOT NULL,
    address2 VARCHAR(255) NULL,
    city VARCHAR(100) NOT NULL,
    governorate VARCHAR(100) NOT NULL COMMENT 'Egyptian Governorate',
    zip VARCHAR(20) NULL,
    country VARCHAR(2) DEFAULT 'EG',

    -- Order Details
    payment_method VARCHAR(50) NOT NULL COMMENT 'online_card, mobile_wallet, cash_on_delivery',
    subtotal DECIMAL(10, 2) NOT NULL,
    shipping DECIMAL(10, 2) DEFAULT 0,
    tax DECIMAL(10, 2) DEFAULT 0,
    total DECIMAL(10, 2) NOT NULL,

    -- Payment Information
    payment_status VARCHAR(50) DEFAULT 'pending' COMMENT 'pending, paid, failed',
    transaction_id VARCHAR(100) NULL COMMENT 'Paymob transaction ID',
    payment_response TEXT NULL COMMENT 'Full payment callback response',

    -- Order Status
    status VARCHAR(50) DEFAULT 'pending' COMMENT 'pending, confirmed, processing, shipped, delivered, cancelled, payment_failed',
    order_notes TEXT NULL,

    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Indexes
    INDEX idx_order_id (order_id),
    INDEX idx_email (email),
    INDEX idx_status (status),
    INDEX idx_payment_status (payment_status),
    INDEX idx_governorate (governorate),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create order_items table
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

    -- Foreign key constraint
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,

    -- Indexes
    INDEX idx_order_id (order_id),
    INDEX idx_product_id (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Display success message
SELECT 'Orders tables created successfully!' AS message;
SELECT 'Tables created: orders, order_items' AS info;
