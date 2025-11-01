# Aura Vibe - Luxury Watch Store

A stunning 3D watch e-commerce website built with PHP, JavaScript, and Three.js featuring luxury gold design and interactive 3D elements.

## Features

- **3D Interactive Hero Section** - Stunning Three.js animated background
- **Luxury Gold Theme** - Premium design matching the brand identity
- **Responsive Design** - Works perfectly on all devices
- **Admin Panel** - Complete backend management system
- **Product Management** - Easy product CRUD operations
- **3D Product Viewer** - Display watches with 3D models (GLB/GLTF)
- **Shopping Cart & Checkout** - Full e-commerce functionality
- **Customer Accounts** - User registration and order tracking
- **Wishlist** - Save favorite products
- **Newsletter Subscription** - Email marketing integration ready

## Technology Stack

### Frontend
- **HTML5 & CSS3** - Modern semantic markup
- **JavaScript ES6+** - Interactive features
- **Three.js** - 3D graphics and animations
- **Font Awesome** - Icons
- **Google Fonts** - Typography (Playfair Display & Montserrat)

### Backend
- **PHP 7.4+** - Server-side logic
- **MySQL/MariaDB** - Database
- **No Framework** - Pure PHP for maximum compatibility

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or MariaDB 10.3+
- Apache/Nginx web server
- Hostinger or similar hosting with PHP support

### Step 1: Upload Files
Upload all files to your hosting account via FTP or File Manager to your `public_html` directory.

### Step 2: Create Database
1. Log in to your hosting control panel (cPanel/Hostinger Panel)
2. Go to MySQL Databases
3. Create a new database named `auravibe_db`
4. Create a database user and assign it to the database
5. Import the `database.sql` file using phpMyAdmin

### Step 3: Configure Database Connection
Edit `includes/db.php` and update with your database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');
define('DB_NAME', 'auravibe_db');
```

### Step 4: Set Permissions
Set proper permissions for upload directories:
```bash
chmod 755 uploads
chmod 755 uploads/products
chmod 755 uploads/categories
chmod 755 assets/images
```

### Step 5: Add Logo
1. Copy your logo image to `assets/images/logo.png`
2. The logo should be in PNG format with transparent background
3. Recommended size: 200x80px or similar aspect ratio

### Step 6: Access Website
- **Frontend**: http://yourdomain.com/
- **Admin Panel**: http://yourdomain.com/admin/login.php

### Default Admin Credentials
```
Username: admin
Email: admin@auravibe.com
Password: admin123
```

**Important:** Change these credentials immediately after first login!

## Project Structure

```
auravibe/
├── admin/                    # Admin panel files
│   ├── assets/              # Admin CSS/JS
│   ├── dashboard.php        # Admin dashboard
│   ├── products.php         # Product management
│   └── ...
├── assets/                   # Frontend assets
│   ├── css/
│   │   └── style.css       # Main stylesheet
│   ├── js/
│   │   └── main.js         # Main JavaScript
│   ├── images/             # Static images
│   └── models/             # 3D models (GLB/GLTF)
├── includes/                # PHP includes
│   ├── db.php              # Database connection
│   ├── functions.php       # Helper functions
│   ├── header.php          # HTML header
│   ├── navbar.php          # Navigation
│   └── footer.php          # HTML footer
├── uploads/                 # User uploads
│   ├── products/           # Product images
│   └── categories/         # Category images
├── index.php               # Homepage
├── shop.php                # Shop page
├── product.php             # Single product
├── cart.php                # Shopping cart
├── checkout.php            # Checkout
├── database.sql            # Database structure
└── README.md               # This file
```

## Database Structure

- **categories** - Product categories
- **products** - Product information
- **product_images** - Product gallery
- **customers** - Customer accounts
- **addresses** - Customer addresses
- **orders** - Order information
- **order_items** - Order details
- **wishlist** - Customer wishlists
- **reviews** - Product reviews
- **newsletter_subscribers** - Email subscribers
- **admins** - Admin users
- **settings** - Site configuration

## Adding 3D Models

1. Get your watch 3D model in GLB or GLTF format
2. Upload to `assets/models/` directory
3. In admin panel, add product and specify the model path
4. The 3D viewer will automatically load on product pages

### Where to Get 3D Models
- **Sketchfab** - https://sketchfab.com/
- **TurboSquid** - https://www.turbosquid.com/
- **CGTrader** - https://www.cgtrader.com/
- **Blender** - Create custom models

## Customization

### Colors
Edit `assets/css/style.css` and modify CSS variables:
```css
:root {
    --gold-primary: #D4AF37;
    --gold-secondary: #C9A65C;
    --dark-primary: #1a1a1a;
    /* ... */
}
```

### Fonts
Change Google Fonts in `includes/header.php`:
```html
<link href="https://fonts.googleapis.com/css2?family=Your+Font&display=swap" rel="stylesheet">
```

### 3D Hero Animation
Modify `assets/js/main.js` in the `initHero3D()` function to customize particles, colors, and animation speed.

## Security Recommendations

1. Change default admin password immediately
2. Use strong passwords for database
3. Keep PHP and MySQL updated
4. Enable HTTPS (SSL certificate)
5. Implement CSRF protection
6. Add input validation and sanitization
7. Use prepared statements for all queries
8. Set proper file permissions (755 for directories, 644 for files)

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## Performance Optimization

1. **Enable Gzip Compression** in .htaccess
2. **Optimize Images** - Use WebP format
3. **Minify CSS/JS** - Use build tools
4. **Enable Browser Caching**
5. **Use CDN** for Three.js and other libraries
6. **Optimize 3D Models** - Keep under 5MB

## Troubleshooting

### White Screen
- Check PHP error logs
- Verify database connection in `includes/db.php`
- Ensure PHP version is 7.4+

### 3D Animation Not Working
- Check browser console for JavaScript errors
- Verify Three.js CDN links in `includes/header.php`
- Ensure WebGL is supported in browser

### Images Not Displaying
- Check file paths and permissions
- Verify uploads directory is writable
- Ensure correct base_url in functions

### Database Connection Error
- Verify database credentials
- Check if database exists
- Ensure MySQL service is running

## Future Enhancements

- [ ] Payment gateway integration (Stripe, PayPal)
- [ ] Email notifications system
- [ ] Advanced search with filters
- [ ] Product comparison feature
- [ ] Customer reviews and ratings
- [ ] Coupon/discount system
- [ ] Multi-language support
- [ ] PWA (Progressive Web App)
- [ ] Advanced analytics dashboard
- [ ] Social media integration

## Support

For issues and questions:
- Check the documentation
- Review database.sql for schema reference
- Verify all paths and configurations

## License

This project is proprietary software for Aura Vibe watch store.

## Credits

- **Design & Development**: Aura Vibe Team
- **3D Graphics**: Three.js
- **Icons**: Font Awesome
- **Fonts**: Google Fonts

---

**Made with luxury in mind for Aura Vibe** ✨

Last Updated: 2024
