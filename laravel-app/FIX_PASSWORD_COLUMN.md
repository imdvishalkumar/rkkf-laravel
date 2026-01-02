# Fix Password Column Size Issue

## Problem

The `users` table has a `password` column that is only 16 characters long, but bcrypt hashed passwords are 60 characters. This causes the error:

```
SQLSTATE[22001]: String data, right truncated: 1406 Data too long for column 'password' at row 1
```

## Solution

A migration has been created to alter the password column from `VARCHAR(16)` to `VARCHAR(255)`.

### Step 1: Run the Migration

Run the migration to update the column size:

```bash
cd C:\Apache24\htdocs\rkkf\laravel-app
php artisan migrate
```

### Step 2: Verify the Change

You can verify the column was updated by checking your database:

```sql
DESCRIBE users;
```

The `password` column should now show `varchar(255)`.

### Alternative: Manual SQL Update

If you prefer to run the SQL directly:

```sql
ALTER TABLE `users` MODIFY COLUMN `password` VARCHAR(255) NOT NULL;
```

## Why VARCHAR(255)?

- **Bcrypt hashed passwords**: 60 characters
- **Future-proof**: Supports other hashing algorithms (Argon2, etc.)
- **Backward compatible**: Still supports plain text passwords (legacy system)
- **Laravel standard**: Laravel uses VARCHAR(255) for password columns

## After Migration

Once the migration is run, your registration endpoint should work:

```
POST /api/frontend/user/register
```

The password will be properly hashed and stored.

## Important Notes

1. **Existing Data**: Existing plain text passwords will continue to work
2. **New Registrations**: Will have bcrypt hashed passwords (60 chars)
3. **No Data Loss**: The migration only increases the column size, no data is lost

