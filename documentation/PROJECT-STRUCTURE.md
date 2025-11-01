# Project Structure Guide

This document explains the reorganized project structure and how to use it.

## Overview

The project has been reorganized into a modern MVC-like structure for better maintainability, security, and scalability.

## Directory Structure

### `/app` - Application Logic
Contains all application-specific code:
- **`/app/classes/`** - PHP classes (Paymob, future models, controllers)
- **`/app/includes/`** - Reusable view components (header, footer, navbar)

### `/config` - Configuration Files
All configuration files that load settings from `.env`:
- **`env.php`** - Environment variable loader
- **`app.php`** - Application settings
- **`database.php`** - Database configuration (MySQLi + PDO)
- **`paymob.php`** - Paymob payment gateway configuration

### `/database` - Database Files
SQL schema and migration files:
- **`database.sql`** - Main database schema
- **`paymob-database.sql`** - Paymob-specific tables

### `/docs` - Documentation
All project documentation:
- `README.md` - Main documentation
- `SETUP.md` - Setup instructions
- `LOCALHOST-GUIDE.md` - Local development
- `PRODUCTION_DEPLOYMENT_GUIDE.md` - Production deployment
- `PAYMOB-INTEGRATION-GUIDE.md` - Payment integration
- `ASSETS-GUIDE.md` - Assets management
- `PROJECT-STRUCTURE.md` - This file

### `/scripts` - Utility Scripts
Bash scripts for setup and maintenance:
- `setup-localhost.sh` - Localhost setup
- `start.sh` - Start development server

### `/temp` - Temporary Files
Files that may be removed or archived:
- Animation previews
- Old files pending cleanup

### `/admin` - Admin Panel
Admin dashboard and management:
- Product management
- Order management
- Customer management
- Settings

### `/assets` - Public Assets
Static files (CSS, JS, images):
- `/css` - Stylesheets
- `/js` - JavaScript files
- `/images` - Image assets
- `/models` - 3D models (if any)

### `/uploads` - User Uploads
Files uploaded by users:
- `/products` - Product images
- `/categories` - Category images

### Root Directory
Public-facing PHP pages:
- `index.php` - Homepage
- `shop.php` - Shop listing
- `product.php` - Product details
- `cart.php` - Shopping cart
- `checkout.php` - Checkout page
- `process-order.php` - Order processing
- `paymob-callback.php` - Payment callback handler
- `order-success.php` - Order confirmation
- `login.php`, `register.php` - Authentication
- `about.php`, `contact.php`, `privacy.php`, `terms.php` - Static pages

## Important Files

### `bootstrap.php`
The main initialization file that loads all configurations. Include this at the top of every PHP file:

```php
<?php
require_once __DIR__ . '/bootstrap.php';
```

This automatically loads:
1. Environment variables from `.env`
2. App configuration
3. Database connections (MySQLi + PDO)
4. Paymob configuration
5. Class autoloader
6. Session initialization

### `.env`
**NEVER COMMIT THIS FILE!**

Contains sensitive credentials:
- Database credentials
- Paymob API keys
- App settings

Always use `.env.example` as a template.

### `.env.example`
Template for environment variables. Copy this to create your `.env`:

```bash
cp .env.example .env
```

## Migration Guide

### Old Code → New Code

#### Loading Database Configuration

**Old way:**
```php
require_once 'includes/db.php';
```

**New way:**
```php
require_once __DIR__ . '/bootstrap.php';
// Database is now automatically loaded
```

#### Loading Paymob

**Old way:**
```php
require_once 'includes/paymob-config.php';
require_once 'includes/Paymob.php';
```

**New way:**
```php
require_once __DIR__ . '/bootstrap.php';
// Paymob class is autoloaded, config is loaded
$paymob = new Paymob();
```

#### Using Environment Variables

**Old way:**
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
```

**New way:**
```php
// In config files
$dbHost = env('DB_HOST', 'localhost');
$dbUser = env('DB_USERNAME', 'root');
```

## Configuration Priority

1. Environment variables in `.env` (highest priority)
2. Default values in config files
3. Hardcoded fallbacks

## Best Practices

### 1. Use Bootstrap
Always include `bootstrap.php` at the top of your PHP files:

```php
<?php
require_once __DIR__ . '/bootstrap.php';
// Rest of your code
```

### 2. Use Environment Variables
Never hardcode credentials. Use `.env`:

```php
// Good
$apiKey = env('PAYMOB_API_KEY');

// Bad
$apiKey = 'sk_test_1234567890';
```

### 3. Use PDO for New Code
PDO is safer and more modern than MySQLi:

```php
global $pdo;
if (pdo_available()) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
}
```

### 4. Organize Classes
Put new classes in `/app/classes/`:

```php
// app/classes/Order.php
class Order {
    // Order logic
}

// Usage (autoloaded via bootstrap)
$order = new Order();
```

### 5. Keep Config Separate
Don't mix configuration with logic:

```php
// Good
require_once __DIR__ . '/bootstrap.php';
$apiUrl = PAYMOB_API_URL;

// Bad
$apiUrl = 'https://accept.paymob.com/api';
```

## Security Notes

### Files to Protect
- `.env` - Contains all credentials
- `/config/*` - Configuration files
- `/database/*` - Database schemas

### Files to Never Commit
- `.env`
- `/temp/*`
- `*.log`
- `/uploads/*` (optional)

See `.gitignore` for complete list.

## Troubleshooting

### "Class not found" Error
Make sure you're including `bootstrap.php`:
```php
require_once __DIR__ . '/bootstrap.php';
```

### ".env file not found"
Make sure `.env` exists in the root directory:
```bash
cp .env.example .env
```

### Database Connection Failed
Check your `.env` settings:
```
DB_HOST=localhost
DB_DATABASE=auravibe
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Paymob Not Working
Verify your Paymob credentials in `.env`:
```
PAYMOB_API_KEY=your_api_key
PAYMOB_IFRAME_ID=your_iframe_id
PAYMOB_HMAC_SECRET=your_hmac_secret
```

## Future Improvements

Planned enhancements:
1. Move all public pages to `/public` folder
2. Add proper routing system
3. Implement MVC pattern fully
4. Add Composer for dependency management
5. Add unit tests
6. Implement caching layer

## Questions?

Check other documentation files in `/docs` or the main `README.md`.
