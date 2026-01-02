# Laravel Sanctum Installation Guide

## ⚠️ Current Error
```
Trait "Laravel\Sanctum\HasApiTokens" not found
```

This error occurs because Laravel Sanctum is not installed on your server, even though it's listed in `composer.json`.

---

## 🔧 Solution: Install Laravel Sanctum

### Option 1: Using Composer (Recommended)

**On your server, navigate to the Laravel app directory and run:**

```bash
cd /path/to/your/laravel-app
composer require laravel/sanctum
```

**Or if composer is not in PATH, use full path:**
```bash
cd /path/to/your/laravel-app
php /path/to/composer.phar require laravel/sanctum
```

### Option 2: Using Composer Install (If already in composer.json)

If `laravel/sanctum` is already in your `composer.json` (which it is), you can run:

```bash
cd /path/to/your/laravel-app
composer install
```

This will install all dependencies including Sanctum.

---

## 📋 After Installation

### 1. Publish Sanctum Configuration (Optional)
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### 2. Clear All Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 3. Optimize (Production)
```bash
php artisan config:cache
php artisan route:cache
```

---

## ✅ Verification

After installation, verify that Sanctum is installed:

```bash
composer show laravel/sanctum
```

You should see output showing the installed version.

---

## 🚨 If Composer is Not Installed

If you don't have Composer installed on your server, you need to install it first:

### Install Composer on Linux/Mac:
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Install Composer on Windows:
1. Download from: https://getcomposer.org/download/
2. Run the installer
3. Add to PATH or use full path to `composer.phar`

---

## 📝 Quick Fix Commands (Copy & Paste)

**For Linux/Unix servers:**
```bash
cd /home/imobiledesigns-api-rkkf/htdocs/api.rkkf.imobiledesigns.cloud
composer require laravel/sanctum
php artisan config:clear
php artisan cache:clear
```

**For Windows servers:**
```cmd
cd C:\Apache24\htdocs\rkkf\laravel-app
composer require laravel/sanctum
php artisan config:clear
php artisan cache:clear
```

---

## 🔍 Troubleshooting

### Issue: "composer: command not found"
**Solution:** Install Composer or use full path to composer.phar

### Issue: "Permission denied"
**Solution:** Use `sudo` (Linux) or run as Administrator (Windows)

### Issue: "Memory limit exhausted"
**Solution:** Increase PHP memory limit:
```bash
php -d memory_limit=512M /path/to/composer require laravel/sanctum
```

---

## 📞 After Installation

Once Sanctum is installed, your API registration endpoint should work:
```
POST /api/frontend/user/register
```

The error should be resolved! ✅

