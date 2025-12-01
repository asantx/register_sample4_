<?php
/**
 * Check if your payment actually went through
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'settings/db_class.php';
require_once 'settings/core.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Check Payment Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 30px auto;
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
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #d72660;
            color: white;
            font-weight: bold;
        }
        tr:hover {
            background: #f5f5f5;
        }
        .status-active {
            color: #28a745;
            font-weight: bold;
        }
        .status-inactive {
            color: #dc3545;
        }
        .login-warning {
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
        <h1>💳 Payment Status Checker</h1>

        <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="login-warning">
                <strong>⚠ Not Logged In</strong><br>
                Please <a href="login/login.php">login</a> to check your payments.
            </div>
        <?php else: ?>
            <p><strong>Logged in as:</strong> User ID <?php echo $_SESSION['user_id']; ?> (<?php echo $_SESSION['user_email'] ?? 'N/A'; ?>)</p>

            <?php
            try {
                $db = new db_connection();
                $db->db_conn();
                $user_id = $_SESSION['user_id'];

                // Check premium subscription status
                echo "<h2>🌟 Premium Subscription Status</h2>";

                // Check customer premium status
                $stmt = $db->db->prepare("SELECT is_premium, premium_expires_at FROM customer WHERE customer_id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $customer = $result->fetch_assoc();

                if ($customer) {
                    echo "<table>";
                    echo "<tr><th>Status</th><th>Expires At</th></tr>";
                    echo "<tr>";
                    if ($customer['is_premium']) {
                        echo "<td class='status-active'>✓ PREMIUM ACTIVE</td>";
                        echo "<td>" . ($customer['premium_expires_at'] ?? 'N/A') . "</td>";
                    } else {
                        echo "<td class='status-inactive'>✗ Not Premium</td>";
                        echo "<td>-</td>";
                    }
                    echo "</tr>";
                    echo "</table>";
                } else {
                    echo "<p style='color: red;'>Customer record not found!</p>";
                }

                // Check premium_subscriptions table
                $check_table = $db->db->query("SHOW TABLES LIKE 'premium_subscriptions'");
                if ($check_table->num_rows > 0) {
                    echo "<h3>Premium Subscription Records:</h3>";

                    $stmt = $db->db->prepare("SELECT * FROM premium_subscriptions WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $subs = $stmt->get_result();

                    if ($subs->num_rows > 0) {
                        echo "<table>";
                        echo "<tr><th>ID</th><th>Plan</th><th>Amount</th><th>Start</th><th>End</th><th>Status</th><th>Reference</th></tr>";
                        while ($sub = $subs->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $sub['subscription_id'] . "</td>";
                            echo "<td>" . $sub['plan_type'] . "</td>";
                            echo "<td>GH₵ " . number_format($sub['amount'], 2) . "</td>";
                            echo "<td>" . date('Y-m-d', strtotime($sub['start_date'])) . "</td>";
                            echo "<td>" . date('Y-m-d', strtotime($sub['end_date'])) . "</td>";
                            echo "<td class='status-" . ($sub['status'] === 'active' ? 'active' : 'inactive') . "'>" . strtoupper($sub['status']) . "</td>";
                            echo "<td><small>" . $sub['payment_reference'] . "</small></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>No premium subscription records found.</p>";
                    }
                } else {
                    echo "<p style='color: orange;'>⚠ premium_subscriptions table doesn't exist. Run check_database.php to create it.</p>";
                }

                // Check counseling bookings
                echo "<h2>📅 Counseling Bookings</h2>";

                $check_orders = $db->db->query("SHOW TABLES LIKE 'orders'");
                if ($check_orders->num_rows > 0) {
                    // Check if order_type column exists
                    $check_col = $db->db->query("SHOW COLUMNS FROM orders LIKE 'order_type'");

                    if ($check_col->num_rows > 0) {
                        $stmt = $db->db->prepare("SELECT * FROM orders WHERE user_id = ? AND order_type = 'counseling' ORDER BY created_at DESC LIMIT 5");
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $bookings = $stmt->get_result();

                        if ($bookings->num_rows > 0) {
                            echo "<table>";
                            echo "<tr><th>Ref</th><th>Counselor</th><th>Date</th><th>Time</th><th>Type</th><th>Amount</th><th>Status</th></tr>";
                            while ($booking = $bookings->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td><small>" . $booking['order_reference'] . "</small></td>";
                                echo "<td>" . ($booking['counselor_name'] ?? 'N/A') . "</td>";
                                echo "<td>" . ($booking['session_date'] ?? 'N/A') . "</td>";
                                echo "<td>" . ($booking['session_time'] ?? 'N/A') . "</td>";
                                echo "<td>" . ($booking['session_type'] ?? 'N/A') . "</td>";
                                echo "<td>GH₵ " . number_format($booking['total_amount'], 2) . "</td>";
                                echo "<td>" . strtoupper($booking['status']) . "</td>";
                                echo "</tr>";
                            }
                            echo "</table>";
                        } else {
                            echo "<p>No counseling bookings found.</p>";
                        }
                    } else {
                        echo "<p style='color: orange;'>⚠ order_type column doesn't exist. Run check_database.php to add it.</p>";
                    }
                } else {
                    echo "<p style='color: red;'>✗ orders table doesn't exist!</p>";
                }

                // Check all recent orders
                echo "<h2>📦 All Recent Orders</h2>";
                $stmt = $db->db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $all_orders = $stmt->get_result();

                if ($all_orders->num_rows > 0) {
                    echo "<table>";
                    echo "<tr><th>Reference</th><th>Type</th><th>Amount</th><th>Status</th><th>Created</th></tr>";
                    while ($order = $all_orders->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><small>" . $order['order_reference'] . "</small></td>";
                        echo "<td>" . ($order['order_type'] ?? 'N/A') . "</td>";
                        echo "<td>GH₵ " . number_format($order['total_amount'], 2) . "</td>";
                        echo "<td>" . strtoupper($order['status']) . "</td>";
                        echo "<td>" . ($order['created_at'] ?? 'N/A') . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No orders found for your account.</p>";
                }

            } catch (Exception $e) {
                echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
            }
            ?>
        <?php endif; ?>

        <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 5px;">
            <h3>What This Shows:</h3>
            <ul>
                <li>Your premium subscription status in the customer table</li>
                <li>All premium subscription records from premium_subscriptions table</li>
                <li>All counseling bookings</li>
                <li>All recent orders</li>
            </ul>
            <p><strong>If you made a payment but don't see it here, the payment was initialized but verification failed.</strong></p>
        </div>

        <div style="margin-top: 20px; text-align: center;">
            <a href="test_verification.php" style="color: #d72660; text-decoration: none; font-weight: bold; margin: 0 10px;">
                Test Verification →
            </a>
            <a href="debug_payment.php" style="color: #d72660; text-decoration: none; font-weight: bold; margin: 0 10px;">
                Full Diagnostic →
            </a>
        </div>
    </div>
</body>
</html>
