<?php
require_once __DIR__ . '/../settings/db_class.php';

class Category extends db_connection {
    private $table = 'categories';

    public function addCategory($name) {
        // ensure name unique globally
        $stmt = $this->db->prepare("SELECT cat_id FROM categories WHERE cat_name = ? LIMIT 1");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            return false; // already exists
        }

        $stmt = $this->db->prepare("INSERT INTO categories (cat_name) VALUES (?)");
        $stmt->bind_param("s", $name);
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function getAllCategories() {
        $stmt = $this->db->prepare("SELECT * FROM categories ORDER BY cat_id DESC");
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = [];
        while ($r = $res->fetch_assoc()) $rows[] = $r;
        return $rows;
    }

    public function getCategoryById($cat_id) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE cat_id = ? LIMIT 1");
        $stmt->bind_param("i", $cat_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateCategory($cat_id, $name) {
        // check unique name globally (excluding this id)
        $stmt = $this->db->prepare("SELECT cat_id FROM categories WHERE cat_name = ? AND cat_id != ? LIMIT 1");
        $stmt->bind_param("si", $name, $cat_id);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            return false; // name conflict
        }

        $stmt = $this->db->prepare("UPDATE categories SET cat_name = ? WHERE cat_id = ?");
        $stmt->bind_param("si", $name, $cat_id);
        return $stmt->execute();
    }

    public function deleteCategory($cat_id) {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE cat_id = ?");
        $stmt->bind_param("i", $cat_id);
        return $stmt->execute();
    }
}
?>
