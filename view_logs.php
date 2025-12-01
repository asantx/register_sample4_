<?php
/**
 * Log Viewer - View Paystack and PHP error logs
 */

$log_dir = __DIR__ . '/logs';
$php_error_log = ini_get('error_log');

?>
<!DOCTYPE html>
<html>
<head>
    <title>Log Viewer - DistantLove</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #d72660;
            margin-bottom: 10px;
        }
        .log-section {
            margin: 30px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }
        .log-header {
            background: #d72660;
            color: white;
            padding: 15px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .log-content {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 15px;
            max-height: 500px;
            overflow-y: auto;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            line-height: 1.6;
        }
        .log-line {
            padding: 2px 0;
            border-bottom: 1px solid #333;
        }
        .log-line:hover {
            background: #2d2d2d;
        }
        .timestamp {
            color: #4ec9b0;
        }
        .log-type {
            color: #c586c0;
            font-weight: bold;
        }
        .error {
            color: #f48771;
        }
        .success {
            color: #6a9955;
        }
        .info {
            color: #569cd6;
        }
        .no-logs {
            padding: 30px;
            text-align: center;
            color: #666;
            background: #f9f9f9;
        }
        .button {
            display: inline-block;
            background: #d72660;
            color: white;
            padding: 8px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }
        .button:hover {
            background: #a8325e;
        }
        .button-secondary {
            background: #666;
        }
        .button-secondary:hover {
            background: #444;
        }
        .refresh-notice {
            background: #e7f3ff;
            padding: 15px;
            border-left: 4px solid #2196F3;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 Log Viewer</h1>
        <p>Real-time logs for Paystack payments and booking system</p>

        <div class="refresh-notice">
            <strong>ℹ️ Info:</strong> Logs are displayed in reverse order (newest first).
            <button class="button" onclick="location.reload()">🔄 Refresh Logs</button>
        </div>

        <?php
        // Get all Paystack log files
        $paystack_logs = [];
        if (is_dir($log_dir)) {
            $files = scandir($log_dir);
            foreach ($files as $file) {
                if (preg_match('/^paystack_.*\.log$/', $file)) {
                    $paystack_logs[] = $file;
                }
            }
            rsort($paystack_logs); // Newest first
        }

        // Display Paystack logs
        if (!empty($paystack_logs)) {
            foreach ($paystack_logs as $log_file) {
                $file_path = $log_dir . '/' . $log_file;
                $file_size = filesize($file_path);
                $file_modified = date('Y-m-d H:i:s', filemtime($file_path));

                echo "<div class='log-section'>";
                echo "<div class='log-header'>";
                echo "<span>📄 {$log_file}</span>";
                echo "<span style='font-size: 12px; font-weight: normal;'>Modified: {$file_modified} | Size: " . number_format($file_size) . " bytes</span>";
                echo "</div>";
                echo "<div class='log-content'>";

                $lines = file($file_path);
                if ($lines) {
                    $lines = array_reverse($lines); // Show newest first
                    $lines = array_slice($lines, 0, 100); // Show last 100 lines

                    foreach ($lines as $line) {
                        $line = htmlspecialchars($line);

                        // Color code based on log type
                        $class = '';
                        if (strpos($line, 'ERROR') !== false || strpos($line, 'FAILED') !== false) {
                            $class = 'error';
                        } elseif (strpos($line, 'SUCCESS') !== false) {
                            $class = 'success';
                        } elseif (strpos($line, 'INIT') !== false || strpos($line, 'VERIFY') !== false) {
                            $class = 'info';
                        }

                        // Highlight timestamps
                        $line = preg_replace('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', '<span class="timestamp">[$1]</span>', $line);

                        // Highlight log types
                        $line = preg_replace('/\[(INIT|VERIFY|SUCCESS|ERROR|FAILED|BOOKING_.*?)\]/', '<span class="log-type">[$1]</span>', $line);

                        echo "<div class='log-line {$class}'>{$line}</div>";
                    }
                } else {
                    echo "<div class='no-logs'>Empty log file</div>";
                }

                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<div class='log-section'>";
            echo "<div class='log-header'>📄 Paystack Logs</div>";
            echo "<div class='no-logs'>";
            echo "<strong>No Paystack logs found</strong><br><br>";
            echo "Logs will appear here after you make a payment attempt.<br>";
            echo "Log directory: <code>{$log_dir}</code>";
            echo "</div>";
            echo "</div>";
        }

        // Check PHP error log
        echo "<div class='log-section'>";
        echo "<div class='log-header'>🐛 PHP Error Log</div>";

        if ($php_error_log && file_exists($php_error_log) && is_readable($php_error_log)) {
            echo "<div class='log-content'>";
            $error_lines = file($php_error_log);
            if ($error_lines) {
                $error_lines = array_reverse($error_lines);
                $error_lines = array_slice($error_lines, 0, 50); // Last 50 errors

                // Filter for counseling/booking related errors
                $filtered_lines = array_filter($error_lines, function($line) {
                    return stripos($line, 'counseling') !== false ||
                           stripos($line, 'booking') !== false ||
                           stripos($line, 'paystack') !== false ||
                           stripos($line, 'order') !== false;
                });

                if (!empty($filtered_lines)) {
                    foreach ($filtered_lines as $line) {
                        $line = htmlspecialchars($line);
                        $line = preg_replace('/\[(\d{2}-\w{3}-\d{4} \d{2}:\d{2}:\d{2}.*?)\]/', '<span class="timestamp">[$1]</span>', $line);
                        echo "<div class='log-line error'>{$line}</div>";
                    }
                } else {
                    echo "<div class='no-logs'>No counseling/booking related errors in PHP error log</div>";
                }
            } else {
                echo "<div class='no-logs'>PHP error log is empty</div>";
            }
            echo "</div>";
        } else {
            echo "<div class='no-logs'>";
            echo "PHP error log not accessible or not configured<br>";
            echo "Configure error_log in php.ini to see PHP errors here";
            echo "</div>";
        }
        echo "</div>";
        ?>

        <div style="margin-top: 30px; text-align: center;">
            <a href="views/counseling.php" class="button">Go to Counseling Page</a>
            <a href="check_database.php" class="button button-secondary">Check Database Schema</a>
            <a href="test_paystack.php" class="button button-secondary">Test Paystack</a>
        </div>

        <div style="margin-top: 30px; padding: 20px; background: #fff3cd; border-radius: 5px; border-left: 4px solid #ffc107;">
            <strong>⚠️ Security Warning:</strong> This log viewer should be deleted in production.
            It may expose sensitive information.
        </div>
    </div>
</body>
</html>
