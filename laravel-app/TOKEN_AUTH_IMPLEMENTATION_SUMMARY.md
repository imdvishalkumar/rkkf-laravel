# Token Authentication Implementation Summary

## ✅ What Has Been Implemented

### 1. **Laravel Sanctum Integration**
   - User model updated with `HasApiTokens` trait
   - API middleware updated to use Sanctum authentication
   - All API routes protected with `auth:sanctum` middleware

### 2. **Authentication Controller** (`AuthApiController.php`)
   - ✅ `login()` - Generate token after successful email/password authentication
   - ✅ `me()` - Get authenticated user information
   - ✅ `logout()` - Revoke current token
   - ✅ `logoutAll()` - Revoke all tokens for user

### 3. **API Routes Updated**
   - ✅ Public route: `POST /api/login`
   - ✅ Protected routes: All other API endpoints require token
   - ✅ Token endpoints: `/api/me`, `/api/logout`, `/api/logout-all`

### 4. **Password Support**
   - ✅ Supports both plain text passwords (legacy system)
   - ✅ Supports hashed passwords (new Laravel system)
   - ✅ Automatic password verification for both types

### 5. **Documentation**
   - ✅ Complete setup guide: `API_AUTHENTICATION_SETUP.md`
   - ✅ Quick reference: `QUICK_API_AUTH_GUIDE.md`

## 📋 Setup Required (One-Time)

### Step 1: Install Laravel Sanctum
```bash
cd laravel-app
composer require laravel/sanctum
```

### Step 2: Publish Sanctum Configuration
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### Step 3: Run Migration
```bash
php artisan migrate
```

This creates the `personal_access_tokens` table needed for token storage.

## 🔐 How It Works

### Login Flow:
1. User sends `POST /api/login` with email and password
2. System validates credentials (supports both plain text and hashed)
3. System generates a unique token using Sanctum
4. Token is returned to the client
5. Client stores token and includes it in all subsequent requests

### Protected Route Flow:
1. Client includes token in `Authorization: Bearer {token}` header
2. Sanctum middleware validates the token
3. If valid, user is authenticated and request proceeds
4. If invalid, 401 Unauthorized response is returned

## 📝 API Endpoints

### Authentication Endpoints

| Method | Endpoint | Auth Required | Description |
|--------|----------|---------------|-------------|
| POST | `/api/login` | ❌ No | Login and get token |
| GET | `/api/me` | ✅ Yes | Get current user info |
| POST | `/api/logout` | ✅ Yes | Logout (revoke current token) |
| POST | `/api/logout-all` | ✅ Yes | Logout from all devices |

### All Other Endpoints
All other API endpoints require authentication via token.

## 🔧 Configuration

### Token Expiration (Optional)
Edit `config/sanctum.php` to set token expiration:

```php
'expiration' => 60 * 24, // 24 hours in minutes (null = no expiration)
```

### Single Device Login (Optional)
In `AuthApiController::login()`, uncomment this line to revoke all existing tokens on login:

```php
// $user->tokens()->delete(); // Uncomment for single device login
```

## 📱 Usage Examples

### JavaScript/Fetch
```javascript
// Login
const response = await fetch('/api/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email: 'user@example.com', password: 'password' })
});
const { data } = await response.json();
const token = data.token;

// Use token
const apiResponse = await fetch('/api/attendance/get-students', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
    },
    body: JSON.stringify({ branch_id: 1 })
});
```

### cURL
```bash
# Login
TOKEN=$(curl -s -X POST http://localhost/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@gmail.com","password":"Admin1234"}' \
  | jq -r '.data.token')

# Use token
curl -X POST http://localhost/api/attendance/get-students \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{"branch_id":1}'
```

### Postman
1. Create login request → Save token from response
2. Set collection variable: `token`
3. Set Authorization: Bearer Token = `{{token}}`
4. All requests automatically include token

## ⚠️ Important Notes

1. **Password Handling**: The system supports both plain text (legacy) and hashed passwords. When a user logs in with a plain text password, you can optionally hash it for future use.

2. **Token Storage**: Tokens are stored in the `personal_access_tokens` table. Each token is unique and can be revoked individually.

3. **Multiple Devices**: By default, users can have multiple active tokens (one per device). To enforce single device login, uncomment the token deletion line in the login method.

4. **Security**: 
   - Tokens are long, random strings that are difficult to guess
   - Tokens can be revoked at any time
   - Tokens are stored securely in the database
   - Passwords are never sent in responses

## 🐛 Troubleshooting

### "Unauthorized" Error
- Check that token is included in `Authorization` header
- Verify token format: `Bearer {token}` (with space)
- Ensure token hasn't been revoked
- Check that Sanctum migrations have been run

### Login Fails
- Verify user exists in database
- Check email and password are correct
- Ensure user has appropriate role
- Check database connection

### Token Not Working
- Token may have expired (if expiration configured)
- Token may have been revoked
- Token format may be incorrect
- User may have been deleted

## 📚 Files Modified/Created

### Created:
- `app/Http/Controllers/Api/AuthApiController.php`
- `API_AUTHENTICATION_SETUP.md`
- `QUICK_API_AUTH_GUIDE.md`
- `TOKEN_AUTH_IMPLEMENTATION_SUMMARY.md` (this file)

### Modified:
- `app/Models/User.php` - Added `HasApiTokens` trait
- `app/Http/Middleware/ApiAuthMiddleware.php` - Updated to use Sanctum
- `routes/api.php` - Added auth routes, updated middleware to `auth:sanctum`

## ✅ Next Steps

1. **Install Sanctum** (if not already installed):
   ```bash
   composer require laravel/sanctum
   php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   php artisan migrate
   ```

2. **Test Login**:
   ```bash
   curl -X POST http://localhost/api/login \
     -H "Content-Type: application/json" \
     -d '{"email":"admin@gmail.com","password":"Admin1234"}'
   ```

3. **Update Postman Collection** (Optional):
   - Add login request
   - Set collection variable for token
   - Add Authorization header to all requests

4. **Update Frontend/Client Applications**:
   - Implement login flow
   - Store token securely (localStorage, sessionStorage, or secure cookie)
   - Include token in all API requests

## 🎯 Summary

Token-based authentication is now fully implemented. Users can:
- ✅ Login with email/password to get a token
- ✅ Use the token to access all protected API endpoints
- ✅ Logout to revoke tokens
- ✅ Have multiple active tokens (one per device)

All API endpoints (except `/api/login`) are now secured and require a valid token in the `Authorization: Bearer {token}` header.

