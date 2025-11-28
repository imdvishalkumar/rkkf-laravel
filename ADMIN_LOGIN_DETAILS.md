# Admin Login Details

## Default Admin Credentials

After setting up the admin user, you can login with:

**Email:** `admin@rkkf.com`  
**Password:** `admin123`  
**Role:** Admin (role = 1)

## How to Create Admin User

### Option 1: Using Laravel Seeder (Recommended)

Run this command:
```bash
cd laravel-app
php artisan db:seed --class=AdminUserSeeder
```

### Option 2: Using SQL Query

Run the SQL query from `CREATE_ADMIN_USER.sql` directly in your database:

```sql
INSERT INTO `users` (`firstname`, `lastname`, `email`, `password`, `role`, `mobile`, `created_at`, `updated_at`) 
VALUES ('Admin', 'User', 'admin@rkkf.com', 'admin123', 1, NULL, NOW(), NOW())
ON DUPLICATE KEY UPDATE `email` = `email`;
```

### Option 3: Check Existing Admin Users

If you already have admin users in your database, you can check them:

```sql
SELECT user_id, firstname, lastname, email, role FROM users WHERE role = 1;
```

Use any of those email addresses with their corresponding password to login.

## Important Notes

### Password Storage
- The current system uses **plain text passwords** (matching your existing PHP code)
- The AuthController supports both plain text and hashed passwords
- You can migrate to hashed passwords later if needed

### Special Email Addresses
Based on your original code, these emails have special redirects:
- `savvyswaraj@gmail.com` → Redirects to club view
- `tmc@gmail.com` → Redirects to TMC view  
- `baroda@gmail.com` → Redirects to Baroda view

### Login Requirements
- User must have `role = 1` (admin role)
- Email and password must match exactly (case-sensitive)
- User must exist in the `users` table

## Changing Admin Password

### Using SQL:
```sql
UPDATE users 
SET password = 'newpassword123' 
WHERE email = 'admin@rkkf.com';
```

### Using Laravel Tinker:
```bash
cd laravel-app
php artisan tinker
```
Then in tinker:
```php
$user = App\Models\User::where('email', 'admin@rkkf.com')->first();
$user->password = 'newpassword123';
$user->save();
```

## Troubleshooting

### Can't Login?
1. **Check if user exists:**
   ```sql
   SELECT * FROM users WHERE email = 'admin@rkkf.com' AND role = 1;
   ```

2. **Verify password:**
   - Make sure password matches exactly (case-sensitive)
   - Check if password has any extra spaces

3. **Check role:**
   ```sql
   SELECT email, role FROM users WHERE email = 'admin@rkkf.com';
   ```
   Role must be `1` for admin access.

4. **Clear cache:**
   ```bash
   cd laravel-app
   php artisan config:clear
   php artisan cache:clear
   ```

## Security Recommendations

1. **Change default password immediately** after first login
2. **Use strong passwords** (minimum 8 characters, mix of letters, numbers, symbols)
3. **Consider migrating to hashed passwords** for better security
4. **Limit admin access** to trusted users only

## Login URL

Once the admin user is created, login at:
```
http://localhost:8000/login
```
(Or your Laravel app URL + /login)

