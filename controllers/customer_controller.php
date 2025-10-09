<?php
require_once __DIR__ . '/../classes/customer_class.php';

function register_customer_ctr($name, $email, $password, $country, $city, $phone_number, $role, $image = null) {
    $customer = new Customer();
    return $customer->createCustomer($name, $email, $password, $country, $city, $phone_number, $role, $image);
}

function check_email_ctr($email) {
    $customer = new Customer();
    return $customer->checkEmailExists($email);
}
?>
