-- Create Admin User for RKKF Laravel Application
-- Run this SQL query directly in your database

-- Option 1: Create a new admin user
INSERT INTO `users` (`firstname`, `lastname`, `email`, `password`, `role`, `mobile`, `created_at`, `updated_at`) 
VALUES ('Admin', 'User', 'admin@rkkf.com', 'admin123', 1, NULL, NOW(), NOW())
ON DUPLICATE KEY UPDATE `email` = `email`;

-- Option 2: If you want to use an existing user from your database
-- Check existing admin users:
-- SELECT * FROM users WHERE role = 1;

-- Option 3: Update existing user to admin
-- UPDATE users SET role = 1 WHERE email = 'your-email@example.com';

-- Default Admin Credentials:
-- Email: admin@rkkf.com
-- Password: admin123
-- Role: 1 (Admin)

-- Note: The password is stored in plain text to match your existing system.
-- You can change it later to use hashed passwords.

