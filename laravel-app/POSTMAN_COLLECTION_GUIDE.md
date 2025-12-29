# Postman Collection Guide

## How to Import the Collection

1. Open Postman
2. Click **Import** button (top left)
3. Select the file: `RKKF_API_Collection.postman_collection.json`
4. Click **Import**

## Setting Base URL

1. After importing, click on the collection name: **RKKF API Collection**
2. Go to **Variables** tab
3. Set the `base_url` variable:
   - **Initial Value**: `http://localhost:8000` (for local development)
   - **Current Value**: Your actual base URL (e.g., `https://yourdomain.com`)

## Authentication Setup

### Option 1: Session-Based (Web)
If using session-based authentication:
1. Login through the web interface first
2. Copy the `laravel_session` cookie value
3. In Postman, go to **Cookies** (under Send button)
4. Add cookie: `laravel_session` with your session value

### Option 2: Bearer Token
If using API tokens:
1. Go to collection **Variables**
2. Add variable: `auth_token`
3. In each request, add header:
   ```
   Authorization: Bearer {{auth_token}}
   ```

## Collection Structure

The collection is organized into folders:

1. **Attendance** - 12 endpoints
   - Regular attendance operations
   - Additional attendance
   - Exam/Event attendance
   - Attendance logs and views

2. **Fees** - 12 endpoints
   - Fee management
   - Payment reports
   - Fee entry operations

3. **Students** - 7 endpoints
   - Student search and filtering
   - Reports

4. **Orders** - 4 endpoints
   - Order management
   - Product operations

5. **Exam** - 5 endpoints
   - Exam eligibility
   - Exam results

6. **Event** - 2 endpoints
   - Event eligibility
   - Event applications

7. **Other** - 5 endpoints
   - Additional reports and operations

## Testing Tips

1. **Start with simple requests**: Test with `Get Students for Attendance` first
2. **Check response format**: Some endpoints return HTML, add `Accept: application/json` header for JSON
3. **Use variables**: Replace hardcoded IDs with variables for easier testing
4. **Save responses**: Use Postman's "Save Response" feature to document expected responses

## Common Request Patterns

### Search Pattern
```json
{
    "grno": "101"
}
```

### Filter Pattern
```json
{
    "branch_id": 1,
    "start_date": "2024-01",
    "end_date": "2024-12",
    "param": "true"
}
```

### Date Formats
- Full date: `"2024-01-15"` (YYYY-MM-DD)
- Month filter: `"2024-01"` (YYYY-MM)
- Date range: `"2024-01-01"` to `"2024-12-31"`

## Environment Variables

Create a Postman Environment with:
- `base_url`: Your API base URL
- `auth_token`: Your authentication token (if using)
- `branch_id`: Default branch ID for testing
- `student_id`: Default student ID for testing

## Notes

- All requests use POST method
- All requests require authentication
- Date formats must match exactly (YYYY-MM-DD or YYYY-MM)
- Branch ID `0` means "All Branches"
- Belt ID `0` means "All Belts"
- `param: "true"` typically means "include all" or "show all"


