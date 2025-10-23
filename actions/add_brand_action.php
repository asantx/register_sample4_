<?php
header('Content-Type: application/json');
// Debug temporarily
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/brand_controller.php';

if (!isAdmin()) {
    echo json_encode(['status'=>'error','message'=>'Not authorized']);
    exit();
}

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
if ($name === '') {
    echo json_encode(['status'=>'error','message'=>'Brand name required']);
    exit();
}

$brand_id = add_brand_ctr($name);
if ($brand_id) {
    echo json_encode(['status'=>'success','message'=>'Brand added','brand_id'=>$brand_id]);
} else {
    echo json_encode(['status'=>'error','message'=>'Could not add brand (name may already exist)']);
}
?>