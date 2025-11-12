<?php
require_once dirname(__DIR__) . '/settings/db_class.php';

class Cart {
    private $db;

    public function __construct() {
        $this->db = new DB_Connection();
        $this->db = $this->db->db_conn();
    }

    private function getCartIdentifier() {
        if (isset($_SESSION['user_id'])) {
            return ['type' => 'user_id', 'value' => $_SESSION['user_id']];
        }
        $guest_ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        return ['type' => 'guest_ip', 'value' => $guest_ip];
    }

    public function addToCart($product_id, $quantity = 1) {
        try {
            $identifier = $this->getCartIdentifier();
            $quantity = max(1, intval($quantity));

            if ($identifier['type'] === 'user_id') {
                $sql = "SELECT cart_id, quantity FROM cart WHERE user_id = ? AND product_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("ii", $identifier['value'], $product_id);
            } else {
                $sql = "SELECT cart_id, quantity FROM cart WHERE guest_ip = ? AND product_id = ? AND user_id IS NULL";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("si", $identifier['value'], $product_id);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $existing = $result->fetch_assoc();

            if ($existing) {
                $new_quantity = $existing['quantity'] + $quantity;
                $sql = "UPDATE cart SET quantity = ? WHERE cart_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("ii", $new_quantity, $existing['cart_id']);
                return $stmt->execute();
            } else {
                if ($identifier['type'] === 'user_id') {
                    $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bind_param("iii", $identifier['value'], $product_id, $quantity);
                } else {
                    $sql = "INSERT INTO cart (guest_ip, product_id, quantity) VALUES (?, ?, ?)";
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

            if ($identifier['type'] === 'user_id') {
                $sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("iii", $quantity, $identifier['value'], $product_id);
            } else {
                $sql = "UPDATE cart SET quantity = ? WHERE guest_ip = ? AND product_id = ? AND user_id IS NULL";
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

            if ($identifier['type'] === 'user_id') {
                $sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("ii", $identifier['value'], $product_id);
            } else {
                $sql = "DELETE FROM cart WHERE guest_ip = ? AND product_id = ? AND user_id IS NULL";
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

            if ($identifier['type'] === 'user_id') {
                $sql = "SELECT c.cart_id, c.product_id, c.quantity, p.product_title, p.product_price, p.product_image FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = ? ORDER BY c.added_at DESC";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("i", $identifier['value']);
            } else {
                $sql = "SELECT c.cart_id, c.product_id, c.quantity, p.product_title, p.product_price, p.product_image FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.guest_ip = ? AND c.user_id IS NULL ORDER BY c.added_at DESC";
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

            if ($identifier['type'] === 'user_id') {
                $sql = "SELECT COUNT(*) as count FROM cart WHERE user_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("i", $identifier['value']);
            } else {
                $sql = "SELECT COUNT(*) as count FROM cart WHERE guest_ip = ? AND user_id IS NULL";
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

            if ($identifier['type'] === 'user_id') {
                $sql = "SELECT SUM(p.product_price * c.quantity) as total FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("i", $identifier['value']);
            } else {
                $sql = "SELECT SUM(p.product_price * c.quantity) as total FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.guest_ip = ? AND c.user_id IS NULL";
                $stmt = $this->db->prepare($sql);
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

            if ($identifier['type'] === 'user_id') {
                $sql = "DELETE FROM cart WHERE user_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("i", $identifier['value']);
            } else {
                $sql = "DELETE FROM cart WHERE guest_ip = ? AND user_id IS NULL";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("s", $identifier['value']);
            }
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function emptyCartByUserId($user_id) {
        try {
            $sql = "DELETE FROM cart WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $user_id);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
