-- Paystack Payment Integration Schema Updates
-- DistantLove Platform
-- Run this SQL to add Paystack-specific fields to your payment and orders tables

-- Update payment table to support Paystack
ALTER TABLE payment
ADD COLUMN IF NOT EXISTS payment_method VARCHAR(50) DEFAULT 'paystack' COMMENT 'Payment gateway used',
ADD COLUMN IF NOT EXISTS transaction_ref VARCHAR(100) UNIQUE COMMENT 'Paystack transaction reference',
ADD COLUMN IF NOT EXISTS authorization_code VARCHAR(100) COMMENT 'Paystack authorization code for recurring payments',
ADD COLUMN IF NOT EXISTS payment_channel VARCHAR(50) COMMENT 'Payment channel (card, bank, ussd, etc)',
ADD COLUMN IF NOT EXISTS payment_status VARCHAR(20) DEFAULT 'pending' COMMENT 'Payment status (pending, success, failed)',
ADD COLUMN IF NOT EXISTS paid_at DATETIME COMMENT 'When payment was completed',
ADD COLUMN IF NOT EXISTS payment_metadata TEXT COMMENT 'Additional payment data in JSON format';

-- Add indexes for better query performance
ALTER TABLE payment
ADD INDEX idx_transaction_ref (transaction_ref),
ADD INDEX idx_payment_status (payment_status),
ADD INDEX idx_payment_method (payment_method);

-- Update orders table to link with payment
ALTER TABLE orders
ADD COLUMN IF NOT EXISTS payment_id INT COMMENT 'Link to payment table',
ADD COLUMN IF NOT EXISTS payment_reference VARCHAR(100) COMMENT 'Quick reference to transaction',
ADD COLUMN IF NOT EXISTS payment_status VARCHAR(20) DEFAULT 'pending' COMMENT 'Order payment status';

-- Add foreign key if payment table exists
-- ALTER TABLE orders
-- ADD CONSTRAINT fk_orders_payment FOREIGN KEY (payment_id) REFERENCES payment(payment_id) ON DELETE SET NULL;

-- Create premium_subscriptions table for managing premium memberships
CREATE TABLE IF NOT EXISTS premium_subscriptions (
    subscription_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    plan_type VARCHAR(50) DEFAULT 'monthly' COMMENT 'Subscription plan type',
    amount DECIMAL(10, 2) NOT NULL COMMENT 'Subscription amount in GHS',
    start_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    end_date DATETIME NOT NULL COMMENT 'When subscription expires',
    status VARCHAR(20) DEFAULT 'active' COMMENT 'active, expired, cancelled',
    payment_reference VARCHAR(100) COMMENT 'Link to Paystack transaction',
    auto_renew BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES customer(customer_id) ON DELETE CASCADE,
    INDEX idx_user_status (user_id, status),
    INDEX idx_payment_ref (payment_reference)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Premium membership subscriptions';

-- Create payment_logs table for tracking all payment attempts
CREATE TABLE IF NOT EXISTS payment_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    transaction_ref VARCHAR(100),
    payment_type VARCHAR(50) COMMENT 'counseling, premium, date_idea',
    amount DECIMAL(10, 2),
    status VARCHAR(20) COMMENT 'init, success, failed, pending',
    gateway_response TEXT COMMENT 'Full response from Paystack',
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_user_id (user_id),
    INDEX idx_transaction_ref (transaction_ref),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Payment transaction logs';

-- Add premium status to customer table if not exists
ALTER TABLE customer
ADD COLUMN IF NOT EXISTS is_premium BOOLEAN DEFAULT FALSE COMMENT 'Premium membership status',
ADD COLUMN IF NOT EXISTS premium_expires_at DATETIME COMMENT 'When premium membership expires';

-- Sample data verification queries (run these after updates)
-- Check payment table structure
-- DESCRIBE payment;

-- Check orders table structure
-- DESCRIBE orders;

-- Check premium subscriptions table
-- DESCRIBE premium_subscriptions;

-- Check payment logs table
-- DESCRIBE payment_logs;
