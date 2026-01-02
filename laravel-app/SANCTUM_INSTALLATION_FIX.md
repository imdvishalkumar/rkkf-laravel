# 🔴 CRITICAL: Sanctum Not Installed - Installation Required

## Problem Diagnosis

Your error:
```
Trait "Laravel\Sanctum\HasApiTokens" not found
```

**Root Cause:** Laravel Sanctum is listed in `composer.json` and `composer.lock`, but **it is NOT actually installed** in the `vendor/` directory.

## Verification

I checked your codebase and found:
- ✅ `composer.json` has `"laravel/sanctum": "^4.0"`
- ✅ `composer.lock` has `"laravel/sanctum": "^4.1.1"`
- ❌ `vendor/laravel/sanctum/` directory **DOES NOT EXIST**

This means the package was never installed, even though it's in your dependency files.

---

## 🔧 SOLUTION: Install Sanctum

### Step 1: Run the Diagnostic Script

First, check the current status:

```bash
cd C:\Apache24\htdocs\rkkf\laravel-app
php check_sanctum.php
```

This will show you exactly what's missing.

### Step 2: Install Sanctum

**You MUST run this on your server where the Laravel app is running:**

#### Option A: If you have Composer installed globally
```bash
cd C:\Apache24\htdocs\rkkf\laravel-app
composer require laravel/sanctum
```

#### Option B: If Composer is not in PATH
```bash
cd C:\Apache24\htdocs\rkkf\laravel-app
php composer.phar require laravel/sanctum
```

#### Option C: Install all dependencies (if Sanctum is already in composer.json)
```bash
cd C:\Apache24\htdocs\rkkf\laravel-app
composer install
```

### Step 3: Verify Installation

After installation, verify Sanctum is installed:

```bash
composer show laravel/sanctum
```

You should see output like:
```
name     : laravel/sanctum
versions : * 4.1.1
```

### Step 4: Clear All Caches

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Step 5: Test Your API

Now try your registration endpoint again:
```
POST /api/frontend/user/register
```

---

## 🚨 If Composer is Not Available

### Install Composer on Windows:

1. **Download Composer:**
   - Go to: https://getcomposer.org/download/
   - Download `Composer-Setup.exe`
   - Run the installer

2. **Or use Composer PHAR:**
   ```bash
   # Download composer.phar
   php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
   php composer-setup.php
   php -r "unlink('composer-setup.php');"
   
   # Then use it
   php composer.phar require laravel/sanctum
   ```

---

## 📋 Quick Checklist

- [ ] Run `php check_sanctum.php` to diagnose
- [ ] Install Composer if not available
- [ ] Run `composer require laravel/sanctum`
- [ ] Verify with `composer show laravel/sanctum`
- [ ] Clear all caches
- [ ] Test API endpoint

---

## 🔍 Why This Happened

This typically happens when:
1. Code was copied from another environment where Sanctum was installed
2. `composer.json` was updated but `composer install` was never run
3. `vendor/` directory was excluded from deployment (which is correct, but dependencies need to be installed)

---

## ✅ After Installation

Once Sanctum is installed, you should see:
- `vendor/laravel/sanctum/` directory exists
- `vendor/laravel/sanctum/src/HasApiTokens.php` file exists
- Your API registration endpoint works

---

## 📞 Still Having Issues?

If you're still getting errors after installation:

1. **Check PHP version:** Sanctum requires PHP 8.1+
   ```bash
   php -v
   ```

2. **Check file permissions:** Ensure vendor directory is writable

3. **Check autoload:** Regenerate autoload files
   ```bash
   composer dump-autoload
   ```

4. **Check error logs:** Look at `storage/logs/laravel.log` for detailed errors

---

**Remember:** This MUST be done on your server, not just in your local development environment!

