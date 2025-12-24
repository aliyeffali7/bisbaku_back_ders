# 🚀 YOUR EXACT DEPLOYMENT STEPS

## ✅ YOUR SETUP:
- **Subdomain:** `backend.bisbaku.az`
- **Document Root:** `public_html/backend`
- **Database:** Using existing (you'll provide name)

---

## 📤 STEP 1: PREPARE FILES ON YOUR COMPUTER

1. **Zip your project folder** (`bisbaku-back`)
2. **IMPORTANT - EXCLUDE these from zip:**
   - ❌ `vendor/` folder (we'll install on server)
   - ❌ `node_modules/` (not needed)
   - ❌ `.env` file (we'll create on server)
   - ❌ `.git/` folder (optional)

**OR** just zip everything and we'll handle it on server.

---

## 📤 STEP 2: UPLOAD TO cPanel

1. **Login to cPanel**
2. Go to **"File Manager"**
3. Navigate to: **`public_html/backend`**
4. Click **"Upload"** button
5. Upload your zip file
6. **Right-click the zip file** → **Extract**
7. **Delete the zip file** after extraction

---

## 📁 STEP 3: FIX FILE STRUCTURE

**IMPORTANT:** After extraction, you need to move files correctly.

### Current structure (WRONG):
```
public_html/backend/
  └── bisbaku-back/          (your extracted folder)
      ├── app/
      ├── public/
      ├── config/
      └── ...
```

### What you need (CORRECT):
```
public_html/backend/
  ├── .htaccess              (from public folder)
  ├── index.php              (from public folder)
  ├── app/
  ├── bootstrap/
  ├── config/
  ├── database/
  ├── routes/
  ├── storage/
  └── ... (all other files)
```

### How to fix:

**Option A: Move files manually**
1. Go into `public_html/backend/bisbaku-back/` folder
2. **Select ALL files and folders** (Ctrl+A)
3. **Cut** (Ctrl+X)
4. Go back to `public_html/backend/` (parent folder)
5. **Paste** (Ctrl+V)
6. Delete empty `bisbaku-back` folder
7. Go into `public_html/backend/public/` folder
8. **Select ALL files** (including `.htaccess` and `index.php`)
9. **Cut** them
10. Go back to `public_html/backend/`
11. **Paste** them here
12. Delete empty `public/` folder

**Option B: Use this structure (if Option A is confusing)**
- Just make sure `index.php` and `.htaccess` are in `public_html/backend/` root
- All other folders (app, config, etc.) should be in `public_html/backend/`

---

## ⚙️ STEP 4: CREATE .ENV FILE

1. In File Manager, go to `public_html/backend/`
2. Look for `.env.example` file
3. **Right-click** → **Copy**
4. **Right-click** in same folder → **Paste**
5. **Rename** the copy to `.env`
6. **Right-click** `.env` → **Edit**
7. Replace with this (I'll give you exact values after you send database info):

```env
APP_NAME="Bisbaku API"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://backend.bisbaku.az

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=YOUR_DATABASE_NAME_HERE
DB_USERNAME=YOUR_USERNAME_HERE
DB_PASSWORD=YOUR_PASSWORD_HERE

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
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```

8. **Save** the file

---

## 🔐 STEP 5: SET FILE PERMISSIONS

1. In File Manager, go to `public_html/backend/`
2. **Right-click** `storage` folder → **Change Permissions**
3. Set to: **755** (check all boxes in first row, first box in second row)
4. Click **Change Permissions**
5. Repeat for `bootstrap/cache` folder

---

## 📦 STEP 6: INSTALL DEPENDENCIES

### Option A: Using cPanel Terminal (if available)
1. Go to **"Terminal"** in cPanel
2. Run these commands one by one:

```bash
cd ~/public_html/backend
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Option B: Using SSH
- Connect via SSH
- Run same commands as above

### Option C: No Terminal Access
- Contact your hosting provider
- Ask them to run the commands above in `public_html/backend`

---

## ✅ STEP 7: CREATE/CHECK .HTACCESS

1. In `public_html/backend/` folder
2. Check if `.htaccess` file exists
3. If NOT, create new file: `.htaccess`
4. Paste this content:

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

5. **Save**

---

## 🧪 STEP 8: TEST YOUR API

1. Open browser
2. Visit: `https://backend.bisbaku.az/api/courses`
3. Should see JSON response (even if empty `[]`)
4. Visit: `https://backend.bisbaku.az/api/documentation` (Swagger UI)

---

## 📝 WHAT I NEED FROM YOU:

Send me these details so I can give you exact `.env` values:

1. ✅ **Database name:** (from cPanel → MySQL Databases)
2. ✅ **Database username:** (from cPanel)
3. ✅ **Database password:** (you know this)

Then I'll give you the complete `.env` file content!

---

## 🐛 IF YOU GET ERRORS:

**500 Error:**
- Check `.env` file exists and has correct values
- Check file permissions (storage, bootstrap/cache)
- Check `storage/logs/laravel.log` for details

**Database Error:**
- Verify database credentials in `.env`
- Make sure database user has privileges

**404 Error:**
- Make sure `.htaccess` file exists in root
- Check `index.php` is in root folder

---

## ✅ CHECKLIST:

- [ ] Files uploaded to `public_html/backend/`
- [ ] Files extracted
- [ ] File structure fixed (index.php in root)
- [ ] `.env` file created
- [ ] Permissions set (storage, bootstrap/cache)
- [ ] Dependencies installed (composer install)
- [ ] App key generated
- [ ] Migrations run
- [ ] `.htaccess` file in root
- [ ] Test API endpoint

---

**Start with Step 1-3, then send me your database info for Step 4!**

