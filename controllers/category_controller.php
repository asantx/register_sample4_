<?php
require_once __DIR__ . '/../classes/category_class.php';

function add_category_ctr($name, $user_id) {
    $cat = new Category();
    return $cat->addCategory($name, $user_id);
}

function fetch_categories_ctr($user_id) {
    $cat = new Category();
    return $cat->getCategoriesByUser($user_id);
}

function update_category_ctr($cat_id, $name, $user_id) {
    $cat = new Category();
    return $cat->updateCategory($cat_id, $name, $user_id);
}

function delete_category_ctr($cat_id, $user_id) {
    $cat = new Category();
    return $cat->deleteCategory($cat_id, $user_id);
}
?>
