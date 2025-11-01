# AuraVibe - Modern E-Commerce Platform

> A feature-rich, bilingual (English/Arabic) e-commerce platform built with PHP, MySQL, and integrated with Paymob payment gateway.

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.4-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange.svg)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## Features

### Customer Features
- **Bilingual Support** - Full English and Arabic language support with RTL layout
- **Product Catalog** - Browse products with categories, search, and filters
- **Shopping Cart** - Add to cart, update quantities, and manage items
- **Wishlist** - Save favorite products for later
- **User Accounts** - Registration, login, and profile management
- **Secure Checkout** - Multi-step checkout with address validation
- **Payment Integration** - Paymob Unified Checkout (Card, Mobile Wallet, Cash on Delivery)
- **Order Tracking** - View order history and status
- **Responsive Design** - Mobile-friendly interface

### Admin Panel
- **Dashboard** - Overview of sales, orders, and customers
- **Product Management** - Add, edit, delete products with images
- **Category Management** - Organize products into categories
- **Order Management** - View and update order statuses
- **Customer Management** - View customer information and history
- **Shipping Costs** - Configure shipping rates by governorate
- **Settings** - Store configuration and preferences

### Payment Gateway
- **Paymob Unified Checkout** - Latest Paymob API integration
- **Multiple Payment Methods:**
  - Credit/Debit Cards (Visa, Mastercard)
  - Mobile Wallets (Vodafone Cash, Orange Cash, etc.)
  - Cash on Delivery
- **Secure Transactions** - HMAC signature verification
- **Order Callbacks** - Automatic payment status updates

---

## Tech Stack

- **Backend:** PHP 7.4+ with PDO/MySQLi
- **Database:** MySQL 5.7+
- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5
- **Payment:** Paymob Unified Checkout API
- **Server:** Apache with mod_rewrite

---

## Quick Start

### Prerequisites

```bash
- PHP >= 7.4
- MySQL >= 5.7
- Apache with mod_rewrite enabled
- Composer (optional)
```

### Installation

1. **Clone the repository**
```bash
git clone https://github.com/momenawab/Auravibe.git
cd auravibe
```

2. **Configure environment**
```bash
cp .env.example .env
# Edit .env with your credentials
```

3. **Set up database**
```bash
mysql -u root -p
CREATE DATABASE auravibe;
USE auravibe;
SOURCE database/database.sql;
SOURCE database/paymob-database.sql;
```

4. **Configure web server**
```bash
# For Apache, point document root to project directory
# Ensure .htaccess is enabled
```

5. **Set permissions**
```bash
chmod -R 755 uploads/
chmod -R 755 assets/
chmod 644 .env
```

6. **Create admin account**
```bash
# Visit: http://your-domain.com/public/setup-admin.php
# Or run: php public/create-admin.php
```

7. **Access the site**
```
Frontend: http://your-domain.com
Admin Panel: http://your-domain.com/public/admin/login.php
```

---

## Configuration

### Environment Variables

Edit `.env` file with your settings:

```env
# Application
APP_NAME=AuraVibe
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_HOST=localhost
DB_DATABASE=auravibe
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Paymob Payment Gateway
PAYMOB_SECRET_KEY=your_secret_key_here
PAYMOB_PUBLIC_KEY=your_public_key_here
PAYMOB_HMAC_SECRET=your_hmac_secret_here
PAYMOB_INTEGRATION_ONLINE_CARD=your_card_integration_id
PAYMOB_INTEGRATION_MOBILE_WALLET=your_wallet_integration_id

# Email (Optional)
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
```

### Paymob Setup

1. Create account at [Paymob Dashboard](https://accept.paymob.com)
2. Get API credentials from Settings > API Keys
3. Configure integration IDs for payment methods
4. Add your callback URL: `https://yourdomain.com/public/paymob-callback.php`
5. Test with sandbox credentials before going live

See [Paymob Integration Guide](documentation/PAYMOB-INTEGRATION-GUIDE.md) for details.

---

## Project Structure

```
auravibe/
├── app/
│   └── classes/          # PHP classes (Paymob, Models)
├── assets/               # Public assets (CSS, JS, images)
│   ├── css/
│   ├── js/
│   └── images/
├── config/               # Configuration files
│   ├── app.php
│   ├── database.php
│   ├── env.php
│   └── paymob.php
├── database/             # SQL schemas and migrations
│   ├── database.sql
│   └── migrations/
├── documentation/        # Project documentation
├── includes/             # Reusable components
│   ├── header.php
│   ├── footer.php
│   ├── navbar.php
│   └── languages/        # Translation files
├── public/               # Public pages
│   ├── admin/            # Admin panel
│   ├── api/              # API endpoints
│   ├── index.php         # Homepage
│   ├── shop.php
│   ├── product.php
│   ├── cart.php
│   ├── checkout.php
│   └── process-order.php
├── scripts/              # Utility scripts
├── uploads/              # User uploaded files
│   └── products/
├── .env.example          # Environment template
├── .htaccess             # Apache configuration
├── bootstrap.php         # Application bootstrap
└── README.md
```

---

## Usage

### Adding Products

1. Log in to admin panel
2. Navigate to **Products > Add Product**
3. Fill in product details (name, price, description)
4. Upload product image
5. Select category
6. Click **Save**

### Managing Orders

1. Go to **Orders** in admin panel
2. View order details by clicking on order ID
3. Update order status (Pending, Processing, Shipped, Delivered)
4. Track payment status

### Configuring Shipping

1. Navigate to **Shipping Costs**
2. Set prices for each governorate
3. Enable/disable governorates
4. Save changes

---

## API Endpoints

### Cart API

**Add to Cart**
```php
POST /public/api/cart-add.php
Body: {
  "product_id": 1,
  "quantity": 2
}
```

**Update Cart**
```php
POST /public/api/cart-update.php
Body: {
  "product_id": 1,
  "quantity": 3
}
```

### Payment Callback

**Paymob Callback**
```php
POST /public/paymob-callback.php
# Handles payment status updates from Paymob
```

---

## Development

### Local Development

```bash
# Start PHP development server
php -S localhost:8000

# Or use the provided script
bash start.sh
```

### Database Migrations

```bash
# Run migration script
php run_migration.php

# Or manually execute SQL files
mysql -u root -p auravibe < database/migrations/your_migration.sql
```

### Adding Translations

Edit language files:
- `includes/languages/en.php` - English
- `includes/languages/ar.php` - Arabic

```php
// includes/languages/en.php
return [
    'welcome' => 'Welcome',
    'shop_now' => 'Shop Now'
];
```

---

## Deployment

### Production Checklist

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false`
- [ ] Use production Paymob credentials
- [ ] Enable HTTPS (SSL certificate)
- [ ] Configure proper file permissions
- [ ] Set up automated backups
- [ ] Configure error logging
- [ ] Test payment flow end-to-end
- [ ] Verify callback URLs

### Hostinger Deployment

See detailed guide: [Hostinger Setup](HOSTINGER_SETUP.md)

```bash
1. Upload files via FTP or File Manager
2. Import database via phpMyAdmin
3. Update .env with production settings
4. Set file permissions
5. Test the site
```

---

## Security

### Best Practices

- Never commit `.env` file to git
- Use environment variables for sensitive data
- Enable HTTPS in production
- Validate and sanitize all user inputs
- Use prepared statements for database queries
- Implement CSRF protection
- Set proper file permissions (644 for files, 755 for directories)
- Keep software updated

### HMAC Verification

All Paymob callbacks are verified using HMAC signatures:

```php
// Automatic verification in paymob-callback.php
$paymob = new PaymobUnified();
$isValid = $paymob->verifyCallback($_GET);
```

---

## Troubleshooting

### Common Issues

**Database Connection Failed**
```bash
# Check .env credentials
# Verify MySQL service is running
sudo service mysql status
```

**Paymob Integration Errors**
```bash
# Verify API keys in .env
# Check callback URL in Paymob dashboard
# Review logs in public/paymob-callback.php
```

**Image Upload Issues**
```bash
# Set proper permissions
chmod -R 755 uploads/
chown -R www-data:www-data uploads/
```

**404 Errors**
```bash
# Ensure .htaccess is enabled
# Check Apache mod_rewrite is enabled
sudo a2enmod rewrite
sudo service apache2 restart
```

---

## Documentation

- [Project Structure](documentation/PROJECT-STRUCTURE.md)
- [Paymob Integration](documentation/PAYMOB-INTEGRATION-GUIDE.md)
- [Deployment Guide](documentation/PRODUCTION_DEPLOYMENT_GUIDE.md)
- [API Documentation](documentation/API.md)
- [Assets Guide](documentation/ASSETS-GUIDE.md)

---

## Testing

### Test Paymob Integration

Use these test credentials in sandbox mode:

**Test Card (Visa)**
```
Card Number: 4987654321098769
Expiry: Any future date (e.g., 12/25)
CVV: Any 3 digits (e.g., 123)
Name: Test User
```

**Test Wallet**
```
Mobile: 01010101010
PIN: 123456
OTP: 123456
```

---

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## Roadmap

- [ ] Add product reviews and ratings
- [ ] Implement coupon/discount system
- [ ] Add email notifications
- [ ] Integrate shipping APIs
- [ ] Add product variations (size, color)
- [ ] Implement inventory management
- [ ] Add analytics dashboard
- [ ] Create mobile app
- [ ] Multi-vendor support

---

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## Support

For support, email support@auravibe.com or create an issue on GitHub.

---

## Acknowledgments

- [Paymob](https://paymob.com) - Payment gateway
- [Bootstrap](https://getbootstrap.com) - UI framework
- [Font Awesome](https://fontawesome.com) - Icons

---

## Authors

**AuraVibe Development Team**
- GitHub: [@momenawab](https://github.com/momenawab)

---

Made with ❤️ in Egypt
