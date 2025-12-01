<?php
/**
 * Auto-Fix Database Schema for Counseling Bookings
 * This script automatically adds missing columns
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'settings/db_class.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Auto-Fix Database</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
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
        .info {
            background: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            color: #0c5460;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #d72660;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-right: 10px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        pre {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            font-size: 12px;
        }
        a {
            color: #d72660;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔧 Auto-Fix Database Schema</h1>
";

try {
    $db = new db_connection();
    $db->db_conn();

    echo "<div class='info'><div class='spinner'></div>Checking database schema...</div>";

    // Check if orders table exists
    $check_table = $db->db->query("SHOW TABLES LIKE 'orders'");
    if ($check_table->num_rows === 0) {
        echo "<div class='error'><strong>✗ CRITICAL:</strong> Orders table does not exist!</div>";
        exit();
    }

    // Required columns
    $required_columns = [
        'order_type' => "VARCHAR(50) DEFAULT 'product' COMMENT 'Type of order: product, counseling, etc.'",
        'session_date' => "DATE NULL COMMENT 'Date of counseling session'",
        'session_time' => "VARCHAR(20) NULL COMMENT 'Time of counseling session'",
        'session_type' => "VARCHAR(20) NULL COMMENT 'Type of session: video, phone, chat'",
        'counselor_name' => "VARCHAR(100) NULL COMMENT 'Name of the counselor'",
        'session_notes' => "TEXT NULL COMMENT 'Client notes about the session'"
    ];

    $missing_columns = [];
    $existing_columns = [];

    foreach ($required_columns as $column_name => $column_definition) {
        $check_column = $db->db->query("SHOW COLUMNS FROM orders LIKE '$column_name'");
        if ($check_column->num_rows > 0) {
            $existing_columns[] = $column_name;
        } else {
            $missing_columns[$column_name] = $column_definition;
        }
    }

    if (empty($missing_columns)) {
        echo "<div class='success'><strong>✓ ALL COLUMNS ALREADY EXIST!</strong><br>";
        echo "Your database schema is complete for counseling bookings.</div>";

        echo "<h2>Columns Present:</h2>";
        echo "<ul>";
        foreach ($existing_columns as $col) {
            echo "<li><strong>$col</strong> ✓</li>";
        }
        echo "</ul>";

        echo "<div class='info'>";
        echo "<h3>Since all columns exist, let's check other potential issues:</h3>";
        echo "<p><a href='check_payment_status.php'>→ Check your payment status</a></p>";
        echo "<p><a href='test_verification.php'>→ Test payment verification</a></p>";
        echo "</div>";

    } else {
        echo "<h2>⚠ Missing Columns Detected</h2>";
        echo "<div class='info'>";
        echo "<p>The following columns need to be added:</p>";
        echo "<ul>";
        foreach ($missing_columns as $col => $def) {
            echo "<li><strong>$col</strong></li>";
        }
        echo "</ul>";
        echo "</div>";

        // Build ALTER TABLE statement
        $alter_statements = [];
        foreach ($missing_columns as $column_name => $column_definition) {
            $alter_statements[] = "ADD COLUMN $column_name $column_definition";
        }
        $sql = "ALTER TABLE orders " . implode(", ", $alter_statements);

        echo "<h2>SQL to Execute:</h2>";
        echo "<pre>$sql</pre>";

        // Execute the ALTER TABLE
        echo "<div class='info'><div class='spinner'></div>Executing database modifications...</div>";

        try {
            if ($db->db->query($sql)) {
                echo "<div class='success'><strong>✓✓✓ SUCCESS! ✓✓✓</strong><br><br>";
                echo "All missing columns have been added to the orders table!<br><br>";
                echo "<strong>Columns Added:</strong><br>";
                echo "<ul>";
                foreach ($missing_columns as $col => $def) {
                    echo "<li>✓ $col</li>";
                }
                echo "</ul>";
                echo "<p><strong>You can now make counseling bookings!</strong></p>";
                echo "</div>";

                echo "<div style='margin-top: 20px; padding: 20px; background: #fff3cd; border-radius: 5px;'>";
                echo "<h3>✅ Next Steps:</h3>";
                echo "<ol>";
                echo "<li><a href='views/counseling.php'>Go to Counseling Page</a> and try booking again</li>";
                echo "<li>Your previous payment reference was: <code>COUNSELING-12-1764612324-9714</code></li>";
                echo "<li>If you already paid, <a href='test_verification.php'>test that reference</a> to complete the booking</li>";
                echo "</ol>";
                echo "</div>";

            } else {
                throw new Exception($db->db->error);
            }
        } catch (Exception $e) {
            echo "<div class='error'><strong>✗ AUTO-FIX FAILED!</strong><br>";
            echo "Error: " . $e->getMessage() . "<br><br>";
            echo "<strong>Manual Fix Required:</strong><br>";
            echo "Copy the SQL above and execute it in phpMyAdmin.</div>";
        }
    }

    // Also check premium_subscriptions table
    echo "<hr style='margin: 30px 0;'>";
    echo "<h2>📊 Premium Subscriptions Table</h2>";

    $check_premium_table = $db->db->query("SHOW TABLES LIKE 'premium_subscriptions'");
    if ($check_premium_table->num_rows > 0) {
        echo "<div class='success'>✓ premium_subscriptions table exists</div>";
    } else {
        echo "<div class='error'>✗ premium_subscriptions table is missing<br>";
        echo "Premium subscription payments will fail without this table.</div>";

        echo "<h3>SQL to Create Table:</h3>";
        $premium_sql = "CREATE TABLE IF NOT EXISTS premium_subscriptions (
    subscription_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    plan_type VARCHAR(20) NOT NULL DEFAULT 'monthly',
    amount DECIMAL(10,2) NOT NULL,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'active',
    payment_reference VARCHAR(100) NOT NULL,
    auto_renew BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_payment_reference (payment_reference)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        echo "<pre>$premium_sql</pre>";

        echo "<div class='info'>Creating premium_subscriptions table...</div>";

        try {
            if ($db->db->query($premium_sql)) {
                echo "<div class='success'>✓ Successfully created premium_subscriptions table!</div>";
            } else {
                throw new Exception($db->db->error);
            }
        } catch (Exception $e) {
            echo "<div class='error'>Failed to create table: " . $e->getMessage() . "</div>";
        }
    }

    // Check customer premium columns
    echo "<hr style='margin: 30px 0;'>";
    echo "<h2>👤 Customer Premium Columns</h2>";

    $customer_columns = [
        'is_premium' => "BOOLEAN DEFAULT FALSE COMMENT 'Whether user has active premium subscription'",
        'premium_expires_at' => "DATETIME NULL COMMENT 'Premium subscription expiry date'"
    ];

    $missing_customer_cols = [];

    foreach ($customer_columns as $col_name => $col_def) {
        $check = $db->db->query("SHOW COLUMNS FROM customer LIKE '$col_name'");
        if ($check->num_rows > 0) {
            echo "<div class='success'>✓ customer.$col_name exists</div>";
        } else {
            echo "<div class='error'>✗ customer.$col_name is missing</div>";
            $missing_customer_cols[$col_name] = $col_def;
        }
    }

    if (!empty($missing_customer_cols)) {
        $customer_alter = [];
        foreach ($missing_customer_cols as $col => $def) {
            $customer_alter[] = "ADD COLUMN $col $def";
        }
        $customer_sql = "ALTER TABLE customer " . implode(", ", $customer_alter);

        echo "<h3>SQL to Fix Customer Table:</h3>";
        echo "<pre>$customer_sql</pre>";

        echo "<div class='info'>Adding missing columns to customer table...</div>";

        try {
            if ($db->db->query($customer_sql)) {
                echo "<div class='success'>✓ Successfully added premium columns to customer table!</div>";
            } else {
                throw new Exception($db->db->error);
            }
        } catch (Exception $e) {
            echo "<div class='error'>Failed: " . $e->getMessage() . "</div>";
        }
    }

} catch (Exception $e) {
    echo "<div class='error'><strong>✗ DATABASE ERROR:</strong><br>" . $e->getMessage() . "</div>";
}

echo "
    <hr style='margin: 30px 0;'>
    <div style='text-align: center; padding: 20px; background: #f8f9fa; border-radius: 5px;'>
        <h3>🎯 Quick Links</h3>
        <a href='views/counseling.php' style='margin: 0 10px;'>Try Counseling Booking</a> |
        <a href='views/shop.php' style='margin: 0 10px;'>Premium Subscription</a> |
        <a href='check_payment_status.php' style='margin: 0 10px;'>Check Payment Status</a> |
        <a href='test_verification.php' style='margin: 0 10px;'>Test Verification</a>
    </div>
</div>
</body>
</html>";
?>
