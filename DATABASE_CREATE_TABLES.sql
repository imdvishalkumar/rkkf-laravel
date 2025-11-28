-- RKKF Database Schema - CREATE TABLE Statements
-- This file contains all CREATE TABLE queries for reference

-- ============================================
-- 1. USERS TABLE
-- ============================================
CREATE TABLE `users` (
  `user_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `firstname` VARCHAR(255) NOT NULL,
  `lastname` VARCHAR(255) NOT NULL,
  `mobile` VARCHAR(255) NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` INT NOT NULL DEFAULT 1 COMMENT '1 = admin, 2 = instructor',
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 2. BELT TABLE
-- ============================================
CREATE TABLE `belt` (
  `belt_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `code` VARCHAR(255) NULL,
  `exam_fees` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`belt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 3. BRANCH TABLE
-- ============================================
CREATE TABLE `branch` (
  `branch_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL UNIQUE,
  `days` VARCHAR(255) NULL COMMENT 'Comma-separated days',
  `fees` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `late` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `discount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 4. STUDENTS TABLE
-- ============================================
CREATE TABLE `students` (
  `student_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `firstname` VARCHAR(255) NOT NULL,
  `lastname` VARCHAR(255) NOT NULL,
  `gender` INT NOT NULL COMMENT '1 = Male, 2 = Female',
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `belt_id` BIGINT UNSIGNED NOT NULL,
  `dadno` VARCHAR(255) NULL COMMENT 'Dad mobile number',
  `dadwp` VARCHAR(255) NULL COMMENT 'Dad WhatsApp',
  `momno` VARCHAR(255) NULL COMMENT 'Mom mobile number',
  `momwp` VARCHAR(255) NULL COMMENT 'Mom WhatsApp',
  `selfno` VARCHAR(255) NOT NULL COMMENT 'Self mobile number',
  `selfwp` VARCHAR(255) NULL COMMENT 'Self WhatsApp',
  `dob` DATE NOT NULL COMMENT 'Date of birth',
  `doj` DATE NOT NULL COMMENT 'Date of joining',
  `address` TEXT NULL,
  `branch_id` BIGINT UNSIGNED NOT NULL,
  `pincode` VARCHAR(255) NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`student_id`),
  FOREIGN KEY (`belt_id`) REFERENCES `belt` (`belt_id`),
  FOREIGN KEY (`branch_id`) REFERENCES `branch` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 5. COUPON TABLE
-- ============================================
CREATE TABLE `coupon` (
  `coupon_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `coupon_txt` VARCHAR(255) NOT NULL UNIQUE,
  `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `used` INT NOT NULL DEFAULT 0,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`coupon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 6. FEES TABLE
-- ============================================
CREATE TABLE `fees` (
  `fee_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` BIGINT UNSIGNED NOT NULL,
  `months` INT NOT NULL COMMENT 'Month number (1-12)',
  `year` INT NOT NULL,
  `date` DATE NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `coupon_id` BIGINT UNSIGNED NOT NULL DEFAULT 1,
  `additional` TINYINT(1) NOT NULL DEFAULT 0,
  `disabled` TINYINT(1) NOT NULL DEFAULT 0,
  `mode` VARCHAR(255) NOT NULL DEFAULT 'cash' COMMENT 'cash, online',
  `remarks` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`fee_id`),
  FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  FOREIGN KEY (`coupon_id`) REFERENCES `coupon` (`coupon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 7. ATTENDANCE TABLE
-- ============================================
CREATE TABLE `attendance` (
  `attendance_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` BIGINT UNSIGNED NOT NULL,
  `date` DATE NOT NULL,
  `attend` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0 = absent, 1 = present',
  `branch_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NULL,
  `is_additional` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`attendance_id`),
  FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  FOREIGN KEY (`branch_id`) REFERENCES `branch` (`branch_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  UNIQUE KEY `unique_attendance` (`student_id`, `date`, `branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 8. PRODUCTS TABLE
-- ============================================
CREATE TABLE `products` (
  `product_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `details` TEXT NULL,
  `image1` VARCHAR(255) NULL,
  `image2` VARCHAR(255) NULL,
  `image3` VARCHAR(255) NULL,
  `belt_ids` VARCHAR(255) NULL COMMENT 'Comma-separated belt IDs',
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 9. VARIATION TABLE
-- ============================================
CREATE TABLE `variation` (
  `variation_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `variation` VARCHAR(255) NOT NULL COMMENT 'Variation name',
  `price` DECIMAL(10,2) NOT NULL,
  `qty` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`variation_id`),
  FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 10. ORDERS TABLE
-- ============================================
CREATE TABLE `orders` (
  `order_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` BIGINT UNSIGNED NOT NULL,
  `name_var` VARCHAR(255) NOT NULL COMMENT 'Product variation name',
  `qty` INT NOT NULL,
  `p_price` DECIMAL(10,2) NOT NULL COMMENT 'Product price',
  `date` DATE NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0 = pending, 1 = success',
  `rp_order_id` VARCHAR(255) NULL COMMENT 'RazorPay order ID',
  `counter` VARCHAR(255) NULL COMMENT 'Order counter/number',
  `flag_delivered` TINYINT(1) NOT NULL DEFAULT 0,
  `viewed` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`order_id`),
  FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 11. EXAM TABLE
-- ============================================
CREATE TABLE `exam` (
  `exam_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `date` DATE NOT NULL,
  `sessions_count` INT NOT NULL DEFAULT 1,
  `fees` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `fess_due_date` DATE NULL COMMENT 'Fees due date',
  `from_criteria` DATE NULL COMMENT 'From date for eligibility',
  `to_criteria` DATE NULL COMMENT 'To date for eligibility',
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`exam_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 12. EVENT TABLE
-- ============================================
CREATE TABLE `event` (
  `event_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `from_date` DATE NOT NULL,
  `to_date` DATE NOT NULL,
  `venue` VARCHAR(255) NULL,
  `type` VARCHAR(255) NULL,
  `description` TEXT NULL,
  `fees` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `fees_due_date` DATE NULL,
  `penalty` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `penalty_due_date` DATE NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 13. ENQUIRE TABLE
-- ============================================
CREATE TABLE `enquire` (
  `enquire_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `firstname` VARCHAR(255) NOT NULL,
  `lastname` VARCHAR(255) NOT NULL,
  `gender` INT NOT NULL COMMENT '1 = Male, 2 = Female',
  `email` VARCHAR(255) NOT NULL,
  `dob` DATE NOT NULL,
  `doj` DATE NOT NULL,
  `dadno` VARCHAR(255) NULL,
  `dadwp` VARCHAR(255) NULL,
  `momno` VARCHAR(255) NULL,
  `momwp` VARCHAR(255) NULL,
  `selfno` VARCHAR(255) NOT NULL,
  `selfwp` VARCHAR(255) NULL,
  `address` TEXT NULL,
  `branch_id` BIGINT UNSIGNED NOT NULL,
  `pincode` VARCHAR(255) NULL,
  `order_id` VARCHAR(255) NOT NULL DEFAULT '0',
  `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `payment_id` VARCHAR(255) NULL,
  `payment_status` TINYINT(1) NOT NULL DEFAULT 0,
  `inserted_status` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0 = not inserted to students, 1 = inserted',
  `direct_entry` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`enquire_id`),
  FOREIGN KEY (`branch_id`) REFERENCES `branch` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 14. NOTIFICATION TABLE
-- ============================================
CREATE TABLE `notification` (
  `notification_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `details` TEXT NOT NULL,
  `student_id` BIGINT UNSIGNED NULL,
  `viewed` TINYINT(1) NOT NULL DEFAULT 0,
  `type` VARCHAR(255) NULL COMMENT 'exam, event, custom',
  `sent` TINYINT(1) NOT NULL DEFAULT 0,
  `timestamp` TIMESTAMP NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`notification_id`),
  FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 15. EXAM_FEES TABLE
-- ============================================
CREATE TABLE `exam_fees` (
  `exam_fees_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `exam_id` BIGINT UNSIGNED NOT NULL,
  `student_id` BIGINT UNSIGNED NOT NULL,
  `date` DATE NOT NULL,
  `mode` VARCHAR(255) NOT NULL DEFAULT 'manual' COMMENT 'manual, online',
  `rp_order_id` VARCHAR(255) NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 0,
  `amount` DECIMAL(10,2) NOT NULL,
  `exam_belt_id` BIGINT UNSIGNED NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`exam_fees_id`),
  FOREIGN KEY (`exam_id`) REFERENCES `exam` (`exam_id`),
  FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  FOREIGN KEY (`exam_belt_id`) REFERENCES `belt` (`belt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 16. EXAM_ATTENDANCE TABLE
-- ============================================
CREATE TABLE `exam_attendance` (
  `exam_attendance_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `exam_id` BIGINT UNSIGNED NOT NULL,
  `student_id` BIGINT UNSIGNED NOT NULL,
  `attend` TINYINT(1) NOT NULL DEFAULT 0,
  `user_id` BIGINT UNSIGNED NULL,
  `certificate_no` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`exam_attendance_id`),
  FOREIGN KEY (`exam_id`) REFERENCES `exam` (`exam_id`),
  FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 17. SPECIAL_CASE_EXAM TABLE
-- ============================================
CREATE TABLE `special_case_exam` (
  `special_case_exam_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` BIGINT UNSIGNED NOT NULL,
  `exam_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`special_case_exam_id`),
  FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  FOREIGN KEY (`exam_id`) REFERENCES `exam` (`exam_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 18. FASTRACK TABLE
-- ============================================
CREATE TABLE `fastrack` (
  `fastrack_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` BIGINT UNSIGNED NOT NULL,
  `from_belt_id` BIGINT UNSIGNED NOT NULL,
  `to_belt_id` BIGINT UNSIGNED NOT NULL,
  `from_date` DATE NOT NULL,
  `to_date` DATE NOT NULL,
  `months_count` INT NOT NULL,
  `total_fees` DECIMAL(10,2) NOT NULL,
  `total_hours` INT NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`fastrack_id`),
  FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  FOREIGN KEY (`from_belt_id`) REFERENCES `belt` (`belt_id`),
  FOREIGN KEY (`to_belt_id`) REFERENCES `belt` (`belt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 19. REFUND TABLE
-- ============================================
CREATE TABLE `refund` (
  `refund_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_id` VARCHAR(255) NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `cheque_no` VARCHAR(255) NULL,
  `description` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`refund_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 20. TEAM TABLE
-- ============================================
CREATE TABLE `team` (
  `team_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `post` VARCHAR(255) NOT NULL,
  `image` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 21. INS_TIMETABLE TABLE
-- ============================================
CREATE TABLE `ins_timetable` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `branch_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`branch_id`) REFERENCES `branch` (`branch_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 22. POST TABLE (News Feed)
-- ============================================
CREATE TABLE `post` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `created` TIMESTAMP NOT NULL,
  `is_deleted` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 23. MEDIA TABLE
-- ============================================
CREATE TABLE `media` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id` BIGINT UNSIGNED NOT NULL,
  `path` VARCHAR(255) NOT NULL,
  `type` VARCHAR(255) NOT NULL COMMENT 'image, video',
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`post_id`) REFERENCES `post` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 24. GUIDE TABLE
-- ============================================
CREATE TABLE `guide` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `link` VARCHAR(255) NOT NULL,
  `created_by` BIGINT UNSIGNED NULL,
  `is_deleted` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

