<?php
require_once dirname(__DIR__) . '/classes/order_class.php';

function create_order_ctr($user_id, $total_amount, $customer_name, $customer_email, $shipping_address) {
    $order = new Order();
    return $order->createOrder($user_id, $total_amount, $customer_name, $customer_email, $shipping_address);
}

function add_order_detail_ctr($order_id, $product_id, $product_name, $quantity, $unit_price) {
    $order = new Order();
    return $order->addOrderDetail($order_id, $product_id, $product_name, $quantity, $unit_price);
}

function record_payment_ctr($order_id, $user_id, $amount, $payment_method = 'online') {
    $order = new Order();
    return $order->recordPayment($order_id, $user_id, $amount, $payment_method);
}

function update_order_status_ctr($order_id, $status) {
    $order = new Order();
    return $order->updateOrderStatus($order_id, $status);
}

function get_order_ctr($order_id) {
    $order = new Order();
    return $order->getOrder($order_id);
}

function get_order_details_ctr($order_id) {
    $order = new Order();
    return $order->getOrderDetails($order_id);
}

function get_user_orders_ctr($user_id) {
    $order = new Order();
    return $order->getUserOrders($user_id);
}

function get_order_payment_ctr($order_id) {
    $order = new Order();
    return $order->getOrderPayment($order_id);
}
