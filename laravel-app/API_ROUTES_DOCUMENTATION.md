# API Routes Documentation

This document lists all API routes created based on the core PHP AJAX files.

## Base URL
All API routes are prefixed with `/api` by default in Laravel.

## Authentication
All routes require authentication middleware (`auth`). Make sure to include authentication token in requests.

## Route Groups

### 1. Attendance API Routes

#### Get Students for Attendance
- **Route**: `POST /api/attendance/get-students`
- **PHP File**: `insert_attendance_ajax.php`
- **Description**: Get list of students for a branch to mark attendance
- **Parameters**: 
  - `branchId` (required): Branch ID

#### Get Attendance by Branch and Date
- **Route**: `POST /api/attendance/get-attendance`
- **PHP File**: `attendance_ajax.php`
- **Description**: Get attendance records for a specific branch and date
- **Parameters**:
  - `branchId` (required): Branch ID
  - `date` (required): Date (YYYY-MM-DD)

#### Insert/Update Attendance
- **Route**: `POST /api/attendance/insert`
- **PHP File**: `insert_attendance.php`
- **Description**: Insert or update attendance records
- **Parameters**: Array of attendance records

#### Additional Attendance - Get Students
- **Route**: `POST /api/attendance/additional/get-students`
- **PHP File**: `additional_attendance_ajax.php`
- **Description**: Get students for additional attendance
- **Parameters**:
  - `branchId` (required): Branch ID
  - `date` (required): Date

#### Additional Attendance - Get Attendance
- **Route**: `POST /api/attendance/additional/get-attendance`
- **PHP File**: `additional_attendance_ajax.php`
- **Description**: Get additional attendance records
- **Parameters**:
  - `branchId` (required): Branch ID
  - `date` (required): Date

#### Additional Attendance - Insert/Update
- **Route**: `POST /api/attendance/additional/insert`
- **PHP File**: `additional_attendance.php`
- **Description**: Insert or update additional attendance

#### Attendance Log
- **Route**: `POST /api/attendance/log`
- **PHP File**: `attendance_log_ajax.php`
- **Description**: Get attendance log with filters

#### View Attendance
- **Route**: `POST /api/attendance/view`
- **PHP File**: `view_attendance_ajax.php`
- **Description**: View attendance with filters
- **Parameters**:
  - `branch_id` (optional): Branch ID
  - `start_date` (optional): Start date
  - `end_date` (optional): End date
  - `param` (optional): Filter parameter

#### Exam Attendance - Get Students
- **Route**: `POST /api/exam-attendance/get-students`
- **PHP File**: `exam_attendance_ajax.php`
- **Description**: Get students for exam attendance
- **Parameters**:
  - `branchId` (required): Exam ID

#### Exam Attendance - Insert/Update
- **Route**: `POST /api/exam-attendance/insert`
- **PHP File**: `exam_attendance.php`
- **Description**: Insert or update exam attendance

#### Event Attendance - Get Students
- **Route**: `POST /api/event-attendance/get-students`
- **PHP File**: `event_attendance_ajax.php`
- **Description**: Get students for event attendance
- **Parameters**:
  - `branchId` (required): Event ID
  - `date` (required): Date

#### Event Attendance - Insert/Update
- **Route**: `POST /api/event-attendance/insert`
- **PHP File**: `event_attendance.php`
- **Description**: Insert or update event attendance

### 2. Fees API Routes

#### Get Student Info by GR Number
- **Route**: `POST /api/fees/get-student-info`
- **PHP File**: `enter_fees_ajax.php`
- **Description**: Get student information and last fee paid details
- **Parameters**:
  - `grno` (required): Student GR number or name

#### Get Fees List
- **Route**: `POST /api/fees/get-fees`
- **PHP File**: `view_fees_ajax.php`
- **Description**: Get fees list with filters
- **Parameters**:
  - `branch_id` (optional): Branch ID
  - `start_date` (optional): Start date (YYYY-MM format)
  - `end_date` (optional): End date (YYYY-MM format)
  - `param` (optional): Filter parameter ('true' for all)

#### Delete Fee
- **Route**: `POST /api/fees/delete`
- **PHP File**: `delete_fees_ajax.php`
- **Description**: Delete a fee record
- **Parameters**:
  - `fee_id` (required): Fee ID
- **Response**: `{"deleted": true/false}`

#### Enter Fees - Get Student
- **Route**: `POST /api/fees/enter/get-student`
- **PHP File**: `enter_fees_ajax.php`
- **Description**: Get student for entering fees
- **Parameters**:
  - `grno` (required): Student GR number

#### Enter Old Fees - Get Student
- **Route**: `POST /api/fees/enter-old/get-student`
- **PHP File**: `enter_fees_old_ajax.php`
- **Description**: Get student for entering old fees
- **Parameters**:
  - `grno` (required): Student GR number

#### Enter Exam Fees
- **Route**: `POST /api/fees/enter-exam`
- **PHP File**: `enter_exam_fees_ajax.php`
- **Description**: Enter exam fees for a student

#### View Combined Fees
- **Route**: `POST /api/fees/combined`
- **PHP File**: `view_combined_fees_ajax.php`
- **Description**: Get combined fees report

#### View Fees Without Admission
- **Route**: `POST /api/fees/view-without-admission`
- **PHP File**: `view_fees_ajax_without_admission_fees.php`
- **Description**: Get fees excluding admission fees

#### Disable Fees - Get Student
- **Route**: `POST /api/fees/disable/get-student`
- **PHP File**: `enter_fees_ajax.php` (disable_fees.php uses this)
- **Description**: Get student info for disabling fees
- **Parameters**:
  - `grno` (required): Student GR number
  - `disable_student_id` (optional): Student ID

#### Fix Payment Entry
- **Route**: `POST /api/fees/fix-payment`
- **PHP File**: `fix_payment_entry_ajax.php`
- **Description**: Fix payment entry issues

#### Payment Report
- **Route**: `POST /api/fees/payment-report`
- **PHP File**: `payment_report_ajax.php`
- **Description**: Get payment report with filters

#### Full Report
- **Route**: `POST /api/fees/full-report`
- **PHP File**: `full_report_ajax.php`
- **Description**: Get full financial report

### 3. Student API Routes

#### Get Students by Branch
- **Route**: `POST /api/students/get-by-branch`
- **PHP File**: `view_students_by_branch_ajax.php`
- **Description**: Get students filtered by branch, belt, and date range
- **Parameters**:
  - `branch_id` (optional): Branch ID (0 for all)
  - `belt_id` (optional): Belt ID (0 for all)
  - `start_date` (optional): Start date (DOJ)
  - `end_date` (optional): End date (DOJ)

#### Search Students
- **Route**: `POST /api/students/search`
- **PHP File**: `enter_fees_ajax.php`, `disable_fees.php`
- **Description**: Search students by GR number or name
- **Parameters**:
  - `grno` (required): GR number or name

#### Get Students for Additional Attendance
- **Route**: `POST /api/students/get-for-additional`
- **PHP File**: `get_students_from_name_or_grno_additional.php`
- **Description**: Get students for additional attendance search

#### Get Students for Fastrack
- **Route**: `POST /api/students/get-for-fastrack`
- **PHP File**: `get_students_from_name_or_grno_fastrack.php`
- **Description**: Get students for fastrack search

#### Deactive Report
- **Route**: `POST /api/students/deactive-report`
- **PHP File**: `deactive_report_ajax.php`
- **Description**: Get deactivated students report

#### View Foundation Students
- **Route**: `POST /api/students/view-foundation`
- **PHP File**: `view_foundation_ajax.php`
- **Description**: Get foundation belt students

#### Set Status
- **Route**: `POST /api/students/set-status`
- **PHP File**: `set_status_ajax.php`
- **Description**: Set call flag for student or fee
- **Parameters**:
  - `stuId` (optional): Student ID
  - `feeId` (optional): Fee ID
  - `from` (required): 1 for student, 2 for fee

### 4. Order API Routes

#### Get Orders
- **Route**: `POST /api/orders/get-orders`
- **PHP File**: `orders_ajax.php`
- **Description**: Get orders list
- **Parameters**:
  - `param` (required): 'true' for successful orders, 'false' for others

#### Mark Order as Viewed
- **Route**: `POST /api/orders/mark-viewed`
- **PHP File**: `order_viewed.php`
- **Description**: Mark an order as viewed

#### Mark Order as Delivered
- **Route**: `POST /api/orders/mark-delivered`
- **PHP File**: `order_delivered_mail.php`
- **Description**: Mark an order as delivered

#### Delete Product
- **Route**: `POST /api/products/delete`
- **PHP File**: `delete_product_ajax.php`
- **Description**: Delete a product

### 5. Exam API Routes

#### Get Eligible Students
- **Route**: `POST /api/exam/get-eligible-students`
- **PHP File**: `eligible_students_ajax.php`
- **Description**: Get students eligible for an exam
- **Parameters**:
  - `exam_id` (required): Exam ID

#### Set Exam Eligibility
- **Route**: `POST /api/exam/set-eligibility`
- **PHP File**: `eligible_students_ajax.php`
- **Description**: Set eligibility for a student in an exam
- **Parameters**:
  - `exam_id` (required): Exam ID
  - `student_id` (required): Student ID
  - `eligible` (required): true/false

#### Get Exam Applied
- **Route**: `POST /api/exam/get-applied`
- **PHP File**: `exam_applied_ajax.php`
- **Description**: Get students who applied for exam
- **Parameters**:
  - `branch_id` (required): Exam ID
  - `param` (required): 'true' for applied, 'false' for not applied

#### Exam Result Report
- **Route**: `POST /api/exam/result-report`
- **PHP File**: `exam_result_report_ajax.php`
- **Description**: Get exam result report

#### Special Exam - Set Eligibility
- **Route**: `POST /api/exam/special/set-eligibility`
- **PHP File**: `special_ajax.php`
- **Description**: Set special case exam eligibility
- **Parameters**:
  - `exam_id` (required): Exam ID
  - `student_id` (required): Student ID
  - `eligible` (required): true/false

### 6. Event API Routes

#### Get Eligible Students
- **Route**: `POST /api/event/get-eligible-students`
- **PHP File**: `eligible_students_event_ajax.php`
- **Description**: Get students eligible for an event
- **Parameters**:
  - `event_id` (required): Event ID

#### Get Event Applied
- **Route**: `POST /api/event/get-applied`
- **PHP File**: `event_applied_ajax.php`
- **Description**: Get students who applied for event
- **Parameters**:
  - `branch_id` (required): Event ID
  - `param` (required): 'true' for applied, 'false' for not applied

## Response Format

Most endpoints return HTML tables (matching original PHP behavior), but can be modified to return JSON by setting `Accept: application/json` header or adding `format=json` parameter.

## Usage Example

```javascript
// Using fetch API
fetch('/api/attendance/get-students', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer YOUR_TOKEN'
    },
    body: JSON.stringify({
        branchId: 1
    })
})
.then(response => response.json())
.then(data => console.log(data));
```

## Notes

- All routes are protected by `auth` middleware
- Routes match the original PHP AJAX file functionality
- Controllers need to be implemented to handle the logic
- Consider adding rate limiting for production use


