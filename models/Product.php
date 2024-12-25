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
            (name, description, price, is_active)   
            VALUES (:name, :description, :price, 1)");  
            
        return $statement->execute([  
            ':name' => $data['name'],  
            ':description' => $data['description'],  
            ':price' => $data['price']  
        ]);  
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
    
    public function toggleActive($id, $active) {  
        $statement = $this->db->prepare("UPDATE products SET is_active = :active WHERE id = :id");  
        return $statement->execute([':id' => $id, ':active' => $active]);  
    }  
}  