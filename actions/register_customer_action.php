<?php

header('Content-Type: application/json');

session_start();

$response = array();

// TODO: Check if the user is already logged in and redirect to the dashboard
if (isset($_SESSION['user_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'You are already logged in';
    echo json_encode($response);
    exit();
}

require_once '../controllers/customer_controller.php';

// Helper function for validation
function validate_input($name, $value, $max_length, $pattern = null) {
    if (!isset($value) || trim($value) === '') {
        return "$name is required.";
    }
    if (mb_strlen($value) > $max_length) {
        return "$name must not exceed $max_length characters.";
    }
    if ($pattern && !preg_match($pattern, $value)) {
        return "Invalid $name format.";
    }
    return null;
}

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$country = $_POST['country'] ?? '';
$city = $_POST['city'] ?? '';
$phone_number = $_POST['phone_number'] ?? '';
$role = $_POST['role'] ?? '';
$image = null; // Image is null by default

// Validate fields (adjust max lengths to match DB schema)
$errors = [];
$err = validate_input('Full name', $name, 100, '/^[a-zA-Z\s]{1,100}$/');
if ($err) $errors[] = $err;
$err = validate_input('Email', $email, 50, '/^[^\s@]+@[^\s@]+\.[^\s@]+$/');
if ($err) $errors[] = $err;
$err = validate_input('Password', $password, 255, '/^.{6,}$/'); // 6+ chars
if ($err) $errors[] = $err;
$err = validate_input('Country', $country, 30, '/^[a-zA-Z\s]{1,30}$/');
if ($err) $errors[] = $err;
$err = validate_input('City', $city, 30, '/^[a-zA-Z\s]{1,30}$/');
if ($err) $errors[] = $err;
$err = validate_input('Contact Number', $phone_number, 15, '/^\+?[\d\s\-\(\)]{7,15}$/');
if ($err) $errors[] = $err;
if (!in_array($role, ['1', '2'], true)) {
    $errors[] = 'Invalid user role.';
}

if (!empty($errors)) {
    $response['status'] = 'error';
    $response['message'] = implode(' ', $errors);
    echo json_encode($response);
    exit();
}

// Check if email already exists
if (check_email_ctr($email)) {
    $response['status'] = 'error';
    $response['message'] = 'Email already exists';
    echo json_encode($response);
    exit();
}

$customer_id = register_customer_ctr($name, $email, $password, $country, $city, $phone_number, $role, $image);

if ($customer_id) {
    $response['status'] = 'success';
    $response['message'] = 'Registered successfully';
    $response['customer_id'] = $customer_id;
} else {
    $response['status'] = 'error';
    $response['message'] = 'Failed to register';
}

echo json_encode($response);
