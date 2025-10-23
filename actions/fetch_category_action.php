<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/category_controller.php';

if (!isUserLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}
$user_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null);
if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'User not found in session']);
    exit();
}

$cats = fetch_categories_ctr($user_id);
echo json_encode(['status' => 'success', 'categories' => $cats]);
