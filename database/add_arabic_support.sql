-- Add Arabic Language Support to AuraVibe Database
-- This script adds Arabic name and description columns to products and categories

USE u446437128_auravibe;

-- Add Arabic columns to categories table
ALTER TABLE categories
ADD COLUMN IF NOT EXISTS name_ar VARCHAR(255) NULL AFTER name,
ADD COLUMN IF NOT EXISTS description_ar TEXT NULL AFTER description;

-- Add Arabic columns to products table
ALTER TABLE products
ADD COLUMN IF NOT EXISTS name_ar VARCHAR(255) NULL AFTER name,
ADD COLUMN IF NOT EXISTS description_ar TEXT NULL AFTER description;

-- Update existing categories with sample Arabic names
UPDATE categories SET name_ar = 'ساعات فاخرة' WHERE name = 'Luxury Watches';
UPDATE categories SET name_ar = 'ساعات رجالية' WHERE name = 'Men\'s Watches';
UPDATE categories SET name_ar = 'ساعات نسائية' WHERE name = 'Women\'s Watches';
UPDATE categories SET name_ar = 'ساعات ذكية' WHERE name = 'Smart Watches';
UPDATE categories SET name_ar = 'ساعات رياضية' WHERE name = 'Sports Watches';
UPDATE categories SET name_ar = 'كرونوغراف' WHERE name = 'Chronograph';
UPDATE categories SET name_ar = 'ساعات غواص' WHERE name = 'Diver';
UPDATE categories SET name_ar = 'ساعات كلاسيكية' WHERE name = 'Classic';

-- Sample update for products (you'll need to customize these based on your actual products)
-- UPDATE products SET name_ar = 'كرونوغراف الذهبي الملكي', description_ar = 'ساعة فاخرة بتصميم كلاسيكي' WHERE id = 1;

-- Display success message
SELECT 'Arabic language support added successfully!' AS message;
SELECT 'Tables updated: categories, products' AS info;
SELECT 'Next steps:' AS todo;
SELECT '1. Update product Arabic names via admin panel or manually' AS step1;
SELECT '2. Update category descriptions in Arabic' AS step2;
SELECT '3. Test the language switcher on the website' AS step3;
