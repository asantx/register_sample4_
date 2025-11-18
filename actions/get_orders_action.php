<?php
session_start();
require_once '../controllers/order_controller.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'User must be logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$orders = get_user_orders_ctr($user_id);

$base_url = 'http://' . $_SERVER['HTTP_HOST'];

$result = [];
foreach ($orders as $order) {
    $order_details = get_order_details_ctr($order['order_id']);
    $payment_info = get_order_payment_ctr($order['order_id']);

    // Transform image paths
    foreach ($order_details as &$detail) {
        if ($detail['product_image']) {
            $detail['product_image'] = $base_url . '/' . $detail['product_image'];
        }
    }

    $result[] = [
        'order_id' => $order['order_id'],
        'order_reference' => $order['order_reference'],
        'order_date' => $order['order_date'],
        'status' => $order['status'],
        'total_amount' => $order['total_amount'],
        'customer_name' => $order['customer_name'],
        'customer_email' => $order['customer_email'],
        'shipping_address' => $order['shipping_address'],
        'items' => $order_details,
        'payment' => $payment_info
    ];
}

echo json_encode($result);