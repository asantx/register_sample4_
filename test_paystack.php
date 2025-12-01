<?php
/**
 * Paystack Integration Test Script
 * This script tests if the Paystack integration is working correctly
 */

session_start();
require_once 'settings/paystack_config.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Paystack Integration Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .test-container { background: white; padding: 30px; border-radius: 10px; max-width: 800px; margin: 0 auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #d72660; }
        .test-item { padding: 15px; margin: 10px 0; border-left: 4px solid #ddd; background: #f9f9f9; }
        .pass { border-left-color: #28a745; }
        .fail { border-left-color: #dc3545; }
        .info { border-left-color: #17a2b8; }
        .test-label { font-weight: bold; margin-bottom: 5px; }
        .test-value { font-family: monospace; background: #fff; padding: 5px; margin-top: 5px; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class='test-container'>
        <h1>🔍 Paystack Integration Test</h1>
        <p>Testing the Paystack payment integration for DistantLove platform...</p>
        <hr>";

// Test 1: Check PHP Extensions
echo "<div class='test-item " . (extension_loaded('curl') ? 'pass' : 'fail') . "'>
        <div class='test-label'>✓ Test 1: cURL Extension</div>
        <div>Status: " . (extension_loaded('curl') ? '<strong style=\"color: #28a745;\">Enabled ✓</strong>' : '<strong style=\"color: #dc3545;\">Not Enabled ✗</strong>') . "</div>
      </div>";

echo "<div class='test-item " . (extension_loaded('json') ? 'pass' : 'fail') . "'>
        <div class='test-label'>✓ Test 2: JSON Extension</div>
        <div>Status: " . (extension_loaded('json') ? '<strong style=\"color: #28a745;\">Enabled ✓</strong>' : '<strong style=\"color: #dc3545;\">Not Enabled ✗</strong>') . "</div>
      </div>";

// Test 2: Check Configuration
echo "<div class='test-item info'>
        <div class='test-label'>✓ Test 3: Paystack Configuration</div>
        <div class='test-value'>Public Key: " . substr(PAYSTACK_PUBLIC_KEY, 0, 20) . "...</div>
        <div class='test-value'>Secret Key: " . substr(PAYSTACK_SECRET_KEY, 0, 20) . "...</div>
        <div class='test-value'>Currency: " . PAYSTACK_CURRENCY . "</div>
        <div class='test-value'>Callback URL: " . PAYSTACK_CALLBACK_URL . "</div>
      </div>";

// Test 3: Session Check
echo "<div class='test-item " . (isset($_SESSION['user_id']) ? 'pass' : 'fail') . "'>
        <div class='test-label'>✓ Test 4: User Session</div>
        <div>User ID: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '<strong style=\"color: #dc3545;\">Not logged in</strong>') . "</div>
        <div>User Email: " . (isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Not set') . "</div>
      </div>";

// Test 4: Test Paystack API Connection
echo "<div class='test-item info'>
        <div class='test-label'>✓ Test 5: Paystack API Connection</div>";

$test_email = "test@distantlove.com";
$test_amount = 100; // GH₵ 100
$test_reference = "TEST-" . time();

try {
    $result = paystack_initialize_transaction($test_email, $test_amount, $test_reference, [
        'test_mode' => true,
        'description' => 'Integration test transaction'
    ]);

    if ($result['status']) {
        echo "<div style='color: #28a745;'><strong>✓ Success!</strong> Paystack API is reachable</div>";
        echo "<div class='test-value'>Authorization URL: " . $result['data']['authorization_url'] . "</div>";
        echo "<div class='test-value'>Access Code: " . $result['data']['access_code'] . "</div>";
        echo "<div class='test-value'>Reference: " . $result['data']['reference'] . "</div>";
    } else {
        echo "<div style='color: #dc3545;'><strong>✗ Failed!</strong> " . $result['message'] . "</div>";
    }
} catch (Exception $e) {
    echo "<div style='color: #dc3545;'><strong>✗ Error:</strong> " . $e->getMessage() . "</div>";
}

echo "</div>";

// Test 5: Test Helper Functions
echo "<div class='test-item info'>
        <div class='test-label'>✓ Test 6: Helper Functions</div>";

$test_cedis = 192;
$test_pesewas = cedis_to_pesewas($test_cedis);
$back_to_cedis = pesewas_to_cedis($test_pesewas);

echo "<div class='test-value'>GH₵ {$test_cedis} = {$test_pesewas} pesewas</div>";
echo "<div class='test-value'>{$test_pesewas} pesewas = GH₵ {$back_to_cedis}</div>";

$test_ref = generate_paystack_reference(1, 'counseling');
echo "<div class='test-value'>Generated Reference: {$test_ref}</div>";

echo "</div>";

// Test 6: Check Log Directory
$log_dir = __DIR__ . '/logs';
$log_writable = is_dir($log_dir) ? is_writable($log_dir) : (mkdir($log_dir, 0755, true) && is_writable($log_dir));

echo "<div class='test-item " . ($log_writable ? 'pass' : 'fail') . "'>
        <div class='test-label'>✓ Test 7: Log Directory</div>
        <div>Directory: {$log_dir}</div>
        <div>Status: " . ($log_writable ? '<strong style=\"color: #28a745;\">Writable ✓</strong>' : '<strong style=\"color: #dc3545;\">Not Writable ✗</strong>') . "</div>
      </div>";

// Test 7: Check Action Files
$action_files = [
    'paystack_init_transaction.php',
    'paystack_verify_payment.php'
];

foreach ($action_files as $file) {
    $file_path = __DIR__ . '/actions/' . $file;
    $exists = file_exists($file_path);
    echo "<div class='test-item " . ($exists ? 'pass' : 'fail') . "'>
            <div class='test-label'>✓ Test 8: Action File - {$file}</div>
            <div>Path: {$file_path}</div>
            <div>Status: " . ($exists ? '<strong style=\"color: #28a745;\">Exists ✓</strong>' : '<strong style=\"color: #dc3545;\">Missing ✗</strong>') . "</div>
          </div>";
}

echo "
        <hr>
        <h3>📋 Summary</h3>
        <p>If all tests pass (shown in green), your Paystack integration should work correctly.</p>
        <p>If any tests fail (shown in red), please address those issues before proceeding.</p>

        <h3>🔧 Common Issues & Solutions:</h3>
        <ul>
            <li><strong>cURL not enabled:</strong> Contact your hosting provider to enable the cURL PHP extension</li>
            <li><strong>Not logged in:</strong> Log in to the platform before testing payments</li>
            <li><strong>API connection failed:</strong> Check your internet connection and verify API keys are correct</li>
            <li><strong>Log directory not writable:</strong> Set permissions on the logs folder to 755 or 777</li>
        </ul>

        <div style='margin-top: 30px; padding: 20px; background: #fff3cd; border-radius: 5px; border-left: 4px solid #ffc107;'>
            <strong>⚠️ Important:</strong> This is a test script. Delete it after testing is complete for security.
        </div>

        <div style='margin-top: 20px; text-align: center;'>
            <a href='views/counseling.php' style='display: inline-block; background: #d72660; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;'>
                Go to Counseling Page
            </a>
        </div>
    </div>
</body>
</html>";
