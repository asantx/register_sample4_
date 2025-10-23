<?php
require_once dirname(__DIR__) . '/settings/core.php';
require_once dirname(__DIR__) . '/controllers/product_controller.php';

header('Content-Type: application/json');

// Ensure admin access
if (!isAdmin()) {
    echo json_encode(['error' => 'Not authorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

$cat_id = $_POST['category_id'] ?? null;
$brand_id = $_POST['brand_id'] ?? null;
$title = $_POST['title'] ?? null;
$price = $_POST['price'] ?? null;
$desc = $_POST['description'] ?? '';
$keywords = $_POST['keywords'] ?? '';

// Validate required fields
if (!$cat_id || !$brand_id || !$title || !$price) {
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

// Handle file upload
$image = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    
    if (!in_array($ext, $allowed)) {
        echo json_encode(['error' => 'Invalid file type']);
        exit;
    }
    
    if ($_FILES['image']['size'] > 5000000) { // 5MB limit
        echo json_encode(['error' => 'File too large']);
        exit;
    }
    
    $image = $_FILES['image'];
}

// Get user ID from session for folder structure
$user_id = $_SESSION['user_id'];

$result = add_product_ctr(
    $cat_id,
    $brand_id,
    $title,
    $price,
    $desc,
    $image,
    $keywords,
    $user_id
);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to add product']);
}