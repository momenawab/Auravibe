# Quick Setup Guide - Aura Vibe

## Step-by-Step Installation

### 1. Database Setup (5 minutes)

**Create Database:**
```sql
-- Go to phpMyAdmin in your hosting panel
-- Create a new database called: auravibe_db
```

**Import Database:**
- In phpMyAdmin, select your `auravibe_db` database
- Click "Import" tab
- Choose the `database.sql` file
- Click "Go" to import

### 2. Configure Database Connection (2 minutes)

Edit `includes/db.php`:

```php
define('DB_HOST', 'localhost');           // Usually 'localhost'
define('DB_USER', 'your_db_username');    // Your database username
define('DB_PASS', 'your_db_password');    // Your database password
define('DB_NAME', 'auravibe_db');         // Database name
```

### 3. Upload Logo (1 minute)

1. Save your logo image as `logo.png`
2. Upload to: `assets/images/logo.png`
3. Recommended size: 200x80px (PNG with transparent background)

### 4. Set Permissions (1 minute)

If using SSH/Terminal:
```bash
chmod 755 uploads uploads/products uploads/categories assets/images
```

If using cPanel File Manager:
- Right-click on folders → Change Permissions → 755

### 5. Access Your Website

**Frontend (Customer Side):**
```
https://yourdomain.com/
```

**Admin Panel:**
```
https://yourdomain.com/admin/login.php

Username: admin
Password: admin123
```

**⚠️ IMPORTANT:** Change admin password immediately after first login!

## Quick Checks

### Test if website works:
1. Visit homepage - should see 3D animated hero section
2. Check navigation - all links should work
3. View shop page - should display properly

### Test admin panel:
1. Login with default credentials
2. Dashboard should show statistics
3. Navigate to Products section

## Common Issues & Solutions

### Issue: White/Blank Page
**Solution:**
- Check `includes/db.php` has correct credentials
- Enable error reporting in PHP
- Check PHP error logs

### Issue: Database Connection Error
**Solution:**
```php
// Test connection with:
<?php
$conn = mysqli_connect('localhost', 'user', 'pass', 'auravibe_db');
if (!$conn) {
    die("Failed: " . mysqli_connect_error());
} else {
    echo "Connected successfully!";
}
?>
```

### Issue: 3D Animation Not Working
**Solution:**
- Check browser console (F12) for errors
- Ensure JavaScript is enabled
- Try different browser (Chrome/Firefox)
- Check if Three.js CDN is accessible

### Issue: CSS Not Loading
**Solution:**
- Clear browser cache (Ctrl+Shift+R)
- Check `assets/css/style.css` exists
- Verify file permissions (644)

### Issue: Images Not Displaying
**Solution:**
- Check file paths in `includes/functions.php`
- Verify `base_url()` returns correct URL
- Check image file permissions

## Next Steps

### 1. Add Sample Products
- Login to admin panel
- Go to Products → Add New
- Upload product images
- Add product details

### 2. Configure Settings
- Go to Settings in admin panel
- Update site information
- Configure payment methods
- Set shipping options

### 3. Customize Design
- Edit `assets/css/style.css` for styling
- Modify colors in CSS variables
- Adjust 3D animation in `assets/js/main.js`

### 4. Add 3D Models
- Get GLB/GLTF watch models
- Upload to `assets/models/`
- Reference in product upload

### 5. Security Hardening
```php
// Change admin password
UPDATE admins SET password = PASSWORD_HASH('new_password', PASSWORD_DEFAULT) WHERE id = 1;

// Enable HTTPS in .htaccess (uncomment lines)
```

## File Locations

### Frontend Files
- Homepage: `index.php`
- Shop: `shop.php`
- Product: `product.php`
- Cart: `cart.php`

### Admin Files
- Login: `admin/login.php`
- Dashboard: `admin/dashboard.php`
- Products: `admin/products.php`

### Assets
- CSS: `assets/css/style.css`
- JavaScript: `assets/js/main.js`
- Images: `assets/images/`
- 3D Models: `assets/models/`

### Configuration
- Database: `includes/db.php`
- Functions: `includes/functions.php`

## Hostinger Specific

### Upload Methods:
1. **File Manager** (Easiest)
   - Login to Hostinger panel
   - Open File Manager
   - Navigate to `public_html`
   - Upload all files

2. **FTP** (Recommended for large files)
   - Use FileZilla
   - Connect to your hosting
   - Upload to `public_html`

3. **Git** (Advanced)
   - Initialize git repository
   - Push to GitHub
   - Clone on server

### Database Creation:
1. Hostinger Panel → Databases
2. Create MySQL Database
3. Add database user
4. Assign user to database
5. Note credentials for `db.php`

## Performance Tips

1. **Enable Gzip** - Already configured in `.htaccess`
2. **Optimize Images** - Use TinyPNG before uploading
3. **Use CDN** - Three.js loads from CDN
4. **Browser Caching** - Already configured
5. **Minify CSS/JS** - Use online minifier for production

## Support Resources

- **PHP Documentation**: https://www.php.net/docs.php
- **MySQL Documentation**: https://dev.mysql.com/doc/
- **Three.js Documentation**: https://threejs.org/docs/
- **Hostinger Tutorials**: https://www.hostinger.com/tutorials/

## Testing Checklist

- [ ] Homepage loads with 3D animation
- [ ] Navigation menu works
- [ ] Mobile menu responsive
- [ ] Shop page displays products
- [ ] Product detail page works
- [ ] Cart functionality works
- [ ] Admin login works
- [ ] Admin dashboard displays stats
- [ ] Product CRUD operations work
- [ ] Images upload successfully
- [ ] Logo displays correctly
- [ ] Newsletter signup works
- [ ] Contact form sends emails
- [ ] SSL certificate active
- [ ] All pages load under 3 seconds

## Maintenance

### Regular Tasks:
- **Daily**: Check orders
- **Weekly**: Review products, update inventory
- **Monthly**: Database backup, update statistics
- **As Needed**: Update PHP, check security

### Backup Schedule:
```bash
# Database backup (run monthly)
mysqldump -u user -p auravibe_db > backup_$(date +%Y%m%d).sql

# Files backup (use hosting control panel)
```

---

**Need Help?** Check README.md for detailed documentation.

**Quick Start Time:** ~10 minutes
**Full Setup Time:** ~30 minutes

Good luck with your luxury watch store! ✨
