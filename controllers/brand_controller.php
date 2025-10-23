<?php
require_once __DIR__ . '/../classes/brand_class.php';

function add_brand_ctr($name) {
    $b = new Brand();
    return $b->addBrand($name);
}

function fetch_brands_ctr() {
    $b = new Brand();
    return $b->getAllBrands();
}

function update_brand_ctr($brand_id, $name) {
    $b = new Brand();
    return $b->updateBrand($brand_id, $name);
}

function delete_brand_ctr($brand_id) {
    $b = new Brand();
    return $b->deleteBrand($brand_id);
}
?>