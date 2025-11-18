<?php
require_once '../settings/db_class.php';

class Cart extends db_connection {
    public function __construct() {
        $this->db_connect();
    }

    private function getCartIdentifier() {
        if (isset($_SESSION['user_id'])) {
            return ['type' => 'c_id', 'value' => $_SESSION['user_id']];
        }
        $ip_add = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        return ['type' => 'ip_add', 'value' => $ip_add];
    }

    public function addToCart($product_id, $quantity = 1) {
        try {
            $identifier = $this->getCartIdentifier();
            $quantity = max(1, intval($quantity));

            if ($identifier['type'] === 'c_id') {
                $sql = "SELECT qty FROM cart WHERE c_id = ? AND p_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("ii", $identifier['value'], $product_id);
            } else {
                $sql = "SELECT qty FROM cart WHERE ip_add = ? AND p_id = ? AND c_id IS NULL";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("si", $identifier['value'], $product_id);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $existing = $result->fetch_assoc();

            if ($existing) {
                $new_quantity = $existing['qty'] + $quantity;
                if ($identifier['type'] === 'c_id') {
                    $sql = "UPDATE cart SET qty = ? WHERE c_id = ? AND p_id = ?";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bind_param("iii", $new_quantity, $identifier['value'], $product_id);
                } else {
                    $sql = "UPDATE cart SET qty = ? WHERE ip_add = ? AND p_id = ? AND c_id IS NULL";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bind_param("isi", $new_quantity, $identifier['value'], $product_id);
                }
                return $stmt->execute();
            } else {
                if ($identifier['type'] === 'c_id') {
                    $sql = "INSERT INTO cart (c_id, p_id, qty) VALUES (?, ?, ?)";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bind_param("iii", $identifier['value'], $product_id, $quantity);
                } else {
                    $sql = "INSERT INTO cart (ip_add, p_id, qty) VALUES (?, ?, ?)";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bind_param("sii", $identifier['value'], $product_id, $quantity);
                }
                return $stmt->execute();
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateQuantity($product_id, $quantity) {
        try {
            $identifier = $this->getCartIdentifier();
            $quantity = max(1, intval($quantity));

            if ($identifier['type'] === 'c_id') {
                $sql = "UPDATE cart SET qty = ? WHERE c_id = ? AND p_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("iii", $quantity, $identifier['value'], $product_id);
            } else {
                $sql = "UPDATE cart SET qty = ? WHERE ip_add = ? AND p_id = ? AND c_id IS NULL";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("isi", $quantity, $identifier['value'], $product_id);
            }
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function removeFromCart($product_id) {
        try {
            $identifier = $this->getCartIdentifier();

            if ($identifier['type'] === 'c_id') {
                $sql = "DELETE FROM cart WHERE c_id = ? AND p_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("ii", $identifier['value'], $product_id);
            } else {
                $sql = "DELETE FROM cart WHERE ip_add = ? AND p_id = ? AND c_id IS NULL";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("si", $identifier['value'], $product_id);
            }
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function getCartItems() {
        try {
            $identifier = $this->getCartIdentifier();

            if ($identifier['type'] === 'c_id') {
                $sql = "SELECT c.p_id as product_id, c.qty as quantity, p.product_title, p.product_price, p.product_image FROM cart c JOIN products p ON c.p_id = p.product_id WHERE c.c_id = ? ORDER BY p.product_title ASC";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("i", $identifier['value']);
            } else {
                $sql = "SELECT c.p_id as product_id, c.qty as quantity, p.product_title, p.product_price, p.product_image FROM cart c JOIN products p ON c.p_id = p.product_id WHERE c.ip_add = ? AND c.c_id IS NULL ORDER BY p.product_title ASC";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("s", $identifier['value']);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $items = [];
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
            return $items;
        } catch (Exception $e) {
            return [];
        }
    }

    public function getCartCount() {
        try {
            $identifier = $this->getCartIdentifier();

            if ($identifier['type'] === 'c_id') {
                $sql = "SELECT COUNT(*) as count FROM cart WHERE c_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("i", $identifier['value']);
            } else {
                $sql = "SELECT COUNT(*) as count FROM cart WHERE ip_add = ? AND c_id IS NULL";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("s", $identifier['value']);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['count'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function getCartTotal() {
        try {
            $identifier = $this->getCartIdentifier();

            if ($identifier['type'] === 'c_id') {
                $sql = "SELECT SUM(p.product_price * c.qty) as total FROM cart c JOIN products p ON c.p_id = p.product_id WHERE c.c_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("i", $identifier['value']);
            } else {
                $sql = "SELECT SUM(p.product_price * c.qty) as total FROM cart c JOIN products p ON c.p_id = p.product_id WHERE c.ip_add = ? AND c.c_id IS NULL";
                $stmt = $this->db->db->prepare($sql);
                $stmt->bind_param("s", $identifier['value']);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return floatval($row['total'] ?? 0);
        } catch (Exception $e) {
            return 0;
        }
    }

    public function emptyCart() {
        try {
            $identifier = $this->getCartIdentifier();

            if ($identifier['type'] === 'c_id') {
                $sql = "DELETE FROM cart WHERE c_id = ?";
                $stmt = $this->db->db->prepare($sql);
                $stmt->bind_param("i", $identifier['value']);
            } else {
                $sql = "DELETE FROM cart WHERE ip_add = ? AND c_id IS NULL";
                $stmt = $this->db->db->prepare($sql);
                $stmt->bind_param("s", $identifier['value']);
            }
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function emptyCartByUserId($user_id) {
        try {
            $sql = "DELETE FROM cart WHERE c_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $user_id);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
