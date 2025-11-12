<?php
session_start();
require_once dirname(__DIR__) . '/controllers/cart_controller.php';
require_once dirname(__DIR__) . '/controllers/order_controller.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'User must be logged in to checkout']);
    exit;
}

$user_id = $_SESSION['user_id'];
$customer_name = $_POST['customer_name'] ?? null;
$customer_email = $_POST['customer_email'] ?? null;
$shipping_address = $_POST['shipping_address'] ?? null;

if (!$customer_name || !$customer_email || !$shipping_address) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields: customer_name, customer_email, shipping_address']);
    exit;
}

$cart_items = get_cart_items_ctr();
if (empty($cart_items)) {
    http_response_code(400);
    echo json_encode(['error' => 'Cart is empty']);
    exit;
}

$total_amount = get_cart_total_ctr();

// Create order
$order_result = create_order_ctr($user_id, $total_amount, $customer_name, $customer_email, $shipping_address);
if (!$order_result) {
    http_response_code(400);
    echo json_encode(['error' => 'Failed to create order']);
    exit;
}

$order_id = $order_result['order_id'];
$order_reference = $order_result['order_reference'];

// Add order details from cart items
foreach ($cart_items as $item) {
    add_order_detail_ctr(
        $order_id,
        $item['product_id'],
        $item['product_title'],
        $item['quantity'],
        $item['product_price']
    );
}

// Record payment
$transaction_id = record_payment_ctr($order_id, $user_id, $total_amount, 'online');
if (!$transaction_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Failed to process payment']);
    exit;
}

// Update order status to confirmed
update_order_status_ctr($order_id, 'confirmed');

// Empty cart
empty_cart_ctr();

echo json_encode([
    'status' => 'success',
    'order_id' => $order_id,
    'order_reference' => $order_reference,
    'transaction_id' => $transaction_id,
    'total_amount' => number_format($total_amount, 2),
    'message' => 'Order placed successfully! Your order reference is ' . $order_reference
]);
