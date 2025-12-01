<?php
require_once '../settings/db_class.php';

class Order extends db_connection {

    public function __construct()
    {
        $this->db_conn();
    }

    private function generateOrderReference() {
        return 'ORD-' . time() . '-' . strtoupper(substr(md5(rand()), 0, 6));
    }

    private function generateTransactionId() {
        return 'TXN-' . time() . '-' . strtoupper(substr(md5(rand()), 0, 8));
    }

    public function createOrder($user_id, $total_amount, $customer_name, $customer_email, $shipping_address) {
        try {
            $order_reference = $this->generateOrderReference();
            $status = 'pending';

            $sql = "INSERT INTO orders (user_id, order_reference, total_amount, status, customer_name, customer_email, shipping_address) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("isdssss", $user_id, $order_reference, $total_amount, $status, $customer_name, $customer_email, $shipping_address);

            if ($stmt->execute()) {
                return [
                    'order_id' => $this->db->insert_id,
                    'order_reference' => $order_reference
                ];
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function addOrderDetail($order_id, $product_id, $product_name, $quantity, $unit_price) {
        try {
            $subtotal = $quantity * $unit_price;

            $sql = "INSERT INTO orderdetails (order_id, product_id, product_name, quantity, unit_price, subtotal) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("iisids", $order_id, $product_id, $product_name, $quantity, $unit_price, $subtotal);

            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function recordPayment($order_id, $user_id, $amount, $payment_method = 'online') {
        try {
            $transaction_id = $this->generateTransactionId();
            $payment_status = 'completed';

            $sql = "INSERT INTO payments (order_id, user_id, payment_method, amount, payment_status, transaction_id) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("iisdss", $order_id, $user_id, $payment_method, $amount, $payment_status, $transaction_id);

            if ($stmt->execute()) {
                return $transaction_id;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateOrderStatus($order_id, $status) {
        try {
            $sql = "UPDATE orders SET status = ? WHERE order_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("si", $status, $order_id);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function getOrder($order_id) {
        try {
            $sql = "SELECT * FROM orders WHERE order_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (Exception $e) {
            return false;
        }
    }

    public function getOrderDetails($order_id) {
        try {
            $sql = "SELECT od.*, p.product_image FROM orderdetails od LEFT JOIN products p ON od.product_id = p.product_id WHERE od.order_id = ? ORDER BY od.orderdetail_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $details = [];
            while ($row = $result->fetch_assoc()) {
                $details[] = $row;
            }
            return $details;
        } catch (Exception $e) {
            return [];
        }
    }

    public function getUserOrders($user_id) {
        try {
            $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $orders = [];
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }
            return $orders;
        } catch (Exception $e) {
            return [];
        }
    }

    public function getOrderPayment($order_id) {
        try {
            $sql = "SELECT * FROM payments WHERE order_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (Exception $e) {
            return false;
        }
    }

    // Counseling Session Methods
    public function createCounselingBooking($user_id, $counselor_name, $session_date, $session_time, $session_type, $cost, $session_notes = '') {
        try {
            $booking_reference = 'DL-' . date('Y') . '-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
            $status = 'confirmed';

            // Get user details
            $user_sql = "SELECT customer_name, customer_email FROM customer WHERE customer_id = ?";
            $user_stmt = $this->db->prepare($user_sql);

            if (!$user_stmt) {
                error_log("Counseling booking error: Failed to prepare user query - " . $this->db->error);
                return false;
            }

            $user_stmt->bind_param("i", $user_id);
            $user_stmt->execute();
            $user_result = $user_stmt->get_result();
            $user = $user_result->fetch_assoc();

            if (!$user) {
                error_log("Counseling booking error: User not found with ID: " . $user_id);
                return false;
            }

            $customer_name = $user['customer_name'];
            $customer_email = $user['customer_email'];

            $sql = "INSERT INTO orders (user_id, order_reference, total_amount, status, customer_name, customer_email, order_type, session_date, session_time, session_type, counselor_name, session_notes)
                    VALUES (?, ?, ?, ?, ?, ?, 'counseling', ?, ?, ?, ?, ?)";

            $stmt = $this->db->prepare($sql);

            if (!$stmt) {
                error_log("Counseling booking error: Failed to prepare insert query - " . $this->db->error);
                return false;
            }

            $stmt->bind_param("isdssssssss",
                $user_id,
                $booking_reference,
                $cost,
                $status,
                $customer_name,
                $customer_email,
                $session_date,
                $session_time,
                $session_type,
                $counselor_name,
                $session_notes
            );

            if ($stmt->execute()) {
                error_log("Counseling booking success: Order ID " . $this->db->insert_id . ", Reference: " . $booking_reference);
                return [
                    'order_id' => $this->db->insert_id,
                    'booking_reference' => $booking_reference
                ];
            } else {
                error_log("Counseling booking error: Execute failed - " . $stmt->error . " | SQL Error: " . $this->db->error);
                return false;
            }
        } catch (Exception $e) {
            error_log("Counseling booking exception: " . $e->getMessage() . " | Trace: " . $e->getTraceAsString());
            return false;
        }
    }

    public function getUserCounselingBookings($user_id) {
        try {
            $sql = "SELECT * FROM orders WHERE user_id = ? AND order_type = 'counseling' ORDER BY session_date DESC, session_time DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $bookings = [];
            while ($row = $result->fetch_assoc()) {
                $bookings[] = $row;
            }
            return $bookings;
        } catch (Exception $e) {
            error_log("Get bookings error: " . $e->getMessage());
            return [];
        }
    }

    public function getBookingByReference($reference) {
        try {
            $sql = "SELECT * FROM orders WHERE order_reference = ? LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("s", $reference);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Get booking by reference error: " . $e->getMessage());
            return null;
        }
    }
}
