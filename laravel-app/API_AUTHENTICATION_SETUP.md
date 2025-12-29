# API Token Authentication Setup Guide

This guide explains how to set up and use token-based authentication for the API using Laravel Sanctum.

## Installation Steps

### 1. Install Laravel Sanctum

Run the following command in your Laravel project root:

```bash
composer require laravel/sanctum
```

### 2. Publish Sanctum Configuration

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### 3. Run Migrations

Sanctum requires a `personal_access_tokens` table. Run the migration:

```bash
php artisan migrate
```

This will create the `personal_access_tokens` table needed for token storage.

### 4. Update User Model

The User model has already been updated to use the `HasApiTokens` trait from Sanctum.

### 5. Configure Sanctum (Optional)

Edit `config/sanctum.php` if you need to customize token expiration or other settings.

## API Authentication Endpoints

### Login
**POST** `/api/login`

**Request Body:**
```json
{
    "email": "user@example.com",
    "password": "password123"
}
```

**Success Response (200):**
```json
{
    "status": true,
    "message": "Login successful",
    "data": {
        "user": {
            "user_id": 1,
            "firstname": "John",
            "lastname": "Doe",
            "email": "user@example.com",
            "mobile": "1234567890",
            "role": 1
        },
        "token": "1|abcdef1234567890...",
        "token_type": "Bearer"
    },
    "errors": null
}
```

**Error Response (401):**
```json
{
    "status": false,
    "message": "Invalid credentials",
    "data": null,
    "errors": {
        "email": ["The provided credentials are incorrect."]
    }
}
```

### Get Authenticated User
**GET** `/api/me`

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
    "status": true,
    "message": "User information retrieved successfully",
    "data": {
        "user": {
            "user_id": 1,
            "firstname": "John",
            "lastname": "Doe",
            "email": "user@example.com",
            "mobile": "1234567890",
            "role": 1
        }
    },
    "errors": null
}
```

### Logout (Current Device)
**POST** `/api/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
    "status": true,
    "message": "Logged out successfully",
    "data": null,
    "errors": null
}
```

### Logout from All Devices
**POST** `/api/logout-all`

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
    "status": true,
    "message": "Logged out from all devices successfully",
    "data": null,
    "errors": null
}
```

## Using the API with Tokens

### 1. Login to Get Token

First, make a POST request to `/api/login` with email and password to receive a token.

### 2. Include Token in Requests

For all protected API endpoints, include the token in the `Authorization` header:

```
Authorization: Bearer {your_token_here}
```

### Example with cURL:

```bash
# Login
curl -X POST http://your-domain.com/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password123"}'

# Use token for protected endpoint
curl -X POST http://your-domain.com/api/attendance/get-students \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer 1|abcdef1234567890..." \
  -d '{"branch_id":1}'
```

### Example with JavaScript (Fetch API):

```javascript
// Login
const loginResponse = await fetch('http://your-domain.com/api/login', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        email: 'user@example.com',
        password: 'password123'
    })
});

const loginData = await loginResponse.json();
const token = loginData.data.token;

// Use token for protected endpoint
const apiResponse = await fetch('http://your-domain.com/api/attendance/get-students', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
    },
    body: JSON.stringify({
        branch_id: 1
    })
});

const apiData = await apiResponse.json();
```

## Protected Routes

All API routes (except `/api/login`) are protected and require a valid token. The middleware `auth:sanctum` is applied to all protected routes.

If you try to access a protected route without a token or with an invalid token, you'll receive:

```json
{
    "status": false,
    "message": "Authentication required. Please provide a valid token.",
    "data": null,
    "errors": null
}
```

## Token Management

### Token Expiration

By default, Sanctum tokens don't expire. You can configure expiration in `config/sanctum.php`:

```php
'expiration' => 60 * 24, // 24 hours in minutes
```

### Revoking Tokens

- **Single Device**: Use `/api/logout` to revoke the current token
- **All Devices**: Use `/api/logout-all` to revoke all tokens for the user

### Multiple Devices

Users can have multiple active tokens (one per device). Each login creates a new token without invalidating existing ones, unless you uncomment the line in `AuthApiController::login()`:

```php
// $user->tokens()->delete(); // Uncomment for single device login
```

## Security Features

1. **Token Storage**: Tokens are stored securely in the `personal_access_tokens` table
2. **Password Support**: The system supports both plain text (legacy) and hashed passwords
3. **Token Revocation**: Tokens can be revoked individually or all at once
4. **Middleware Protection**: All API routes (except login) are protected by Sanctum middleware

## Testing with Postman

1. **Create Login Request**:
   - Method: POST
   - URL: `{{base_url}}/api/login`
   - Body (raw JSON):
     ```json
     {
         "email": "admin@gmail.com",
         "password": "Admin1234"
     }
     ```
   - Save the token from the response

2. **Set Authorization**:
   - Go to Authorization tab
   - Type: Bearer Token
   - Token: Paste the token from login response

3. **Create Collection Variable**:
   - In Postman collection, add variable: `token`
   - Use `{{token}}` in Authorization header

4. **Test Protected Endpoints**:
   - All other API endpoints will automatically use the token

## Troubleshooting

### Token Not Working

1. Check that the token is included in the `Authorization` header
2. Verify the token format: `Bearer {token}` (with space)
3. Ensure the token hasn't been revoked
4. Check that Sanctum migrations have been run

### Login Fails

1. Verify user exists in database
2. Check email and password are correct
3. Ensure user has appropriate role
4. Check database connection

### 401 Unauthorized

1. Token may have expired (if expiration is configured)
2. Token may have been revoked
3. Token format may be incorrect
4. User may have been deleted

## Migration Notes

The system supports both plain text passwords (for existing users) and hashed passwords (for new users). When a user logs in with a plain text password, you can optionally hash it for future use by uncommenting the relevant lines in `AuthApiController::login()`.

