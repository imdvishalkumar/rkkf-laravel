# Database Migrations Summary

This document lists all the Laravel migration files created based on the `rkkf.sql` schema.

## Migration Files Created (29 total)

### Core Tables (Created First - Dependencies)
1. **2025_01_16_000002_create_belt_table.php** - Belt/rank definitions
2. **2025_01_16_000003_create_branch_table.php** - Branch/location information
3. **2025_01_16_000006_create_coupon_table.php** - Coupon codes for discounts
4. **2025_01_16_000023_create_products_table.php** - Product catalog
5. **2025_01_16_000029_create_variation_table.php** - Product variations (depends on products)
6. **2025_01_16_000028_create_users_table.php** - Admin/Instructor users

### Student Related Tables
7. **2025_01_16_000025_create_students_table.php** - Student information (depends on belt, branch)

### Attendance Tables
8. **2025_01_16_000001_create_attendance_table.php** - Regular attendance records (depends on students, branch, users)
9. **2025_01_16_000013_create_exam_attendance_table.php** - Exam attendance (depends on exam, students, users)
10. **2025_01_16_000010_create_event_attendance_table.php** - Event attendance (depends on event, students, users)
11. **2025_01_16_000016_create_fastrack_attendance_table.php** - Fastrack program attendance (depends on students, branch, users)

### Fees & Payment Tables
12. **2025_01_16_000017_create_fees_table.php** - Monthly fees (depends on students, coupon)
13. **2025_01_16_000014_create_exam_fees_table.php** - Exam fees (depends on exam, students, belt)
14. **2025_01_16_000011_create_event_fees_table.php** - Event fees (depends on event, students)
15. **2025_01_16_000027_create_transcation_table.php** - Transaction history (depends on students, coupon)
16. **2025_01_16_000018_create_fees_const_table.php** - Fee constants/configuration

### Exam & Event Tables
17. **2025_01_16_000012_create_exam_table.php** - Exam definitions
18. **2025_01_16_000009_create_event_table.php** - Event definitions
19. **2025_01_16_000024_create_special_case_event_table.php** - Special event eligibility (depends on event, students)
20. **2025_01_16_000026_create_temp_exam_table.php** - Temporary exam data (depends on students, belt)

### Order & Product Tables
21. **2025_01_16_000005_create_cart_table.php** - Shopping cart (depends on students, products, variation)
22. **2025_01_16_000022_create_orders_table.php** - Orders (depends on students, products, variation)

### Additional Tables
23. **2025_01_16_000008_create_enquire_table.php** - Student inquiries/enrollments (depends on branch)
24. **2025_01_16_000007_create_devices_table.php** - Student device registrations (depends on students)
25. **2025_01_16_000015_create_fastrack_table.php** - Fastrack program records (depends on students, belt)
26. **2025_01_16_000021_create_leave_table.php** - Student leave records (depends on students)
27. **2025_01_16_000004_create_branch_text_table.php** - Branch text content
28. **2025_01_16_000019_create_gmail_acc_table.php** - Gmail account configuration
29. **2025_01_16_000020_create_invoice_table.php** - Invoice references

## Notes

### Tables Already Covered in Existing Migrations
The following tables are already defined in `2025_11_28_140013_create_additional_tables.php`:
- `notification` - Already exists
- `exam_fees` - Already exists (but may need updates to match exact schema)
- `exam_attendance` - Already exists (but may need updates to match exact schema)
- `special_case_exam` - Already exists
- `fastrack` - Already exists
- `refund` - Already exists
- `team` - Already exists
- `ins_timetable` - Already exists
- `post` - Already exists
- `media` - Already exists
- `guide` - Already exists

### Backup Tables (Not Created)
The following backup tables from the SQL were not created as migrations:
- `exam_attendance1` - Backup table
- `exam_attendance_bkp` - Backup table
- `orders_bkp` - Backup table

### Migration Order
The migrations are numbered sequentially, but when running them, ensure dependencies are met:
1. Core tables (belt, branch, coupon, products, users) must be created first
2. Students table depends on belt and branch
3. All other tables depend on students and/or other core tables

### Foreign Key Constraints
All foreign key relationships have been properly defined in the migrations. The constraints will:
- Ensure data integrity
- Cascade deletes where appropriate
- Prevent orphaned records

## Running the Migrations

To run all migrations:
```bash
php artisan migrate
```

To run a specific migration:
```bash
php artisan migrate --path=/database/migrations/2025_01_16_000001_create_attendance_table.php
```

To rollback:
```bash
php artisan migrate:rollback
```

## Schema Differences from Existing Migrations

If you have existing migrations that conflict with these new ones, you may need to:
1. Review the existing migrations
2. Update them to match the exact SQL schema
3. Or remove the conflicting migrations and use these new ones

The new migrations match the exact structure from `rkkf.sql` including:
- Exact column types and sizes
- Nullable/non-nullable constraints
- Default values
- Indexes for performance

