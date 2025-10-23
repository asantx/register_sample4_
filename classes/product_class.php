<?php
require_once dirname(__DIR__) . '/settings/db_class.php';

class Product {
    private $db;

    public function __construct() {
        $this->db = new DB_Connection();
        $this->db = $this->db->db_conn();
    }

    private function ensureUploadPath($user_id, $product_id) {
        $base_path = dirname(__DIR__) . '/uploads';
        $user_path = $base_path . '/' . $user_id;
        $product_path = $user_path . '/' . $product_id;
        
        if (!file_exists($user_path)) {
            mkdir($user_path, 0755, true);
        }
        if (!file_exists($product_path)) {
            mkdir($product_path, 0755, true);
        }
        return $product_path;
    }

    private function processKeywords($keywords) {
        // Convert comma-separated keywords to normalized space-separated string
        // This format works well with FULLTEXT search
        if (is_array($keywords)) {
            $keywords = implode(' ', $keywords);
        }
        // Remove extra spaces, commas and normalize
        $keywords = preg_replace('/[,\s]+/', ' ', trim($keywords));
        return strtolower($keywords);
    }

    public function addProduct($cat_id, $brand_id, $title, $price, $desc, $image, $keywords, $user_id) {
        try {
            // First insert the product to get the ID
            $sql = "INSERT INTO products (product_cat, product_brand, product_title, product_price, product_desc, product_keywords) 
                   VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            
            // Process keywords for efficient search
            $processed_keywords = $this->processKeywords($keywords);
            
            $stmt->bind_param("iisdss", $cat_id, $brand_id, $title, $price, $desc, $processed_keywords);
            $stmt->execute();
            
            $product_id = $this->db->insert_id;
            
            // Handle image upload if provided
            if ($image && $image['error'] == 0) {
                $upload_path = $this->ensureUploadPath($user_id, $product_id);
                $file_ext = pathinfo($image['name'], PATHINFO_EXTENSION);
                $filename = 'product_' . $product_id . '.' . $file_ext;
                $file_path = $upload_path . '/' . $filename;
                
                if (move_uploaded_file($image['tmp_name'], $file_path)) {
                    // Update the product with the image path
                    $relative_path = 'uploads/' . $user_id . '/' . $product_id . '/' . $filename;
                    $sql = "UPDATE products SET product_image = ? WHERE product_id = ?";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bind_param("si", $relative_path, $product_id);
                    $stmt->execute();
                }
            }
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateProduct($product_id, $cat_id, $brand_id, $title, $price, $desc, $image, $keywords, $user_id) {
        try {
            $sql = "UPDATE products SET product_cat = ?, product_brand = ?, product_title = ?, 
                   product_price = ?, product_desc = ?, product_keywords = ? WHERE product_id = ?";
            $stmt = $this->db->prepare($sql);
            
            // Process keywords for efficient search
            $processed_keywords = $this->processKeywords($keywords);
            
            $stmt->bind_param("iisdssi", $cat_id, $brand_id, $title, $price, $desc, $processed_keywords, $product_id);
            $stmt->execute();
            
            // Handle image upload if provided
            if ($image && $image['error'] == 0) {
                $upload_path = $this->ensureUploadPath($user_id, $product_id);
                $file_ext = pathinfo($image['name'], PATHINFO_EXTENSION);
                $filename = 'product_' . $product_id . '.' . $file_ext;
                $file_path = $upload_path . '/' . $filename;
                
                if (move_uploaded_file($image['tmp_name'], $file_path)) {
                    // Update the product with the new image path
                    $relative_path = 'uploads/' . $user_id . '/' . $product_id . '/' . $filename;
                    $sql = "UPDATE products SET product_image = ? WHERE product_id = ?";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bind_param("si", $relative_path, $product_id);
                    $stmt->execute();
                }
            }
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function deleteProduct($product_id) {
        try {
            // Get the image path before deleting
            $sql = "SELECT product_image FROM products WHERE product_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();
            
            // Delete the product
            $sql = "DELETE FROM products WHERE product_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $product_id);
            $success = $stmt->execute();
            
            // If deletion successful and image exists, delete the image
            if ($success && $product && $product['product_image']) {
                $image_path = dirname(__DIR__) . '/' . $product['product_image'];
                if (file_exists($image_path)) {
                    unlink($image_path);
                    // Try to remove product directory if empty
                    $product_dir = dirname($image_path);
                    if (is_dir($product_dir) && count(scandir($product_dir)) <= 2) {
                        rmdir($product_dir);
                    }
                }
            }
            
            return $success;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getAllProducts() {
        try {
            $sql = "SELECT p.*, c.cat_name, b.brand_name 
                   FROM products p 
                   LEFT JOIN categories c ON p.product_cat = c.cat_id 
                   LEFT JOIN brands b ON p.product_brand = b.brand_id 
                   ORDER BY p.product_id DESC";
            $result = $this->db->query($sql);
            
            $products = array();
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
            return $products;
        } catch (Exception $e) {
            return array();
        }
    }

    public function getProductById($product_id) {
        try {
            $sql = "SELECT p.*, c.cat_name, b.brand_name 
                   FROM products p 
                   LEFT JOIN categories c ON p.product_cat = c.cat_id 
                   LEFT JOIN brands b ON p.product_brand = b.brand_id 
                   WHERE p.product_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (Exception $e) {
            return null;
        }
    }

    public function searchProducts($keyword) {
        try {
            // Using FULLTEXT search on the keywords field
            $sql = "SELECT p.*, c.cat_name, b.brand_name,
                   MATCH(p.product_keywords) AGAINST(?) as relevance
                   FROM products p 
                   LEFT JOIN categories c ON p.product_cat = c.cat_id 
                   LEFT JOIN brands b ON p.product_brand = b.brand_id 
                   WHERE MATCH(p.product_keywords) AGAINST(?)
                   ORDER BY relevance DESC";
            
            $stmt = $this->db->prepare($sql);
            $keyword = strtolower($keyword); // normalize search term
            $stmt->bind_param("ss", $keyword, $keyword);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $products = array();
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
            return $products;
        } catch (Exception $e) {
            return array();
        }
    }
}