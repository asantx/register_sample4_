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
$name = isset($_POST['name']) ? trim($_POST['name']) : '';

if ($brand_id <= 0 || $name === '') {
    echo json_encode(['status'=>'error','message'=>'Invalid input']);
    exit();
}

$res = update_brand_ctr($brand_id, $name);
if ($res) echo json_encode(['status'=>'success','message'=>'Brand updated']);
else echo json_encode(['status'=>'error','message'=>'Could not update brand (name may conflict)']);
?>