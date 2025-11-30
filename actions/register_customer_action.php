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

$name = $_POST['customer_name'] ?? '';
$email = $_POST['customer_email'] ?? '';
$password = $_POST['customer_pass'] ?? '';
$country = $_POST['customer_country'] ?? '';
$city = $_POST['customer_city'] ?? '';
$phone_number = $_POST['customer_contact'] ?? '';
$account_type = $_POST['account_type'] ?? 'individual';
$admin_code = $_POST['admin_code'] ?? '';
$image = null; // Image is null by default

// Partner information for couple accounts
$partner_name = $_POST['partner_name'] ?? '';
$partner_email = $_POST['partner_email'] ?? '';
$partner_country = $_POST['partner_country'] ?? '';
$partner_city = $_POST['partner_city'] ?? '';

// Determine user role based on admin code
$ADMIN_SECRET_CODE = 'DISTANTLOVE2025ADMIN';
$role = ($admin_code === $ADMIN_SECRET_CODE) ? '1' : '2'; // 1 = admin, 2 = customer

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

// Validate couple account information
if ($account_type === 'couple') {
    $err = validate_input("Partner's name", $partner_name, 100, '/^[a-zA-Z\s]{1,100}$/');
    if ($err) $errors[] = $err;
    $err = validate_input("Partner's email", $partner_email, 50, '/^[^\s@]+@[^\s@]+\.[^\s@]+$/');
    if ($err) $errors[] = $err;
    $err = validate_input("Partner's country", $partner_country, 30, '/^[a-zA-Z\s]{1,30}$/');
    if ($err) $errors[] = $err;
    $err = validate_input("Partner's city", $partner_city, 30, '/^[a-zA-Z\s]{1,30}$/');
    if ($err) $errors[] = $err;
}

if (!in_array($role, ['1', '2'], true)) {
    $errors[] = 'Invalid user role.';
}

if (!empty($errors)) {
    $response['status'] = 'error';
    $response['message'] = implode(' ', $errors);
    echo json_encode($response);
    exit();
}

// Special validation for admin registration
if ($role === '1') {
    // Only allow admin registration with the exact secret code
    if ($admin_code !== $ADMIN_SECRET_CODE) {
        $response['status'] = 'error';
        $response['message'] = 'Invalid admin credentials';
        echo json_encode($response);
        exit();
    }

    // Only allow specific admin email (you can customize this)
    $allowed_admin_emails = ['admin@distantlove.com', 'superadmin@distantlove.com'];
    if (!in_array($email, $allowed_admin_emails)) {
        // For security, create admin accounts with any email if they have the secret code
        // You can comment this section if you want to restrict to specific emails only
    }
}

// Check if email already exists
if (check_email_ctr($email)) {
    $response['status'] = 'error';
    $response['message'] = 'Email already exists';
    echo json_encode($response);
    exit();
}

// Check if partner email exists (for couple accounts)
if ($account_type === 'couple' && !empty($partner_email)) {
    if (check_email_ctr($partner_email)) {
        $response['status'] = 'error';
        $response['message'] = 'Partner email already exists';
        echo json_encode($response);
        exit();
    }
}

// Register main customer
$customer_id = register_customer_ctr($name, $email, $password, $country, $city, $phone_number, $role, $image);

if ($customer_id) {
    // Automatically log in the user after successful registration
    $_SESSION['user_id'] = $customer_id;
    $_SESSION['user_role'] = $role;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;

    // If couple account, create partner account and link them
    if ($account_type === 'couple') {
        // Create partner account with role 2 (customer)
        $partner_id = register_customer_ctr($partner_name, $partner_email, $password, $partner_country, $partner_city, '', '2', $image);

        if ($partner_id) {
            // TODO: Link the couple accounts in a couples table (you may need to create this table)
            $response['status'] = 'success';
            $response['message'] = 'Couple account registered successfully';
            $response['customer_id'] = $customer_id;
            $response['partner_id'] = $partner_id;
            $response['account_type'] = 'couple';
            $response['redirect'] = $role === '1' ? '../admin/dashboard.php' : '../views/shop.php';
        } else {
            // If partner registration fails, you might want to delete the main account
            // For now, we'll just return success for the main account
            $response['status'] = 'success';
            $response['message'] = 'Account registered, but partner account creation failed';
            $response['customer_id'] = $customer_id;
            $response['redirect'] = $role === '1' ? '../admin/dashboard.php' : '../views/shop.php';
        }
    } else {
        $response['status'] = 'success';
        $response['message'] = $role === '1' ? 'Admin account registered successfully' : 'Registered successfully';
        $response['customer_id'] = $customer_id;
        $response['account_type'] = $role === '1' ? 'admin' : 'individual';
        $response['redirect'] = $role === '1' ? '../admin/dashboard.php' : '../views/shop.php';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Failed to register';
}

echo json_encode($response);
