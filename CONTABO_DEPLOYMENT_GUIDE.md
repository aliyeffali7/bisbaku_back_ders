# 🚀 Contabo Server Deployment Guide

## ✅ YOUR SETUP:
- **Server IP:** 194.163.173.179
- **OS:** Ubuntu
- **GitHub Repo:** https://github.com/rustammgasanovv/bisbaku-back.git
- **SSH Access:** ✅ Yes

---

## 📋 STEP 1: CONNECT TO SERVER

```bash
ssh root@194.163.173.179
# Or if you have a username:
ssh your_username@194.163.173.179
```

---

## 📦 STEP 2: INSTALL REQUIRED SOFTWARE

Run these commands one by one:

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2 and extensions
sudo apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Install MySQL (if not installed)
sudo apt install -y mysql-server

# Install Nginx
sudo apt install -y nginx

# Install Git (if not installed)
sudo apt install -y git

# Verify installations
php -v
composer --version
mysql --version
nginx -v
```

---

## 🗄️ STEP 3: SETUP DATABASE

```bash
# Secure MySQL installation
sudo mysql_secure_installation
# Follow prompts (set root password, remove test DB, etc.)

# Login to MySQL
sudo mysql -u root -p

# Create database and user
CREATE DATABASE bisbaku_ders_api;
CREATE USER 'bisbaku_ders_user'@'localhost' IDENTIFIED BY 'fluenT072315';
GRANT ALL PRIVILEGES ON bisbaku_ders_api.* TO 'bisbaku_ders_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## 📥 STEP 4: CLONE PROJECT

```bash
# Navigate to web root (or create directory)
cd /var/www
# Or use: cd /home/your_username

# Clone repository
git clone https://github.com/rustammgasanovv/bisbaku-back.git
cd bisbaku-back

# Or if you want to name it differently:
# git clone https://github.com/rustammgasanovv/bisbaku-back.git backend
# cd backend
```

---

## ⚙️ STEP 5: INSTALL DEPENDENCIES

```bash
# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Create .env file
cp .env.example .env

# Edit .env file
nano .env
# Or use: vi .env
```

**Update .env with these values:**

```env
APP_NAME="Bisbaku API"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://194.163.173.179

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bisbaku_ders_api
DB_USERNAME=bisbaku_ders_user
DB_PASSWORD=fluenT072315

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mail.bisbaku.az
MAIL_PORT=465
MAIL_USERNAME=office@bisbaku.az
MAIL_PASSWORD=ndtbis2019@
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=office@bisbaku.az
MAIL_FROM_NAME="BIS Baku"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

**Save:** `Ctrl+X`, then `Y`, then `Enter`

---

## 🔑 STEP 6: GENERATE APP KEY & RUN MIGRATIONS

```bash
# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🔐 STEP 7: SET PERMISSIONS

```bash
# Set ownership (replace 'www-data' with your web server user if different)
sudo chown -R www-data:www-data /var/www/bisbaku-back
# Or if in home directory:
# sudo chown -R $USER:$USER /home/your_username/bisbaku-back

# Set permissions
sudo chmod -R 755 /var/www/bisbaku-back
sudo chmod -R 775 /var/www/bisbaku-back/storage
sudo chmod -R 775 /var/www/bisbaku-back/bootstrap/cache
```

---

## 🌐 STEP 8: CONFIGURE NGINX

```bash
# Create Nginx configuration
sudo nano /etc/nginx/sites-available/bisbaku-back
```

**Paste this configuration:**

```nginx
server {
    listen 80;
    server_name 194.163.173.179;
    root /var/www/bisbaku-back/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

**Save:** `Ctrl+X`, then `Y`, then `Enter`

```bash
# Enable the site
sudo ln -s /etc/nginx/sites-available/bisbaku-back /etc/nginx/sites-enabled/

# Test Nginx configuration
sudo nginx -t

# Restart Nginx
sudo systemctl restart nginx
```

---

## 🔥 STEP 9: CONFIGURE FIREWALL (Optional but Recommended)

```bash
# Install UFW if not installed
sudo apt install -y ufw

# Allow SSH (IMPORTANT - do this first!)
sudo ufw allow 22/tcp

# Allow HTTP
sudo ufw allow 80/tcp

# Allow HTTPS (if you set up SSL later)
sudo ufw allow 443/tcp

# Enable firewall
sudo ufw enable

# Check status
sudo ufw status
```

---

## ✅ STEP 10: TEST YOUR API

1. **Open browser:**
   - Visit: `http://194.163.173.179/api/courses`
   - Should see JSON response

2. **Test Swagger:**
   - Visit: `http://194.163.173.179/api/documentation`

---

## 🔄 STEP 11: SETUP AUTO-DEPLOYMENT (Optional)

Create a script to update from GitHub:

```bash
# Create update script
nano /var/www/bisbaku-back/update.sh
```

**Paste:**

```bash
#!/bin/bash
cd /var/www/bisbaku-back
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Make executable:**
```bash
chmod +x /var/www/bisbaku-back/update.sh
```

**To update later, just run:**
```bash
/var/www/bisbaku-back/update.sh
```

---

## 🐛 TROUBLESHOOTING

### 502 Bad Gateway:
```bash
# Check PHP-FPM status
sudo systemctl status php8.2-fpm

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm
```

### Permission Denied:
```bash
# Fix ownership
sudo chown -R www-data:www-data /var/www/bisbaku-back
sudo chmod -R 755 /var/www/bisbaku-back
sudo chmod -R 775 /var/www/bisbaku-back/storage
```

### Database Connection Error:
```bash
# Test MySQL connection
mysql -u bisbaku_ders_user -p bisbaku_ders_api
# Enter password: fluenT072315
```

### Check Logs:
```bash
# Laravel logs
tail -f /var/www/bisbaku-back/storage/logs/laravel.log

# Nginx error logs
sudo tail -f /var/log/nginx/error.log

# PHP-FPM logs
sudo tail -f /var/log/php8.2-fpm.log
```

---

## 📝 QUICK COMMAND REFERENCE

```bash
# Restart services
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
sudo systemctl restart mysql

# Check service status
sudo systemctl status nginx
sudo systemctl status php8.2-fpm
sudo systemctl status mysql

# Clear Laravel cache
cd /var/www/bisbaku-back
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 🎯 FRONTEND CONNECTION

In your frontend code, use:
```javascript
const API_URL = 'http://194.163.173.179/api';
```

Update CORS in `config/cors.php`:
```php
'allowed_origins' => ['*'], // Or specify your frontend URL
```

---

## ✅ DEPLOYMENT CHECKLIST

- [ ] Connected to server via SSH
- [ ] Installed PHP 8.2, Composer, MySQL, Nginx
- [ ] Created database and user
- [ ] Cloned repository from GitHub
- [ ] Installed Composer dependencies
- [ ] Created and configured .env file
- [ ] Generated app key
- [ ] Ran migrations
- [ ] Set correct permissions
- [ ] Configured Nginx
- [ ] Tested API endpoint
- [ ] Configured firewall (optional)

---

**Follow these steps in order and let me know if you hit any errors!** 🚀

