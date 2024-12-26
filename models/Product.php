<?php

require_once 'models/DBInit.php';

class Product {

    private $db;

    public function __construct() {
        $this->db = DBInit::getInstance();
    }

    public function getById($id, $includeInactive = false) {
        try {
            $sql = "SELECT * FROM products WHERE id = :id";
            if (!$includeInactive) {
                $sql .= " AND is_active = 1";
            }

            $statement = $this->db->prepare($sql);
            $statement->bindValue(":id", $id, PDO::PARAM_INT);
            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);

            // Convert numeric strings to appropriate types  
            if ($result) {
                $result['id'] = (int) $result['id'];
                $result['price'] = (float) $result['price'];
                $result['is_active'] = (bool) $result['is_active'];
            }

            return $result;
        } catch (PDOException $e) {
            // Log error here if you have logging implemented  
            throw new Exception("Error fetching product: " . $e->getMessage());
        }
    }

    public function getAll($activeOnly = true) {
        $sql = "SELECT * FROM products";
        if ($activeOnly) {
            $sql .= " WHERE is_active = 1";
        }
        $statement = $this->db->prepare($sql);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function create($data) {
        $statement = $this->db->prepare("INSERT INTO products   
            (name, description, price, stock, is_active)   
            VALUES (:name, :description, :price, :stock,  1)");

        return $statement->execute([
                    ':name' => $data['name'],
                    ':description' => $data['description'],
                    ':price' => $data['price'],
                    ':stock' => $data['stock']
        ]);
    }

    public function createWithImages($productData, $images) {
        try {
            $this->db->beginTransaction();

            // Insert product  
            $sql = "INSERT INTO products (name, description, price, stock, category_id, created_at)   
                    VALUES (:name, :description, :price, :stock, :category_id, NOW())";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':name' => $productData['name'],
                ':description' => $productData['description'],
                ':price' => $productData['price'],
                ':stock' => $productData['stock'],
                ':category_id' => $productData['category_id']
            ]);

            $productId = $this->db->lastInsertId();

            // Insert images  
            if (!empty($images)) {
                $sql = "INSERT INTO product_images (product_id, file_name, file_path, is_primary)   
                        VALUES (:product_id, :file_name, :file_path, :is_primary)";

                $stmt = $this->db->prepare($sql);

                foreach ($images as $index => $image) {
                    $stmt->execute([
                        ':product_id' => $productId,
                        ':file_name' => $image['file_name'],
                        ':file_path' => $image['file_path'],
                        ':is_primary' => $index === 0 // First image is primary  
                    ]);
                }
            }

            $this->db->commit();
            return $productId;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error creating product: " . $e->getMessage());
            return false;
        }
    }

    public function getProductImages($productId) {
        try {
            $sql = "SELECT * FROM product_images   
                WHERE product_id = :product_id   
                ORDER BY is_primary DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':product_id' => $productId]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting product images: " . $e->getMessage());
            return [];
        }
    }

    public function update($id, $data) {
        $statement = $this->db->prepare("UPDATE products SET   
            name = :name,  
            description = :description,  
            price = :price  
            WHERE id = :id");

        return $statement->execute([
                    ':id' => $id,
                    ':name' => $data['name'],
                    ':description' => $data['description'],
                    ':price' => $data['price']
        ]);
    }

    public function getCount() {
        try {
            $sql = "SELECT COUNT(*) as count   
                    FROM products   
                    WHERE deleted_at IS NULL";  // Assuming soft deletes  

            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['count'];
        } catch (PDOException $e) {
            // Log error  
            error_log("Error getting products count: " . $e->getMessage());
            return 0;
        }
    }

    public function toggleActive($id, $active) {
        $statement = $this->db->prepare("UPDATE products SET is_active = :active WHERE id = :id");
        return $statement->execute([':id' => $id, ':active' => $active]);
    }
}
