<?php
/**
 * Fix Counseling Booking Database Schema
 * This will check and add missing columns needed for counseling bookings
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'settings/db_class.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Fix Counseling Booking Schema</title>
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
        .success {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .error {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .info {
            background: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }
        pre {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
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
            margin: 10px 5px;
        }
        button:hover {
            background: #a8325e;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔧 Fix Counseling Booking Database</h1>
";

try {
    $db = new db_connection();
    $db->db_conn();

    // Check if orders table exists
    $check_table = $db->db->query("SHOW TABLES LIKE 'orders'");

    if ($check_table->num_rows === 0) {
        echo "<div class='error'><strong>✗ CRITICAL ERROR:</strong> The 'orders' table does not exist!<br>";
        echo "Please create the orders table first.</div>";
        exit();
    }

    echo "<div class='success'>✓ Orders table exists</div>";

    // Required columns for counseling bookings
    $required_columns = [
        'order_type' => "VARCHAR(50) DEFAULT 'product' COMMENT 'Type of order: product, counseling, etc.'",
        'session_date' => "DATE NULL COMMENT 'Date of counseling session'",
        'session_time' => "VARCHAR(20) NULL COMMENT 'Time of counseling session'",
        'session_type' => "VARCHAR(20) NULL COMMENT 'Type of session: video, phone, chat'",
        'counselor_name' => "VARCHAR(100) NULL COMMENT 'Name of the counselor'",
        'session_notes' => "TEXT NULL COMMENT 'Client notes about the session'"
    ];

    echo "<h2>Checking Required Columns</h2>";

    $missing_columns = [];
    $existing_columns = [];

    foreach ($required_columns as $column_name => $column_definition) {
        $check_column = $db->db->query("SHOW COLUMNS FROM orders LIKE '$column_name'");

        if ($check_column->num_rows > 0) {
            echo "<div class='success'>✓ Column '$column_name' exists</div>";
            $existing_columns[] = $column_name;
        } else {
            echo "<div class='warning'>⚠ Column '$column_name' is MISSING</div>";
            $missing_columns[$column_name] = $column_definition;
        }
    }

    if (empty($missing_columns)) {
        echo "<div class='success'><strong>✓ ALL COLUMNS EXIST!</strong><br>";
        echo "Your database schema is complete. The booking issue must be something else.</div>";

        echo "<h2>Next Steps:</h2>";
        echo "<div class='info'>";
        echo "<p>Since all columns exist, the issue might be:</p>";
        echo "<ul>";
        echo "<li>User not found in customer table</li>";
        echo "<li>SQL syntax error</li>";
        echo "<li>Session data not properly passed</li>";
        echo "</ul>";
        echo "<p>Try booking again and check the exact error message.</p>";
        echo "</div>";
    } else {
        echo "<h2>⚠ Missing Columns Found</h2>";
        echo "<div class='warning'>";
        echo "<p>The following columns are missing and need to be added:</p>";
        echo "<ul>";
        foreach ($missing_columns as $col => $def) {
            echo "<li><strong>$col</strong></li>";
        }
        echo "</ul>";
        echo "</div>";

        // Generate SQL
        $sql = "ALTER TABLE orders\n";
        $alters = [];
        foreach ($missing_columns as $column_name => $column_definition) {
            $alters[] = "ADD COLUMN $column_name $column_definition";
        }
        $sql .= implode(",\n", $alters) . ";";

        echo "<h2>SQL to Fix</h2>";
        echo "<div class='info'>";
        echo "<p>Execute this SQL to add the missing columns:</p>";
        echo "<pre>$sql</pre>";
        echo "</div>";

        // Offer to auto-fix
        if (isset($_POST['auto_fix'])) {
            echo "<h2>Executing Auto-Fix...</h2>";

            try {
                $db->db->multi_query($sql);

                // Clear any result sets
                do {
                    if ($result = $db->db->store_result()) {
                        $result->free();
                    }
                } while ($db->db->more_results() && $db->db->next_result());

                echo "<div class='success'><strong>✓ SUCCESS!</strong><br>";
                echo "All missing columns have been added to the orders table.<br>";
                echo "<a href='fix_counseling_booking.php'>Refresh page</a> to verify.</div>";
            } catch (Exception $e) {
                echo "<div class='error'><strong>✗ AUTO-FIX FAILED!</strong><br>";
                echo "Error: " . $e->getMessage() . "<br>";
                echo "Please execute the SQL manually in phpMyAdmin.</div>";
            }
        } else {
            echo "<form method='POST' style='margin: 20px 0;'>";
            echo "<button type='submit' name='auto_fix'>🔧 Auto-Fix Database (Add Missing Columns)</button>";
            echo "<a href='#' style='display: inline-block; padding: 12px 30px; margin-left: 10px;'>";
            echo "<button type='button' class='btn-secondary' onclick='copySQL()'>📋 Copy SQL</button>";
            echo "</a>";
            echo "</form>";

            echo "<script>
                function copySQL() {
                    const sql = `$sql`;
                    navigator.clipboard.writeText(sql).then(() => {
                        alert('SQL copied to clipboard!');
                    });
                }
            </script>";
        }
    }

    // Check customer table for user
    if (isset($_POST['check_user'])) {
        echo "<h2>Checking User Data</h2>";
        $user_id = intval($_POST['user_id']);

        $stmt = $db->db->prepare("SELECT customer_id, customer_name, customer_email FROM customer WHERE customer_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            echo "<div class='success'>✓ User found<br>";
            echo "ID: " . $user['customer_id'] . "<br>";
            echo "Name: " . $user['customer_name'] . "<br>";
            echo "Email: " . $user['customer_email'] . "</div>";
        } else {
            echo "<div class='error'>✗ User ID $user_id not found in customer table!</div>";
        }
    }

    echo "<h2>🔍 Test User Lookup</h2>";
    echo "<form method='POST'>";
    echo "<label>Enter User ID to check if it exists:</label><br>";
    echo "<input type='number' name='user_id' value='12' style='padding: 8px; margin: 10px 0; width: 200px;'><br>";
    echo "<button type='submit' name='check_user'>Check User</button>";
    echo "</form>";

} catch (Exception $e) {
    echo "<div class='error'><strong>✗ DATABASE ERROR:</strong><br>";
    echo $e->getMessage() . "</div>";
}

echo "
    <div style='margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 5px;'>
        <h3>What This Tool Does:</h3>
        <ol>
            <li>Checks if the orders table exists</li>
            <li>Verifies all required columns for counseling bookings</li>
            <li>Generates SQL to add missing columns</li>
            <li>Can automatically add missing columns (if you click Auto-Fix)</li>
            <li>Can check if a user exists in the customer table</li>
        </ol>
    </div>

    <div style='margin-top: 20px; text-align: center;'>
        <a href='check_payment_status.php' style='color: #d72660; text-decoration: none; font-weight: bold; margin: 0 10px;'>
            Check Payment Status →
        </a>
        <a href='test_verification.php' style='color: #d72660; text-decoration: none; font-weight: bold; margin: 0 10px;'>
            Test Verification →
        </a>
    </div>
</div>
</body>
</html>";
?>
