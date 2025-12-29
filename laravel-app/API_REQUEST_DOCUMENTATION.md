# API Request Documentation

## Base URL
```
{{base_url}}/api
```

## Authentication
All API endpoints require authentication. Include the authentication token in the request headers:
```
Authorization: Bearer YOUR_TOKEN
```
or use session-based authentication (for web requests):
```
Cookie: laravel_session=YOUR_SESSION_ID
```

## Content Type
All requests should use:
```
Content-Type: application/json
Accept: application/json
```

---

## 1. ATTENDANCE API ENDPOINTS

### 1.1 Get Students for Attendance
**Endpoint:** `POST /api/attendance/get-students`

**Description:** Get list of students for a branch to mark attendance

**Request Body:**
```json
{
    "branchId": 1
}
```

**Parameters:**
- `branchId` (integer, required): Branch ID

**Response:** List of students with attendance form

---

### 1.2 Get Attendance by Branch and Date
**Endpoint:** `POST /api/attendance/get-attendance`

**Description:** Get attendance records for a specific branch and date

**Request Body:**
```json
{
    "branchId": 1,
    "date": "2024-01-15"
}
```

**Parameters:**
- `branchId` (integer, required): Branch ID
- `date` (string, required): Date in YYYY-MM-DD format

**Response:** Attendance records with edit form

---

### 1.3 Insert/Update Attendance
**Endpoint:** `POST /api/attendance/insert`

**Description:** Insert or update attendance records

**Request Body:**
```json
{
    "branchId": 1,
    "date": "2024-01-15",
    "attendance": [
        {
            "student_id": 101,
            "attend": "P"
        },
        {
            "student_id": 102,
            "attend": "A"
        },
        {
            "student_id": 103,
            "attend": "L"
        }
    ]
}
```

**Parameters:**
- `branchId` (integer, required): Branch ID
- `date` (string, required): Date in YYYY-MM-DD format
- `attendance` (array, required): Array of attendance records
  - `student_id` (integer): Student ID
  - `attend` (string): 'P' for Present, 'A' for Absent, 'L' for Leave

**Response:** Success message

---

### 1.4 Additional Attendance - Get Students
**Endpoint:** `POST /api/attendance/additional/get-students`

**Description:** Get students for additional attendance

**Request Body:**
```json
{
    "branchId": 1,
    "date": "2024-01-15"
}
```

**Parameters:**
- `branchId` (integer, required): Branch ID
- `date` (string, required): Date in YYYY-MM-DD format

---

### 1.5 Additional Attendance - Get Attendance
**Endpoint:** `POST /api/attendance/additional/get-attendance`

**Description:** Get additional attendance records

**Request Body:**
```json
{
    "branchId": 1,
    "date": "2024-01-15"
}
```

---

### 1.6 Additional Attendance - Insert/Update
**Endpoint:** `POST /api/attendance/additional/insert`

**Description:** Insert or update additional attendance

**Request Body:**
```json
{
    "branchId": 1,
    "date": "2024-01-15",
    "attendance": [
        {
            "attendance_id": 501,
            "attend": "A"
        }
    ]
}
```

---

### 1.7 Attendance Log
**Endpoint:** `POST /api/attendance/log`

**Description:** Get attendance log with filters

**Request Body:**
```json
{
    "branch_id": 1,
    "start_date": "2024-01-01",
    "end_date": "2024-01-31",
    "student_id": 101
}
```

**Parameters:**
- `branch_id` (integer, optional): Branch ID
- `start_date` (string, optional): Start date
- `end_date` (string, optional): End date
- `student_id` (integer, optional): Student ID

---

### 1.8 View Attendance
**Endpoint:** `POST /api/attendance/view`

**Description:** View attendance with filters

**Request Body:**
```json
{
    "branch_id": 1,
    "start_date": "2024-01",
    "end_date": "2024-12",
    "param": "true"
}
```

**Parameters:**
- `branch_id` (integer, optional): Branch ID (0 for all)
- `start_date` (string, optional): Start date in YYYY-MM format
- `end_date` (string, optional): End date in YYYY-MM format
- `param` (string, optional): 'true' for all records

---

### 1.9 Exam Attendance - Get Students
**Endpoint:** `POST /api/exam-attendance/get-students`

**Description:** Get students for exam attendance

**Request Body:**
```json
{
    "branchId": 1
}
```

**Parameters:**
- `branchId` (integer, required): Exam ID

---

### 1.10 Exam Attendance - Insert/Update
**Endpoint:** `POST /api/exam-attendance/insert`

**Description:** Insert or update exam attendance

**Request Body:**
```json
{
    "exam_id": 1,
    "attendance": [
        {
            "exam_attendance_id": 201,
            "attend": "P",
            "certificate_no": "CERT123"
        }
    ]
}
```

---

### 1.11 Event Attendance - Get Students
**Endpoint:** `POST /api/event-attendance/get-students`

**Description:** Get students for event attendance

**Request Body:**
```json
{
    "branchId": 1,
    "date": "2024-01-15"
}
```

**Parameters:**
- `branchId` (integer, required): Event ID
- `date` (string, required): Date

---

### 1.12 Event Attendance - Insert/Update
**Endpoint:** `POST /api/event-attendance/insert`

**Description:** Insert or update event attendance

**Request Body:**
```json
{
    "event_id": 1,
    "attendance": [
        {
            "event_attendance_id": 301,
            "attend": "P",
            "result": "Winner",
            "medal": "Gold"
        }
    ]
}
```

---

## 2. FEES API ENDPOINTS

### 2.1 Get Student Info by GR Number
**Endpoint:** `POST /api/fees/get-student-info`

**Description:** Get student information and last fee paid details

**Request Body:**
```json
{
    "grno": "101"
}
```

**Parameters:**
- `grno` (string, required): Student GR number or name (partial match)

**Response:** Student dropdown list or student info

---

### 2.2 Get Fees List
**Endpoint:** `POST /api/fees/get-fees`

**Description:** Get fees list with filters

**Request Body:**
```json
{
    "branch_id": 1,
    "start_date": "2024-01",
    "end_date": "2024-12",
    "param": "true"
}
```

**Parameters:**
- `branch_id` (integer, optional): Branch ID (0 for all)
- `start_date` (string, optional): Start date in YYYY-MM format
- `end_date` (string, optional): End date in YYYY-MM format
- `param` (string, optional): 'true' for all active students

**Response:** Fees table with filters

---

### 2.3 Delete Fee
**Endpoint:** `POST /api/fees/delete`

**Description:** Delete a fee record

**Request Body:**
```json
{
    "fee_id": 123
}
```

**Parameters:**
- `fee_id` (integer, required): Fee ID

**Response:**
```json
{
    "deleted": true
}
```

---

### 2.4 Enter Fees - Get Student
**Endpoint:** `POST /api/fees/enter/get-student`

**Description:** Get student for entering fees

**Request Body:**
```json
{
    "grno": "101"
}
```

**Parameters:**
- `grno` (string, required): Student GR number or name

---

### 2.5 Enter Old Fees - Get Student
**Endpoint:** `POST /api/fees/enter-old/get-student`

**Description:** Get student for entering old fees

**Request Body:**
```json
{
    "grno": "101"
}
```

---

### 2.6 Enter Exam Fees
**Endpoint:** `POST /api/fees/enter-exam`

**Description:** Enter exam fees for a student

**Request Body:**
```json
{
    "student_id": 101,
    "exam_id": 1,
    "amount": 500.00,
    "date": "2024-01-15",
    "mode": "Online",
    "rp_order_id": "order_123",
    "exam_belt_id": 2
}
```

---

### 2.7 View Combined Fees
**Endpoint:** `POST /api/fees/combined`

**Description:** Get combined fees report

**Request Body:**
```json
{
    "branch_id": 1,
    "start_date": "2024-01",
    "end_date": "2024-12"
}
```

---

### 2.8 View Fees Without Admission
**Endpoint:** `POST /api/fees/view-without-admission`

**Description:** Get fees excluding admission fees

**Request Body:**
```json
{
    "branch_id": 1,
    "start_date": "2024-01",
    "end_date": "2024-12"
}
```

---

### 2.9 Disable Fees - Get Student
**Endpoint:** `POST /api/fees/disable/get-student`

**Description:** Get student info for disabling fees

**Request Body:**
```json
{
    "grno": "101",
    "disable_student_id": 101
}
```

**Parameters:**
- `grno` (string, optional): Student GR number (for search)
- `disable_student_id` (integer, optional): Student ID (for getting fee info)

---

### 2.10 Fix Payment Entry
**Endpoint:** `POST /api/fees/fix-payment`

**Description:** Fix payment entry issues

**Request Body:**
```json
{
    "fee_id": 123,
    "amount": 1000.00,
    "date": "2024-01-15"
}
```

---

### 2.11 Payment Report
**Endpoint:** `POST /api/fees/payment-report`

**Description:** Get payment report with filters

**Request Body:**
```json
{
    "branch_id": 1,
    "start_date": "2024-01-01",
    "end_date": "2024-12-31"
}
```

---

### 2.12 Full Report
**Endpoint:** `POST /api/fees/full-report`

**Description:** Get full financial report

**Request Body:**
```json
{
    "branch_id": 1,
    "start_date": "2024-01-01",
    "end_date": "2024-12-31"
}
```

---

## 3. STUDENT API ENDPOINTS

### 3.1 Get Students by Branch
**Endpoint:** `POST /api/students/get-by-branch`

**Description:** Get students filtered by branch, belt, and date range

**Request Body:**
```json
{
    "branch_id": 1,
    "belt_id": 2,
    "start_date": "2024-01-01",
    "end_date": "2024-12-31"
}
```

**Parameters:**
- `branch_id` (integer, optional): Branch ID (0 for all)
- `belt_id` (integer, optional): Belt ID (0 for all)
- `start_date` (string, optional): Start date (DOJ) in YYYY-MM-DD format
- `end_date` (string, optional): End date (DOJ) in YYYY-MM-DD format

**Response:** Students table with filters

---

### 3.2 Search Students
**Endpoint:** `POST /api/students/search`

**Description:** Search students by GR number or name

**Request Body:**
```json
{
    "grno": "101"
}
```

**Parameters:**
- `grno` (string, required): GR number or name (partial match)

---

### 3.3 Get Students for Additional Attendance
**Endpoint:** `POST /api/students/get-for-additional`

**Description:** Get students for additional attendance search

**Request Body:**
```json
{
    "grno": "101"
}
```

---

### 3.4 Get Students for Fastrack
**Endpoint:** `POST /api/students/get-for-fastrack`

**Description:** Get students for fastrack search

**Request Body:**
```json
{
    "grno": "101"
}
```

---

### 3.5 Deactive Report
**Endpoint:** `POST /api/students/deactive-report`

**Description:** Get deactivated students report

**Request Body:**
```json
{
    "branch_id": 1,
    "start_date": "2024-01-01",
    "end_date": "2024-12-31"
}
```

---

### 3.6 View Foundation Students
**Endpoint:** `POST /api/students/view-foundation`

**Description:** Get foundation belt students

**Request Body:**
```json
{
    "branch_id": 1
}
```

---

### 3.7 Set Status
**Endpoint:** `POST /api/students/set-status`

**Description:** Set call flag for student or fee

**Request Body:**
```json
{
    "stuId": 101,
    "from": 1
}
```

**OR**

```json
{
    "feeId": 123,
    "from": 2
}
```

**Parameters:**
- `stuId` (integer, optional): Student ID (when from=1)
- `feeId` (integer, optional): Fee ID (when from=2)
- `from` (integer, required): 1 for student, 2 for fee

---

## 4. ORDER API ENDPOINTS

### 4.1 Get Orders
**Endpoint:** `POST /api/orders/get-orders`

**Description:** Get orders list

**Request Body:**
```json
{
    "param": "true"
}
```

**Parameters:**
- `param` (string, required): 'true' for successful orders (status=1), 'false' for others

**Response:** Orders table

---

### 4.2 Mark Order as Viewed
**Endpoint:** `POST /api/orders/mark-viewed`

**Description:** Mark an order as viewed

**Request Body:**
```json
{
    "order_id": 501
}
```

**Parameters:**
- `order_id` (integer, required): Order ID

---

### 4.3 Mark Order as Delivered
**Endpoint:** `POST /api/orders/mark-delivered`

**Description:** Mark an order as delivered

**Request Body:**
```json
{
    "order_id": 501
}
```

**Parameters:**
- `order_id` (integer, required): Order ID

---

### 4.4 Delete Product
**Endpoint:** `POST /api/products/delete`

**Description:** Delete a product

**Request Body:**
```json
{
    "product_id": 201
}
```

**Parameters:**
- `product_id` (integer, required): Product ID

**Response:**
```json
{
    "deleted": true
}
```

---

## 5. EXAM API ENDPOINTS

### 5.1 Get Eligible Students
**Endpoint:** `POST /api/exam/get-eligible-students`

**Description:** Get students eligible for an exam

**Request Body:**
```json
{
    "exam_id": 1
}
```

**Parameters:**
- `exam_id` (integer, required): Exam ID

---

### 5.2 Set Exam Eligibility
**Endpoint:** `POST /api/exam/set-eligibility`

**Description:** Set eligibility for a student in an exam

**Request Body:**
```json
{
    "exam_id": 1,
    "student_id": 101,
    "eligible": true
}
```

**Parameters:**
- `exam_id` (integer, required): Exam ID
- `student_id` (integer, required): Student ID
- `eligible` (boolean, required): true/false

---

### 5.3 Get Exam Applied
**Endpoint:** `POST /api/exam/get-applied`

**Description:** Get students who applied for exam

**Request Body:**
```json
{
    "branch_id": 1,
    "param": "true"
}
```

**Parameters:**
- `branch_id` (integer, required): Exam ID
- `param` (string, required): 'true' for applied students, 'false' for not applied

---

### 5.4 Exam Result Report
**Endpoint:** `POST /api/exam/result-report`

**Description:** Get exam result report

**Request Body:**
```json
{
    "exam_id": 1,
    "branch_id": 1
}
```

---

### 5.5 Special Exam - Set Eligibility
**Endpoint:** `POST /api/exam/special/set-eligibility`

**Description:** Set special case exam eligibility

**Request Body:**
```json
{
    "exam_id": 1,
    "student_id": 101,
    "eligible": true
}
```

**Parameters:**
- `exam_id` (integer, required): Exam ID
- `student_id` (integer, required): Student ID
- `eligible` (boolean, required): true/false

---

## 6. EVENT API ENDPOINTS

### 6.1 Get Eligible Students
**Endpoint:** `POST /api/event/get-eligible-students`

**Description:** Get students eligible for an event

**Request Body:**
```json
{
    "event_id": 1
}
```

**Parameters:**
- `event_id` (integer, required): Event ID

---

### 6.2 Get Event Applied
**Endpoint:** `POST /api/event/get-applied`

**Description:** Get students who applied for event

**Request Body:**
```json
{
    "branch_id": 1,
    "param": "true"
}
```

**Parameters:**
- `branch_id` (integer, required): Event ID
- `param` (string, required): 'true' for applied students, 'false' for not applied

---

## Response Format

### Success Response
```json
{
    "success": true,
    "data": {...},
    "message": "Operation successful"
}
```

### Error Response
```json
{
    "success": false,
    "error": "Error message",
    "code": 400
}
```

### HTML Response (Legacy)
Some endpoints may return HTML tables (matching original PHP behavior). To get JSON, add header:
```
Accept: application/json
```

---

## Common Error Codes

- `200` - Success
- `400` - Bad Request (validation error)
- `401` - Unauthorized (authentication required)
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

---

## Notes

1. All dates should be in `YYYY-MM-DD` format
2. All month filters use `YYYY-MM` format
3. Branch ID `0` means "All Branches"
4. Belt ID `0` means "All Belts"
5. Attendance values: `P` = Present, `A` = Absent, `L` = Leave
6. All endpoints require authentication
7. Use `Accept: application/json` header to receive JSON responses instead of HTML


