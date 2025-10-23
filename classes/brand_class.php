<?php
require_once __DIR__ . '/../settings/db_class.php';

class Brand extends db_connection {
    private $table = 'brands';

    public function __construct()
    {
        // ensure DB connection is initialized
        $this->db_conn();
    }

    public function addBrand($name) {
        // ensure name unique globally
        $stmt = $this->db->prepare("SELECT brand_id FROM brands WHERE brand_name = ? LIMIT 1");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            return false; // already exists
        }

        $stmt = $this->db->prepare("INSERT INTO brands (brand_name) VALUES (?)");
        $stmt->bind_param("s", $name);
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function getAllBrands() {
        $stmt = $this->db->prepare("SELECT * FROM brands ORDER BY brand_id DESC");
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = [];
        while ($r = $res->fetch_assoc()) $rows[] = $r;
        return $rows;
    }

    public function getBrandById($brand_id) {
        $stmt = $this->db->prepare("SELECT * FROM brands WHERE brand_id = ? LIMIT 1");
        $stmt->bind_param("i", $brand_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateBrand($brand_id, $name) {
        // check unique name globally (excluding this id)
        $stmt = $this->db->prepare("SELECT brand_id FROM brands WHERE brand_name = ? AND brand_id != ? LIMIT 1");
        $stmt->bind_param("si", $name, $brand_id);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            return false; // name conflict
        }

        $stmt = $this->db->prepare("UPDATE brands SET brand_name = ? WHERE brand_id = ?");
        $stmt->bind_param("si", $name, $brand_id);
        return $stmt->execute();
    }

    public function deleteBrand($brand_id) {
        $stmt = $this->db->prepare("DELETE FROM brands WHERE brand_id = ?");
        $stmt->bind_param("i", $brand_id);
        return $stmt->execute();
    }
}
?>