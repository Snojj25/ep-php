<?php

require_once 'models/DBInit.php';

class Product {

    private $db;

    public function __construct() {
        $this->db = DBInit::getInstance();
    }

    public function binarySearch($search_term, $exclude_term) {

        //[':search_term' => $search_term, ':exclude_term' => $exclude_term]
        
        try {
//            $sql = "SELECT * FROM products WHERE is_active=1 AND MATCH (name) AGAINST (CONCAT('+', :search_term, ' -', :exclude_term) IN BOOLEAN MODE)";
            $sql = "SELECT * FROM products WHERE is_active=1 AND MATCH (name) AGAINST (CONCAT('+', :search_term, ' -', :exclude_term) IN NATURAL LANGUAGE MODE)";
            //
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":search_term" => $search_term, ':exclude_term' => $exclude_term]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting sorted products: " . $e->getMessage());
            return [];
        }
    }

    public function getProductsSortedByName() {
        try {
            $sql = "SELECT * FROM products WHERE is_active = 1 ORDER BY name ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting sorted products: " . $e->getMessage());
            return [];
        }
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

    public function createWithImages($productData, $pimage) {
        try {
            $this->db->beginTransaction();

            // Insert product  
            $sql = "INSERT INTO products (name, description, price, stock, created_at)   
                    VALUES (:name, :description, :price, :stock, NOW())";

            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                ':name' => $productData['name'],
                ':description' => $productData['description'],
                ':price' => $productData['price'],
                ':stock' => $productData['stock']
            ]);
            if (!$success) {
                $_SESSION['errors'] = ["Failed to insert product"];
            }

            $productId = $this->db->lastInsertId();

            // Insert images  
            if ($pimage != null) {
                $sql = "INSERT INTO product_images (product_id, file_name, file_path, is_primary)   
                        VALUES (:product_id, :file_name, :file_path, :is_primary)";

                $stmt = $this->db->prepare($sql);

                $success = $stmt->execute([
                    ':product_id' => $productId,
                    ':file_name' => $pimage['file_name'],
                    ':file_path' => $pimage['file_path'],
                    ':is_primary' => 1 // First image is primary  
                ]);
                if (!$success) {
                    $_SESSION['errors'] = ["Failed to insert product image"];
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

    public function getProductImage($productId) {
        try {
            $sql = "SELECT * FROM product_images   
                WHERE product_id = :product_id";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':product_id' => $productId]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting product images: " . $e->getMessage());
            return [];
        }
    }

    public function update($id, $data) {
        $statement = $this->db->prepare("UPDATE products SET   
            name = :name,  
            description = :description,  
            price = :price,
            stock = :stock
            WHERE id = :id");

        return $statement->execute([
                    ':id' => $id,
                    ':name' => $data['name'],
                    ':description' => $data['description'],
                    ':price' => $data['price'],
                    ':stock' => $data['stock']
        ]);
    }

    public function updateWithImage($id, $productData, $newImage) {
        try {
            $this->db->beginTransaction();

            // 1. Update product details  
            $sql = "UPDATE products SET   
                name = :name,  
                description = :description,  
                price = :price,  
                stock = :stock  
                WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                ':id' => $id,
                ':name' => $productData['name'],
                ':description' => $productData['description'],
                ':price' => $productData['price'],
                ':stock' => $productData['stock']
            ]);

            if (!$success) {
                throw new PDOException("Failed to update product details");
            }

            // 2. If there's a new image, handle the image update  
            if ($newImage != null) {
                // 2.1 First, get the current image(s)  
                $sql = "SELECT id, file_path FROM product_images WHERE product_id = :product_id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':product_id' => $id]);
                $oldImages = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // 2.2 Delete old images from filesystem  
                foreach ($oldImages as $oldImage) {
                    if (file_exists($oldImage['file_path'])) {
                        unlink($oldImage['file_path']); // Delete file from filesystem  
                    }
                }

                // 2.3 Delete old images from database  
                $sql = "DELETE FROM product_images WHERE product_id = :product_id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':product_id' => $id]);

                // 2.4 Insert new image  
                $sql = "INSERT INTO product_images (product_id, file_name, file_path, is_primary)   
                    VALUES (:product_id, :file_name, :file_path, :is_primary)";

                $stmt = $this->db->prepare($sql);
                $success = $stmt->execute([
                    ':product_id' => $id,
                    ':file_name' => $newImage['file_name'],
                    ':file_path' => $newImage['file_path'],
                    ':is_primary' => 1
                ]);

                if (!$success) {
                    throw new PDOException("Failed to insert new image");
                }
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error updating product with images: " . $e->getMessage());
            $_SESSION['errors'] = ["Database error: " . $e->getMessage()];
            return false;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("General error: " . $e->getMessage());
            $_SESSION['errors'] = ["Error: " . $e->getMessage()];
            return false;
        }
    }

// Helper function to get current images for a product  
    public function getProductImages($productId) {
        try {
            $sql = "SELECT * FROM product_images WHERE product_id = :product_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':product_id' => $productId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting product images: " . $e->getMessage());
            return [];
        }
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
