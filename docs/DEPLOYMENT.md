# Deployment Guide - AuraVibe

Complete guide for deploying AuraVibe to production servers.

## Table of Contents

- [Pre-Deployment Checklist](#pre-deployment-checklist)
- [Server Requirements](#server-requirements)
- [Deployment Methods](#deployment-methods)
- [Production Configuration](#production-configuration)
- [SSL/HTTPS Setup](#sslhttps-setup)
- [Database Migration](#database-migration)
- [Performance Optimization](#performance-optimization)
- [Monitoring](#monitoring)
- [Backup Strategy](#backup-strategy)
- [Rollback Procedures](#rollback-procedures)

## Pre-Deployment Checklist

- [ ] All features tested locally
- [ ] Database migrations prepared
- [ ] Environment variables configured
- [ ] SSL certificate ready
- [ ] Backup strategy in place
- [ ] Monitoring tools configured
- [ ] Payment gateway in production mode
- [ ] Email configuration verified
- [ ] Security audit completed
- [ ] Performance testing done
- [ ] Documentation updated

## Server Requirements

### Minimum Requirements

- **OS:** Ubuntu 20.04 LTS or newer
- **PHP:** 7.4 or higher
- **MySQL:** 8.0 or higher
- **Web Server:** Apache 2.4+ or Nginx 1.18+
- **RAM:** 2GB minimum, 4GB recommended
- **Storage:** 20GB minimum, SSD recommended
- **CPU:** 2 cores minimum

### PHP Extensions

```bash
php -m | grep -E 'mysqli|pdo|mbstring|json|curl|xml|gd|zip'
```

Required extensions:
- mysqli
- pdo_mysql
- mbstring
- json
- curl
- xml
- gd
- zip
- openssl

## Deployment Methods

### Method 1: Manual Deployment via FTP/SFTP

1. **Prepare Files Locally**
   ```bash
   # Remove development files
   rm -rf .git/
   rm .env
   rm -rf tests/

   # Create deployment package
   tar -czf auravibe-deploy.tar.gz .
   ```

2. **Upload to Server**
   ```bash
   scp auravibe-deploy.tar.gz user@yourserver.com:/var/www/
   ```

3. **Extract on Server**
   ```bash
   ssh user@yourserver.com
   cd /var/www
   tar -xzf auravibe-deploy.tar.gz
   ```

4. **Set Permissions**
   ```bash
   chown -R www-data:www-data /var/www/auravibe
   chmod -R 755 /var/www/auravibe
   chmod -R 775 /var/www/auravibe/public/uploads
   ```

### Method 2: Git Deployment

1. **Setup Git on Server**
   ```bash
   ssh user@yourserver.com
   cd /var/www
   git clone https://github.com/yourusername/auravibe.git
   cd auravibe
   ```

2. **Create Deployment Script**
   ```bash
   nano deploy.sh
   ```

   ```bash
   #!/bin/bash

   # Pull latest changes
   git pull origin main

   # Set permissions
   chown -R www-data:www-data /var/www/auravibe
   chmod -R 755 /var/www/auravibe
   chmod -R 775 /var/www/auravibe/public/uploads

   # Clear cache (if applicable)
   rm -rf cache/*

   # Restart services
   sudo systemctl reload apache2

   echo "Deployment complete!"
   ```

   ```bash
   chmod +x deploy.sh
   ```

3. **Deploy Updates**
   ```bash
   ./deploy.sh
   ```

### Method 3: Automated CI/CD (GitHub Actions)

Create `.github/workflows/deploy.yml`:

```yaml
name: Deploy to Production

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest

    steps:
    - uses: actions/checkout@v2

    - name: Deploy to Server
      uses: appleboy/ssh-action@master
      with:
        host: ${{ secrets.HOST }}
        username: ${{ secrets.USERNAME }}
        key: ${{ secrets.SSH_KEY }}
        script: |
          cd /var/www/auravibe
          git pull origin main
          chown -R www-data:www-data /var/www/auravibe
          chmod -R 755 /var/www/auravibe
          systemctl reload apache2
```

## Production Configuration

### 1. Environment Variables

Create production `.env`:

```bash
nano /var/www/auravibe/.env
```

```env
# Application Settings
APP_NAME="AuraVibe"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://auravibe.com

# Database Configuration
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=auravibe_prod
DB_USERNAME=auravibe_prod_user
DB_PASSWORD=STRONG_RANDOM_PASSWORD_HERE

# Paymob Payment Gateway (PRODUCTION)
PAYMOB_API_KEY=your_production_api_key
PAYMOB_HMAC_SECRET=your_production_hmac_secret
PAYMOB_SECRET_KEY=egy_sk_live_...
PAYMOB_PUBLIC_KEY=egy_pk_live_...

# Paymob PRODUCTION Integration IDs
PAYMOB_INTEGRATION_ONLINE_CARD=5362354
PAYMOB_INTEGRATION_TAP_ON_PHONE=5362355
PAYMOB_INTEGRATION_MOBILE_WALLET=5362356

# Session Configuration
SESSION_LIFETIME=120
SESSION_SECURE=true

# Security
SECURE_COOKIES=true
HTTPS_ONLY=true

# Debug Mode (MUST BE FALSE)
DEBUG=false
DISPLAY_ERRORS=false
```

**IMPORTANT:** Protect .env file:
```bash
chmod 600 /var/www/auravibe/.env
chown www-data:www-data /var/www/auravibe/.env
```

### 2. PHP Configuration

Edit `php.ini`:

```bash
sudo nano /etc/php/7.4/apache2/php.ini
```

Production settings:
```ini
; Error handling
display_errors = Off
display_startup_errors = Off
error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT
log_errors = On
error_log = /var/log/php/error.log

; Performance
memory_limit = 256M
max_execution_time = 60
max_input_time = 60
post_max_size = 20M
upload_max_filesize = 10M

; OPcache
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.fast_shutdown=1

; Session
session.cookie_secure = 1
session.cookie_httponly = 1
session.use_strict_mode = 1
```

Restart PHP:
```bash
sudo systemctl restart apache2
```

### 3. Apache Virtual Host

```bash
sudo nano /etc/apache2/sites-available/auravibe.conf
```

```apache
<VirtualHost *:80>
    ServerName auravibe.com
    ServerAlias www.auravibe.com

    # Redirect to HTTPS
    Redirect permanent / https://auravibe.com/
</VirtualHost>

<VirtualHost *:443>
    ServerName auravibe.com
    ServerAlias www.auravibe.com

    DocumentRoot /var/www/auravibe/public

    <Directory /var/www/auravibe/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/auravibe.com/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/auravibe.com/privkey.pem

    # Security Headers
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-XSS-Protection "1; mode=block"

    # Logging
    ErrorLog ${APACHE_LOG_DIR}/auravibe_error.log
    CustomLog ${APACHE_LOG_DIR}/auravibe_access.log combined

    # Compression
    <IfModule mod_deflate.c>
        AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
    </IfModule>
</VirtualHost>
```

Enable site:
```bash
sudo a2ensite auravibe
sudo a2enmod ssl headers deflate rewrite
sudo systemctl reload apache2
```

### 4. Nginx Configuration (Alternative)

```bash
sudo nano /etc/nginx/sites-available/auravibe
```

```nginx
# Redirect HTTP to HTTPS
server {
    listen 80;
    server_name auravibe.com www.auravibe.com;
    return 301 https://auravibe.com$request_uri;
}

# HTTPS Server
server {
    listen 443 ssl http2;
    server_name auravibe.com www.auravibe.com;

    root /var/www/auravibe/public;
    index index.php index.html;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/auravibe.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/auravibe.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Security Headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    # Logging
    access_log /var/log/nginx/auravibe_access.log;
    error_log /var/log/nginx/auravibe_error.log;

    # Main location
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP handling
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Deny access to sensitive files
    location ~ /\.env {
        deny all;
    }

    location ~ /\.git {
        deny all;
    }

    # Cache static files
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/auravibe /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## SSL/HTTPS Setup

### Using Let's Encrypt (Free)

1. **Install Certbot**
   ```bash
   sudo apt install certbot python3-certbot-apache
   ```

2. **Obtain Certificate**
   ```bash
   sudo certbot --apache -d auravibe.com -d www.auravibe.com
   ```

3. **Auto-Renewal**
   ```bash
   sudo certbot renew --dry-run
   ```

Certbot will automatically add cron job for renewal.

### Using Custom SSL Certificate

1. **Upload Certificate Files**
   ```bash
   sudo mkdir -p /etc/ssl/auravibe
   sudo cp fullchain.pem /etc/ssl/auravibe/
   sudo cp privkey.pem /etc/ssl/auravibe/
   sudo chmod 600 /etc/ssl/auravibe/*
   ```

2. **Update Apache/Nginx Config**
   Point to certificate files in virtual host configuration.

## Database Migration

### Production Database Setup

1. **Create Production Database**
   ```bash
   mysql -u root -p
   ```

   ```sql
   CREATE DATABASE auravibe_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   CREATE USER 'auravibe_prod_user'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD';
   GRANT ALL PRIVILEGES ON auravibe_prod.* TO 'auravibe_prod_user'@'localhost';
   FLUSH PRIVILEGES;
   EXIT;
   ```

2. **Import Schema**
   ```bash
   mysql -u auravibe_prod_user -p auravibe_prod < database/schema.sql
   ```

3. **Import Initial Data**
   ```bash
   mysql -u auravibe_prod_user -p auravibe_prod < database/seed.sql
   ```

### Migrating from Development

1. **Export Development Database**
   ```bash
   mysqldump -u auravibe_user -p auravibe > dev_backup.sql
   ```

2. **Import to Production**
   ```bash
   mysql -u auravibe_prod_user -p auravibe_prod < dev_backup.sql
   ```

3. **Update Configuration**
   - Change admin passwords
   - Update payment gateway to production keys
   - Verify all data

## Performance Optimization

### 1. Enable PHP OPcache

Already configured in php.ini above.

### 2. MySQL Optimization

```sql
-- Add indexes for better performance
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_stock ON products(stock);
CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(order_status);
CREATE INDEX idx_order_items_order ON order_items(order_id);
```

### 3. Enable Apache/Nginx Compression

Already configured in virtual host.

### 4. Image Optimization

Install image optimization tools:
```bash
sudo apt install optipng jpegoptim
```

Optimize existing images:
```bash
find public/uploads -name "*.png" -exec optipng {} \;
find public/uploads -name "*.jpg" -exec jpegoptim --max=85 {} \;
```

### 5. Enable CDN (Optional)

Use Cloudflare for free CDN:
1. Sign up at cloudflare.com
2. Add your domain
3. Update nameservers
4. Enable caching rules

## Monitoring

### 1. Error Monitoring

Create log directory:
```bash
sudo mkdir -p /var/log/auravibe
sudo chown www-data:www-data /var/log/auravibe
```

Update error logging in code:
```php
ini_set('error_log', '/var/log/auravibe/php-errors.log');
```

### 2. Uptime Monitoring

Use services like:
- UptimeRobot (free)
- Pingdom
- StatusCake

### 3. Application Monitoring

Install monitoring tools:
```bash
# New Relic (optional)
wget -O - https://download.newrelic.com/548C16BF.gpg | sudo apt-key add -
echo 'deb http://apt.newrelic.com/debian/ newrelic non-free' | sudo tee /etc/apt/sources.list.d/newrelic.list
sudo apt update && sudo apt install newrelic-php5
```

### 4. Log Monitoring

Setup log rotation:
```bash
sudo nano /etc/logrotate.d/auravibe
```

```
/var/log/auravibe/*.log {
    daily
    rotate 14
    compress
    delaycompress
    notifempty
    create 640 www-data www-data
    sharedscripts
    postrotate
        systemctl reload apache2
    endscript
}
```

## Backup Strategy

### 1. Database Backup

Create backup script:
```bash
nano /root/backup-db.sh
```

```bash
#!/bin/bash

# Configuration
DB_NAME="auravibe_prod"
DB_USER="auravibe_prod_user"
DB_PASS="YOUR_PASSWORD"
BACKUP_DIR="/backups/database"
DATE=$(date +%Y%m%d_%H%M%S)

# Create backup
mkdir -p $BACKUP_DIR
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/auravibe_$DATE.sql.gz

# Keep only last 30 days
find $BACKUP_DIR -name "auravibe_*.sql.gz" -mtime +30 -delete

echo "Database backup completed: $DATE"
```

Make executable:
```bash
chmod +x /root/backup-db.sh
```

### 2. File Backup

```bash
nano /root/backup-files.sh
```

```bash
#!/bin/bash

# Configuration
SOURCE_DIR="/var/www/auravibe"
BACKUP_DIR="/backups/files"
DATE=$(date +%Y%m%d_%H%M%S)

# Create backup
mkdir -p $BACKUP_DIR
tar -czf $BACKUP_DIR/auravibe_files_$DATE.tar.gz $SOURCE_DIR

# Keep only last 14 days
find $BACKUP_DIR -name "auravibe_files_*.tar.gz" -mtime +14 -delete

echo "File backup completed: $DATE"
```

Make executable:
```bash
chmod +x /root/backup-files.sh
```

### 3. Automated Backups

Setup cron jobs:
```bash
sudo crontab -e
```

```cron
# Daily database backup at 2 AM
0 2 * * * /root/backup-db.sh >> /var/log/backup-db.log 2>&1

# Weekly file backup every Sunday at 3 AM
0 3 * * 0 /root/backup-files.sh >> /var/log/backup-files.log 2>&1
```

### 4. Offsite Backup

Sync to S3 or similar:
```bash
aws s3 sync /backups s3://auravibe-backups/ --delete
```

## Rollback Procedures

### Database Rollback

1. **Restore from Backup**
   ```bash
   gunzip < /backups/database/auravibe_YYYYMMDD_HHMMSS.sql.gz | mysql -u auravibe_prod_user -p auravibe_prod
   ```

2. **Verify Data**
   ```sql
   SELECT COUNT(*) FROM products;
   SELECT COUNT(*) FROM orders;
   ```

### Application Rollback

#### Using Git

```bash
cd /var/www/auravibe
git log --oneline  # Find commit to rollback to
git checkout <commit-hash>
sudo systemctl reload apache2
```

#### Using Backup

```bash
cd /var/www
rm -rf auravibe
tar -xzf /backups/files/auravibe_files_YYYYMMDD_HHMMSS.tar.gz
sudo systemctl reload apache2
```

## Post-Deployment Checklist

- [ ] Website accessible via HTTPS
- [ ] All pages loading correctly
- [ ] Admin panel accessible
- [ ] Shopping cart working
- [ ] Checkout process functional
- [ ] Payment gateway tested
- [ ] Email notifications working
- [ ] Mobile responsiveness verified
- [ ] Arabic/RTL display correct
- [ ] SSL certificate valid
- [ ] Monitoring tools active
- [ ] Backups scheduled
- [ ] Error logs checked

## Troubleshooting

### Issue: 500 Internal Server Error

Check error logs:
```bash
tail -f /var/log/apache2/auravibe_error.log
tail -f /var/log/auravibe/php-errors.log
```

Common causes:
- Incorrect file permissions
- .htaccess errors
- PHP errors (check error log)

### Issue: Database Connection Failed

Verify:
```bash
mysql -u auravibe_prod_user -p auravibe_prod
```

Check .env configuration.

### Issue: Payment Not Working

1. Verify production Paymob keys
2. Check integration IDs
3. Test callback URL is accessible
4. Review Paymob dashboard for errors

## Security Hardening

### 1. Disable Directory Listing

Already configured in Apache/Nginx.

### 2. Protect Sensitive Files

Create .htaccess in root:
```apache
<Files ".env">
    Order allow,deny
    Deny from all
</Files>

<Files "*.sql">
    Order allow,deny
    Deny from all
</Files>
```

### 3. Install Fail2Ban

```bash
sudo apt install fail2ban
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

### 4. Configure Firewall

```bash
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

### 5. Regular Updates

```bash
sudo apt update && sudo apt upgrade -y
```

## Maintenance Mode

Create maintenance page:
```bash
nano /var/www/auravibe/public/maintenance.html
```

Enable maintenance mode:
```bash
mv /var/www/auravibe/public/index.php /var/www/auravibe/public/index.php.bak
mv /var/www/auravibe/public/maintenance.html /var/www/auravibe/public/index.html
```

Disable maintenance mode:
```bash
mv /var/www/auravibe/public/index.html /var/www/auravibe/public/maintenance.html
mv /var/www/auravibe/public/index.php.bak /var/www/auravibe/public/index.php
```

---

## Support

For deployment issues:
- Email: devops@auravibe.com
- Slack: #auravibe-ops
- Emergency: +20 XXX XXX XXXX

---

**Deployment Checklist:** Use this guide step-by-step for a successful deployment!
