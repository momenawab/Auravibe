# Running Aura Vibe on Localhost

## ✅ Your Website is Now Running!

The PHP development server has been started and is running at:

**Frontend:** http://localhost:8000
**Admin Panel:** http://localhost:8000/admin/login.php

## Quick Access

### Frontend Website
```
http://localhost:8000
```
Features:
- 3D animated hero section with gold particles
- Luxury watch store design
- Responsive navigation
- Product showcase

### Admin Panel
```
http://localhost:8000/admin/login.php
```
Credentials:
- **Username:** admin
- **Password:** admin123

## Important Notes

### Database Setup (Optional for Now)
The website will run without database for viewing the design. To enable full functionality:

1. **Create Database:**
```bash
mysql -u root -p
CREATE DATABASE auravibe_db;
exit;
```

2. **Import Database:**
```bash
mysql -u root -p auravibe_db < database.sql
```

3. **Update Credentials:**
Edit `includes/db.php` if your MySQL credentials are different:
```php
define('DB_USER', 'root');
define('DB_PASS', 'your_password');
```

### Logo Customization
- Current logo: `assets/images/logo.svg` (placeholder)
- Replace with your actual logo: Save your logo image as `logo.png` or `logo.svg`
- Recommended size: 200x80px

## Manual Start/Stop

### Start Server Manually
```bash
cd /home/believer/auravibe
./start.sh
```

Or:
```bash
php -S localhost:8000
```

### Stop Server
Press `Ctrl+C` in the terminal where server is running

Or:
```bash
pkill -f "php -S localhost:8000"
```

## Testing the Website

1. **Open Browser:** Navigate to http://localhost:8000
2. **Watch 3D Animation:** The hero section should show gold particles and rotating rings
3. **Test Navigation:** Click through the menu items
4. **View Products:** Scroll down to see featured products section
5. **Test Admin:** Login at /admin/login.php

## Troubleshooting

### Port Already in Use
If port 8000 is busy, use a different port:
```bash
php -S localhost:8080
```

### Database Connection Errors
The site will still display without database. To fix:
1. Make sure MySQL is running: `sudo systemctl start mysql`
2. Import the database.sql file
3. Check credentials in includes/db.php

### 3D Animation Not Showing
- Clear browser cache (Ctrl+Shift+R)
- Check browser console (F12) for errors
- Ensure WebGL is supported in your browser

### Styling Issues
- Clear browser cache
- Check if assets/css/style.css loads correctly
- Open browser developer tools (F12) to debug

## Browser Recommendations

For best results, use:
- Google Chrome (recommended)
- Mozilla Firefox
- Microsoft Edge
- Safari

## Development Tips

### File Locations
- Homepage: `index.php`
- Admin Panel: `admin/`
- CSS: `assets/css/style.css`
- JavaScript: `assets/js/main.js`
- 3D Settings: Look for `initHero3D()` in main.js

### Making Changes
- Edit files and refresh browser (Ctrl+R)
- For CSS changes: Hard refresh (Ctrl+Shift+R)
- For PHP changes: Just refresh normally

### Adding Products
1. Login to admin panel
2. Navigate to Products section (when created)
3. Or add directly to database via phpMyAdmin

## Next Steps

1. ✅ Website is running - **Check http://localhost:8000**
2. 📦 Import database for full functionality
3. 🎨 Replace placeholder logo with your actual logo
4. 🛠️ Customize colors and design as needed
5. 📝 Add actual watch products
6. 🚀 Ready to deploy to Hostinger when done

## Features to Explore

- **3D Hero Animation** - Move your mouse around to see interactive particles
- **Responsive Design** - Resize browser to see mobile layout
- **Gold Theme** - Premium luxury watch aesthetic
- **Admin Dashboard** - Check statistics and manage store
- **Product Cards** - Hover over products for effects

## Quick Commands Reference

```bash
# Start server
./start.sh

# Stop server
Ctrl+C

# Check if running
curl http://localhost:8000

# View server logs
php -S localhost:8000

# Create database
mysql -u root -p < database.sql
```

## Getting Help

- Check README.md for detailed documentation
- Check SETUP.md for deployment instructions
- Check browser console (F12) for JavaScript errors
- Check terminal for PHP errors

---

**Enjoy building your luxury watch store!** ✨

Current Status: **RUNNING** on http://localhost:8000
