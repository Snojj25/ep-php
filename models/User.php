<?php

require_once 'models/DBInit.php';

class User {

    private $db;

    public function __construct() {
        $this->db = DBInit::getInstance();
    }

    public function getById($id) {
        $statement = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $statement->bindParam(":id", $id, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }

    public function getAllByRole($role) {
        $sql = "SELECT * FROM users WHERE role = :role";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['role' => $role]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() > 0;
    }

    public function create($data) {
        $sql = "INSERT INTO users (first_name, last_name, email, password, role, address, postal_code, city, is_active)   
            VALUES (:first_name, :last_name, :email, :password, :role, :address, :postal_code, :city,  1)";
        $stmt = $this->db->prepare($sql);

        // Hash the password  
        $hashedPass = password_hash($data['password'], PASSWORD_DEFAULT);

        $result = $stmt->execute([
            ':first_name' => $data['first_name'] ?? '',
            ':last_name' => $data['last_name'] ?? '',
            ':email' => $data['email'],
            ':password' => $hashedPass,
            ':role' => $data['role'],
            ':address' => $data['address'] ?? '',
            ':postal_code' => $data['postal_code'] ?? '',
            ':city' => $data['city'],
        ]);

        if ($result) {
            return true;
        }
        return false;
    }

    public function updateUser($id, $data) {
        // Start with base query  
        $sql = "UPDATE users SET";
        $params = [':id' => $id];

        // Build the SET clause dynamically based on provided data  
        $setParts = [];
        $allowedFields = ['first_name', 'last_name', 'email', 'address', 'postal_code', 'city'];

        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $data)) {
                $setParts[] = " $field = :$field";
                $params[":$field"] = $data[$field];
            }
        }

        // If no fields to update, return true (no update needed)  
        if (empty($setParts)) {
            return true;
        }

        // Complete the query  
        $sql .= implode(',', $setParts);
        $sql .= " WHERE id = :id";

        // Prepare and execute  
        $statement = $this->db->prepare($sql);
        return $statement->execute($params);
    }

    public function updatePassword($userId, $currentPassword, $newPassword) {
        try {
            // First, get the current hashed password from database  
            $statement = $this->db->prepare("SELECT password FROM users WHERE id = :id");
            $statement->execute([':id' => $userId]);
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            // Verify current password  
            if (!password_verify($currentPassword, $user['password'])) {
                return false; // Current password is incorrect  
            }

            // Hash new password  
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT, ['cost' => 12]);

            // Update password in database  
            $statement = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id");
            $result = $statement->execute([
                ':password' => $hashedPassword,
                ':id' => $userId
            ]);

            return $result;
        } catch (PDOException $e) {
            // Log the error (implement proper logging)  
            error_log("Password update failed: " . $e->getMessage());
            return false;
        }
    }

    public function toggleActive($userId) {
        // First, get the current is_active value from database  
        $statement = $this->db->prepare("SELECT is_active FROM users WHERE id = :id");
        $statement->execute([':id' => $userId]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false; // User not found  
        }

        // Flip the is_active value (0 to 1 or 1 to 0)  
        $active = $user["is_active"] ? 0 : 1;

        // Prepare and execute update query  
        $sql = "UPDATE users SET is_active = :active WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':active' => $active, ':id' => $userId]);
    }

    public function getCustomersCount() {
        try {
            $sql = "SELECT COUNT(*) as count   
                    FROM users   
                    WHERE role = 'customer'   
                    AND deleted_at IS NULL";  // Assuming soft deletes  

            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['count'];
        } catch (PDOException $e) {
            // Log error  
            error_log("Error getting customers count: " . $e->getMessage());
            return 0;
        }
    }
    
    public function getAllCustomers() {
        try {  
            $sql = "SELECT id, first_name, last_name, email, address, city, postal_code,   
                           phone, created_at
                    FROM users   
                    WHERE role = 'customer'   
                    AND deleted_at IS NULL   
                    ORDER BY created_at DESC";  
            
            $stmt = $this->db->prepare($sql);  
            $stmt->execute();  
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);  
        } catch (PDOException $e) {  
            error_log("Error getting all customers: " . $e->getMessage());  
            return [];  
        }  
    }  
}
