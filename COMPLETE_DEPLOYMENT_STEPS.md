# 🚀 COMPLETE DEPLOYMENT STEPS - Follow This Exactly

## 📋 INFORMATION I NEED FROM YOU:

Please provide:
1. **Main domain name:** (e.g., `yourdomain.com`)
2. **Subdomain for backend:** (e.g., `api` or `backend`)
3. **Existing database name:** (from cPanel)
4. **Existing database username:** (from cPanel)
5. **Database password:** (you should know this)

---

## ✅ STEP 1: CREATE SUBDOMAIN IN cPanel

1. Login to cPanel
2. Find **"Subdomains"** (search in top bar if needed)
3. Click **"Subdomains"**
4. Fill in:
   - **Subdomain:** `api` (or `backend` - your choice)
   - **Domain:** Select your main domain
   - **Document Root:** Leave default (usually `/public_html/api`)
5. Click **"Create"**
6. ✅ **Note the Document Root path!** (e.g., `/public_html/api`)

---

## ✅ STEP 2: PREPARE FILES FOR UPLOAD

### Option A: Using File Manager (Easier)
1. On your computer, **zip the entire project folder**
2. **EXCLUDE these from zip:**
   - `vendor/` folder (we'll install on server)
   - `node_modules/` (not needed)
   - `.env` file (we'll create on server)
   - `.git/` folder (optional)

### Option B: Using FTP
- Just upload all files directly (excluding vendor, node_modules, .env)

---

## ✅ STEP 3: UPLOAD FILES TO cPanel

### Using File Manager:
1. Go to **"File Manager"** in cPanel
2. Navigate to your subdomain folder (e.g., `public_html/api`)
3. Click **"Upload"** button
4. Upload your zip file
5. **Right-click the zip file** → **Extract**
6. Delete the zip file after extraction

### Using FTP:
1. Connect via FileZilla or similar
2. Upload all files to: `/public_html/api/` (or your subdomain path)

---

## ✅ STEP 4: MOVE FILES TO CORRECT LOCATION

**IMPORTANT:** Laravel needs files in a specific structure.

### If you uploaded to subdomain root directly:
- Files should be in: `/public_html/api/`
- Move contents of `public/` folder to subdomain root
- Move all other folders one level up

### OR use this structure (Recommended):
```
/public_html/api/          (subdomain root)
  ├── .htaccess           (from public folder)
  ├── index.php           (from public folder)
  ├── app/                (from project root)
  ├── bootstrap/           (from project root)
  ├── config/              (from project root)
  ├── database/            (from project root)
  ├── routes/               (from project root)
  ├── storage/              (from project root)
  ├── vendor/               (will install)
  └── ... (all other files)
```

**EASIER WAY:** 
- Upload everything to `/public_html/api/`
- Then move `public/*` files to `/public_html/api/`
- Delete empty `public/` folder

---

## ✅ STEP 5: CREATE .ENV FILE

1. In File Manager, go to your project folder
2. Look for `.env.example` file
3. **Right-click** → **Copy**
4. Rename copy to `.env`
5. **Right-click** `.env` → **Edit**
6. Update these values (I'll give you exact values after you provide info):

```env
APP_NAME="Bisbaku API"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://api.yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_existing_database_name
DB_USERNAME=your_existing_username
DB_PASSWORD=your_existing_password
```

7. **Save** the file

---

## ✅ STEP 6: SET FILE PERMISSIONS

1. In File Manager, go to your project folder
2. **Right-click** `storage` folder → **Change Permissions**
3. Set to: **755** (or **775**)
4. Click **Change Permissions**
5. Repeat for `bootstrap/cache` folder

**OR via SSH/Terminal:**
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

---

## ✅ STEP 7: INSTALL DEPENDENCIES

### Option A: Using cPanel Terminal (if available)
1. Go to **"Terminal"** in cPanel
2. Run these commands:

```bash
cd ~/public_html/api
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate
php artisan config:cache
php artisan route:cache
```

### Option B: Using SSH (if you have access)
- Connect via SSH
- Run same commands as above

### Option C: Manual (if no terminal access)
- You'll need to contact hosting to run these commands
- OR use cPanel's "PHP Selector" if available

---

## ✅ STEP 8: CREATE .HTACCESS FILE

1. In File Manager, go to your subdomain root (e.g., `/public_html/api/`)
2. Create new file: `.htaccess`
3. Paste this content:

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

4. **Save** the file

---

## ✅ STEP 9: TEST YOUR API

1. Open browser
2. Visit: `https://api.yourdomain.com/api/courses`
3. Should see JSON response (even if empty array `[]`)
4. Visit: `https://api.yourdomain.com/api/documentation` (Swagger UI)

---

## ✅ STEP 10: UPDATE CORS FOR FRONTEND

1. In File Manager, go to `config/cors.php`
2. Edit the file
3. Update `allowed_origins`:

```php
'allowed_origins' => [
    'https://app.yourdomain.com',  // Your frontend URL
    'https://www.yourdomain.com',  // Or main domain
],
```

4. **Save**

---

## 🐛 TROUBLESHOOTING

### 500 Error:
- Check `.env` file exists
- Check file permissions (storage, bootstrap/cache)
- Check `storage/logs/laravel.log` for errors

### Database Error:
- Verify database credentials in `.env`
- Check database user has privileges

### Route Not Found:
- Make sure `.htaccess` file exists
- Run: `php artisan route:cache` (via terminal)

---

## 📝 QUICK COMMAND REFERENCE

If you have terminal/SSH access:

```bash
# Navigate to project
cd ~/public_html/api

# Install dependencies
composer install --no-dev --optimize-autoloader

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Clear and cache
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🎯 WHAT TO SEND ME:

After you complete the steps, send me:
1. ✅ Subdomain created? (What URL?)
2. ✅ Files uploaded?
3. ✅ Database name, username, password
4. ✅ Any errors you encountered?

Then I'll give you the exact `.env` configuration!

