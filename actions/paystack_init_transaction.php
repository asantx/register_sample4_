<?php
session_start();
header('Content-Type: application/json');

require_once '../settings/paystack_config.php';
require_once '../settings/core.php';

$response = ['status' => false, 'message' => 'Unknown error'];

try {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Please login to continue');
    }

    // Get POST data
    $email = $_POST['email'] ?? '';
    $amount = floatval($_POST['amount'] ?? 0);
    $payment_type = $_POST['payment_type'] ?? ''; // counseling, premium, date_idea
    $service_id = $_POST['service_id'] ?? null;
    $service_data = json_decode($_POST['service_data'] ?? '{}', true);

    // Validate inputs
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }

    if ($amount <= 0) {
        throw new Exception('Invalid payment amount');
    }

    if (empty($payment_type)) {
        throw new Exception('Payment type is required');
    }

    $user_id = $_SESSION['user_id'];

    // Generate unique reference
    $reference = generate_paystack_reference($user_id, $payment_type);

    // Prepare metadata
    $metadata = [
        'user_id' => $user_id,
        'payment_type' => $payment_type,
        'service_id' => $service_id,
        'service_data' => $service_data,
        'custom_fields' => [
            [
                'display_name' => 'Customer ID',
                'variable_name' => 'user_id',
                'value' => $user_id
            ],
            [
                'display_name' => 'Service Type',
                'variable_name' => 'payment_type',
                'value' => ucfirst($payment_type)
            ]
        ]
    ];

    // Add specific metadata based on payment type
    if ($payment_type === 'counseling' && !empty($service_data)) {
        $metadata['custom_fields'][] = [
            'display_name' => 'Counselor',
            'variable_name' => 'counselor',
            'value' => $service_data['counselor_name'] ?? ''
        ];
        $metadata['custom_fields'][] = [
            'display_name' => 'Session Date',
            'variable_name' => 'session_date',
            'value' => $service_data['session_date'] ?? ''
        ];
    } elseif ($payment_type === 'premium') {
        $metadata['custom_fields'][] = [
            'display_name' => 'Membership Plan',
            'variable_name' => 'plan',
            'value' => 'Premium Monthly'
        ];
    }

    // Store transaction data in session for verification later
    $_SESSION['pending_transaction'] = [
        'reference' => $reference,
        'amount' => $amount,
        'email' => $email,
        'payment_type' => $payment_type,
        'service_id' => $service_id,
        'service_data' => $service_data,
        'created_at' => time()
    ];

    // Log the initialization attempt
    log_payment_transaction('INIT', [
        'reference' => $reference,
        'user_id' => $user_id,
        'email' => $email,
        'amount' => $amount,
        'type' => $payment_type
    ]);

    // Initialize Paystack transaction
    $result = paystack_initialize_transaction($email, $amount, $reference, $metadata);

    if (!$result['status']) {
        throw new Exception($result['message']);
    }

    // Return success with authorization URL
    $response = [
        'status' => true,
        'message' => 'Payment initialized successfully',
        'data' => [
            'authorization_url' => $result['data']['authorization_url'],
            'access_code' => $result['data']['access_code'],
            'reference' => $result['data']['reference']
        ]
    ];

    log_payment_transaction('INIT_SUCCESS', [
        'reference' => $reference,
        'authorization_url' => $result['data']['authorization_url']
    ]);

} catch (Exception $e) {
    $response = [
        'status' => false,
        'message' => $e->getMessage()
    ];

    log_payment_transaction('INIT_ERROR', [
        'error' => $e->getMessage(),
        'user_id' => $_SESSION['user_id'] ?? null
    ]);
}

echo json_encode($response);
