<?php
require_once dirname(__DIR__) . '/settings/core.php';
require_once dirname(__DIR__) . '/controllers/product_controller.php';

header('Content-Type: application/json');

// Ensure admin access
if (!isAdmin()) {
    echo json_encode(['error' => 'Not authorized']);
    exit;
}

$products = get_all_products_ctr();

// Transform image paths to full URLs
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$base_url = rtrim($base_url, '/') . '/' . dirname(dirname($_SERVER['PHP_SELF'])) . '/';

foreach ($products as &$product) {
    if ($product['product_image']) {
        $product['product_image'] = $base_url . $product['product_image'];
    }
}

echo json_encode($products);