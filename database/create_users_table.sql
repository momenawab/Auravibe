-- Create Users/Admin Table
-- Run this to enable admin login functionality

USE u446437128_auravibe;

-- Create users table for admin authentication
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL COMMENT 'Hashed password',
    full_name VARCHAR(100) NULL,
    role VARCHAR(20) DEFAULT 'admin' COMMENT 'admin, staff, etc',
    is_active TINYINT(1) DEFAULT 1,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user
-- Username: admin
-- Password: admin123 (CHANGE THIS IMMEDIATELY!)
INSERT INTO users (username, email, password, full_name, role) VALUES
('admin', 'admin@auravibe.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin')
ON DUPLICATE KEY UPDATE username = username;

-- Display success message
SELECT 'Users table created successfully!' AS message;
SELECT 'Default admin user created' AS info;
SELECT 'Username: admin' AS username;
SELECT 'Password: admin123 (PLEASE CHANGE!)' AS password;
