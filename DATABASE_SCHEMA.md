# Database Schema Documentation

This document contains all the CREATE TABLE statements for the RKKF database schema.

## Main Tables

### 1. users
- **Primary Key**: `user_id`
- **Fields**: firstname, lastname, mobile, email, password, role
- **Purpose**: Stores admin and instructor user accounts

### 2. belt
- **Primary Key**: `belt_id`
- **Fields**: name, code, exam_fees
- **Purpose**: Stores belt/rank information

### 3. branch
- **Primary Key**: `branch_id`
- **Fields**: name, days, fees, late, discount
- **Purpose**: Stores branch/location information

### 4. students
- **Primary Key**: `student_id`
- **Fields**: firstname, lastname, gender, email, password, belt_id, dadno, dadwp, momno, momwp, selfno, selfwp, dob, doj, address, branch_id, pincode, active
- **Foreign Keys**: belt_id → belt.belt_id, branch_id → branch.branch_id
- **Purpose**: Stores student information

### 5. coupon
- **Primary Key**: `coupon_id`
- **Fields**: coupon_txt, amount, used, active
- **Purpose**: Stores discount coupon codes

### 6. fees
- **Primary Key**: `fee_id`
- **Fields**: student_id, months, year, date, amount, coupon_id, additional, disabled, mode, remarks
- **Foreign Keys**: student_id → students.student_id, coupon_id → coupon.coupon_id
- **Purpose**: Stores fee payment records

### 7. attendance
- **Primary Key**: `attendance_id`
- **Fields**: student_id, date, attend, branch_id, user_id, is_additional
- **Foreign Keys**: student_id → students.student_id, branch_id → branch.branch_id, user_id → users.user_id
- **Unique Constraint**: (student_id, date, branch_id)
- **Purpose**: Stores attendance records

### 8. products
- **Primary Key**: `product_id`
- **Fields**: name, details, image1, image2, image3, belt_ids, active
- **Purpose**: Stores product information

### 9. variation
- **Primary Key**: `variation_id`
- **Fields**: product_id, variation, price, qty
- **Foreign Keys**: product_id → products.product_id
- **Purpose**: Stores product variations (sizes, colors, etc.)

### 10. orders
- **Primary Key**: `order_id`
- **Fields**: student_id, name_var, qty, p_price, date, status, rp_order_id, counter, flag_delivered, viewed
- **Foreign Keys**: student_id → students.student_id
- **Purpose**: Stores product orders

### 11. exam
- **Primary Key**: `exam_id`
- **Fields**: name, date, sessions_count, fees, fess_due_date, from_criteria, to_criteria, active
- **Purpose**: Stores exam information

### 12. event
- **Primary Key**: `event_id`
- **Fields**: name, from_date, to_date, venue, type, description, fees, fees_due_date, penalty, penalty_due_date, active
- **Purpose**: Stores event information

### 13. enquire
- **Primary Key**: `enquire_id`
- **Fields**: firstname, lastname, gender, email, dob, doj, dadno, dadwp, momno, momwp, selfno, selfwp, address, branch_id, pincode, order_id, amount, payment_id, payment_status, inserted_status, direct_entry
- **Foreign Keys**: branch_id → branch.branch_id
- **Purpose**: Stores enquiry/registration information before converting to students

## Additional Tables

### 14. notification
- Stores notifications for students

### 15. exam_fees
- Stores exam fee payments

### 16. exam_attendance
- Stores exam attendance records

### 17. special_case_exam
- Stores special exam eligibility cases

### 18. fastrack
- Stores fastrack program records

### 19. refund
- Stores refund information

### 20. team
- Stores team member information

### 21. ins_timetable
- Stores instructor timetable

### 22. post
- Stores news feed posts

### 23. media
- Stores media files for posts

### 24. guide
- Stores guide/documentation links

## Migration Files Location

All migration files are located in:
```
laravel-app/database/migrations/
```

## Running Migrations

**Note**: Since you have an existing database, you may not want to run these migrations. They are provided for documentation and reference purposes.

If you want to run them on a fresh database:
```bash
cd laravel-app
php artisan migrate
```

To rollback:
```bash
php artisan migrate:rollback
```

## Field Types Reference

- `id()` - Auto-incrementing integer primary key
- `string()` - VARCHAR(255)
- `text()` - TEXT
- `integer()` - INTEGER
- `boolean()` - TINYINT(1)
- `decimal(10, 2)` - DECIMAL(10,2)
- `date()` - DATE
- `timestamp()` - TIMESTAMP
- `foreignId()` - UNSIGNED BIGINT with foreign key constraint

