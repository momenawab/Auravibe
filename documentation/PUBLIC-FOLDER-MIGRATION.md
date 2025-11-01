# Public Folder Migration Guide

**Date:** October 25, 2025
**Status:** ✅ Complete

## What Changed

All public-facing PHP pages have been moved to the `/public` folder for better security and organization.

## New Structure

```
auravibe/
├── public/                    # ⭐ NEW - All public pages here
│   ├── assets/               # Static files (CSS, JS, images)
│   ├── uploads/              # User uploads
│   ├── index.php            # Homepage
│   ├── shop.php             # Shop page
│   ├── product.php          # Product details
│   ├── cart.php             # Shopping cart
│   ├── checkout.php         # Checkout
│   ├── process-order.php    # Order processing
│   ├── paymob-callback.php  # Payment callback
│   ├── order-success.php    # Order confirmation
│   ├── login.php            # Login
│   ├── register.php         # Registration
│   ├── about.php            # About page
│   ├── contact.php          # Contact page
│   ├── privacy.php          # Privacy policy
│   ├── terms.php            # Terms of service
│   ├── wishlist.php         # Wishlist
│   ├── account.php          # Account page
│   ├── config-paths.php     # Path configuration
│   └── .htaccess            # Security and routing rules
│
├── app/                      # Application classes
├── config/                   # Configuration files
├── includes/                 # View templates (header, footer, navbar)
├── admin/                    # Admin panel
├── database/                 # Database files
├── docs/                     # Documentation
├── scripts/                  # Utility scripts
├── temp/                     # Old files (backups)
│   ├── old-assets-backup/   # Backup of old assets
│   └── old-uploads-backup/  # Backup of old uploads
│
├── assets -> public/assets   # Symlink for compatibility
├── uploads -> public/uploads # Symlink for compatibility
├── bootstrap.php             # Application bootstrap
├── index.php                 # Redirects to /public
└── .env                      # Environment configuration
```

## Why This Change?

### 1. **Security** 🔐
- Application code (app/, config/, database/) is outside the web root
- Sensitive files (.env, configs) cannot be accessed directly via browser
- Only files in /public are web-accessible

### 2. **Best Practices** ✨
- Follows modern PHP framework structure (Laravel, Symfony, etc.)
- Clear separation between public and private code
- Professional, industry-standard organization

### 3. **Easier Deployment** 🚀
- Point web server document root to `/public`
- No need to protect sensitive folders with .htaccess
- Cleaner URL structure

## How It Works

### Path Configuration

Every public page now includes `config-paths.php` at the top:

```php
<?php require_once __DIR__ . '/config-paths.php';
```

This file:
- Loads the bootstrap system
- Defines ROOT path (one level up)
- Provides helper functions for includes

### Helper Functions

**`include_view($file)`** - Include header, footer, navbar:

```php
include_view('header.php');  // Loads ../includes/header.php
include_view('navbar.php');  // Loads ../includes/navbar.php
include_view('footer.php');  // Loads ../includes/footer.php
```

## Web Server Configuration

### Apache (Recommended)

Update your VirtualHost to point to `/public`:

```apache
<VirtualHost *:80>
    ServerName auravibe.local
    DocumentRoot /path/to/auravibe/public

    <Directory /path/to/auravibe/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Nginx

```nginx
server {
    listen 80;
    server_name auravibe.local;
    root /path/to/auravibe/public;

    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### PHP Built-in Server (Development)

```bash
cd /path/to/auravibe/public
php -S localhost:8000
```

Or from root:

```bash
cd /path/to/auravibe
php -S localhost:8000 -t public
```

## Backward Compatibility

### Symlinks Created

For backward compatibility, symlinks were created:
- `assets/` → `public/assets/`
- `uploads/` → `public/uploads/`

This means old URLs still work:
- `/assets/css/style.css` → `/public/assets/css/style.css`
- `/uploads/products/watch.jpg` → `/public/uploads/products/watch.jpg`

### Root Index Redirect

`/index.php` in root redirects to `/public/index.php` for development servers that don't support custom document roots.

## What Was Changed

### Files Moved

- ✅ All 16 public PHP pages → `/public`
- ✅ `assets/` folder → `/public/assets`
- ✅ `uploads/` folder → `/public/uploads`

### Files Updated

All public PHP files now:
1. Include `config-paths.php` at the top
2. Use `include_view()` instead of direct includes
3. Have access to bootstrap system automatically

### Backups Created

Old files backed up to `/temp`:
- `temp/old-assets-backup/` - Original assets folder
- `temp/old-uploads-backup/` - Original uploads folder

## Files Reference

### config-paths.php

Located at: `public/config-paths.php`

```php
<?php
// Define the root path (one level up from public)
define('ROOT', dirname(__DIR__));

// Load bootstrap from root
require_once ROOT . '/bootstrap.php';

// Define paths for includes
define('INCLUDES_PATH', ROOT . '/includes/');

// Helper function to include files
function include_view($file) {
    $path = INCLUDES_PATH . $file;
    if (file_exists($path)) {
        include $path;
    }
}
```

### public/.htaccess

Security and configuration:
- Enables mod_rewrite
- Protects sensitive files (.env, .sql, .md)
- Prevents directory listing
- Adds security headers
- Caches static assets

## Admin Panel

The `/admin` folder remains in the root for now. In the future, it can be:
1. Moved to `/public/admin` (if you want it web-accessible)
2. Converted to use the same bootstrap system
3. Protected with additional authentication

## URLs

### Before:
```
http://localhost/auravibe/index.php
http://localhost/auravibe/shop.php
http://localhost/auravibe/assets/css/style.css
```

### After (with proper web server config):
```
http://localhost/index.php         # or just /
http://localhost/shop.php
http://localhost/assets/css/style.css
```

### After (without web server config):
```
http://localhost/auravibe/public/index.php
http://localhost/auravibe/public/shop.php
http://localhost/auravibe/public/assets/css/style.css
```

## Troubleshooting

### "File not found" errors

**Problem:** Assets or includes not loading

**Solution:**
1. Check that assets are in `/public/assets`
2. Check that includes are in `/includes`
3. Verify `config-paths.php` is included at top of page

### "Can't connect to database"

**Problem:** Database connection fails

**Solution:**
1. Ensure `.env` is in root (not in /public)
2. Check `bootstrap.php` is being loaded
3. Verify database credentials in `.env`

### Admin panel broken

**Problem:** Admin pages have broken links

**Solution:**
Admin folder needs separate update. For now, access it via:
```
http://localhost/auravibe/admin/
```

## Next Steps

### Optional Improvements:

1. **Update Admin Panel**
   - Move to `/public/admin` or keep separate
   - Update to use bootstrap system

2. **Clean URL Routing**
   - Implement proper routing (remove .php from URLs)
   - Use mod_rewrite for cleaner URLs

3. **Remove Symlinks**
   - Once all paths are verified, remove symlinks
   - Delete backup files in `/temp`

4. **Update Admin**
   - Apply same migration to admin panel
   - Use consistent bootstrap system

## Security Checklist

- ✅ Public files separated from application code
- ✅ `.env` file protected (outside web root)
- ✅ `.htaccess` denies access to sensitive files
- ✅ Directory listing disabled
- ✅ Security headers enabled
- ✅ Old folders backed up

## Summary

✅ All public pages moved to `/public`
✅ Backward compatibility maintained (symlinks)
✅ Security improved (app code outside web root)
✅ Professional structure implemented
✅ Documentation updated
✅ Ready for production deployment

**The site is now organized following industry best practices!** 🎉
