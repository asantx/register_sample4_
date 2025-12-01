<?php
/**
 * Paystack Payment Gateway Configuration
 * DistantLove - Relationship Services Platform
 */

// Paystack API Configuration
define('PAYSTACK_PUBLIC_KEY', 'pk_test_fd603b60acb471f7b76457edbf1203a5f4bf36c9');
define('PAYSTACK_SECRET_KEY', 'sk_test_ba21d866be9f8de44a81b13d1ee57b973ba5f68e');
define('PAYSTACK_BASE_URL', 'https://api.paystack.co');
define('PAYSTACK_CURRENCY', 'GHS'); // Ghana Cedis

// Application Configuration
define('APP_BASE_URL', 'http://169.239.251.102:442/~yaa.asante');
define('PAYSTACK_CALLBACK_URL', APP_BASE_URL . '/views/paystack_callback.php');

/**
 * Initialize a Paystack transaction
 *
 * @param string $email Customer email
 * @param float $amount Amount in GHS
 * @param string $reference Unique transaction reference
 * @param array $metadata Additional transaction data
 * @return array Response from Paystack API
 */
function paystack_initialize_transaction($email, $amount, $reference, $metadata = []) {
    // Convert amount to pesewas (Paystack requires amount in kobo/pesewas)
    $amount_in_pesewas = $amount * 100;

    $url = PAYSTACK_BASE_URL . '/transaction/initialize';

    $fields = [
        'email' => $email,
        'amount' => $amount_in_pesewas,
        'currency' => PAYSTACK_CURRENCY,
        'reference' => $reference,
        'callback_url' => PAYSTACK_CALLBACK_URL,
        'metadata' => $metadata
    ];

    $fields_string = json_encode($fields);

    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . PAYSTACK_SECRET_KEY,
        'Content-Type: application/json',
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute request
    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        error_log("Paystack initialization error: " . $error);
        return [
            'status' => false,
            'message' => 'Payment gateway connection failed'
        ];
    }

    curl_close($ch);

    $response = json_decode($result, true);

    if ($http_code !== 200 || !$response['status']) {
        error_log("Paystack initialization failed: " . json_encode($response));
        return [
            'status' => false,
            'message' => isset($response['message']) ? $response['message'] : 'Payment initialization failed'
        ];
    }

    return [
        'status' => true,
        'data' => $response['data']
    ];
}

/**
 * Verify a Paystack transaction
 *
 * @param string $reference Transaction reference
 * @return array Response from Paystack API
 */
function paystack_verify_transaction($reference) {
    $url = PAYSTACK_BASE_URL . '/transaction/verify/' . rawurlencode($reference);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . PAYSTACK_SECRET_KEY,
    ]);

    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        error_log("Paystack verification error: " . $error);
        return [
            'status' => false,
            'message' => 'Payment verification failed'
        ];
    }

    curl_close($ch);

    $response = json_decode($result, true);

    if ($http_code !== 200 || !$response['status']) {
        error_log("Paystack verification failed: " . json_encode($response));
        return [
            'status' => false,
            'message' => isset($response['message']) ? $response['message'] : 'Payment verification failed'
        ];
    }

    return [
        'status' => true,
        'data' => $response['data']
    ];
}

/**
 * Generate a unique transaction reference
 *
 * @param int $customer_id Customer ID
 * @param string $type Transaction type (counseling, premium, etc.)
 * @return string Unique reference
 */
function generate_paystack_reference($customer_id, $type = 'order') {
    $timestamp = time();
    $random = rand(1000, 9999);
    return strtoupper($type) . '-' . $customer_id . '-' . $timestamp . '-' . $random;
}

/**
 * Convert amount from pesewas to cedis
 *
 * @param int $pesewas Amount in pesewas
 * @return float Amount in cedis
 */
function pesewas_to_cedis($pesewas) {
    return $pesewas / 100;
}

/**
 * Convert amount from cedis to pesewas
 *
 * @param float $cedis Amount in cedis
 * @return int Amount in pesewas
 */
function cedis_to_pesewas($cedis) {
    return (int)($cedis * 100);
}

/**
 * Log payment transaction for debugging
 *
 * @param string $type Log type
 * @param array $data Data to log
 */
function log_payment_transaction($type, $data) {
    $log_file = __DIR__ . '/../logs/paystack_' . date('Y-m-d') . '.log';
    $log_entry = date('[Y-m-d H:i:s]') . " [{$type}] " . json_encode($data) . PHP_EOL;

    // Create logs directory if it doesn't exist
    $log_dir = dirname($log_file);
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }

    file_put_contents($log_file, $log_entry, FILE_APPEND);
}

/**
 * Validate Paystack webhook signature
 *
 * @param string $input Raw POST data
 * @return bool Whether signature is valid
 */
function validate_paystack_webhook($input) {
    if (!isset($_SERVER['HTTP_X_PAYSTACK_SIGNATURE'])) {
        return false;
    }

    $signature = $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'];
    $computed_signature = hash_hmac('sha512', $input, PAYSTACK_SECRET_KEY);

    return hash_equals($signature, $computed_signature);
}
