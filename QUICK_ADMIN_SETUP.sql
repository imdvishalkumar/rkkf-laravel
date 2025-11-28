-- Quick Admin User Setup for RKKF Laravel
-- Run this in your database (phpMyAdmin, MySQL Workbench, or command line)

-- Create default admin user (if it doesn't exist)
INSERT INTO `users` (`firstname`, `lastname`, `email`, `password`, `role`, `mobile`, `created_at`, `updated_at`) 
VALUES ('Admin', 'User', 'admin@rkkf.com', 'admin123', 1, NULL, NOW(), NOW())
ON DUPLICATE KEY UPDATE `email` = `email`;

-- Verify the admin user was created
SELECT user_id, firstname, lastname, email, role FROM users WHERE email = 'admin@rkkf.com';

-- ============================================
-- LOGIN CREDENTIALS:
-- ============================================
-- Email: admin@rkkf.com
-- Password: admin123
-- ============================================

