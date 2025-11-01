# 🚀 AuraVibe - Clean Deployment Instructions

## ✅ What's Included in This Package

This is a CLEAN deployment package with:
- ✓ All test files removed
- ✓ All diagnostic files removed  
- ✓ Updated .htaccess with HTTPS enabled
- ✓ Security protections added
- ✓ Correct file structure for Hostinger

---

## 📦 Quick Deployment to Hostinger

### Step 1: Upload Files

1. Log in to **Hostinger hPanel**
2. Go to **File Manager**
3. Navigate to `public_html`
4. **Delete** all existing files (backup first if needed)
5. Upload `auravibe-clean-deploy.zip`
6. Right-click → **Extract**
7. Delete the zip file

---

### Step 2: Move Files from public/ folder to public_html/

Since Hostinger's document root is `public_html`, you need to move contents:

1. Go into `public_html/public/` folder
2. Select ALL files and folders (index.php, shop.php, cart.php, admin/, api/, assets/, etc.)
3. Click **Move**
4. Navigate to `public_html` (parent folder)
5. Click **Move Here**
6. Confirm overwrite for any existing files

**Your final structure should be:**
```
public_html/
├── index.php          ← From public folder
├── shop.php           ← From public folder
├── cart.php           ← From public folder
├── admin/             ← From public folder
├── api/               ← From public folder
├── assets/            ← From public folder
├── uploads/           ← From public folder
├── config-paths.php   ← From public folder
├── app/               ← Original location
├── config/            ← Original location
├── database/          ← Original location
├── includes/          ← Original location
├── bootstrap.php      ← Original location
├── .htaccess          ← Root file
└── .env               ← Create this next
```

---

### Step 3: Create .env File

1. In `public_html`, create new file: `.env`
2. Copy content from `.env.example`
3. Update with YOUR actual credentials:

```env
# Application
APP_ENV=production
APP_DEBUG=false
APP_URL=https://auravibe.site
APP_NAME=AuraVibe

# Database (GET FROM HOSTINGER)
DB_HOST=localhost
DB_DATABASE=u446437128_auravibe    ← YOUR database name
DB_USERNAME=u446437128_auravibe    ← YOUR username
DB_PASSWORD=your_database_password ← YOUR password

# Paymob (GET FROM PAYMOB DASHBOARD)
PAYMOB_API_KEY=your_paymob_api_key
PAYMOB_HMAC_SECRET=your_hmac_secret
PAYMOB_CARD_INTEGRATION_ID=5362354
PAYMOB_WALLET_INTEGRATION_ID=5362355
PAYMOB_INSTALLMENTS_INTEGRATION_ID=5362356
PAYMOB_IFRAME_ID=5362354

# Currency
CURRENCY=EGP
CURRENCY_SYMBOL=EGP
```

---

### Step 4: Create Database

1. Go to hPanel → **Databases** → **MySQL Databases**
2. Click **Create New Database**
3. Name: `auravibe`
4. Hostinger creates: `u446437128_auravibe` (note the prefix)
5. **Add User to Database:**
   - Go to **Add User To Database**
   - Select user and database
   - Check **ALL PRIVILEGES**
   - Click **Make Changes**

---

### Step 5: Import Database

1. Go to **Databases** → **phpMyAdmin**
2. Select your database
3. Click **Import** tab
4. Import these files IN ORDER:
   - First: `public_html/database/create_users_table.sql`
   - Then: `public_html/database/create_orders_tables.sql`
   - Then: `public_html/database/create_shipping_table.sql`
   - Finally: `public_html/database/database.sql` (if you have products)

---

### Step 6: Set Permissions

1. Right-click `uploads/` folder → **Permissions** → Set to `755`
2. Right-click `.env` file → **Permissions** → Set to `600`

---

### Step 7: Update config-paths.php (if needed)

Open `public_html/config-paths.php` and verify:

```php
<?php
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
define('INCLUDES_PATH', BASE_PATH . '/includes');
define('PUBLIC_PATH', BASE_PATH);
define('UPLOADS_PATH', BASE_PATH . '/uploads');
define('ASSETS_PATH', BASE_PATH . '/assets');
define('BASE_URL', 'https://auravibe.site'); // ← YOUR DOMAIN

function base_url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

function include_view($file) {
    $viewPath = INCLUDES_PATH . '/' . $file;
    if (file_exists($viewPath)) {
        include $viewPath;
    }
}

function asset_url($path) {
    return BASE_URL . '/assets/' . ltrim($path, '/');
}
?>
```

---

### Step 8: Enable SSL

1. Go to hPanel → **Security** → **SSL**
2. Click **Install SSL**
3. Select **Free SSL** (Let's Encrypt)
4. Click **Install**
5. Wait 5-10 minutes

---

### Step 9: Test Your Site

Visit: `https://auravibe.site`

Test these pages:
- ✅ Homepage
- ✅ Shop
- ✅ Product page
- ✅ Cart
- ✅ Checkout
- ✅ Admin panel: `/admin/login.php`
- ✅ Language switcher (EN/AR)

---

### Step 10: Configure Paymob

1. Log in to **Paymob Dashboard**: https://accept.paymob.com
2. Go to **Settings** → **Account Info**
3. Copy your API Key, HMAC Secret, Integration IDs
4. Update `.env` file with these values
5. Set Callback URLs:
   - Transaction Processed: `https://auravibe.site/paymob-callback.php`
   - Transaction Response: `https://auravibe.site/order-success.php`

---

## 🎯 What's New in This Package

### Security Improvements:
- ✓ HTTPS forced (no more HTTP)
- ✓ .env file protected
- ✓ Database files protected
- ✓ Config files protected
- ✓ Directory browsing disabled

### Fixes Applied:
- ✓ Brand field added to cart items
- ✓ Modal popups for Terms & Privacy
- ✓ All test/diagnostic files removed
- ✓ Clean file structure

---

## 📝 Post-Deployment Tasks

1. **Delete the `public/` folder** (after moving files)
2. **Add products** via Admin panel
3. **Test checkout** with Paymob
4. **Set up backups** in Hostinger
5. **Monitor error logs**: hPanel → Advanced → Error Logs

---

## ⚠️ Troubleshooting

### HTTP 500 Error
- Check error logs in hPanel
- Verify `.env` file exists and has correct values
- Check file permissions

### Database Connection Error
- Verify database credentials in `.env`
- Make sure user has ALL PRIVILEGES
- Check database name includes prefix (u446437128_)

### Images Not Loading
- Check `uploads/` folder permissions (755)
- Verify `BASE_URL` in config-paths.php
- Check image paths in database

---

## ✅ Success Checklist

- [ ] Files uploaded and extracted
- [ ] Files moved from public/ to public_html/
- [ ] .env file created with correct credentials
- [ ] Database created in Hostinger
- [ ] Database tables imported
- [ ] Permissions set correctly
- [ ] SSL certificate installed
- [ ] Homepage loads without errors
- [ ] All pages working
- [ ] Admin panel accessible
- [ ] Paymob configured

---

🎉 **Your AuraVibe store is now LIVE!**

For support, check error logs or contact Hostinger support.
