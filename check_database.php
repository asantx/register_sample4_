<?php
/**
 * Database Schema Checker
 * Checks if all required columns exist in the database for counseling bookings
 */

require_once 'settings/db_class.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Schema Checker</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 10px; max-width: 1000px; margin: 0 auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #d72660; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #d72660; color: white; }
        .exists { color: #28a745; font-weight: bold; }
        .missing { color: #dc3545; font-weight: bold; }
        .info { background: #e7f3ff; padding: 15px; border-left: 4px solid #2196F3; margin: 20px 0; }
        .warning { background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0; }
        .error { background: #f8d7da; padding: 15px; border-left: 4px solid #dc3545; margin: 20px 0; }
        .success { background: #d4edda; padding: 15px; border-left: 4px solid #28a745; margin: 20px 0; }
        pre { background: #f4f4f4; padding: 15px; border-radius: 5px; overflow-x: auto; }
        .button { display: inline-block; background: #d72660; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 10px 5px; }
        .button:hover { background: #a8325e; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>📊 Database Schema Checker</h1>
        <p>Checking if all required database columns exist for counseling bookings...</p>";

$db = new db_connection();
$db->db_conn();

// Required columns for orders table
$required_columns = [
    'order_type' => 'VARCHAR',
    'session_date' => 'DATE',
    'session_time' => 'VARCHAR/TIME',
    'session_type' => 'VARCHAR',
    'counselor_name' => 'VARCHAR',
    'session_notes' => 'TEXT'
];

// Check if orders table exists
$table_check = $db->db->query("SHOW TABLES LIKE 'orders'");
if ($table_check->num_rows === 0) {
    echo "<div class='error'><strong>❌ Error:</strong> The 'orders' table does not exist!</div>";
    echo "</div></body></html>";
    exit;
}

echo "<div class='success'><strong>✓ Success:</strong> The 'orders' table exists</div>";

// Get all columns from orders table
$result = $db->db->query("DESCRIBE orders");
$existing_columns = [];
while ($row = $result->fetch_assoc()) {
    $existing_columns[$row['Field']] = [
        'Type' => $row['Type'],
        'Null' => $row['Null'],
        'Key' => $row['Key'],
        'Default' => $row['Default'],
        'Extra' => $row['Extra']
    ];
}

echo "<h2>📋 Required Columns Status</h2>";
echo "<table>
        <tr>
            <th>Column Name</th>
            <th>Expected Type</th>
            <th>Status</th>
            <th>Actual Type</th>
        </tr>";

$missing_columns = [];
foreach ($required_columns as $column => $expected_type) {
    if (array_key_exists($column, $existing_columns)) {
        echo "<tr>
                <td><strong>{$column}</strong></td>
                <td>{$expected_type}</td>
                <td class='exists'>✓ EXISTS</td>
                <td>{$existing_columns[$column]['Type']}</td>
              </tr>";
    } else {
        echo "<tr>
                <td><strong>{$column}</strong></td>
                <td>{$expected_type}</td>
                <td class='missing'>✗ MISSING</td>
                <td>-</td>
              </tr>";
        $missing_columns[] = $column;
    }
}

echo "</table>";

// Show all existing columns
echo "<h2>🗂️ All Existing Columns in 'orders' Table</h2>";
echo "<table>
        <tr>
            <th>Column Name</th>
            <th>Type</th>
            <th>Null</th>
            <th>Key</th>
            <th>Default</th>
        </tr>";

foreach ($existing_columns as $column_name => $column_info) {
    echo "<tr>
            <td><strong>{$column_name}</strong></td>
            <td>{$column_info['Type']}</td>
            <td>{$column_info['Null']}</td>
            <td>{$column_info['Key']}</td>
            <td>" . ($column_info['Default'] ?: 'NULL') . "</td>
          </tr>";
}

echo "</table>";

// Show SQL to fix missing columns
if (!empty($missing_columns)) {
    echo "<div class='warning'>
            <h3>⚠️ Missing Columns Detected</h3>
            <p>The following columns are missing from the 'orders' table. Run this SQL to add them:</p>
          </div>";

    echo "<pre>ALTER TABLE orders";

    $sql_parts = [];
    foreach ($missing_columns as $column) {
        switch ($column) {
            case 'order_type':
                $sql_parts[] = "ADD COLUMN order_type VARCHAR(50) DEFAULT 'product' COMMENT 'Type of order: product, counseling, etc.'";
                break;
            case 'session_date':
                $sql_parts[] = "ADD COLUMN session_date DATE COMMENT 'Date of counseling session'";
                break;
            case 'session_time':
                $sql_parts[] = "ADD COLUMN session_time VARCHAR(20) COMMENT 'Time of counseling session'";
                break;
            case 'session_type':
                $sql_parts[] = "ADD COLUMN session_type VARCHAR(20) COMMENT 'Type of session: video, phone, chat'";
                break;
            case 'counselor_name':
                $sql_parts[] = "ADD COLUMN counselor_name VARCHAR(100) COMMENT 'Name of the counselor'";
                break;
            case 'session_notes':
                $sql_parts[] = "ADD COLUMN session_notes TEXT COMMENT 'Client notes about the session'";
                break;
        }
    }

    echo "\n" . implode(",\n", $sql_parts) . ";</pre>";

    echo "<div class='info'>
            <strong>How to fix:</strong>
            <ol>
                <li>Copy the SQL query above</li>
                <li>Open phpMyAdmin</li>
                <li>Select your database</li>
                <li>Click on the 'SQL' tab</li>
                <li>Paste the query and click 'Go'</li>
            </ol>
          </div>";
} else {
    echo "<div class='success'>
            <h3>✅ All Required Columns Exist!</h3>
            <p>Your database schema is correctly set up for counseling bookings.</p>
          </div>";
}

// Check customer table for required fields
echo "<h2>👤 Customer Table Check</h2>";
$customer_check = $db->db->query("SHOW TABLES LIKE 'customer'");
if ($customer_check->num_rows > 0) {
    echo "<div class='success'>✓ Customer table exists</div>";

    // Check for required customer fields
    $customer_cols = $db->db->query("DESCRIBE customer");
    $customer_fields = [];
    while ($row = $customer_cols->fetch_assoc()) {
        $customer_fields[] = $row['Field'];
    }

    $required_customer_fields = ['customer_id', 'customer_name', 'customer_email'];
    $missing_customer = [];

    foreach ($required_customer_fields as $field) {
        if (!in_array($field, $customer_fields)) {
            $missing_customer[] = $field;
        }
    }

    if (empty($missing_customer)) {
        echo "<div class='success'>✓ All required customer fields exist</div>";
    } else {
        echo "<div class='error'>✗ Missing customer fields: " . implode(', ', $missing_customer) . "</div>";
    }
} else {
    echo "<div class='error'>✗ Customer table does not exist!</div>";
}

echo "<hr style='margin: 30px 0;'>
      <div style='text-align: center;'>
        <a href='views/counseling.php' class='button'>Go to Counseling Page</a>
        <a href='test_paystack.php' class='button'>Test Paystack Integration</a>
      </div>

      <div style='margin-top: 30px; padding: 20px; background: #fff3cd; border-radius: 5px; border-left: 4px solid #ffc107;'>
        <strong>⚠️ Important:</strong> Delete this file after checking for security reasons.
      </div>
    </div>
</body>
</html>";
