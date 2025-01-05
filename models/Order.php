<?php

require_once 'models/DBInit.php';

class Order {

    private $db;

    public function __construct() {
        $this->db = DBInit::getInstance();
    }

    public function getById($id) {
        $sql = "SELECT * FROM orders WHERE id = :id";
        $statement = $this->db->prepare($sql);
        $statement->execute([':id' => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrderItems($orderId) {
        $sql = "SELECT oi.*, p.name   
                FROM order_items oi   
                JOIN products p ON oi.product_id = p.id   
                WHERE oi.order_id = :order_id";
        $statement = $this->db->prepare($sql);
        $statement->execute([':order_id' => $orderId]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($userId, $items, $totalAmount, $shippingDetails) {
        try {
            $this->db->beginTransaction();

            // Create order with shipping details  
            $sql = "INSERT INTO orders   
                    (user_id, status, total_amount, shipping_address, postal_code, city, phone, notes)   
                    VALUES   
                    (:user_id, 'pending', :total_amount, :shipping_address, :postal_code, :city, :phone, :notes)";

            $statement = $this->db->prepare($sql);
            $statement->execute([
                ':user_id' => $userId,
                ':total_amount' => $totalAmount,
                ':shipping_address' => $shippingDetails['shipping_address'] ?? null,
                ':postal_code' => $shippingDetails['postal_code'] ?? null,
                ':city' => $shippingDetails['city'] ?? null,
                ':phone' => $shippingDetails['phone'] ?? null,
                ':notes' => $shippingDetails['notes'] ?? null
            ]);

            $orderId = $this->db->lastInsertId();

            // Create order items  
            $statement = $this->db->prepare("INSERT INTO order_items   
                (order_id, product_id, quantity, price)   
                VALUES (:order_id, :product_id, :quantity, :price)");

            foreach ($items as $item) {
                $statement->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $item['product_id'],
                    ':quantity' => $item['quantity'],
                    ':price' => $item['price']
                ]);
            }

            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function updateStatus($orderId, $status) {
        $statement = $this->db->prepare("UPDATE orders SET status = :status WHERE id = :id");
        return $statement->execute([':id' => $orderId, ':status' => $status]);
    }

    public function getByUser($userId) {
        // First, get the basic order information  
        $sql = "SELECT o.*  
            FROM orders o  
            WHERE o.user_id = :user_id  
            ORDER BY o.created_at DESC";

        $statement = $this->db->prepare($sql);
        $statement->execute([':user_id' => $userId]);
        $orders = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Then, for each order, get its items  
        foreach ($orders as &$order) {
            $sql = "SELECT oi.*, p.name as product_name  
                FROM order_items oi  
                JOIN products p ON oi.product_id = p.id  
                WHERE oi.order_id = :order_id";

            $statement = $this->db->prepare($sql);
            $statement->execute([':order_id' => $order['id']]);
            $order['items'] = $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        return $orders;
    }

    public function getPending() {
        $sql = "SELECT o.*, u.first_name, u.last_name  
                FROM orders o  
                JOIN users u ON o.user_id = u.id  
                WHERE o.status = 'pending'  
                ORDER BY o.created_at DESC";

        $statement = $this->db->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // New method to get all orders (useful for admin)  
    public function getAll() {
        $sql = "SELECT o.*, u.first_name, u.last_name  
                FROM orders o  
                JOIN users u ON o.user_id = u.id  
                ORDER BY o.created_at DESC";

        $statement = $this->db->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get count of pending orders  
     * @return int Number of pending orders  
     */
    public function getPendingCount() {
        try {
            $sql = "SELECT COUNT(*) as count   
                    FROM orders   
                    WHERE status = 'pending'";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['count'];
        } catch (PDOException $e) {
            // Log error  
            error_log("Error getting pending orders count: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get count of confirmed orders  
     * @return int Number of confirmed orders  
     */
    public function getConfirmedCount() {
        try {
            $sql = "SELECT COUNT(*) as count   
                    FROM orders   
                    WHERE status = 'confirmed'";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['count'];
        } catch (PDOException $e) {
            // Log error  
            error_log("Error getting confirmed orders count: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get recent orders with limit  
     * @param int $limit Number of orders to return  
     * @return array Recent orders  
     */
    public function getRecent($limit = 5) {
        try {
            $sql = "SELECT o.*, u.first_name, u.last_name   
                    FROM orders o   
                    JOIN users u ON o.user_id = u.id   
                    ORDER BY o.created_at DESC   
                    LIMIT :limit";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log error  
            error_log("Error getting recent orders: " . $e->getMessage());
            return [];
        }
    }

    // New method to update shipping details  
    public function updateShippingDetails($orderId, $shippingDetails) {
        $sql = "UPDATE orders   
                SET shipping_address = :shipping_address,  
                    postal_code = :postal_code,  
                    city = :city,  
                    phone = :phone,  
                    notes = :notes  
                WHERE id = :id";

        $statement = $this->db->prepare($sql);
        return $statement->execute([
                    ':id' => $orderId,
                    ':shipping_address' => $shippingDetails['shipping_address'] ?? null,
                    ':postal_code' => $shippingDetails['postal_code'] ?? null,
                    ':city' => $shippingDetails['city'] ?? null,
                    ':phone' => $shippingDetails['phone'] ?? null,
                    ':notes' => $shippingDetails['notes'] ?? null
        ]);
    }
}
