# Laravel Sanctum Setup Instructions

## ⚠️ Current Error
```
Trait "Laravel\Sanctum\HasApiTokens" not found
```

**This error occurs because Laravel Sanctum is not installed on your server.**

Even though `laravel/sanctum: ^4.0` is listed in `composer.json`, the package hasn't been installed yet.

---

## 🔧 Solution: Install Laravel Sanctum

### Step 1: Install Laravel Sanctum

**On your server, navigate to the Laravel app directory and run:**

```bash
cd /home/imobiledesigns-api-rkkf/htdocs/api.rkkf.imobiledesigns.cloud
composer require laravel/sanctum
```

**OR if you prefer to install all dependencies:**

```bash
cd /home/imobiledesigns-api-rkkf/htdocs/api.rkkf.imobiledesigns.cloud
composer install
```

### Step 2: Publish Sanctum Configuration (Optional)

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### Step 3: Clear All Caches

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### Step 4: Verify Installation

After installation, verify that Sanctum is installed:

```bash
composer show laravel/sanctum
```

You should see output showing the installed version (e.g., `laravel/sanctum v4.x.x`).

---

## ✅ Verification

After installation, the `HasApiTokens` trait should be available and the error should be resolved.

Test your API endpoint:
```
POST /api/frontend/user/register
```

---

## 🚨 Troubleshooting

### Issue: "composer: command not found"
**Solution:** 
- Install Composer: https://getcomposer.org/download/
- Or use full path: `php /path/to/composer.phar require laravel/sanctum`

### Issue: "Permission denied"
**Solution:** 
- Linux: Use `sudo composer require laravel/sanctum`
- Windows: Run as Administrator

### Issue: "Memory limit exhausted"
**Solution:** 
```bash
php -d memory_limit=512M /path/to/composer require laravel/sanctum
```

---

## 📝 Quick Reference

**The `composer.json` file already includes `laravel/sanctum: ^4.0` in the require section.**

You just need to run `composer install` or `composer require laravel/sanctum` on your server to install it.


