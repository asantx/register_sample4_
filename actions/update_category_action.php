<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/category_controller.php';

if (!isUserLoggedIn()) {
    echo json_encode(['status'=>'error','message'=>'Not authenticated']);
    exit();
}
$user_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null);
if (!$user_id) { echo json_encode(['status'=>'error','message'=>'User not found']); exit(); }

$cat_id = isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 0;
$name = isset($_POST['name']) ? trim($_POST['name']) : '';

if ($cat_id <= 0 || $name === '') {
    echo json_encode(['status'=>'error','message'=>'Invalid input']);
    exit();
}

$res = update_category_ctr($cat_id, $name, $user_id);
if ($res) echo json_encode(['status'=>'success','message'=>'Category updated']);
else echo json_encode(['status'=>'error','message'=>'Could not update category (name may conflict)']);
?>