<?php
/**
 * Quick Payment Diagnostic Test
 * Tests the exact failure point for payments
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Payment System Quick Test</h1>";
echo "<hr>";

// Test 1: Check session
session_start();
echo "<h3>Test 1: Session Status</h3>";
if (isset($_SESSION['user_id'])) {
    echo "✓ User is logged in<br>";
    echo "User ID: " . $_SESSION['user_id'] . "<br>";
    echo "Email: " . ($_SESSION['user_email'] ?? 'Not set') . "<br>";
} else {
    echo "<strong style='color:red;'>✗ NOT LOGGED IN - This is your problem!</strong><br>";
    echo "<p>You must be logged in to make payments.</p>";
    echo "<a href='login/login.php'>Click here to login</a><br>";
}
echo "<hr>";

// Test 2: Check Paystack config loads
echo "<h3>Test 2: Paystack Configuration</h3>";
try {
    require_once 'settings/paystack_config.php';
    echo "✓ Config file loaded<br>";

    if (defined('PAYSTACK_PUBLIC_KEY')) {
        echo "✓ Public Key: " . substr(PAYSTACK_PUBLIC_KEY, 0, 15) . "...<br>";
    } else {
        echo "✗ Public Key NOT defined<br>";
    }

    if (defined('PAYSTACK_SECRET_KEY')) {
        echo "✓ Secret Key: " . substr(PAYSTACK_SECRET_KEY, 0, 15) . "...<br>";
    } else {
        echo "✗ Secret Key NOT defined<br>";
    }

    if (defined('PAYSTACK_CALLBACK_URL')) {
        echo "✓ Callback URL: " . PAYSTACK_CALLBACK_URL . "<br>";
    } else {
        echo "✗ Callback URL NOT defined<br>";
    }
} catch (Exception $e) {
    echo "<strong style='color:red;'>✗ Error loading config: " . $e->getMessage() . "</strong><br>";
}
echo "<hr>";

// Test 3: Check cURL
echo "<h3>Test 3: cURL Extension</h3>";
if (extension_loaded('curl')) {
    echo "✓ cURL is enabled<br>";
    $curl_version = curl_version();
    echo "Version: " . $curl_version['version'] . "<br>";
} else {
    echo "<strong style='color:red;'>✗ cURL is NOT enabled - Contact hosting provider</strong><br>";
}
echo "<hr>";

// Test 4: Check logs directory
echo "<h3>Test 4: Logs Directory</h3>";
$log_dir = __DIR__ . '/logs';
if (is_dir($log_dir)) {
    echo "✓ Logs directory exists<br>";
    if (is_writable($log_dir)) {
        echo "✓ Logs directory is writable<br>";
    } else {
        echo "<strong style='color:red;'>✗ Logs directory is NOT writable</strong><br>";
    }
} else {
    echo "⚠ Logs directory doesn't exist yet (will be created on first payment)<br>";
}
echo "<hr>";

// Test 5: Try a simple payment initialization (only if logged in and cURL available)
if (isset($_SESSION['user_id']) && extension_loaded('curl') && defined('PAYSTACK_SECRET_KEY')) {
    echo "<h3>Test 5: Simulate Payment Initialization</h3>";

    $test_email = $_SESSION['user_email'] ?? 'test@example.com';
    $test_amount = 1.00; // GH₵ 1.00
    $test_reference = 'QUICKTEST-' . $_SESSION['user_id'] . '-' . time();

    echo "Testing payment initialization...<br>";
    echo "Email: $test_email<br>";
    echo "Amount: GH₵ $test_amount<br>";
    echo "Reference: $test_reference<br><br>";

    try {
        $result = paystack_initialize_transaction($test_email, $test_amount, $test_reference, [
            'user_id' => $_SESSION['user_id'],
            'test' => true
        ]);

        if ($result['status']) {
            echo "<div style='background: #d4edda; padding: 15px; margin: 10px 0;'>";
            echo "<strong style='color: green;'>✓ SUCCESS! Payment initialization works!</strong><br>";
            echo "Authorization URL: <a href='" . $result['data']['authorization_url'] . "' target='_blank'>Click to test payment</a><br>";
            echo "</div>";
            echo "<p><strong>Your payment system is working!</strong> The issue might be:</p>";
            echo "<ul>";
            echo "<li>Not being logged in when you try to pay</li>";
            echo "<li>JavaScript errors in your browser (check browser console with F12)</li>";
            echo "<li>Form data not being sent correctly</li>";
            echo "</ul>";
        } else {
            echo "<div style='background: #f8d7da; padding: 15px; margin: 10px 0;'>";
            echo "<strong style='color: red;'>✗ FAILED!</strong><br>";
            echo "Error: " . $result['message'] . "<br>";
            echo "</div>";
            echo "<p><strong>This is your problem!</strong> The Paystack API is rejecting your request.</p>";
            echo "<p>Possible causes:</p>";
            echo "<ul>";
            echo "<li>Invalid API keys (check settings/paystack_config.php)</li>";
            echo "<li>Network/firewall blocking Paystack API</li>";
            echo "<li>Paystack account issue</li>";
            echo "</ul>";
        }
    } catch (Exception $e) {
        echo "<div style='background: #f8d7da; padding: 15px; margin: 10px 0;'>";
        echo "<strong style='color: red;'>✗ EXCEPTION!</strong><br>";
        echo "Error: " . $e->getMessage() . "<br>";
        echo "</div>";
    }
} else {
    echo "<h3>Test 5: Skipped</h3>";
    echo "Cannot test payment initialization because:<br>";
    if (!isset($_SESSION['user_id'])) {
        echo "- You are not logged in<br>";
    }
    if (!extension_loaded('curl')) {
        echo "- cURL is not enabled<br>";
    }
    if (!defined('PAYSTACK_SECRET_KEY')) {
        echo "- Paystack config not loaded<br>";
    }
}
echo "<hr>";

// Test 6: Browser JavaScript test
echo "<h3>Test 6: Browser Console Check</h3>";
echo "<p>Open your browser console (Press F12) and check for JavaScript errors when you try to make a payment.</p>";
echo "<p>Common JavaScript errors:</p>";
echo "<ul>";
echo "<li>Fetch API blocked by CORS</li>";
echo "<li>Form validation failing</li>";
echo "<li>Undefined variables</li>";
echo "</ul>";
echo "<hr>";

echo "<h2>Summary</h2>";
echo "<p>Based on the tests above, the most common causes of payment failure are:</p>";
echo "<ol>";
echo "<li><strong>Not logged in</strong> - Login first before attempting payment</li>";
echo "<li><strong>JavaScript errors</strong> - Check browser console (F12) for errors</li>";
echo "<li><strong>cURL not enabled</strong> - Contact hosting provider</li>";
echo "<li><strong>Invalid Paystack API keys</strong> - Verify in settings/paystack_config.php</li>";
echo "</ol>";

echo "<br><a href='debug_payment.php' style='background: #d72660; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Full Diagnostic Tool</a>";
?>
