<?php
require_once '../classes/product_class.php';

function add_product_ctr($cat_id, $brand_id, $title, $price, $desc, $image, $keywords, $user_id)
{
    $product = new Product();
    return $product->addProduct($cat_id, $brand_id, $title, $price, $desc, $image, $keywords, $user_id);
}

function update_product_ctr($product_id, $cat_id, $brand_id, $title, $price, $desc, $image, $keywords, $user_id)
{
    $product = new Product();
    return $product->updateProduct($product_id, $cat_id, $brand_id, $title, $price, $desc, $image, $keywords, $user_id);
}

function delete_product_ctr($product_id)
{
    $product = new Product();
    return $product->deleteProduct($product_id);
}

function get_all_products_ctr()
{
    $product = new Product();
    return $product->getAllProducts();
}

function get_product_ctr($product_id)
{
    $product = new Product();
    return $product->getProductById($product_id);
}

function search_products_ctr($keyword)
{
    $product = new Product();
    return $product->searchProducts($keyword);
}
