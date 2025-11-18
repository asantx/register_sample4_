<?php
require_once '../classes/cart_class.php';

function add_to_cart_ctr($product_id, $quantity = 1)
{
    $cart = new Cart();
    return $cart->addToCart($product_id, $quantity);
}

function remove_from_cart_ctr($product_id)
{
    $cart = new Cart();
    return $cart->removeFromCart($product_id);
}

function update_quantity_ctr($product_id, $quantity)
{
    $cart = new Cart();
    return $cart->updateQuantity($product_id, $quantity);
}

function get_cart_items_ctr()
{
    $cart = new Cart();
    return $cart->getCartItems();
}

function get_cart_count_ctr()
{
    $cart = new Cart();
    return $cart->getCartCount();
}

function get_cart_total_ctr()
{
    $cart = new Cart();
    return $cart->getCartTotal();
}

function empty_cart_ctr()
{
    $cart = new Cart();
    return $cart->emptyCart();
}

function empty_cart_by_user_ctr($user_id)
{
    $cart = new Cart();
    return $cart->emptyCartByUserId($user_id);
}
