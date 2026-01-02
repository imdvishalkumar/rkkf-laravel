# Laravel Sanctum Setup Instructions

## Issue
The error `Trait "Laravel\Sanctum\HasApiTokens" not found` occurs because Laravel Sanctum is not installed.

## Solution

### Step 1: Install Laravel Sanctum

Run the following command in your terminal:

```bash
cd laravel-app
composer require laravel/sanctum
```

### Step 2: Publish Sanctum Configuration (Optional)

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### Step 3: Verify Installation

After installation, verify that Sanctum is properly installed:

```bash
php artisan vendor:publish --tag=sanctum-config
```

### Step 4: Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
```

## Verification

After installation, the `HasApiTokens` trait should be available and the error should be resolved.

## Note

The `composer.json` file has already been updated to include `laravel/sanctum: ^4.0` in the require section. You just need to run `composer install` or `composer update` to install it.

