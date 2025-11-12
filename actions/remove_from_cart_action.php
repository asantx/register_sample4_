<?php
session_start();
require_once dirname(__DIR__) . '/controllers/cart_controller.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$product_id = $_POST['product_id'] ?? null;

if (!$product_id || !is_numeric($product_id)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid product_id']);
    exit;
}

if (remove_from_cart_ctr($product_id)) {
    $cart_count = get_cart_count_ctr();
    $cart_total = get_cart_total_ctr();
    echo json_encode([
        'success' => true,
        'cart_count' => $cart_count,
        'cart_total' => number_format($cart_total, 2)
    ]);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Failed to remove item from cart']);
}
