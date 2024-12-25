<?php // require_once 'db_init.php';  

class DB {  
    // User Management Methods  
    public static function getUserByEmail($email) {  
        $db = DBInit::getInstance();  
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email AND is_active = 1");  
        $stmt->bindParam(":email", $email);  
        $stmt->execute();  
        return $stmt->fetch();  
    }  

    public static function createUser($data) {  
        $db = DBInit::getInstance();  
        $stmt = $db->prepare("INSERT INTO users (first_name, last_name, email, password, role,   
                             address, postal_code, city, is_active)   
                             VALUES (:first_name, :last_name, :email, :password, :role,  
                             :address, :postal_code, :city, 1)");  
        
        return $stmt->execute([  
            ':first_name' => $data['first_name'],  
            ':last_name' => $data['last_name'],  
            ':email' => $data['email'],  
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),  
            ':role' => $data['role'],  
            ':address' => $data['address'] ?? null,  
            ':postal_code' => $data['postal_code'] ?? null,  
            ':city' => $data['city'] ?? null  
        ]);  
    }  

    public static function updateUser($id, $data) {  
        $db = DBInit::getInstance();  
        $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name,   
                email = :email";  
        
        if (isset($data['password']) && !empty($data['password'])) {  
            $sql .= ", password = :password";  
        }  
        
        $sql .= " WHERE id = :id";  
        $stmt = $db->prepare($sql);  
        
        $params = [  
            ':id' => $id,  
            ':first_name' => $data['first_name'],  
            ':last_name' => $data['last_name'],  
            ':email' => $data['email']  
        ];  
        
        if (isset($data['password']) && !empty($data['password'])) {  
            $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);  
        }  
        
        return $stmt->execute($params);  
    }  

    // Product Management Methods  
    public static function getAllProducts($active_only = true) {  
        $db = DBInit::getInstance();  
        $sql = "SELECT * FROM products";  
        if ($active_only) {  
            $sql .= " WHERE is_active = 1";  
        }  
        $stmt = $db->prepare($sql);  
        $stmt->execute();  
        return $stmt->fetchAll();  
    }  

    public static function getProduct($id) {  
        $db = DBInit::getInstance();  
        $stmt = $db->prepare("SELECT * FROM products WHERE id = :id");  
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);  
        $stmt->execute();  
        return $stmt->fetch();  
    }  

    public static function createProduct($data) {  
        $db = DBInit::getInstance();  
        $stmt = $db->prepare("INSERT INTO products (name, description, price, is_active)   
                             VALUES (:name, :description, :price, 1)");  
        return $stmt->execute([  
            ':name' => $data['name'],  
            ':description' => $data['description'],  
            ':price' => $data['price']  
        ]);  
    }  

    public static function updateProduct($id, $data) {  
        $db = DBInit::getInstance();  
        $stmt = $db->prepare("UPDATE products SET name = :name, description = :description,   
                             price = :price WHERE id = :id");  
        return $stmt->execute([  
            ':id' => $id,  
            ':name' => $data['name'],  
            ':description' => $data['description'],  
            ':price' => $data['price']  
        ]);  
    }  

    // Order Management Methods  
    public static function createOrder($user_id, $items) {  
        try {  
            $db = DBInit::getInstance();  
            $db->beginTransaction();  

            // Create order  
            $stmt = $db->prepare("INSERT INTO orders (user_id, status, created_at)   
                                 VALUES (:user_id, 'pending', NOW())");  
            $stmt->execute([':user_id' => $user_id]);  
            $order_id = $db->lastInsertId();  

            // Add order items  
            $stmt = $db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price)   
                                 VALUES (:order_id, :product_id, :quantity, :price)");  
            
            foreach ($items as $item) {  
                $stmt->execute([  
                    ':order_id' => $order_id,  
                    ':product_id' => $item['product_id'],  
                    ':quantity' => $item['quantity'],  
                    ':price' => $item['price']  
                ]);  
            }  

            $db->commit();  
            return $order_id;  
        } catch (Exception $e) {  
            $db->rollBack();  
            throw $e;  
        }  
    }  

    public static function updateOrderStatus($order_id, $status) {  
        $db = DBInit::getInstance();  
        $stmt = $db->prepare("UPDATE orders SET status = :status WHERE id = :id");  
        return $stmt->execute([  
            ':id' => $order_id,  
            ':status' => $status  
        ]);  
    }  

    public static function getOrdersByUser($user_id) {  
        $db = DBInit::getInstance();  
        $stmt = $db->prepare("SELECT o.*, oi.* FROM orders o   
                             JOIN order_items oi ON o.id = oi.order_id   
                             WHERE o.user_id = :user_id");  
        $stmt->execute([':user_id' => $user_id]);  
        return $stmt->fetchAll();  
    }  

    public static function getPendingOrders() {  
        $db = DBInit::getInstance();  
        $stmt = $db->prepare("SELECT o.*, u.first_name, u.last_name FROM orders o   
                             JOIN users u ON o.user_id = u.id   
                             WHERE o.status = 'pending'");  
        $stmt->execute();  
        return $stmt->fetchAll();  
    }  
}  
?>  