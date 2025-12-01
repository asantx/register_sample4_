<?php

require_once '../settings/db_class.php';

/**
 * Customer class for managing customer operations
 */
class Customer extends db_connection
{
    private $customer_id;
    private $name;
    private $email;
    private $password;
    private $country;
    private $city;
    private $role;
    private $date_created;
    private $phone_number;
    private $image;

    public function __construct($customer_id = null)
    {
        parent::db_connect();
        if ($customer_id) {
            $this->customer_id = $customer_id;
            $this->loadCustomer();
        }
    }

    private function loadCustomer($customer_id = null)
    {
        if ($customer_id) {
            $this->customer_id = $customer_id;
        }
        if (!$this->customer_id) {
            return false;
        }
        $stmt = $this->db->prepare("SELECT * FROM customer WHERE customer_id = ?");
        $stmt->bind_param("i", $this->customer_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result) {
            $this->name         = $result['customer_name'];
            $this->email        = $result['customer_email'];
            $this->country      = $result['customer_country'];
            $this->city         = $result['customer_city'];
            $this->role         = $result['user_role'];
            $this->date_created = $result['date_created'] ?? null;
            $this->phone_number = $result['customer_contact'];
            $this->image        = $result['customer_image'];
        }
    }

    public function createCustomer($name, $email, $password, $country, $city, $phone_number, $role, $image = null)
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("
            INSERT INTO customer 
                (customer_name, customer_email, customer_pass, customer_country, customer_city, customer_contact, customer_image, user_role) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sssssssi", $name, $email, $hashed_password, $country, $city, $phone_number, $image, $role);
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function editCustomer($customer_id, $name, $email, $country, $city, $phone_number, $image = null)
    {
        $stmt = $this->db->prepare("
            UPDATE customer 
            SET customer_name = ?, customer_email = ?, customer_country = ?, customer_city = ?, customer_contact = ?, customer_image = ? 
            WHERE customer_id = ?
        ");
        $stmt->bind_param("ssssssi", $name, $email, $country, $city, $phone_number, $image, $customer_id);
        return $stmt->execute();
    }

    public function deleteCustomer($customer_id)
    {
        $stmt = $this->db->prepare("DELETE FROM customer WHERE customer_id = ?");
        $stmt->bind_param("i", $customer_id);
        return $stmt->execute();
    }

    public function checkEmailExists($email)
    {
        $stmt = $this->db->prepare("SELECT customer_id FROM customer WHERE customer_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function getCustomerByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM customer WHERE customer_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function verifyCustomerLogin($email, $password)
    {
        $customer = $this->getCustomerByEmail($email);
        if ($customer) {
            $db_pass = $customer['customer_pass'];

            // Case 1: Hashed password
            if (password_verify($password, $db_pass)) {
                return $customer;
            }

            // Case 2: Plaintext password (legacy data)
            if ($password === $db_pass) {
                return $customer;
            }
        }
        return false;
    }

    /**
     * Activate premium subscription for a user
     *
     * @param int $user_id User ID
     * @param string $plan_type Subscription plan type (monthly, yearly)
     * @param float $amount Subscription amount
     * @param string $payment_reference Paystack payment reference
     * @return bool|array Returns subscription details on success, false on failure
     */
    public function activatePremiumSubscription($user_id, $plan_type, $amount, $payment_reference)
    {
        try {
            // Calculate subscription dates
            $start_date = date('Y-m-d H:i:s');
            $duration_days = ($plan_type === 'yearly') ? 365 : 30;
            $end_date = date('Y-m-d H:i:s', strtotime("+{$duration_days} days"));

            // Start transaction
            $this->db->begin_transaction();

            // 1. Create premium subscription record
            $sql = "INSERT INTO premium_subscriptions
                    (user_id, plan_type, amount, start_date, end_date, status, payment_reference, auto_renew)
                    VALUES (?, ?, ?, ?, ?, 'active', ?, FALSE)";

            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare subscription insert: " . $this->db->error);
            }

            $stmt->bind_param("isdsss", $user_id, $plan_type, $amount, $start_date, $end_date, $payment_reference);

            if (!$stmt->execute()) {
                throw new Exception("Failed to create subscription: " . $stmt->error);
            }

            $subscription_id = $this->db->insert_id;

            // 2. Update customer table to mark user as premium
            $update_sql = "UPDATE customer
                          SET is_premium = TRUE,
                              premium_expires_at = ?
                          WHERE customer_id = ?";

            $update_stmt = $this->db->prepare($update_sql);
            if (!$update_stmt) {
                throw new Exception("Failed to prepare customer update: " . $this->db->error);
            }

            $update_stmt->bind_param("si", $end_date, $user_id);

            if (!$update_stmt->execute()) {
                throw new Exception("Failed to update customer premium status: " . $update_stmt->error);
            }

            // Commit transaction
            $this->db->commit();

            error_log("Premium subscription activated: User ID {$user_id}, Subscription ID {$subscription_id}, Plan: {$plan_type}");

            return [
                'subscription_id' => $subscription_id,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'plan_type' => $plan_type,
                'status' => 'active'
            ];

        } catch (Exception $e) {
            // Rollback transaction on error
            $this->db->rollback();
            error_log("Premium subscription error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if a user has an active premium subscription
     *
     * @param int $user_id User ID
     * @return bool True if premium is active, false otherwise
     */
    public function isPremiumActive($user_id)
    {
        try {
            $sql = "SELECT is_premium, premium_expires_at
                    FROM customer
                    WHERE customer_id = ?";

            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $customer = $result->fetch_assoc();

            if (!$customer) {
                return false;
            }

            // Check if premium is active and not expired
            if ($customer['is_premium'] && $customer['premium_expires_at']) {
                $expiry_date = strtotime($customer['premium_expires_at']);
                $now = time();

                // If expired, update status
                if ($expiry_date < $now) {
                    $this->deactivatePremium($user_id);
                    return false;
                }

                return true;
            }

            return false;

        } catch (Exception $e) {
            error_log("Check premium status error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deactivate premium subscription
     *
     * @param int $user_id User ID
     * @return bool Success status
     */
    private function deactivatePremium($user_id)
    {
        try {
            $sql = "UPDATE customer
                    SET is_premium = FALSE
                    WHERE customer_id = ?";

            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $user_id);
            return $stmt->execute();

        } catch (Exception $e) {
            error_log("Deactivate premium error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get user's premium subscription details
     *
     * @param int $user_id User ID
     * @return array|false Subscription details or false
     */
    public function getPremiumSubscription($user_id)
    {
        try {
            $sql = "SELECT * FROM premium_subscriptions
                    WHERE user_id = ? AND status = 'active'
                    ORDER BY created_at DESC
                    LIMIT 1";

            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();

        } catch (Exception $e) {
            error_log("Get premium subscription error: " . $e->getMessage());
            return false;
        }
    }
}
