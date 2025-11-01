# Admin Panel Database Schema Fixes

## Problem

The admin panel code was written for a different database schema than what actually exists.

## Database Schema (ACTUAL)

```sql
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    brand VARCHAR(100) NOT NULL,           -- TEXT, not ID!
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    discount_price DECIMAL(10,2) DEFAULT NULL,
    image VARCHAR(255),                    -- NOT "main_image"!
    model_3d VARCHAR(255),
    stock INT DEFAULT 0,                    -- NOT "stock_quantity"!
    is_featured BOOLEAN DEFAULT 0,
    is_active BOOLEAN DEFAULT 1,
    sku VARCHAR(50) UNIQUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);
```

## What Admin Code Was Expecting

- `brand_id` (INT) → **Actually `brand` (VARCHAR)**
- `stock_quantity` (INT) → **Actually `stock` (INT)**
- `main_image` (VARCHAR) → **Actually `image` (VARCHAR)**
- `specifications` (TEXT) → **Does NOT exist**

## Files That Need Fixing

- ✅ `/admin/delete-product.php` - FIXED (changed main_image → image)
- ⚠️ `/admin/add-product.php` - NEEDS FIX
- ⚠️ `/admin/edit-product.php` - NEEDS FIX
- ⚠️ `/admin/products.php` - NEEDS FIX

## Changes Required

### 1. Remove `brand_id` references
- Change to use `brand` as text input (VARCHAR)
- Remove brand table queries

### 2. Change `stock_quantity` → `stock`
- Update all queries and form fields

### 3. Change `main_image` → `image`
- Update variable names in code
- Update SQL queries

### 4. Remove `specifications` field
- This column doesn't exist in database
- Remove from forms and queries

## Quick Fix Script

Run this to fix the admin files:

```bash
cd /home/believer/auravibe/admin

# Backup originals
cp add-product.php add-product.php.backup
cp edit-product.php edit-product.php.backup
cp products.php products.php.backup
```

Then I'll update the files manually to match the actual schema.
