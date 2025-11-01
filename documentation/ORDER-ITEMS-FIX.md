# Order Items Table Fix

## Problem
Error when processing orders:
```
Uncaught mysqli_sql_exception: Unknown column 'subtotal' in 'field list' 
in /home/believer/auravibe/public/process-order.php:85
```

## Root Cause
The `order_items` table was missing the `subtotal` column. The table was likely created from an older SQL file or incomplete migration.

## Solution Applied
Added the missing `subtotal` column to the `order_items` table:

```sql
ALTER TABLE order_items ADD COLUMN subtotal DECIMAL(10, 2) NOT NULL AFTER quantity;
```

Also calculated subtotal for 12 existing records:
```sql
UPDATE order_items SET subtotal = price * quantity WHERE subtotal = 0;
```

## Files Involved
- **public/process-order.php** (line 85) - SQL INSERT statement using subtotal column
- **database/create_orders_tables.sql** - Contains correct schema with subtotal
- **fix-order-items-table.php** - Auto-fix script that adds missing columns

## Current Table Structure
```
order_items:
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- order_id (INT, FK to orders.id)
- product_id (INT)
- product_name (VARCHAR(255))
- brand (VARCHAR(100))
- price (DECIMAL(10,2))
- quantity (INT)
- subtotal (DECIMAL(10,2)) ← NOW ADDED
- created_at (TIMESTAMP)
```

## For Deployment
If you encounter this error on Hostinger:

1. Upload `fix-order-items-table.php` to public_html
2. Access it via browser: `https://auravibe.site/fix-order-items-table.php`
3. The script will automatically add missing columns
4. Delete the script after running

OR manually run this SQL in phpMyAdmin:
```sql
ALTER TABLE order_items ADD COLUMN subtotal DECIMAL(10, 2) NOT NULL DEFAULT 0 AFTER quantity;
UPDATE order_items SET subtotal = price * quantity;
```

## Status
✅ **FIXED** - Orders can now be processed successfully
