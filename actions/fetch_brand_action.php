<?php
header('Content-Type: application/json');
// Debug temporarily
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/brand_controller.php';

if (!isAdmin()) {
    echo json_encode(['status' => 'error', 'message' => 'Not authorized']);
    exit();
}

$brands = fetch_brands_ctr();
echo json_encode($brands);
?>