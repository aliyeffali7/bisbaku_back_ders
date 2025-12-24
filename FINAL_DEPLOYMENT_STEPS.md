# 🚀 FINAL DEPLOYMENT STEPS - READY TO GO!

## ✅ YOUR CONFIGURATION:
- **Subdomain:** `backend.bisbaku.az`
- **Database:** `bisbaku_ders_api`
- **Username:** `bisbaku_ders_user`
- **Password:** `fluenT072315`

---

## 📤 STEP 1: UPLOAD FILES

1. **Zip your project** (exclude `vendor`, `node_modules`, `.env`)
2. **Upload to cPanel File Manager**
3. Go to: `public_html/backend/`
4. **Upload** your zip file
5. **Extract** it
6. **Delete** the zip file

---

## 📁 STEP 2: FIX FILE STRUCTURE

After extraction, you should have this structure:

```
public_html/backend/
  ├── .htaccess          ← Must be here!
  ├── index.php          ← Must be here!
  ├── app/
  ├── bootstrap/
  ├── config/
  ├── database/
  ├── routes/
  ├── storage/
  └── ... (all other files)
```

**If files are in a subfolder:**
- Move everything from subfolder to `backend/` root
- Move `public/*` files to `backend/` root
- Delete empty folders

---

## ⚙️ STEP 3: CREATE .ENV FILE

1. In File Manager, go to `public_html/backend/`
2. **Create new file** named `.env`
3. **Copy and paste this EXACT content:**

```env
APP_NAME="Bisbaku API"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://backend.bisbaku.az

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

4. **Save** the file

---

## 🔐 STEP 4: SET PERMISSIONS

1. **Right-click** `storage` folder → **Change Permissions** → **755**
2. **Right-click** `bootstrap/cache` folder → **Change Permissions** → **755**

---

## 📦 STEP 5: INSTALL & CONFIGURE

### Via cPanel Terminal or SSH:

```bash
cd ~/public_html/backend
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**If no terminal access:**
- Contact hosting to run these commands
- OR use cPanel's "Terminal" if available

---

## ✅ STEP 6: CREATE .HTACCESS

1. In `public_html/backend/` root
2. Create file: `.htaccess`
3. Paste this:

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

4. **Save**

---

## 🧪 STEP 7: TEST

1. Visit: `https://backend.bisbaku.az/api/courses`
   - Should see JSON (even if empty `[]`)

2. Visit: `https://backend.bisbaku.az/api/documentation`
   - Should see Swagger UI

---

## 🐛 TROUBLESHOOTING

### 500 Error:
- ✅ Check `.env` file exists
- ✅ Check permissions (storage, bootstrap/cache)
- ✅ Check `storage/logs/laravel.log`

### Database Error:
- ✅ Verify credentials in `.env`
- ✅ Check user has database privileges in cPanel

### 404 Error:
- ✅ Check `.htaccess` exists in root
- ✅ Check `index.php` is in root

---

## ✅ CHECKLIST:

- [ ] Files uploaded and extracted
- [ ] File structure correct (index.php in root)
- [ ] `.env` file created with correct values
- [ ] Permissions set (755 for storage, bootstrap/cache)
- [ ] Dependencies installed (`composer install`)
- [ ] App key generated (`php artisan key:generate`)
- [ ] Migrations run (`php artisan migrate`)
- [ ] `.htaccess` file in root
- [ ] Test API endpoint works

---

## 🎯 NEXT STEPS AFTER DEPLOYMENT:

1. ✅ Update CORS in `config/cors.php` for your frontend domain
2. ✅ Test all API endpoints
3. ✅ Set up SSL certificate (if not already)
4. ✅ Update frontend to use: `https://backend.bisbaku.az/api`

---

**You're all set! Follow these steps and let me know if you hit any errors!** 🚀

