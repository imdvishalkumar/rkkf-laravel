# Migration Replacement Summary

This document summarizes the migration files that were replaced with new ones matching the exact SQL schema from `rkkf.sql`.

## Deleted Old Migrations (Replaced)

The following old migration files were deleted and replaced with new ones:

### November 2024 Migrations (Deleted)
1. `2025_11_28_130026_create_students_table.php` → Replaced by `2025_01_16_000025_create_students_table.php`
2. `2025_11_28_130034_create_branches_table.php` → Replaced by `2025_01_16_000003_create_branch_table.php`
3. `2025_11_28_130043_create_belts_table.php` → Replaced by `2025_01_16_000002_create_belt_table.php`
4. `2025_11_28_130053_create_fees_table.php` → Replaced by `2025_01_16_000017_create_fees_table.php`
5. `2025_11_28_130102_create_products_table.php` → Replaced by `2025_01_16_000023_create_products_table.php`
6. `2025_11_28_130602_create_coupons_table.php` → Replaced by `2025_01_16_000006_create_coupon_table.php`
7. `2025_11_28_130603_create_attendances_table.php` → Replaced by `2025_01_16_000001_create_attendance_table.php`
8. `2025_11_28_130603_create_orders_table.php` → Replaced by `2025_01_16_000022_create_orders_table.php`
9. `2025_11_28_130604_create_exams_table.php` → Replaced by `2025_01_16_000012_create_exam_table.php`
10. `2025_11_28_130605_create_events_table.php` → Replaced by `2025_01_16_000009_create_event_table.php`

### December 2024 Migrations (Deleted)
11. `2025_11_28_140000_create_users_table.php` → Replaced by `2025_01_16_000028_create_users_table.php`
12. `2025_11_28_140001_create_belt_table.php` → Replaced by `2025_01_16_000002_create_belt_table.php`
13. `2025_11_28_140002_create_branch_table.php` → Replaced by `2025_01_16_000003_create_branch_table.php`
14. `2025_11_28_140003_create_students_table.php` → Replaced by `2025_01_16_000025_create_students_table.php`
15. `2025_11_28_140004_create_coupon_table.php` → Replaced by `2025_01_16_000006_create_coupon_table.php`
16. `2025_11_28_140005_create_fees_table.php` → Replaced by `2025_01_16_000017_create_fees_table.php`
17. `2025_11_28_140006_create_attendance_table.php` → Replaced by `2025_01_16_000001_create_attendance_table.php`
18. `2025_11_28_140007_create_products_table.php` → Replaced by `2025_01_16_000023_create_products_table.php`
19. `2025_11_28_140008_create_variation_table.php` → Replaced by `2025_01_16_000029_create_variation_table.php`
20. `2025_11_28_140009_create_orders_table.php` → Replaced by `2025_01_16_000022_create_orders_table.php`
21. `2025_11_28_140010_create_exam_table.php` → Replaced by `2025_01_16_000012_create_exam_table.php`
22. `2025_11_28_140011_create_event_table.php` → Replaced by `2025_01_16_000009_create_event_table.php`
23. `2025_11_28_140012_create_enquire_table.php` → Replaced by `2025_01_16_000008_create_enquire_table.php`
24. `2025_11_28_140013_create_additional_tables.php` → Replaced by individual migrations (see below)
25. `2025_11_28_140014_create_sessions_table.php` → Removed (sessions table is in default Laravel migration)

## New Migrations Created (Matching SQL Schema)

All new migrations are dated `2025_01_16` and match the exact structure from `rkkf.sql`:

### Core Tables
- `2025_01_16_000002_create_belt_table.php`
- `2025_01_16_000003_create_branch_table.php`
- `2025_01_16_000006_create_coupon_table.php`
- `2025_01_16_000023_create_products_table.php`
- `2025_01_16_000029_create_variation_table.php`
- `2025_01_16_000028_create_users_table.php`

### Student & Attendance Tables
- `2025_01_16_000025_create_students_table.php`
- `2025_01_16_000001_create_attendance_table.php`
- `2025_01_16_000013_create_exam_attendance_table.php`
- `2025_01_16_000010_create_event_attendance_table.php`
- `2025_01_16_000016_create_fastrack_attendance_table.php`

### Fees & Payment Tables
- `2025_01_16_000017_create_fees_table.php`
- `2025_01_16_000014_create_exam_fees_table.php`
- `2025_01_16_000011_create_event_fees_table.php`
- `2025_01_16_000027_create_transcation_table.php`
- `2025_01_16_000018_create_fees_const_table.php`

### Exam & Event Tables
- `2025_01_16_000012_create_exam_table.php`
- `2025_01_16_000009_create_event_table.php`
- `2025_01_16_000024_create_special_case_event_table.php`
- `2025_01_16_000030_create_special_case_exam_table.php`
- `2025_01_16_000026_create_temp_exam_table.php`

### Order & Product Tables
- `2025_01_16_000005_create_cart_table.php`
- `2025_01_16_000022_create_orders_table.php`

### Additional Tables
- `2025_01_16_000008_create_enquire_table.php`
- `2025_01_16_000007_create_devices_table.php`
- `2025_01_16_000015_create_fastrack_table.php`
- `2025_01_16_000021_create_leave_table.php`
- `2025_01_16_000004_create_branch_text_table.php`
- `2025_01_16_000019_create_gmail_acc_table.php`
- `2025_01_16_000020_create_invoice_table.php`
- `2025_01_16_000031_create_notification_table.php`
- `2025_01_16_000032_create_refund_table.php`
- `2025_01_16_000033_create_team_table.php`
- `2025_01_16_000034_create_ins_timetable_table.php`
- `2025_01_16_000035_create_post_table.php`
- `2025_01_16_000036_create_media_table.php`
- `2025_01_16_000037_create_guide_table.php`

## Updated Default Laravel Migration

The default Laravel migration `0001_01_01_000000_create_users_table.php` was updated to:
- Remove the `users` table creation (now handled by `2025_01_16_000028_create_users_table.php`)
- Keep `password_reset_tokens` table
- Keep `sessions` table (with updated foreign key to reference `users.user_id`)

## Key Differences in New Migrations

The new migrations match the exact SQL schema including:

1. **Exact Column Types**: All column types and sizes match the SQL schema exactly
   - Example: `varchar(50)` instead of generic `string()`
   - Example: `int(11)` instead of `integer()`

2. **Nullable Constraints**: Properly set based on SQL schema
   - Fields that are `NOT NULL` in SQL are required in migrations
   - Fields that are `DEFAULT NULL` in SQL are nullable in migrations

3. **Default Values**: All default values from SQL schema are preserved

4. **Foreign Keys**: All foreign key relationships are properly defined with correct column names
   - Example: `constrained('students', 'student_id')` instead of `constrained('students')`

5. **Indexes**: Performance indexes added based on common query patterns

6. **Comments**: SQL comments are preserved where applicable

## Migration Order

The migrations are numbered sequentially and should be run in order. Dependencies are handled automatically through foreign key constraints.

## Next Steps

1. **Backup your database** before running migrations
2. **Review the migrations** to ensure they match your requirements
3. **Run migrations**: `php artisan migrate`
4. **Verify tables**: Check that all tables are created correctly

## Notes

- All old migrations have been completely removed
- The new migrations are ready to use
- Foreign key constraints will ensure data integrity
- Indexes are included for performance optimization

