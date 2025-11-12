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
$quantity = $_POST['quantity'] ?? 1;

if (!$product_id || !is_numeric($product_id) || !is_numeric($quantity)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid product_id or quantity']);
    exit;
}

if (update_quantity_ctr($product_id, $quantity)) {
    $cart_count = get_cart_count_ctr();
    $cart_total = get_cart_total_ctr();
    echo json_encode([
        'success' => true,
        'cart_count' => $cart_count,
        'cart_total' => number_format($cart_total, 2)
    ]);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Failed to update quantity']);
}
