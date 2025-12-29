# Quick API Authentication Guide

## Setup (One-Time)

1. **Install Sanctum:**
   ```bash
   composer require laravel/sanctum
   ```

2. **Publish Config:**
   ```bash
   php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   ```

3. **Run Migration:**
   ```bash
   php artisan migrate
   ```

## Usage

### 1. Login to Get Token

```bash
POST /api/login
Content-Type: application/json

{
    "email": "admin@gmail.com",
    "password": "Admin1234"
}
```

**Response:**
```json
{
    "status": true,
    "message": "Login successful",
    "data": {
        "token": "1|abcdef1234567890...",
        "user": { ... }
    }
}
```

### 2. Use Token in All API Requests

Add to **every API request header**:
```
Authorization: Bearer {token}
```

### 3. Logout (Optional)

```bash
POST /api/logout
Authorization: Bearer {token}
```

## All Protected Endpoints

All routes except `/api/login` require the token in the Authorization header.

## Example cURL

```bash
# Login
TOKEN=$(curl -X POST http://localhost/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@gmail.com","password":"Admin1234"}' \
  | jq -r '.data.token')

# Use token
curl -X POST http://localhost/api/attendance/get-students \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{"branch_id":1}'
```

## Postman Setup

1. Login request → Save token from response
2. Set collection variable: `token = {token from login}`
3. Set Authorization: Type = Bearer Token, Token = `{{token}}`
4. All requests will automatically include the token

