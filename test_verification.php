<?php
/**
 * Test Payment Verification
 * Enter your payment reference to see what's happening
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'settings/paystack_config.php';
require_once 'settings/core.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Payment Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #d72660;
            border-bottom: 3px solid #d72660;
            padding-bottom: 10px;
        }
        .form-group {
            margin: 20px 0;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        button {
            background: #d72660;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        button:hover {
            background: #a8325e;
        }
        .result {
            margin-top: 30px;
            padding: 20px;
            border-radius: 5px;
            white-space: pre-wrap;
            font-family: monospace;
            font-size: 13px;
        }
        .success {
            background: #d4edda;
            border: 2px solid #28a745;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            border: 2px solid #dc3545;
            color: #721c24;
        }
        .info {
            background: #d1ecf1;
            border: 2px solid #17a2b8;
            color: #0c5460;
        }
        .session-info {
            background: #fff3cd;
            border: 2px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Test Payment Verification</h1>

        <div class="session-info">
            <strong>Current Session Status:</strong><br>
            <?php if (isset($_SESSION['user_id'])): ?>
                ✓ Logged in as User ID: <?php echo $_SESSION['user_id']; ?><br>
                Email: <?php echo $_SESSION['user_email'] ?? 'Not set'; ?><br>
            <?php else: ?>
                <strong style="color: red;">✗ NOT LOGGED IN</strong><br>
                <a href="login/login.php">Login here</a> to test verification properly
            <?php endif; ?>
        </div>

        <form method="POST">
            <div class="form-group">
                <label for="reference">Enter Payment Reference:</label>
                <input type="text"
                       id="reference"
                       name="reference"
                       placeholder="e.g., PREMIUM-12-1764592060-5726"
                       value="<?php echo htmlspecialchars($_POST['reference'] ?? ''); ?>"
                       required>
                <small style="color: #666;">Copy the reference from your browser URL after payment redirect</small>
            </div>
            <button type="submit" name="test">Test Verification</button>
        </form>

        <?php
        if (isset($_POST['test']) && !empty($_POST['reference'])) {
            $reference = $_POST['reference'];

            echo "<div class='result info'>";
            echo "<strong>Step 1: Checking Reference Format</strong>\n";
            echo "Reference: $reference\n";
            echo "Format looks: " . (preg_match('/^[A-Z]+-\d+-\d+-\d+$/', $reference) ? "✓ Valid" : "⚠ Unusual") . "\n";
            echo "</div>";

            // Step 2: Check if session has pending transaction
            echo "<div class='result info'>";
            echo "<strong>Step 2: Checking Session Data</strong>\n";
            if (isset($_SESSION['pending_transaction'])) {
                echo "✓ Pending transaction found in session\n";
                echo "Details:\n";
                echo json_encode($_SESSION['pending_transaction'], JSON_PRETTY_PRINT);
            } else {
                echo "⚠ No pending transaction in session\n";
                echo "This might cause verification to fail\n";
            }
            echo "</div>";

            // Step 3: Verify with Paystack
            echo "<div class='result info'>";
            echo "<strong>Step 3: Verifying with Paystack API</strong>\n";
            try {
                $verification = paystack_verify_transaction($reference);

                if ($verification['status']) {
                    echo "✓ Paystack verification successful\n\n";
                    echo "Transaction Data:\n";
                    echo json_encode($verification['data'], JSON_PRETTY_PRINT);
                } else {
                    echo "✗ Paystack verification failed\n";
                    echo "Message: " . $verification['message'] . "\n";
                }
            } catch (Exception $e) {
                echo "✗ Exception occurred: " . $e->getMessage() . "\n";
            }
            echo "</div>";

            // Step 4: Test full verification endpoint
            echo "<div class='result info'>";
            echo "<strong>Step 4: Testing Verification Endpoint</strong>\n";
            echo "Simulating AJAX call to actions/paystack_verify_payment.php\n\n";

            // Simulate the verification process
            $_GET['reference'] = $reference;

            ob_start();
            include 'actions/paystack_verify_payment.php';
            $response_json = ob_get_clean();

            echo "Raw Response:\n";
            echo $response_json . "\n\n";

            $response = json_decode($response_json, true);
            if ($response) {
                if ($response['status']) {
                    echo "<div class='success'>";
                    echo "✓ VERIFICATION SUCCESSFUL!\n\n";
                    echo "Message: " . $response['message'] . "\n";
                    if (isset($response['data'])) {
                        echo "\nData:\n";
                        echo json_encode($response['data'], JSON_PRETTY_PRINT);
                    }
                    echo "</div>";
                } else {
                    echo "<div class='error'>";
                    echo "✗ VERIFICATION FAILED\n\n";
                    echo "Message: " . $response['message'] . "\n";
                    echo "</div>";
                }
            } else {
                echo "<div class='error'>";
                echo "✗ Invalid JSON response\n";
                echo "</div>";
            }
            echo "</div>";
        }
        ?>

        <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 5px;">
            <h3>How to Use This Tool:</h3>
            <ol>
                <li>Make sure you're logged in (check status above)</li>
                <li>Try to make a payment (counseling or premium)</li>
                <li>When redirected to paystack_callback.php, copy the reference from the URL</li>
                <li>Paste it in the form above and click "Test Verification"</li>
                <li>Review all 4 steps to see where the problem is</li>
            </ol>

            <h3>What Each Step Checks:</h3>
            <ul>
                <li><strong>Step 1:</strong> Reference format is correct</li>
                <li><strong>Step 2:</strong> Session has pending transaction data</li>
                <li><strong>Step 3:</strong> Paystack API confirms the payment</li>
                <li><strong>Step 4:</strong> Full verification process (booking/subscription creation)</li>
            </ul>
        </div>

        <div style="margin-top: 20px; text-align: center;">
            <a href="debug_payment.php" style="color: #d72660; text-decoration: none; font-weight: bold;">
                ← Back to Full Diagnostic
            </a>
        </div>
    </div>
</body>
</html>
