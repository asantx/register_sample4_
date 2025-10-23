<?php
require_once dirname(__DIR__) . '/settings/core.php';
require_once dirname(__DIR__) . '/controllers/product_controller.php';

header('Content-Type: application/json');

// Ensure admin access
if (!isAdmin()) {
    echo json_encode(['error' => 'Not authorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

$product_id = $_POST['product_id'] ?? null;

if (!$product_id) {
    echo json_encode(['error' => 'Product ID required']);
    exit;
}

$result = delete_product_ctr($product_id);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to delete product']);
}