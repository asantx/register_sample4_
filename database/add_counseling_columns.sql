-- Add counseling session columns to orders table
-- Run this SQL script in your database

-- Add order_type column (if it doesn't exist)
ALTER TABLE orders
ADD COLUMN IF NOT EXISTS order_type VARCHAR(50) DEFAULT 'product';

-- Add session_date column
ALTER TABLE orders
ADD COLUMN IF NOT EXISTS session_date DATE NULL;

-- Add session_time column
ALTER TABLE orders
ADD COLUMN IF NOT EXISTS session_time VARCHAR(20) NULL;

-- Add session_type column (video, phone, chat)
ALTER TABLE orders
ADD COLUMN IF NOT EXISTS session_type VARCHAR(20) NULL;

-- Add counselor_name column
ALTER TABLE orders
ADD COLUMN IF NOT EXISTS counselor_name VARCHAR(100) NULL;

-- Add session_notes column
ALTER TABLE orders
ADD COLUMN IF NOT EXISTS session_notes TEXT NULL;

-- Verify the changes
DESCRIBE orders;
