<?php
session_start();
header('Content-Type: application/json');

require_once '../settings/paystack_config.php';
require_once '../settings/core.php';
require_once '../controllers/order_controller.php';

$response = ['status' => false, 'message' => 'Verification failed'];

try {
    // Get transaction reference
    $reference = $_GET['reference'] ?? $_POST['reference'] ?? '';

    if (empty($reference)) {
        throw new Exception('Transaction reference is required');
    }

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Session expired. Please login again');
    }

    // Verify the transaction hasn't been processed already
    // You would check your database here to prevent double processing

    log_payment_transaction('VERIFY_START', [
        'reference' => $reference,
        'user_id' => $_SESSION['user_id']
    ]);

    // Verify transaction with Paystack
    $verification = paystack_verify_transaction($reference);

    if (!$verification['status']) {
        throw new Exception($verification['message']);
    }

    $transaction_data = $verification['data'];

    // Validate transaction status
    if ($transaction_data['status'] !== 'success') {
        throw new Exception('Payment was not successful');
    }

    // Get pending transaction from session
    $pending = $_SESSION['pending_transaction'] ?? null;

    if (!$pending || $pending['reference'] !== $reference) {
        throw new Exception('Invalid transaction reference');
    }

    // Validate amount matches
    $paid_amount = pesewas_to_cedis($transaction_data['amount']);
    if (abs($paid_amount - $pending['amount']) > 0.01) {
        throw new Exception('Payment amount mismatch');
    }

    // Extract payment details
    $payment_type = $pending['payment_type'];
    $service_data = $pending['service_data'];
    $user_id = $_SESSION['user_id'];

    // Process based on payment type
    $order_id = null;
    $booking_ref = null;

    if ($payment_type === 'counseling') {
        // Log the data being passed to booking creation
        log_payment_transaction('BOOKING_ATTEMPT', [
            'user_id' => $user_id,
            'counselor_name' => $service_data['counselor_name'],
            'session_date' => $service_data['session_date'],
            'session_time' => $service_data['session_time'],
            'session_type' => $service_data['session_type'],
            'cost' => $paid_amount,
            'session_notes' => $service_data['session_notes'] ?? ''
        ]);

        // Create counseling booking
        $booking_result = create_counseling_booking_ctr(
            $user_id,
            $service_data['counselor_name'],
            $service_data['session_date'],
            $service_data['session_time'],
            $service_data['session_type'],
            $paid_amount,
            $service_data['session_notes'] ?? ''
        );

        if (!$booking_result) {
            log_payment_transaction('BOOKING_FAILED', [
                'user_id' => $user_id,
                'error' => 'Booking function returned false'
            ]);
            throw new Exception('Failed to create booking. Please contact support with reference: ' . $reference);
        }

        log_payment_transaction('BOOKING_SUCCESS', [
            'order_id' => $booking_result['order_id'],
            'booking_reference' => $booking_result['booking_reference']
        ]);

        $order_id = $booking_result['order_id'];
        $booking_ref = $booking_result['booking_reference'];

        // Record payment
        // You would call your payment recording function here
        // record_payment($order_id, $paid_amount, $reference, 'paystack', ...);

    } elseif ($payment_type === 'premium') {
        // Activate premium membership
        require_once '../controllers/customer_controller.php';

        $plan_type = $service_data['plan'] ?? 'monthly';

        log_payment_transaction('PREMIUM_ATTEMPT', [
            'user_id' => $user_id,
            'plan_type' => $plan_type,
            'amount' => $paid_amount,
            'reference' => $reference
        ]);

        $subscription_result = activate_premium_subscription_ctr(
            $user_id,
            $plan_type,
            $paid_amount,
            $reference
        );

        if (!$subscription_result) {
            log_payment_transaction('PREMIUM_FAILED', [
                'user_id' => $user_id,
                'error' => 'Failed to activate premium subscription'
            ]);
            throw new Exception('Failed to activate premium subscription. Please contact support with reference: ' . $reference);
        }

        log_payment_transaction('PREMIUM_SUCCESS', [
            'user_id' => $user_id,
            'subscription_id' => $subscription_result['subscription_id'],
            'expires_at' => $subscription_result['end_date']
        ]);

        // Update session to reflect premium status
        $_SESSION['is_premium'] = true;
        $_SESSION['premium_expires_at'] = $subscription_result['end_date'];

        $booking_ref = 'PREMIUM-' . $user_id . '-' . time();
        $order_id = $subscription_result['subscription_id'];

    } elseif ($payment_type === 'date_idea') {
        // Process date idea purchase
        $booking_ref = 'DATEIDEA-' . $user_id . '-' . time();
    }

    // Clear pending transaction from session
    unset($_SESSION['pending_transaction']);

    // Log successful verification
    log_payment_transaction('VERIFY_SUCCESS', [
        'reference' => $reference,
        'user_id' => $user_id,
        'amount' => $paid_amount,
        'payment_type' => $payment_type,
        'order_id' => $order_id,
        'booking_ref' => $booking_ref
    ]);

    $response = [
        'status' => true,
        'message' => 'Payment verified successfully',
        'data' => [
            'order_id' => $order_id,
            'booking_reference' => $booking_ref,
            'amount' => $paid_amount,
            'payment_type' => $payment_type,
            'transaction_ref' => $reference,
            'payment_date' => $transaction_data['paid_at'] ?? date('Y-m-d H:i:s')
        ]
    ];

} catch (Exception $e) {
    $response = [
        'status' => false,
        'message' => $e->getMessage()
    ];

    log_payment_transaction('VERIFY_ERROR', [
        'reference' => $reference ?? 'unknown',
        'error' => $e->getMessage()
    ]);
}

echo json_encode($response);
