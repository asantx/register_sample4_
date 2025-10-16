<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../settings/core.php';

$resp = ['logged_in' => false, 'user_name' => null, 'user_role' => null];
if (isUserLoggedIn()) {
    // Prefer standardized session keys set during login
    $resp['logged_in'] = true;
    $resp['user_name'] = $_SESSION['user_name'] ?? ($_SESSION['user']['name'] ?? null);
    $resp['user_role'] = $_SESSION['user_role'] ?? ($_SESSION['user']['role'] ?? null);
}
echo json_encode($resp);
