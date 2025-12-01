<?php
/**
 * Payment Debug Script
 * Comprehensive check for payment issues
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

echo "<!DOCTYPE html>
<html>
<head>
    <title>Payment Debug</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 10px; max-width: 1000px; margin: 0 auto; }
        h1 { color: #d72660; }
        .test { padding: 15px; margin: 10px 0; border-left: 4px solid #ddd; background: #f9f9f9; }
        .pass { border-left-color: #28a745; background: #d4edda; }
        .fail { border-left-color: #dc3545; background: #f8d7da; }
        .info { border-left-color: #17a2b8; background: #d1ecf1; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 5px; overflow-x: auto; font-size: 12px; }
        .button { display: inline-block; background: #d72660; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔍 Payment System Debug</h1>";

// Test 1: Check if paystack_config.php exists and loads
echo "<div class='test ";
try {
    if (file_exists('settings/paystack_config.php')) {
        require_once 'settings/paystack_config.php';
        echo "pass'><strong>✓ Test 1: Paystack Config File</strong><br>Status: Loaded successfully</div>";
    } else {
        echo "fail'><strong>✗ Test 1: Paystack Config File</strong><br>Status: FILE NOT FOUND at settings/paystack_config.php</div>";
    }
} catch (Exception $e) {
    echo "fail'><strong>✗ Test 1: Paystack Config File</strong><br>Error: " . $e->getMessage() . "</div>";
}

// Test 2: Check constants
echo "<div class='test info'><strong>✓ Test 2: Configuration Values</strong><br>";
if (defined('PAYSTACK_PUBLIC_KEY')) {
    echo "Public Key: " . substr(PAYSTACK_PUBLIC_KEY, 0, 20) . "...<br>";
} else {
    echo "<span style='color: red;'>PAYSTACK_PUBLIC_KEY not defined!</span><br>";
}
if (defined('PAYSTACK_SECRET_KEY')) {
    echo "Secret Key: " . substr(PAYSTACK_SECRET_KEY, 0, 20) . "...<br>";
} else {
    echo "<span style='color: red;'>PAYSTACK_SECRET_KEY not defined!</span><br>";
}
if (defined('PAYSTACK_CALLBACK_URL')) {
    echo "Callback URL: " . PAYSTACK_CALLBACK_URL . "<br>";
} else {
    echo "<span style='color: red;'>PAYSTACK_CALLBACK_URL not defined!</span><br>";
}
echo "</div>";

// Test 3: Check session
echo "<div class='test ";
if (isset($_SESSION['user_id'])) {
    echo "pass'><strong>✓ Test 3: User Session</strong><br>";
    echo "User ID: " . $_SESSION['user_id'] . "<br>";
    echo "User Email: " . ($_SESSION['user_email'] ?? 'Not set') . "<br>";
    echo "User Role: " . ($_SESSION['user_role'] ?? 'Not set') . "<br>";
} else {
    echo "fail'><strong>✗ Test 3: User Session</strong><br>";
    echo "Status: <strong>NOT LOGGED IN</strong><br>";
    echo "You must be logged in to make payments!<br>";
    echo "<a href='login/login.php' class='button'>Login Now</a>";
}
echo "</div>";

// Test 4: Check cURL
echo "<div class='test ";
if (extension_loaded('curl')) {
    echo "pass'><strong>✓ Test 4: cURL Extension</strong><br>Status: Enabled</div>";
} else {
    echo "fail'><strong>✗ Test 4: cURL Extension</strong><br>Status: NOT ENABLED (required for Paystack API)</div>";
}

// Test 5: Test Paystack API connection
echo "<div class='test info'><strong>✓ Test 5: Paystack API Connection Test</strong><br>";
if (extension_loaded('curl') && defined('PAYSTACK_SECRET_KEY')) {
    $test_reference = 'TEST-DEBUG-' . time();
    $test_email = 'test@example.com';
    $test_amount = 100; // GH₵ 1.00

    try {
        $result = paystack_initialize_transaction($test_email, $test_amount, $test_reference, ['test' => true]);

        if ($result['status']) {
            echo "<span style='color: green;'><strong>✓ SUCCESS!</strong> Paystack API is reachable</span><br>";
            echo "Authorization URL: <a href='" . $result['data']['authorization_url'] . "' target='_blank'>Click to test</a><br>";
        } else {
            echo "<span style='color: red;'><strong>✗ FAILED!</strong></span><br>";
            echo "Error: " . $result['message'] . "<br>";
        }
    } catch (Exception $e) {
        echo "<span style='color: red;'><strong>✗ ERROR!</strong></span><br>";
        echo "Exception: " . $e->getMessage() . "<br>";
    }
} else {
    echo "<span style='color: orange;'>SKIPPED (cURL or config not available)</span>";
}
echo "</div>";

// Test 6: Check action files exist
$action_files = [
    'actions/paystack_init_transaction.php',
    'actions/paystack_verify_payment.php',
    'views/paystack_callback.php',
    'views/payment_success.php'
];

echo "<div class='test info'><strong>✓ Test 6: Required Files</strong><br>";
$all_exist = true;
foreach ($action_files as $file) {
    $exists = file_exists($file);
    $color = $exists ? 'green' : 'red';
    $icon = $exists ? '✓' : '✗';
    echo "<span style='color: $color;'>$icon $file</span><br>";
    if (!$exists) $all_exist = false;
}
echo "</div>";

// Test 7: Check database tables
echo "<div class='test info'><strong>✓ Test 7: Database Tables</strong><br>";
try {
    require_once 'settings/db_class.php';
    $db = new db_connection();
    $db->db_conn();

    // Check orders table
    $result = $db->db->query("SHOW TABLES LIKE 'orders'");
    if ($result->num_rows > 0) {
        echo "<span style='color: green;'>✓ orders table exists</span><br>";

        // Check counseling columns
        $cols = $db->db->query("SHOW COLUMNS FROM orders LIKE 'order_type'");
        if ($cols->num_rows > 0) {
            echo "<span style='color: green;'>✓ order_type column exists</span><br>";
        } else {
            echo "<span style='color: red;'>✗ order_type column MISSING (run check_database.php)</span><br>";
        }
    } else {
        echo "<span style='color: red;'>✗ orders table NOT FOUND</span><br>";
    }

    // Check premium_subscriptions table
    $result = $db->db->query("SHOW TABLES LIKE 'premium_subscriptions'");
    if ($result->num_rows > 0) {
        echo "<span style='color: green;'>✓ premium_subscriptions table exists</span><br>";
    } else {
        echo "<span style='color: red;'>✗ premium_subscriptions table MISSING (run paystack_schema_update.sql)</span><br>";
    }

    // Check customer premium columns
    $cols = $db->db->query("SHOW COLUMNS FROM customer LIKE 'is_premium'");
    if ($cols->num_rows > 0) {
        echo "<span style='color: green;'>✓ customer.is_premium column exists</span><br>";
    } else {
        echo "<span style='color: red;'>✗ customer.is_premium column MISSING (run check_database.php)</span><br>";
    }

} catch (Exception $e) {
    echo "<span style='color: red;'>✗ Database connection error: " . $e->getMessage() . "</span><br>";
}
echo "</div>";

// Test 8: Try simulating payment init
if (isset($_SESSION['user_id'])) {
    echo "<div class='test info'><strong>✓ Test 8: Simulate Payment Init</strong><br>";
    echo "<form method='POST' style='margin-top: 10px;'>
            <label>Email: <input type='email' name='test_email' value='" . ($_SESSION['user_email'] ?? 'test@example.com') . "' style='padding: 5px; margin: 5px;'></label><br>
            <label>Amount (GH₵): <input type='number' name='test_amount' value='100' style='padding: 5px; margin: 5px;'></label><br>
            <button type='submit' name='test_init' class='button'>Test Payment Initialization</button>
          </form>";

    if (isset($_POST['test_init'])) {
        $test_email = $_POST['test_email'] ?? '';
        $test_amount = floatval($_POST['test_amount'] ?? 0);

        if ($test_email && $test_amount > 0) {
            try {
                $reference = 'TEST-' . $_SESSION['user_id'] . '-' . time();
                $result = paystack_initialize_transaction($test_email, $test_amount, $reference, [
                    'user_id' => $_SESSION['user_id'],
                    'test' => true
                ]);

                if ($result['status']) {
                    echo "<div style='margin-top: 15px; padding: 15px; background: #d4edda; border-radius: 5px;'>";
                    echo "<strong style='color: green;'>✓ SUCCESS!</strong> Payment initialized<br>";
                    echo "<strong>Reference:</strong> " . $reference . "<br>";
                    echo "<strong>Test Payment Link:</strong><br>";
                    echo "<a href='" . $result['data']['authorization_url'] . "' target='_blank' class='button'>Open Paystack Checkout</a>";
                    echo "</div>";
                } else {
                    echo "<div style='margin-top: 15px; padding: 15px; background: #f8d7da; border-radius: 5px;'>";
                    echo "<strong style='color: red;'>✗ FAILED!</strong><br>";
                    echo "Error: " . $result['message'];
                    echo "</div>";
                }
            } catch (Exception $e) {
                echo "<div style='margin-top: 15px; padding: 15px; background: #f8d7da; border-radius: 5px;'>";
                echo "<strong style='color: red;'>✗ EXCEPTION!</strong><br>";
                echo "Error: " . $e->getMessage();
                echo "</div>";
            }
        }
    }
    echo "</div>";
}

// Check logs
echo "<div class='test info'><strong>✓ Test 9: Recent Logs</strong><br>";
$log_dir = __DIR__ . '/logs';
if (is_dir($log_dir)) {
    $logs = glob($log_dir . '/paystack_*.log');
    if (!empty($logs)) {
        rsort($logs);
        $latest_log = $logs[0];
        echo "Latest log: " . basename($latest_log) . "<br>";
        $lines = file($latest_log);
        if ($lines) {
            $recent = array_slice(array_reverse($lines), 0, 10);
            echo "<pre style='max-height: 200px; overflow-y: auto;'>";
            foreach ($recent as $line) {
                echo htmlspecialchars($line);
            }
            echo "</pre>";
        }
    } else {
        echo "No log files found (no payment attempts yet)<br>";
    }
} else {
    echo "Logs directory doesn't exist yet<br>";
}
echo "</div>";

echo "<hr>
      <h3>🎯 Quick Actions</h3>
      <a href='check_database.php' class='button'>Check Database Schema</a>
      <a href='test_paystack.php' class='button'>Full Paystack Test</a>
      <a href='view_logs.php' class='button'>View All Logs</a>
      <a href='views/counseling.php' class='button'>Try Counseling Booking</a>
      <a href='views/shop.php' class='button'>Try Premium Subscription</a>

      <div style='margin-top: 30px; padding: 20px; background: #fff3cd; border-radius: 5px;'>
        <strong>⚠️ Common Issues:</strong>
        <ul>
            <li><strong>Not logged in</strong> - You must be logged in to make payments</li>
            <li><strong>Missing database columns</strong> - Run check_database.php and execute SQL</li>
            <li><strong>cURL not enabled</strong> - Contact hosting provider</li>
            <li><strong>Wrong API keys</strong> - Verify Paystack keys in paystack_config.php</li>
        </ul>
      </div>
    </div>
</body>
</html>";
