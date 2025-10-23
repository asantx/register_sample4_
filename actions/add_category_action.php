<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/category_controller.php';

if (!isUserLoggedIn()) {
    echo json_encode(['status'=>'error','message'=>'Not authenticated']);
    exit();
}

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
if ($name === '') {
    echo json_encode(['status'=>'error','message'=>'Category name required']);
    exit();
}

$cat_id = add_category_ctr($name);
if ($cat_id) {
    echo json_encode(['status'=>'success','message'=>'Category added','cat_id'=>$cat_id]);
} else {
    echo json_encode(['status'=>'error','message'=>'Could not add category (name may already exist)']);
}
?>