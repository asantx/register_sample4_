<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/category_controller.php';

if (!isUserLoggedIn()) {
    echo json_encode(['status'=>'error','message'=>'Not authenticated']);
    exit();
}

$cat_id = isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 0;
if ($cat_id <= 0) {
    echo json_encode(['status'=>'error','message'=>'Invalid category id']);
    exit();
}

$res = delete_category_ctr($cat_id);
if ($res) echo json_encode(['status'=>'success','message'=>'Category deleted']);
else echo json_encode(['status'=>'error','message'=>'Could not delete category']);
?>