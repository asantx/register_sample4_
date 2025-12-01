# Counseling Booking Debugging Guide

## Problem Summary
The Paystack payment is working correctly, but the booking is not being created after payment verification.

---

## Debugging Tools Created

I've created 4 diagnostic tools to help identify and fix the issue:

### 1. **Database Schema Checker** 📊
**File:** `check_database.php`
**URL:** `http://169.239.251.102:442/~yaa.asante/check_database.php`

**What it does:**
- Checks if all required database columns exist in the `orders` table
- Shows which columns are missing
- Provides SQL to add missing columns
- Verifies customer table structure

**Required columns for counseling bookings:**
- `order_type` (VARCHAR) - Type of order: product, counseling, etc.
- `session_date` (DATE) - Date of counseling session
- `session_time` (VARCHAR/TIME) - Time of counseling session
- `session_type` (VARCHAR) - Type: video, phone, chat
- `counselor_name` (VARCHAR) - Name of the counselor
- `session_notes` (TEXT) - Client notes about the session

### 2. **Paystack Integration Tester** 🔧
**File:** `test_paystack.php`
**URL:** `http://169.239.251.102:442/~yaa.asante/test_paystack.php`

**What it tests:**
- PHP cURL extension (required for Paystack API)
- PHP JSON extension
- Paystack configuration (API keys, callback URL)
- User session status
- Paystack API connectivity
- Helper functions
- Log directory permissions
- Required action files

### 3. **Log Viewer** 📋
**File:** `view_logs.php`
**URL:** `http://169.239.251.102:442/~yaa.asante/view_logs.php`

**What it shows:**
- Real-time Paystack transaction logs
- Booking attempt logs
- PHP error logs (filtered for booking-related errors)
- Color-coded by log type (SUCCESS, ERROR, INFO)
- Shows last 100 log entries (newest first)

### 4. **Enhanced Error Messages** 💬
**File:** `counseling.php` (updated)

**What's new:**
- Detailed error messages in the booking modal
- HTTP status codes
- Specific failure reasons
- Link to diagnostic test page
- Browser console logging

---

## Step-by-Step Debugging Process

Follow these steps in order:

### Step 1: Check Database Schema ✅
1. Open: `http://169.239.251.102:442/~yaa.asante/check_database.php`
2. Check if all required columns exist
3. If any columns are missing:
   - Copy the provided SQL query
   - Open phpMyAdmin
   - Select your database
   - Click "SQL" tab
   - Paste and execute the query

### Step 2: Test Paystack Integration ✅
1. Make sure you're logged in
2. Open: `http://169.239.251.102:442/~yaa.asante/test_paystack.php`
3. Check that all tests pass (shown in green)
4. If any test fails:
   - **cURL not enabled**: Contact hosting provider
   - **Not logged in**: Log in to the platform
   - **API connection failed**: Check internet connection and API keys

### Step 3: Attempt a Booking 🎯
1. Go to: `http://169.239.251.102:442/~yaa.asante/views/counseling.php`
2. Select a counselor
3. Fill in the booking form
4. Complete the payment process
5. Watch for error messages

### Step 4: Check Logs 📊
1. Open: `http://169.239.251.102:442/~yaa.asante/view_logs.php`
2. Look for these log entries (in order):
   - `[INIT]` - Payment initialization
   - `[INIT_SUCCESS]` - Paystack accepted payment
   - `[VERIFY_START]` - Verification started
   - `[BOOKING_ATTEMPT]` - Trying to create booking
   - `[BOOKING_SUCCESS]` or `[BOOKING_FAILED]` - Result

3. If you see `[BOOKING_FAILED]`:
   - Look at the error message
   - Common errors:
     - "User not found" → Session issue
     - "Failed to prepare insert query" → Database column missing
     - "Execute failed" → SQL syntax error or constraint violation

### Step 5: Check PHP Error Log 🐛
1. In the log viewer, scroll to "PHP Error Log" section
2. Look for errors related to:
   - "Counseling booking error"
   - SQL errors
   - Database connection issues

---

## Common Issues & Solutions

### Issue 1: Missing Database Columns
**Symptom:** Booking fails with "Execute failed" error

**Solution:**
1. Run `check_database.php`
2. Execute the provided SQL to add missing columns
3. Try booking again

**SQL to add all columns:**
```sql
ALTER TABLE orders
ADD COLUMN order_type VARCHAR(50) DEFAULT 'product' COMMENT 'Type of order: product, counseling, etc.',
ADD COLUMN session_date DATE COMMENT 'Date of counseling session',
ADD COLUMN session_time VARCHAR(20) COMMENT 'Time of counseling session',
ADD COLUMN session_type VARCHAR(20) COMMENT 'Type of session: video, phone, chat',
ADD COLUMN counselor_name VARCHAR(100) COMMENT 'Name of the counselor',
ADD COLUMN session_notes TEXT COMMENT 'Client notes about the session';
```

### Issue 2: User Not Found
**Symptom:** Log shows "User not found with ID: X"

**Solution:**
1. Log out and log in again
2. Check session is maintained after payment redirect
3. Verify `customer_id` exists in database

### Issue 3: Payment Verifies but Booking Not Created
**Symptom:** Payment successful but no booking in orders.php

**Solution:**
1. Check logs for `[BOOKING_ATTEMPT]` entry
2. If missing: Verification is not reaching booking creation code
3. If present but followed by `[BOOKING_FAILED]`: Check the error message
4. Look for SQL errors in PHP error log

### Issue 4: Session Lost After Payment
**Symptom:** "Please login to continue" error during verification

**Solution:**
1. Check if sessions are working across page redirects
2. Verify `session.save_path` is writable
3. Check if cookies are blocked
4. Try clearing browser cache and cookies

---

## Enhanced Logging

The following files now have detailed logging:

### `actions/paystack_verify_payment.php`
Logs:
- `[BOOKING_ATTEMPT]` - Data being passed to booking creation
- `[BOOKING_SUCCESS]` - Booking created successfully with order ID
- `[BOOKING_FAILED]` - Booking creation failed with reason

### `classes/order_class.php`
Logs:
- User lookup results
- SQL preparation status
- Execution results with MySQL errors
- Success with order ID and booking reference

---

## Files Modified

1. ✅ `settings/core.php` - Fixed admin role check (1 = admin, 2 = user)
2. ✅ `views/counseling.php` - Enhanced error messages and debugging
3. ✅ `actions/paystack_verify_payment.php` - Added detailed logging
4. ✅ `classes/order_class.php` - Enhanced error logging
5. ✅ `check_database.php` - Created (schema checker)
6. ✅ `test_paystack.php` - Created (integration tester)
7. ✅ `view_logs.php` - Created (log viewer)

---

## What to Report

If the issue persists after following all steps, please provide:

1. **Screenshot of check_database.php** - Shows if columns exist
2. **Screenshot of test_paystack.php** - Shows which tests passed/failed
3. **Log entries from view_logs.php** - Especially around the failed booking
4. **Error message shown** - The exact error message you see
5. **Browser console** - Press F12, go to Console tab, screenshot any errors

---

## Security Notes

⚠️ **IMPORTANT:** Delete these files before going to production:
- `check_database.php`
- `test_paystack.php`
- `view_logs.php`
- `DEBUGGING_GUIDE.md`

These files expose sensitive information and should only be used for debugging.

---

## Next Steps

1. Run `check_database.php` first
2. Fix any missing columns
3. Try booking again
4. Check `view_logs.php` for errors
5. Report findings if issue persists

---

**Generated:** 2025-12-01
**Platform:** DistantLove - Relationship Services Platform
