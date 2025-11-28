# Quick Start Guide - Laravel Migration

## What Has Been Done

1. **Laravel Installation**: A fresh Laravel 12.10.1 installation has been created in the `laravel-app/` directory
2. **Basic Structure**: Models, controllers, and middleware have been created
3. **Database Configuration**: Script created to update database settings

## Immediate Next Steps

### 1. Configure Database Connection

The database configuration needs to be verified. Run:

```powershell
cd laravel-app
.\update-env.ps1
```

Or manually edit `laravel-app/.env` and ensure:
```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u931471842_rkkf
DB_USERNAME=u931471842_tony
DB_PASSWORD=Tony@2007
```

### 2. Test Database Connection

```bash
cd laravel-app
php artisan migrate:status
```

Note: Since you have an existing database, you may not need to run migrations. The models are configured to work with your existing tables.

### 3. Update Model Table Names

The models need to know which tables to use. Update each model's `$table` property:

**Example for Student model:**
```php
protected $table = 'students';
protected $primaryKey = 'student_id';
```

### 4. Register Middleware

Update `laravel-app/bootstrap/app.php` to register the RoleMiddleware:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
})
```

### 5. Start Development Server

```bash
cd laravel-app
php artisan serve
```

Visit: http://localhost:8000

## File Structure

```
rkkf/
├── laravel-app/          # New Laravel application
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   └── Middleware/
│   │   └── Models/
│   ├── routes/
│   ├── resources/views/
│   └── public/
├── [original PHP files]  # Your existing code
└── MIGRATION_PROGRESS.md # Track migration progress
```

## Key Files Created

### Models
- `app/Models/User.php` - Updated with role support
- `app/Models/Student.php` - With relationships
- `app/Models/Branch.php`
- `app/Models/Belt.php`
- `app/Models/Fee.php`
- `app/Models/Product.php`

### Controllers
- `app/Http/Controllers/DashboardController.php`
- `app/Http/Controllers/AuthController.php`
- `app/Http/Controllers/StudentController.php`

### Routes
- `routes/web.php` - Web routes configured

## Migration Strategy

1. **Gradual Migration**: Keep original PHP files working while migrating to Laravel
2. **Test Each Module**: Migrate and test one module at a time
3. **Parallel Development**: Run both systems side-by-side during migration
4. **Final Switch**: Once all features are migrated and tested, switch to Laravel

## Common Issues & Solutions

### Issue: Database Connection Failed
**Solution**: Verify `.env` file has correct credentials and database exists

### Issue: Table Not Found
**Solution**: Add `protected $table = 'table_name';` to model

### Issue: Primary Key Error
**Solution**: Add `protected $primaryKey = 'id_column';` to model

### Issue: Authentication Not Working
**Solution**: 
1. Check if User model uses correct table
2. Verify password hashing (may need to migrate from plain text)
3. Check session configuration

## Next Migration Tasks

See `MIGRATION_PROGRESS.md` for detailed task list.

## Need Help?

- Check `LARAVEL_MIGRATION_GUIDE.md` for detailed migration strategy
- Check `MIGRATION_PROGRESS.md` for current progress
- Laravel Documentation: https://laravel.com/docs

