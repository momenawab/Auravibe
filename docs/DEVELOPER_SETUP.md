# Developer Setup Guide - AuraVibe

Complete setup guide for developers working on the AuraVibe project.

## Table of Contents

- [Prerequisites](#prerequisites)
- [Local Development Setup](#local-development-setup)
- [Database Setup](#database-setup)
- [Environment Configuration](#environment-configuration)
- [Development Tools](#development-tools)
- [Code Structure](#code-structure)
- [Development Workflow](#development-workflow)
- [Testing](#testing)
- [Debugging](#debugging)
- [Common Issues](#common-issues)

## Prerequisites

### Required Software

1. **PHP 7.4 or higher**
   ```bash
   php -v
   ```

2. **MySQL 8.0 or higher**
   ```bash
   mysql --version
   ```

3. **Apache or Nginx**
   - Apache with mod_rewrite enabled
   - Or Nginx with proper configuration

4. **Composer** (Optional but recommended)
   ```bash
   composer --version
   ```

5. **Git**
   ```bash
   git --version
   ```

### Recommended Tools

- **IDE:** VS Code, PHPStorm, or Sublime Text
- **Database Client:** phpMyAdmin, MySQL Workbench, or DBeaver
- **API Testing:** Postman or Insomnia
- **Browser DevTools:** Chrome DevTools or Firefox Developer Tools

## Local Development Setup

### Option 1: XAMPP (Easiest for Windows/Mac)

1. **Download and Install XAMPP**
   - Download from [https://www.apachefriends.org/](https://www.apachefriends.org/)
   - Install with Apache, MySQL, and PHP

2. **Clone the Repository**
   ```bash
   cd /path/to/xampp/htdocs
   git clone https://github.com/yourusername/auravibe.git
   cd auravibe
   ```

3. **Start Services**
   - Open XAMPP Control Panel
   - Start Apache and MySQL

4. **Access Application**
   - Frontend: http://localhost/auravibe/public/
   - Admin: http://localhost/auravibe/public/admin/

### Option 2: Native Apache/PHP Installation (Linux)

1. **Install LAMP Stack**
   ```bash
   sudo apt update
   sudo apt install apache2 php php-mysql mysql-server
   sudo apt install php-mbstring php-curl php-json php-xml
   ```

2. **Enable Apache Modules**
   ```bash
   sudo a2enmod rewrite
   sudo systemctl restart apache2
   ```

3. **Clone Repository**
   ```bash
   cd /var/www/html
   sudo git clone https://github.com/yourusername/auravibe.git
   cd auravibe
   ```

4. **Set Permissions**
   ```bash
   sudo chown -R www-data:www-data /var/www/html/auravibe
   sudo chmod -R 755 /var/www/html/auravibe
   sudo chmod -R 775 /var/www/html/auravibe/public/uploads
   ```

5. **Configure Virtual Host** (Optional)
   ```bash
   sudo nano /etc/apache2/sites-available/auravibe.conf
   ```

   Add:
   ```apache
   <VirtualHost *:80>
       ServerName auravibe.local
       DocumentRoot /var/www/html/auravibe/public

       <Directory /var/www/html/auravibe/public>
           Options Indexes FollowSymLinks
           AllowOverride All
           Require all granted
       </Directory>

       ErrorLog ${APACHE_LOG_DIR}/auravibe_error.log
       CustomLog ${APACHE_LOG_DIR}/auravibe_access.log combined
   </VirtualHost>
   ```

   Enable site:
   ```bash
   sudo a2ensite auravibe
   sudo systemctl reload apache2
   ```

   Add to /etc/hosts:
   ```bash
   sudo nano /etc/hosts
   # Add: 127.0.0.1 auravibe.local
   ```

### Option 3: Docker (Most Portable)

1. **Create docker-compose.yml**
   ```yaml
   version: '3.8'
   services:
     web:
       image: php:7.4-apache
       ports:
         - "8080:80"
       volumes:
         - ./public:/var/www/html
         - ./includes:/var/www/includes
         - ./config:/var/www/config
       environment:
         - DB_HOST=db
         - DB_DATABASE=auravibe
         - DB_USERNAME=root
         - DB_PASSWORD=root
       depends_on:
         - db

     db:
       image: mysql:8.0
       ports:
         - "3306:3306"
       environment:
         MYSQL_ROOT_PASSWORD: root
         MYSQL_DATABASE: auravibe
       volumes:
         - db_data:/var/lib/mysql

   volumes:
     db_data:
   ```

2. **Start Containers**
   ```bash
   docker-compose up -d
   ```

3. **Access Application**
   - http://localhost:8080

## Database Setup

### Step 1: Create Database

```bash
mysql -u root -p
```

```sql
CREATE DATABASE auravibe CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'auravibe_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON auravibe.* TO 'auravibe_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Step 2: Import Database Schema

```bash
mysql -u auravibe_user -p auravibe < database/schema.sql
```

If schema file doesn't exist, create tables manually:

```sql
USE auravibe;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    name_ar VARCHAR(255),
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    name_ar VARCHAR(255),
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    description_ar TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    brand VARCHAR(255),
    category_id INT,
    image VARCHAR(255),
    is_featured BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(255) NOT NULL,
    governorate VARCHAR(255) NOT NULL,
    postal_code VARCHAR(20),
    subtotal DECIMAL(10, 2) NOT NULL,
    shipping DECIMAL(10, 2) DEFAULT 0,
    tax DECIMAL(10, 2) DEFAULT 0,
    total DECIMAL(10, 2) NOT NULL,
    payment_method VARCHAR(50),
    payment_status VARCHAR(50) DEFAULT 'pending',
    order_status VARCHAR(50) DEFAULT 'pending',
    paymob_order_id VARCHAR(255),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Order Items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT,
    product_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- Shipping Costs table
CREATE TABLE shipping_costs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    governorate VARCHAR(255) UNIQUE NOT NULL,
    cost DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Step 3: Insert Sample Data

```sql
-- Insert admin user (password: admin123)
INSERT INTO users (name, email, password, is_admin) VALUES
('Admin', 'admin@auravibe.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);

-- Insert categories
INSERT INTO categories (name, name_ar, slug) VALUES
('Luxury Watches', 'ساعات فاخرة', 'luxury-watches'),
('Sport Watches', 'ساعات رياضية', 'sport-watches'),
('Smart Watches', 'ساعات ذكية', 'smart-watches');

-- Insert sample products
INSERT INTO products (name, brand, price, stock, category_id, image) VALUES
('Titanium Sport Pro', 'SPORT MAX', 2299.00, 15, 2, 'uploads/products/watch1.jpg'),
('Royal Classic Gold', 'ETERNITY', 4599.00, 8, 1, 'uploads/products/watch2.jpg'),
('Carbon Fiber Racing', 'SPORT MAX', 2799.00, 12, 2, 'uploads/products/watch3.jpg');

-- Insert shipping costs
INSERT INTO shipping_costs (governorate, cost) VALUES
('Cairo', 0),
('Giza', 0),
('Alexandria', 50.00),
('Other', 100.00);
```

## Environment Configuration

### Step 1: Create .env File

```bash
cp .env.example .env
```

Or create manually:

```bash
nano .env
```

### Step 2: Configure .env

```env
# Application Settings
APP_NAME="AuraVibe"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Database Configuration
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=auravibe
DB_USERNAME=auravibe_user
DB_PASSWORD=your_secure_password

# Paymob Payment Gateway (TEST MODE)
PAYMOB_API_KEY=your_test_api_key
PAYMOB_HMAC_SECRET=your_test_hmac_secret
PAYMOB_SECRET_KEY=egy_sk_test_...
PAYMOB_PUBLIC_KEY=egy_pk_test_...

# Paymob TEST Integration IDs
PAYMOB_INTEGRATION_ONLINE_CARD=5376291
PAYMOB_INTEGRATION_TAP_ON_PHONE=5376291
PAYMOB_INTEGRATION_MOBILE_WALLET=5374707

# Paymob API URLs
PAYMOB_API_URL=https://accept.paymob.com/v1/intention/
PAYMOB_CURRENCY=EGP

# Session Configuration
SESSION_LIFETIME=120
SESSION_SECURE=false

# Security
SECURE_COOKIES=false
HTTPS_ONLY=false

# File Uploads
UPLOAD_PATH=uploads/
MAX_UPLOAD_SIZE=5242880

# Debug Mode
DEBUG=true
DISPLAY_ERRORS=true
```

### Step 3: Verify Configuration

```bash
php -r "require 'config/env.php'; echo 'Config loaded successfully!';"
```

## Development Tools

### IDE Setup (VS Code)

1. **Install Extensions**
   - PHP Intelephense
   - PHP Debug
   - MySQL
   - GitLens
   - Prettier
   - ESLint

2. **Configure settings.json**
   ```json
   {
     "php.suggest.basic": true,
     "php.validate.enable": true,
     "php.validate.executablePath": "/usr/bin/php",
     "editor.formatOnSave": true
   }
   ```

### Git Configuration

```bash
git config user.name "Your Name"
git config user.email "your.email@example.com"
git config core.autocrlf input
```

### Create .gitignore

```bash
# Environment
.env
.env.local

# Dependencies
vendor/
node_modules/

# IDE
.vscode/
.idea/
*.swp
*.swo

# OS
.DS_Store
Thumbs.db

# Uploads
public/uploads/*
!public/uploads/.gitkeep

# Logs
*.log
error_log

# Cache
cache/
temp/

# Backup files
*.bak
*.backup
```

## Code Structure

### Directory Layout

```
auravibe/
├── public/              # Web accessible files
│   ├── index.php       # Homepage
│   ├── shop.php        # Product listing
│   ├── product.php     # Product details
│   ├── cart.php        # Shopping cart
│   ├── checkout.php    # Checkout process
│   ├── api/            # API endpoints
│   ├── admin/          # Admin panel
│   └── assets/         # Static assets
├── includes/           # Shared includes
│   ├── header.php     # Common header
│   ├── footer.php     # Common footer
│   ├── navbar.php     # Navigation
│   └── functions.php  # Helper functions
├── config/            # Configuration files
│   ├── database.php   # DB connection
│   └── app.php       # App config
├── app/              # Application code
│   └── classes/      # PHP classes
├── docs/             # Documentation
└── .env              # Environment variables
```

### Naming Conventions

**Files:**
- PHP files: lowercase with hyphens (e.g., `cart-add.php`)
- Classes: PascalCase (e.g., `Paymob.php`)

**Variables:**
- snake_case: `$product_id`, `$cart_items`

**Functions:**
- snake_case: `get_product()`, `format_price()`

**Constants:**
- UPPERCASE: `DB_HOST`, `APP_NAME`

**CSS Classes:**
- kebab-case: `.product-card`, `.btn-primary`

## Development Workflow

### Feature Development

1. **Create Feature Branch**
   ```bash
   git checkout -b feature/new-feature-name
   ```

2. **Make Changes**
   - Write code
   - Test locally
   - Commit frequently

3. **Commit Changes**
   ```bash
   git add .
   git commit -m "Add: Description of changes"
   ```

4. **Push to Remote**
   ```bash
   git push origin feature/new-feature-name
   ```

5. **Create Pull Request**
   - Review changes
   - Request code review
   - Merge after approval

### Commit Message Format

```
Type: Brief description

Detailed description (optional)

- Bullet points for multiple changes
```

**Types:**
- `Add:` New feature
- `Fix:` Bug fix
- `Update:` Enhance existing feature
- `Refactor:` Code restructure
- `Docs:` Documentation
- `Style:` Formatting changes
- `Test:` Add tests

## Testing

### Manual Testing Checklist

**Cart Functionality:**
- [ ] Add product to cart
- [ ] Update quantity
- [ ] Remove from cart
- [ ] Cart persists across pages

**Checkout Process:**
- [ ] Form validation
- [ ] Shipping cost calculation
- [ ] Order creation
- [ ] Payment integration

**Admin Panel:**
- [ ] Product CRUD operations
- [ ] Order management
- [ ] User management

### Database Testing

```sql
-- Test product queries
SELECT * FROM products WHERE stock > 0;

-- Test order calculations
SELECT
    o.id,
    o.subtotal,
    o.shipping,
    o.tax,
    o.total,
    SUM(oi.price * oi.quantity) as calculated_subtotal
FROM orders o
JOIN order_items oi ON o.id = oi.order_id
GROUP BY o.id;
```

### API Testing with cURL

```bash
# Test add to cart
curl -X POST http://localhost/api/cart-add.php \
  -H "Content-Type: application/json" \
  -d '{"product_id": 1, "quantity": 2}'

# Test update cart
curl -X POST http://localhost/api/cart-update.php \
  -d "action=update_quantity&index=0&change=1"
```

## Debugging

### Enable Error Reporting

```php
// Add to top of PHP files during development
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
```

### Debug Functions

```php
// Dump variable and die
function dd($var) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    die();
}

// Debug log to file
function debug_log($message) {
    error_log(date('Y-m-d H:i:s') . ' - ' . $message . "\n", 3, 'debug.log');
}
```

### Browser DevTools

1. Open DevTools (F12)
2. Check Console for JavaScript errors
3. Network tab for API requests
4. Application tab for session/cookies

### MySQL Query Debugging

```php
// Log failed queries
if (!$result) {
    error_log("MySQL Error: " . mysqli_error($conn));
    error_log("Query: " . $query);
}
```

## Common Issues

### Issue: Database Connection Failed

**Solution:**
```bash
# Check MySQL is running
sudo systemctl status mysql

# Test connection
mysql -u auravibe_user -p auravibe

# Verify credentials in .env
cat .env | grep DB_
```

### Issue: Permission Denied for Uploads

**Solution:**
```bash
sudo chmod -R 775 public/uploads/
sudo chown -R www-data:www-data public/uploads/
```

### Issue: .htaccess Not Working

**Solution:**
```bash
# Enable mod_rewrite
sudo a2enmod rewrite
sudo systemctl restart apache2

# Check AllowOverride in Apache config
sudo nano /etc/apache2/apache2.conf
# Should have: AllowOverride All
```

### Issue: Session Not Persisting

**Solution:**
```bash
# Check session directory permissions
ls -la /var/lib/php/sessions/

# Set correct permissions
sudo chmod 1733 /var/lib/php/sessions/
```

### Issue: Payment Integration Not Working

**Solution:**
1. Verify Paymob credentials in `.env`
2. Check test mode is enabled
3. Review error logs: `tail -f error_log`
4. Test API connection: `curl https://accept.paymob.com/v1/auth/tokens`

## Performance Tips

1. **Enable OPcache**
   ```bash
   sudo nano /etc/php/7.4/apache2/php.ini
   # Enable opcache
   opcache.enable=1
   ```

2. **Optimize MySQL**
   ```sql
   -- Add indexes
   CREATE INDEX idx_products_category ON products(category_id);
   CREATE INDEX idx_orders_user ON orders(user_id);
   ```

3. **Use Browser Caching**
   Already configured in `.htaccess`

## Next Steps

- [ ] Complete initial setup
- [ ] Run test suite
- [ ] Review code structure
- [ ] Read API documentation
- [ ] Start development

## Resources

- [PHP Documentation](https://www.php.net/docs.php)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [Paymob API Docs](https://docs.paymob.com/)
- [Project Wiki](https://github.com/yourusername/auravibe/wiki)

## Support

Need help? Contact:
- Email: dev-support@auravibe.com
- Slack: #auravibe-dev
- GitHub Issues: [Report Issue](https://github.com/yourusername/auravibe/issues)

---

**Happy Coding!**
