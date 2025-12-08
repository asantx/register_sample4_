<?php
/**
 * Counseling Payment Diagnostic Tool
 * Comprehensive check for counseling booking issues
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'settings/db_class.php';
require_once 'settings/core.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Counseling Payment Debug</title>
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
            border-bottom: 3px solid #d72660;
            padding-bottom: 10px;
        }
        .success {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            color: #721c24;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            color: #856404;
        }
        .info {
            background: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            color: #0c5460;
        }
        pre {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #d72660;
            color: white;
        }
        button {
            background: #d72660;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }
        button:hover {
            background: #a8325e;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔍 Counseling Payment Diagnostic</h1>";

// Test 1: Check if user is logged in
echo "<h2>Test 1: User Session</h2>";
if (isset($_SESSION['user_id'])) {
    echo "<div class='success'>";
    echo "✓ User is logged in<br>";
    echo "User ID: " . $_SESSION['user_id'] . "<br>";
    echo "Email: " . ($_SESSION['user_email'] ?? 'Not set') . "<br>";
    echo "</div>";

    $user_id = $_SESSION['user_id'];
} else {
    echo "<div class='error'>";
    echo "✗ NOT LOGGED IN<br>";
    echo "You must be logged in to make counseling bookings.<br>";
    echo "<a href='login/login.php'>Login here</a>";
    echo "</div>";
    echo "</div></body></html>";
    exit();
}

// Test 2: Check database schema
echo "<h2>Test 2: Database Schema</h2>";
try {
    $db = new db_connection();
    $db->db_conn();

    // Check orders table
    $check_table = $db->db->query("SHOW TABLES LIKE 'orders'");
    if ($check_table->num_rows > 0) {
        echo "<div class='success'>✓ orders table exists</div>";

        // Check required columns
        $required_columns = [
            'order_type',
            'session_date',
            'session_time',
            'session_type',
            'counselor_name',
            'session_notes'
        ];

        $missing_columns = [];
        foreach ($required_columns as $col) {
            $check_col = $db->db->query("SHOW COLUMNS FROM orders LIKE '$col'");
            if ($check_col->num_rows > 0) {
                echo "<div class='success'>✓ Column '$col' exists</div>";
            } else {
                echo "<div class='error'>✗ Column '$col' is MISSING</div>";
                $missing_columns[] = $col;
            }
        }

        if (!empty($missing_columns)) {
            echo "<div class='error'>";
            echo "<strong>⚠ DATABASE SCHEMA INCOMPLETE!</strong><br>";
            echo "Missing columns: " . implode(', ', $missing_columns) . "<br>";
            echo "<strong>Action Required:</strong> Run <a href='auto_fix_database.php'>auto_fix_database.php</a> to add missing columns.";
            echo "</div>";
        }
    } else {
        echo "<div class='error'>✗ orders table does NOT exist!</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>Database error: " . $e->getMessage() . "</div>";
}

// Test 3: Check if user exists in customer table
echo "<h2>Test 3: User Data Validation</h2>";
try {
    $stmt = $db->db->prepare("SELECT customer_id, customer_name, customer_email FROM customer WHERE customer_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        echo "<div class='success'>";
        echo "✓ User found in customer table<br>";
        echo "ID: " . $user['customer_id'] . "<br>";
        echo "Name: " . $user['customer_name'] . "<br>";
        echo "Email: " . $user['customer_email'] . "<br>";
        echo "</div>";
    } else {
        echo "<div class='error'>✗ User ID $user_id NOT found in customer table!</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>Error: " . $e->getMessage() . "</div>";
}

// Test 4: Check existing bookings
echo "<h2>Test 4: Existing Counseling Bookings</h2>";
try {
    $stmt = $db->db->prepare("SELECT * FROM orders WHERE user_id = ? AND order_type = 'counseling' ORDER BY created_at DESC LIMIT 5");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $bookings = $stmt->get_result();

    if ($bookings->num_rows > 0) {
        echo "<div class='success'>Found " . $bookings->num_rows() . " counseling booking(s)</div>";
        echo "<table>";
        echo "<tr><th>Reference</th><th>Counselor</th><th>Date</th><th>Time</th><th>Type</th><th>Amount</th><th>Status</th><th>Created</th></tr>";
        while ($booking = $bookings->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($booking['order_reference']) . "</td>";
            echo "<td>" . htmlspecialchars($booking['counselor_name'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($booking['session_date'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($booking['session_time'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($booking['session_type'] ?? 'N/A') . "</td>";
            echo "<td>GH₵ " . number_format($booking['total_amount'], 2) . "</td>";
            echo "<td>" . htmlspecialchars($booking['status']) . "</td>";
            echo "<td>" . htmlspecialchars($booking['created_at'] ?? 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='warning'>No counseling bookings found yet</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>Error: " . $e->getMessage() . "</div>";
}

// Test 5: Test booking creation manually
echo "<h2>Test 5: Test Booking Creation</h2>";
echo "<form method='POST'>";
echo "<p>Enter test booking details to verify the booking function works:</p>";
echo "<label>Counselor Name: <input type='text' name='counselor' value='Dr. Sarah Johnson' required></label><br>";
echo "<label>Session Date: <input type='date' name='session_date' required></label><br>";
echo "<label>Session Time: <input type='time' name='session_time' value='14:00' required></label><br>";
echo "<label>Session Type: <select name='session_type'><option value='video'>Video</option><option value='phone'>Phone</option><option value='chat'>Chat</option></select></label><br>";
echo "<label>Cost: <input type='number' name='cost' value='400' step='0.01' required></label><br>";
echo "<label>Notes: <textarea name='notes' placeholder='Optional notes'></textarea></label><br>";
echo "<button type='submit' name='test_booking'>Test Create Booking</button>";
echo "</form>";

if (isset($_POST['test_booking'])) {
    echo "<div class='info'>Testing booking creation...</div>";

    try {
        require_once 'controllers/order_controller.php';

        $result = create_counseling_booking_ctr(
            $user_id,
            $_POST['counselor'],
            $_POST['session_date'],
            $_POST['session_time'],
            $_POST['session_type'],
            floatval($_POST['cost']),
            $_POST['notes'] ?? ''
        );

        if ($result) {
            echo "<div class='success'>";
            echo "<strong>✓ SUCCESS!</strong> Booking created successfully!<br>";
            echo "Order ID: " . $result['order_id'] . "<br>";
            echo "Booking Reference: " . $result['booking_reference'] . "<br>";
            echo "<p><strong>Your booking system is working!</strong></p>";
            echo "<a href='views/orders.php'>View in My Bookings</a>";
            echo "</div>";
        } else {
            echo "<div class='error'>";
            echo "✗ Booking creation returned FALSE<br>";
            echo "Check error logs for details";
            echo "</div>";
        }
    } catch (Exception $e) {
        echo "<div class='error'>";
        echo "✗ Exception: " . $e->getMessage();
        echo "</div>";
    }
}

// Test 6: Check logs
echo "<h2>Test 6: Recent Payment Logs</h2>";
$log_dir = __DIR__ . '/logs';
if (is_dir($log_dir)) {
    $logs = glob($log_dir . '/paystack_*.log');
    if (!empty($logs)) {
        rsort($logs);
        $latest = $logs[0];
        echo "<div class='info'>Latest log: " . basename($latest) . "</div>";

        $lines = file($latest);
        if ($lines) {
            $recent = array_slice(array_reverse($lines), 0, 20);
            echo "<pre>";
            foreach ($recent as $line) {
                // Highlight errors
                if (strpos($line, 'BOOKING_FAILED') !== false || strpos($line, 'ERROR') !== false) {
                    echo "<span style='color: red; font-weight: bold;'>" . htmlspecialchars($line) . "</span>";
                } elseif (strpos($line, 'BOOKING_SUCCESS') !== false) {
                    echo "<span style='color: green; font-weight: bold;'>" . htmlspecialchars($line) . "</span>";
                } else {
                    echo htmlspecialchars($line);
                }
            }
            echo "</pre>";
        }
    } else {
        echo "<div class='warning'>No log files found (no payment attempts yet)</div>";
    }
} else {
    echo "<div class='warning'>Logs directory doesn't exist yet</div>";
}

echo "
<hr>
<h2>🎯 Quick Actions</h2>
<a href='auto_fix_database.php'><button>Fix Database Schema</button></a>
<a href='views/counseling.php'><button>Try Counseling Booking</button></a>
<a href='check_payment_status.php'><button>Check Payment Status</button></a>
<a href='view_logs.php'><button>View All Logs</button></a>

<hr>
<h2>📋 Common Issues & Solutions</h2>
<div class='info'>
<strong>Issue 1: Missing Database Columns</strong><br>
Solution: Run <a href='auto_fix_database.php'>auto_fix_database.php</a> to add all required columns.
<br><br>
<strong>Issue 2: User Not Found</strong><br>
Solution: Verify you're logged in with a valid account.
<br><br>
<strong>Issue 3: Payment Verified But Booking Not Created</strong><br>
Solution: Check Test 5 above to verify booking creation works. If it works manually, the issue is with payment verification passing data correctly.
<br><br>
<strong>Issue 4: Session Lost After Paystack Redirect</strong><br>
Solution: Browser tracking prevention - try using Chrome instead of Safari/Firefox.
</div>
</div>
</body>
</html>";
?>
