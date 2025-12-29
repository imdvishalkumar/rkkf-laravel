# Setup Instructions
## Registering Middleware and Service Providers

---

## Step 1: Register Middleware

### For Laravel 11

Edit `bootstrap/app.php`:

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        api: __DIR__.'/../routes/api.php', // Add this if you have API routes
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register middleware aliases
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'branch.access' => \App\Http\Middleware\BranchAccessMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
```

### For Laravel 10 and below

Edit `app/Http/Kernel.php`:

```php
protected $middlewareAliases = [
    // ... existing aliases
    'role' => \App\Http\Middleware\RoleMiddleware::class,
    'branch.access' => \App\Http\Middleware\BranchAccessMiddleware::class,
];
```

---

## Step 2: Service Provider (Auto-Discovered in Laravel 11)

### Laravel 11
Service providers are auto-discovered. No registration needed if the file exists at:
`app/Providers/RepositoryServiceProvider.php`

### Laravel 10 and below
Add to `config/app.php`:

```php
'providers' => [
    // ... existing providers
    App\Providers\RepositoryServiceProvider::class,
],
```

---

## Step 3: Run Migration

```bash
cd laravel-app
php artisan migrate
```

This creates the `branch_groups` table.

---

## Step 4: Update Models to Use Traits

### Student Model Example

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasStatus;
use App\Traits\HasBranchAccess;
use App\Enums\StudentStatus;

class Student extends Model
{
    use HasStatus, HasBranchAccess;

    // ... existing code

    // Update scopes to use enum
    public function scopeActive($query)
    {
        return $query->where('active', StudentStatus::ACTIVE->value);
    }

    public function scopeInactive($query)
    {
        return $query->where('active', StudentStatus::INACTIVE->value);
    }
}
```

---

## Step 5: Usage Examples

### Using Middleware in Routes

```php
// routes/web.php
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('students', StudentController::class);
});

Route::middleware(['auth', 'branch.access'])->group(function () {
    Route::get('/branch/{branch_id}/students', [StudentController::class, 'index']);
});
```

### Using Enums in Code

```php
use App\Enums\UserRole;
use App\Enums\StudentStatus;

// Check role
if ($user->role === UserRole::ADMIN) {
    // Admin logic
}

// Check status
if ($student->active === StudentStatus::ACTIVE->value) {
    // Active student logic
}

// Or use model methods
if ($student->isActive()) {
    // Active student logic
}
```

### Using Config for Branch Groups

```php
// Get branch groups from config
$enquiryBranches = config('branch_groups.groups.enquiry_branches');

// Use in query
Branch::whereIn('branch_id', $enquiryBranches)->get();

// Or use model method
$branches = BranchGroup::getBranchesFromConfig('enquiry_branches');
```

### Using Config for Special Users

```php
// In AuthController or anywhere
$specialUsers = config('roles.special_users', []);

if (isset($specialUsers[$email])) {
    $redirectRoute = $specialUsers[$email]['redirect_route'];
    return redirect()->route($redirectRoute);
}
```

---

## Step 6: Test Everything

### Test Enum Usage
```php
// In tinker: php artisan tinker
$user = User::first();
$user->role; // Should return UserRole enum
$user->isAdmin(); // Should return boolean
```

### Test Config
```php
// In tinker
config('roles.roles.admin'); // Should return 1
config('branch_groups.groups.enquiry_branches'); // Should return array
```

### Test Middleware
```php
// Visit a route protected by 'role:admin' middleware
// Should redirect if not admin
```

---

## ✅ Verification Checklist

- [ ] Middleware registered in `bootstrap/app.php` (Laravel 11) or `app/Http/Kernel.php` (Laravel 10)
- [ ] Service Provider exists at `app/Providers/RepositoryServiceProvider.php`
- [ ] Migration run successfully
- [ ] Models updated with traits
- [ ] Enums working correctly
- [ ] Config files accessible
- [ ] Middleware working on protected routes

---

## 🎉 You're Done!

All hard-coded logic has been replaced with:
- ✅ Enums for status/role values
- ✅ Config files for special users and branch groups
- ✅ Traits for reusable functionality
- ✅ Middleware for access control

Next: Start implementing the Repository-Service pattern following `SAMPLE_IMPLEMENTATION_STUDENT.md`!

