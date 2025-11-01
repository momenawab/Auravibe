-- Create Shipping Costs Table
-- Run this to enable dynamic shipping cost management from admin panel

USE u446437128_auravibe;

-- Create shipping_costs table
CREATE TABLE IF NOT EXISTS shipping_costs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    governorate VARCHAR(100) NOT NULL UNIQUE COMMENT 'Egyptian Governorate Name',
    cost DECIMAL(10, 2) NOT NULL DEFAULT 0 COMMENT 'Shipping cost in EGP',
    region VARCHAR(100) NULL COMMENT 'Region group (Cairo & Giza, Delta, etc.)',
    is_active TINYINT(1) DEFAULT 1 COMMENT '1 = active, 0 = disabled',
    display_order INT DEFAULT 0 COMMENT 'Order for dropdown display',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_governorate (governorate),
    INDEX idx_is_active (is_active),
    INDEX idx_display_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default shipping costs for all Egyptian governorates
INSERT INTO shipping_costs (governorate, cost, region, display_order) VALUES
-- Cairo & Giza Region
('Cairo', 50, 'Cairo & Giza', 1),
('Giza', 0, 'Cairo & Giza', 2),
('6th of October', 0, 'Cairo & Giza', 3),
('New Cairo', 0, 'Cairo & Giza', 4),
('Heliopolis', 0, 'Cairo & Giza', 5),
('Maadi', 0, 'Cairo & Giza', 6),
('Nasr City', 0, 'Cairo & Giza', 7),
('Zamalek', 0, 'Cairo & Giza', 8),

-- Alexandria
('Alexandria', 50, 'Alexandria', 10),

-- Delta Governorates
('Qalyubia', 40, 'Delta Governorates', 20),
('Monufia', 50, 'Delta Governorates', 21),
('Dakahlia', 60, 'Delta Governorates', 22),
('Sharqia', 60, 'Delta Governorates', 23),
('Gharbia', 60, 'Delta Governorates', 24),
('Damietta', 70, 'Delta Governorates', 25),
('Kafr El Sheikh', 70, 'Delta Governorates', 26),
('Beheira', 70, 'Delta Governorates', 27),
('Ismailia', 80, 'Delta Governorates', 28),
('Port Said', 80, 'Delta Governorates', 29),
('Suez', 80, 'Delta Governorates', 30),

-- Upper Egypt
('Faiyum', 70, 'Upper Egypt', 40),
('Beni Suef', 80, 'Upper Egypt', 41),
('Minya', 90, 'Upper Egypt', 42),
('Asyut', 100, 'Upper Egypt', 43),
('Sohag', 110, 'Upper Egypt', 44),
('Qena', 120, 'Upper Egypt', 45),
('Luxor', 120, 'Upper Egypt', 46),
('Aswan', 130, 'Upper Egypt', 47),

-- Sinai & Red Sea
('Red Sea', 120, 'Sinai & Red Sea', 50),
('South Sinai', 140, 'Sinai & Red Sea', 51),
('North Sinai', 150, 'Sinai & Red Sea', 52),

-- Border Governorates
('Matrouh', 150, 'Border Governorates', 60),
('New Valley', 160, 'Border Governorates', 61)

ON DUPLICATE KEY UPDATE cost = VALUES(cost), region = VALUES(region);

-- Display success message
SELECT 'Shipping costs table created and populated successfully!' AS message;
SELECT COUNT(*) AS total_governorates FROM shipping_costs;
