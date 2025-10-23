<?php
require_once __DIR__ . '/../classes/category_class.php';

function add_category_ctr($name) {
    $cat = new Category();
    return $cat->addCategory($name);
}

function fetch_categories_ctr() {
    $cat = new Category();
    return $cat->getAllCategories();
}

function update_category_ctr($cat_id, $name) {
    $cat = new Category();
    return $cat->updateCategory($cat_id, $name);
}

function delete_category_ctr($cat_id) {
    $cat = new Category();
    return $cat->deleteCategory($cat_id);
}
?>
