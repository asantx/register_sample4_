<?php
require_once __DIR__ . '/../settings/db_class.php';

class Category extends db_connection {
    private $table = 'categories';

    public function addCategory($name, $user_id) {
        // ensure name unique for this user
        $stmt = $this->db->prepare("SELECT cat_id FROM categories WHERE cat_name = ? AND user_id = ? LIMIT 1");
        $stmt->bind_param("si", $name, $user_id);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            return false; // already exists
        }

        $stmt = $this->db->prepare("INSERT INTO categories (cat_name, user_id, date_created) VALUES (?, ?, NOW())");
        $stmt->bind_param("si", $name, $user_id);
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function getCategoriesByUser($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE user_id = ? ORDER BY cat_id DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = [];
        while ($r = $res->fetch_assoc()) $rows[] = $r;
        return $rows;
    }

    public function getCategoryById($cat_id, $user_id) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE cat_id = ? AND user_id = ? LIMIT 1");
        $stmt->bind_param("ii", $cat_id, $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateCategory($cat_id, $name, $user_id) {
        // check unique name for this user
        $stmt = $this->db->prepare("SELECT cat_id FROM categories WHERE cat_name = ? AND user_id = ? AND cat_id != ? LIMIT 1");
        $stmt->bind_param("sii", $name, $user_id, $cat_id);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            return false; // name conflict
        }

        $stmt = $this->db->prepare("UPDATE categories SET cat_name = ? WHERE cat_id = ? AND user_id = ?");
        $stmt->bind_param("sii", $name, $cat_id, $user_id);
        return $stmt->execute();
    }

    public function deleteCategory($cat_id, $user_id) {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE cat_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $cat_id, $user_id);
        return $stmt->execute();
    }
}
?>
