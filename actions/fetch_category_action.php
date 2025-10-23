<?php
header('Content-Type: application/json');
require_once '../settings/core.php';
require_once '../controllers/category_controller.php';

if (!isUserLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}

$cats = fetch_categories_ctr();
echo json_encode(['status' => 'success', 'categories' => $cats]);
