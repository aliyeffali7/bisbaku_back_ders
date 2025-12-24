# 🚀 Laravel cPanel Deployment Guide

## 📋 Pre-Deployment Checklist

### What You Need:
- ✅ cPanel access
- ✅ Main domain (e.g., `yourdomain.com`)
- ✅ Subdomain for backend (e.g., `api.yourdomain.com`)
- ✅ Subdomain for frontend (e.g., `app.yourdomain.com` or `www.yourdomain.com`)
- ✅ PHP 8.1+ on server
- ✅ MySQL database access

---

## 📦 Step 1: Prepare Project for Upload

### Files to Upload:
- ✅ All project files EXCEPT:
  - `vendor/` folder (will install on server)
  - `node_modules/` (not needed for Laravel backend)
  - `.env` file (will create on server)
  - `.git/` folder (optional)

---

## 🌐 Step 2: Create Subdomain in cPanel

1. **Login to cPanel**
2. **Go to "Subdomains"** (or "Subdomain Manager")
3. **Create Backend Subdomain:**
   - Subdomain: `api` (or `backend`)
   - Domain: Select your main domain
   - Document Root: `/public_html/api` (or `/public_html/backend`)
   - Click "Create"

4. **Create Frontend Subdomain** (if needed):
   - Subdomain: `app` (or `www`)
   - Document Root: `/public_html/app`
   - Click "Create"

---

## 🗄️ Step 3: Create Database in cPanel

1. **Go to "MySQL Databases"** in cPanel
2. **Create New Database:**
   - Database Name: `yourusername_bisbaku` (cPanel adds prefix automatically)
   - Click "Create Database"
   - **Note the full database name!**

3. **Create Database User:**
   - Username: `yourusername_bisbaku_user`
   - Password: Create strong password (save it!)
   - Click "Create User"

4. **Add User to Database:**
   - Select the user
   - Select the database
   - Click "Add"
   - **Check "ALL PRIVILEGES"**
   - Click "Make Changes"

---

## 📤 Step 4: Upload Files to cPanel

### Option A: Using File Manager
1. Go to **"File Manager"** in cPanel
2. Navigate to your subdomain folder (e.g., `public_html/api`)
3. **Upload all project files** (zip first, then extract)

### Option B: Using FTP/SFTP
1. Use FileZilla or similar
2. Connect to your server
3. Upload to: `/public_html/api/` (or your subdomain path)

### ⚠️ Important: After Upload
- Move contents of `public/` folder to subdomain root
- Move all other files one level up
- OR configure `.htaccess` to point to `public/` folder

---

## ⚙️ Step 5: Configure Laravel on Server

### 5.1: Set File Permissions
In cPanel File Manager or via SSH:
```bash
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### 5.2: Create `.env` File
1. In File Manager, go to your project root
2. Copy `.env.example` to `.env`
3. Edit `.env` with these settings:

```env
APP_NAME="Bisbaku API"
APP_ENV=production
APP_KEY=                    # Will generate
APP_DEBUG=false
APP_URL=https://api.yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=yourusername_bisbaku
DB_USERNAME=yourusername_bisbaku_user
DB_PASSWORD=your_password_here
```

### 5.3: Install Dependencies
Via SSH (Terminal in cPanel) or use cPanel's "Terminal":
```bash
cd ~/public_html/api  # or your subdomain path
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🔧 Step 6: Configure .htaccess

Create/Update `.htaccess` in your subdomain root:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

## ✅ Step 7: Test Your API

1. Visit: `https://api.yourdomain.com/api/courses`
2. Should return JSON response
3. Swagger UI: `https://api.yourdomain.com/api/documentation`

---

## 🔗 Step 8: Connect Frontend

In your frontend code, use:
```javascript
const API_URL = 'https://api.yourdomain.com/api';
```

Update CORS in `config/cors.php`:
```php
'allowed_origins' => [
    'https://app.yourdomain.com',
    'https://www.yourdomain.com',
],
```

---

## 🐛 Troubleshooting

### 500 Error:
- Check file permissions
- Check `.env` file exists
- Check `storage/logs/laravel.log`

### Database Connection Error:
- Verify database credentials in `.env`
- Check database user has privileges

### Route Not Found:
- Run: `php artisan route:cache`
- Check `.htaccess` file

---

## 📝 Quick Commands Reference

```bash
# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🎯 Next Steps After Deployment

1. ✅ Test all API endpoints
2. ✅ Update frontend API URL
3. ✅ Test authentication
4. ✅ Set up SSL certificate (Let's Encrypt in cPanel)
5. ✅ Configure backups

