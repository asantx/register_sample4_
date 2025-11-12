<?php
session_start();
require_once dirname(__DIR__) . '/controllers/cart_controller.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (empty_cart_ctr()) {
    echo json_encode([
        'success' => true,
        'cart_count' => 0,
        'cart_total' => '0.00'
    ]);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Failed to empty cart']);
}
