<?php
session_start();
require_once dirname(__DIR__) . '/controllers/cart_controller.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$cart_items = get_cart_items_ctr();
$cart_count = get_cart_count_ctr();
$cart_total = get_cart_total_ctr();

// Transform image paths to full URLs
$base_url = '../';
foreach ($cart_items as &$item) {
    if ($item['product_image']) {
        $item['product_image'] = $base_url . '/' . $item['product_image'];
    }
}

echo json_encode([
    'items' => $cart_items,
    'count' => $cart_count,
    'total' => number_format($cart_total, 2)
]);
