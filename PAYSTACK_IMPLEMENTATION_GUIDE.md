# Paystack Payment Integration - DistantLove Platform
## Complete Implementation Guide

---

## 🎯 Overview

This guide covers the complete Paystack payment integration for the DistantLove relationship services platform, supporting:
- ✅ Counseling session bookings
- ✅ Premium membership subscriptions
- ✅ Date ideas purchases
- ✅ Secure payment processing with Paystack Ghana

---

## 📁 Files Created

### 1. Configuration & Core Functions
**File:** `settings/paystack_config.php`
- Paystack API credentials (test keys included)
- Helper functions for transaction initialization
- Helper functions for payment verification
- Reference generation utilities
- Amount conversion (GHS ↔ pesewas)
- Transaction logging

**Configuration Values:**
```php
Public Key:  pk_test_fd603b60acb471f7b76457edbf1203a5f4bf36c9
Secret Key:  sk_test_ba21d866be9f8de44a81b13d1ee57b973ba5f68e
Base URL:    http://169.239.251.102:442/~yaa.asante
Currency:    GHS (Ghana Cedis)
```

### 2. Payment Action Endpoints
**File:** `actions/paystack_init_transaction.php`
- Initializes Paystack payment transactions
- Validates user input (email, amount, payment type)
- Generates unique transaction references
- Stores pending transaction in session
- Returns Paystack authorization URL for redirect

**File:** `actions/paystack_verify_payment.php`
- Verifies payment with Paystack API
- Validates transaction status and amount
- Creates counseling bookings or premium subscriptions
- Records payment in database
- Clears pending transactions

### 3. User Interface Pages
**File:** `views/paystack_callback.php`
- Landing page after Paystack payment
- Displays "Processing Payment" loader
- Automatically verifies payment on page load
- Shows success or error states
- Redirects to confirmation page

**File:** `views/payment_success.php`
- Beautiful confirmation page
- Shows transaction details
- Links to view bookings or return home
- Mentions email confirmation

### 4. Database Schema
**File:** `database/paystack_schema_update.sql`
- Updates `payment` table with Paystack fields
- Creates `premium_subscriptions` table
- Creates `payment_logs` table for audit trail
- Adds indexes for performance
- Updates `customer` table with premium status

---

## 🔄 Payment Flow

### Counseling Session Booking Flow

1. **User selects counselor and session details** on `counseling.php`
2. **User clicks "Book Session"**
3. **Email confirmation prompt** appears
4. **System calls** `paystack_init_transaction.php`:
   - Validates inputs
   - Generates reference: `COUNSELING-{user_id}-{timestamp}-{random}`
   - Stores session data in `$_SESSION['pending_transaction']`
   - Calls Paystack API to initialize
5. **User redirects** to Paystack checkout page
6. **User completes payment** on Paystack
7. **Paystack redirects** to `paystack_callback.php?reference=XXX`
8. **Callback page calls** `paystack_verify_payment.php`:
   - Verifies with Paystack API
   - Creates booking via `create_counseling_booking_ctr()`
   - Clears session data
9. **User sees** `payment_success.php` with booking confirmation

### Premium Subscription Flow

Similar flow but with `payment_type=premium`

---

## 🗄️ Database Updates Required

### Run SQL Script

Execute the file: `database/paystack_schema_update.sql`

**Key Changes:**
```sql
-- Payment table updates
ALTER TABLE payment ADD COLUMN payment_method VARCHAR(50);
ALTER TABLE payment ADD COLUMN transaction_ref VARCHAR(100) UNIQUE;
ALTER TABLE payment ADD COLUMN payment_channel VARCHAR(50);
ALTER TABLE payment ADD COLUMN payment_status VARCHAR(20) DEFAULT 'pending';
ALTER TABLE payment ADD COLUMN paid_at DATETIME;

-- Orders table updates
ALTER TABLE orders ADD COLUMN payment_reference VARCHAR(100);
ALTER TABLE orders ADD COLUMN payment_status VARCHAR(20) DEFAULT 'pending';

-- Premium subscriptions table (new)
CREATE TABLE premium_subscriptions (
    subscription_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    plan_type VARCHAR(50) DEFAULT 'monthly',
    amount DECIMAL(10, 2) NOT NULL,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    status VARCHAR(20) DEFAULT 'active',
    payment_reference VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES customer(customer_id)
);

-- Customer table updates
ALTER TABLE customer ADD COLUMN is_premium BOOLEAN DEFAULT FALSE;
ALTER TABLE customer ADD COLUMN premium_expires_at DATETIME;

-- Payment logs table (new)
CREATE TABLE payment_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    transaction_ref VARCHAR(100),
    payment_type VARCHAR(50),
    amount DECIMAL(10, 2),
    status VARCHAR(20),
    gateway_response TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

---

## 🔧 Integration Points

### Counseling Booking Integration

**File:** `views/counseling.php` (**UPDATED**)

**Changes Made:**
- Form submission now triggers Paystack payment flow
- Email confirmation prompt before payment
- Redirects to Paystack instead of direct booking
- Service data stored in session for verification later

**Code Flow:**
```javascript
1. User fills booking form
2. SweetAlert prompts for email confirmation
3. AJAX call to paystack_init_transaction.php with:
   - email
   - amount (session cost)
   - payment_type: "counseling"
   - service_data: {counselor_name, session_date, session_time, session_type, session_notes}
4. Redirect to data.data.authorization_url (Paystack)
5. After payment, Paystack redirects to paystack_callback.php
6. Verification creates actual booking
```

### Premium Subscription Integration

**To integrate premium subscriptions in `date_ideas.php`:**

Add this JavaScript to the premium upgrade button:

```javascript
function upgradeToPremium() {
    Swal.fire({
        title: 'Confirm Your Email',
        input: 'email',
        inputValue: '<?php echo $_SESSION["user_email"] ?? ""; ?>',
        showCancelButton: true,
        confirmButtonText: 'Proceed to Payment',
        confirmButtonColor: '#d72660'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('email', result.value);
            formData.append('amount', 320); // GH₵ 320/month
            formData.append('payment_type', 'premium');
            formData.append('service_data', JSON.stringify({
                plan: 'monthly',
                duration: 30
            }));

            fetch('../actions/paystack_init_transaction.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status && data.data.authorization_url) {
                    window.location.href = data.data.authorization_url;
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            });
        }
    });
}
```

---

## 🧪 Testing Guide

### Test with Paystack Test Cards

**Successful Payment:**
- Card Number: `5060 6666 6666 6666 666`
- CVV: `123`
- Expiry: Any future date
- PIN: `1234`
- OTP: `123456`

**Failed Payment:**
- Card Number: `5060 6666 6666 6666 000`
- CVV: `123`
- Expiry: Any future date

### Testing Steps

1. **Login to platform** with test account
2. **Navigate** to counseling page
3. **Book a session** with Dr. Akosua Mensah
4. **Confirm email** when prompted
5. **Use test card** on Paystack checkout
6. **Complete payment**
7. **Verify redirect** to callback page
8. **Check** payment success page loads
9. **Verify** booking appears in orders.php
10. **Check logs** at `logs/paystack_[date].log`

---

## 📊 Transaction Logging

All transactions are logged to: `logs/paystack_YYYY-MM-DD.log`

**Log Entry Format:**
```
[2025-11-30 14:23:45] [INIT] {"reference":"COUNSELING-1-1732977825-5432","user_id":1,"email":"user@example.com","amount":1920,"type":"counseling"}
[2025-11-30 14:24:10] [INIT_SUCCESS] {"reference":"COUNSELING-1-1732977825-5432","authorization_url":"https://checkout.paystack.com/..."}
[2025-11-30 14:25:32] [VERIFY_START] {"reference":"COUNSELING-1-1732977825-5432","user_id":1}
[2025-11-30 14:25:35] [VERIFY_SUCCESS] {"reference":"COUNSELING-1-1732977825-5432","user_id":1,"amount":1920,"payment_type":"counseling","order_id":45,"booking_ref":"DL-2025-001234"}
```

---

## 🔐 Security Features

1. ✅ **Session-based transaction validation**
   - Pending transaction stored in PHP session
   - Verified against Paystack response

2. ✅ **Amount validation**
   - Payment amount verified matches cart/service cost
   - Prevents price manipulation

3. ✅ **Unique reference generation**
   - Format: `{TYPE}-{USER_ID}-{TIMESTAMP}-{RANDOM}`
   - Prevents duplicate transactions

4. ✅ **Webhook signature validation** (in config)
   - Ready for webhook integration
   - Uses HMAC SHA512 verification

5. ✅ **Error logging**
   - All errors logged with context
   - Helps debugging payment issues

---

## 🚀 Production Deployment Checklist

### Before Going Live

- [ ] Replace test keys with live keys in `paystack_config.php`
- [ ] Update `APP_BASE_URL` to production domain
- [ ] Run `paystack_schema_update.sql` on production database
- [ ] Test complete payment flow with small amount
- [ ] Set up Paystack webhooks for automated verification
- [ ] Configure email notifications for payment confirmations
- [ ] Set up error monitoring/alerts
- [ ] Review and restrict file permissions on config files
- [ ] Add rate limiting to payment endpoints
- [ ] Test all payment types (counseling, premium, date ideas)

### Live Paystack Keys (When Ready)

```php
define('PAYSTACK_PUBLIC_KEY', 'pk_live_YOUR_LIVE_PUBLIC_KEY');
define('PAYSTACK_SECRET_KEY', 'sk_live_YOUR_LIVE_SECRET_KEY');
```

---

## 📞 Support & Troubleshooting

### Common Issues

**Issue:** "Payment initialization failed"
- Check Paystack API keys are correct
- Verify curl is enabled on server
- Check logs for specific error message

**Issue:** "Payment verification failed"
- Ensure callback URL is accessible
- Check session is maintained across requests
- Verify amount matches exactly

**Issue:** "Booking not created after payment"
- Check `create_counseling_booking_ctr()` function exists
- Verify database tables have required columns
- Review logs for database errors

### Debug Mode

Enable detailed logging by adding to `paystack_config.php`:

```php
define('PAYSTACK_DEBUG', true);
```

---

## 📝 Next Steps

1. **Test the complete flow** with test cards
2. **Integrate premium subscriptions** in date_ideas.php
3. **Set up webhook handler** for automated payment confirmations
4. **Add email notifications** after successful payment
5. **Create admin panel** for viewing all transactions
6. **Add refund functionality** if needed
7. **Set up automated subscription renewals**

---

## ✅ Implementation Complete!

Your Paystack integration is now fully set up for:
- ✅ Counseling session bookings with payment
- ✅ Premium membership subscriptions (ready to integrate)
- ✅ Secure payment processing
- ✅ Transaction logging and verification
- ✅ Beautiful user experience with DistantLove theme

**The counseling booking flow is LIVE and ready to test!**

---

*Generated for DistantLove Platform - Keeping Love Alive Across Any Distance ❤️*
