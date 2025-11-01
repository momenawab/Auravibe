-- Migration: Add governorate field and update orders table structure
-- Date: 2025-10-26
-- Description: Updates orders table to use Egyptian governorates instead of generic state/country

USE u446437128_auravibe;

-- Check if orders table exists and modify it
ALTER TABLE orders
  -- Rename state column to governorate (if state exists)
  CHANGE COLUMN `state` `governorate` VARCHAR(100) DEFAULT NULL COMMENT 'Egyptian Governorate',

  -- Update country column to default 'EG' for Egypt
  MODIFY COLUMN `country` VARCHAR(2) DEFAULT 'EG' COMMENT 'Country code (EG for Egypt)',

  -- Remove shipping_method column if it exists (not needed anymore)
  DROP COLUMN IF EXISTS `shipping_method`;

-- Update existing records to set country to 'EG' if empty
UPDATE orders SET country = 'EG' WHERE country IS NULL OR country = '';

-- Create index on governorate for faster queries
CREATE INDEX idx_governorate ON orders(governorate);

-- Display success message
SELECT 'Migration completed: Orders table updated with governorate field' AS message;
