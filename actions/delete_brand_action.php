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

$brand_id = isset($_POST['brand_id']) ? intval($_POST['brand_id']) : 0;
if ($brand_id <= 0) {
    echo json_encode(['status'=>'error','message'=>'Invalid brand id']);
    exit();
}

$res = delete_brand_ctr($brand_id);
if ($res) echo json_encode(['status'=>'success','message'=>'Brand deleted']);
else echo json_encode(['status'=>'error','message'=>'Could not delete brand']);
?>