<?php
header('Content-Type: application/json');
// Show errors for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/category_controller.php';



$cats = fetch_categories_ctr();
echo json_encode(['status' => 'success', 'categories' => $cats]);
