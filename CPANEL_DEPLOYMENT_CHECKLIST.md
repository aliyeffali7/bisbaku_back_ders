# ✅ cPanel Deployment Checklist

## Before You Start - Answer These:

1. **What's your main domain?** 
   - Example: `yourdomain.com`

2. **What subdomain for backend API?**
   - Example: `api.yourdomain.com`

3. **What subdomain for frontend?**
   - Example: `app.yourdomain.com` or `www.yourdomain.com`

4. **Do you have cPanel access?** ✅ / ❌

---

## 📋 Step-by-Step Deployment

### ✅ STEP 1: Create Subdomain in cPanel
- [ ] Login to cPanel
- [ ] Go to "Subdomains"
- [ ] Create `api` subdomain
- [ ] Note the Document Root path (usually `/public_html/api`)

### ✅ STEP 2: Create Database
- [ ] Go to "MySQL Databases"
- [ ] Create database: `yourusername_bisbaku`
- [ ] Create user: `yourusername_bisbaku_user`
- [ ] Add user to database with ALL PRIVILEGES
- [ ] **SAVE THESE CREDENTIALS!**

### ✅ STEP 3: Prepare Files for Upload
- [ ] Zip the project (excluding `vendor`, `node_modules`, `.env`)
- [ ] Or use FTP to upload directly

### ✅ STEP 4: Upload to Server
- [ ] Upload to subdomain folder (e.g., `/public_html/api`)
- [ ] Extract if uploaded as zip

### ✅ STEP 5: Configure Laravel
- [ ] Copy `.env.example` to `.env`
- [ ] Update database credentials in `.env`
- [ ] Set `APP_URL=https://api.yourdomain.com`
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`

### ✅ STEP 6: Install Dependencies
- [ ] SSH into server (or use cPanel Terminal)
- [ ] Run: `composer install --no-dev`
- [ ] Run: `php artisan key:generate`
- [ ] Run: `php artisan migrate`

### ✅ STEP 7: Set Permissions
- [ ] `chmod -R 755 storage`
- [ ] `chmod -R 755 bootstrap/cache`

### ✅ STEP 8: Test
- [ ] Visit: `https://api.yourdomain.com/api/courses`
- [ ] Should see JSON response
- [ ] Check Swagger: `https://api.yourdomain.com/api/documentation`

### ✅ STEP 9: Update Frontend
- [ ] Change API URL to: `https://api.yourdomain.com/api`
- [ ] Update CORS settings if needed

---

## 🚨 Common Issues & Fixes

**500 Error:**
- Check file permissions
- Check `.env` file exists
- Check `storage/logs/laravel.log`

**Database Error:**
- Verify credentials in `.env`
- Check user has database privileges

**Route Not Found:**
- Run: `php artisan route:cache`

---

## 📞 Need Help?

If stuck at any step, let me know which step and the error message!

