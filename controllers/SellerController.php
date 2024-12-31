<?php

require_once 'controllers/utils.php';
require_once 'models/Product.php';
require_once 'models/Order.php';
require_once 'models/User.php';

class SellerController {

    private $productModel;
    private $orderModel;
    private $userModel;
    private $uploadDir = 'uploads/products/'; // Define your upload directory  

    public function __construct() {
        checkRole('seller');

        $this->productModel = new Product();
        $this->orderModel = new Order();
        $this->userModel = new User();

        // Ensure upload directory exists  
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function manageProducts() {
        $products = $this->productModel->getAll(false);
        require 'views/seller/products.php';
    }

    public function manageOrders() {
        // $pendingOrders = $this->orderModel->getPending();
        $allOrders = $this->orderModel->getAll();
        require 'views/seller/orders.php';
    }

    public function updateOrderStatus($params) {
        $this->orderModel->updateStatus($params['order_id'], $params['status']);
        header('Location: index.php?controller=seller&action=manageOrders');
    }

    public function index() {
        // Get counts for dashboard  
        $pendingOrdersCount = $this->orderModel->getPendingCount();
        $confirmedOrdersCount = $this->orderModel->getConfirmedCount();
        $productsCount = $this->productModel->getCount();
        $customersCount = $this->userModel->getCustomersCount();

        require 'views/seller/dashboard.php';
    }

    public function manageCustomers() {
        $customers = $this->userModel->getAllCustomers();
        require 'views/seller/customers.php';
    }

    public function createProduct($params) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {


                $data = [
                    'name' => cleanInput($params["name"]),
                    'description' => cleanInput($params["description"]),
                    'price' => cleanInput($params["price"]),
                    'stock' => cleanInput($params["stock"]),
                    'is_active' => 1
                ];

                $this->validateProductData($data);

                // Handle image upload  
                $uploadedImage = null;
                if (isset($_FILES['pimage']) && $_FILES['pimage']['error'] === UPLOAD_ERR_OK) {
                    $uploadedImage = $this->handleSingleImageUpload($_FILES['pimage']);

                    if (isset($uploadedImage['error'])) {
                        $_SESSION['errors'] = [$uploadedImage['error']];
                        $_SESSION['old'] = $_POST;
                        header('Location: index.php?controller=seller&action=createProduct');
                        return;
                    }
                }

//                // Temporary debug output  
//                echo '<pre>';
//                echo "\n uploadedImage Data:\n";
//                print_r($uploadedImage);
//                echo '</pre>';
//                exit(); // Stop execution here to see the debug data  
//                header('Location: index.php?controller=seller&action=createProduct');
//                return;


                if (empty($data['name']) || empty($data['price'])) {
                    throw new Exception("Name and price are required");
                }

                // Create product with or without image  
                if ($uploadedImage === null) {
                    if ($this->productModel->create($data)) {
                        $_SESSION['success'] = "Product created successfully";
                        header('Location: index.php?controller=seller&action=manageProducts');
                        exit();
                    }
                } else {



                    $productId = $this->productModel->createWithImages($data, $uploadedImage);

                    if ($productId) {
                        $_SESSION['success'] = 'Product created successfully!';
                        header('Location: index.php?controller=seller&action=manageProducts');
                        return;
                    }
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                $_SESSION['form_data'] = $_POST;
                header('Location: index.php?controller=seller&action=createProduct');
                exit();
            }
        }

        require 'views/seller/create_product.php';
    }

    public function editProduct($params) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {

                $product = $this->productModel->getById($params["id"]);

                $data = [
                    'name' => cleanInput($params["name"]),
                    'description' => cleanInput($params["description"]),
                    'price' => cleanInput($params["price"]),
                    'stock' => cleanInput($params["stock"]),
                    'is_active' => 1
                ];

                // Handle image upload  
                $uploadedImage = null;
                if (isset($_FILES['pimage']) && $_FILES['pimage']['error'] === UPLOAD_ERR_OK) {
                    $uploadedImage = $this->handleSingleImageUpload($_FILES['pimage']);

                    if (isset($uploadedImage['error'])) {
                        $_SESSION['errors'] = [$uploadedImage['error']];
                        $_SESSION['old'] = $_POST;
                        header('Location: index.php?controller=seller&action=createProduct');
                        return;
                    }
                }


                if (empty($data['name']) || empty($data['price'])) {
                    throw new Exception("Name and price are required");
                }



                // Create product with or without image  
                if ($uploadedImage === null) {
                    if ($this->productModel->update($params["id"], $data)) {
                        $_SESSION['success'] = "Product updated successfully";
                        header('Location: index.php?controller=seller&action=manageProducts');
                        exit();
                    }
                } else {
                    if ($this->productModel->updateWithImage($params["id"], $data, $uploadedImage)) {
                        $_SESSION['success'] = "Product updated successfully!";
                        header('Location: index.php?controller=seller&action=manageProducts');
                        exit();
                    }
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                $_SESSION['form_data'] = $_POST;
                $id = $params["id"];
                header("Location: index.php?controller=seller&action=editProduct&id=$id");
                exit();
            }
        }

        $product = $this->productModel->getById($params["id"]);
        if (!$product) {
            $_SESSION['error'] = "Product not found";
            header('Location: index.php?controller=seller&action=manageProducts');
            exit();
        }

        // Generate CSRF token  
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        require 'views/seller/edit_product.php';
    }

    public function toggleProductStatus($id) {
        try {
            if ($this->productModel->toggleActive($id)) {
                $_SESSION['success'] = "Product status updated successfully";
            } else {
                $_SESSION['error'] = "Failed to update product status";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        header('Location: index.php?controller=seller&action=manageProducts');
        exit();
    }

    public function orderHistory() {
        $orders = $this->orderModel->getProcessedOrders();
        require 'views/seller/order_history.php';
    }

    public function viewOrder($params) {
        $order = $this->orderModel->getById($params["id"]);
        if (!$order) {
            $_SESSION['error'] = "Order not found";
            header('Location: index.php?controller=seller&action=manageOrders');
            exit();
        }

        $orderItems = $this->orderModel->getOrderItems($params["id"]);
        $customer = $this->userModel->getById($order['user_id']);

        require 'views/seller/view_order.php';
    }

    public function toggleCustomerStatus($id) {
        try {
            if ($this->userModel->toggleActive($id)) {
                $_SESSION['success'] = "Customer status updated successfully";
            } else {
                $_SESSION['error'] = "Failed to update customer status";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        header('Location: index.php?controller=seller&action=manageCustomers');
        exit();
    }

    // ------------------- ------------------- ------------------- -------------------

    /**
     * Handle single image upload  
     * @param array $file Single file from $_FILES  
     * @return array|null Image data or null on failure  
     */
    private function handleSingleImageUpload($file) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB  
        // Validate file  
        if (!in_array($file['type'], $allowedTypes)) {
            return ['error' => "File '{$file['name']}' is not an allowed image type."];
        }

        if ($file['size'] > $maxSize) {
            return ['error' => "File '{$file['name']}' exceeds maximum size of 5MB."];
        }

        // Create upload directory if it doesn't exist  
        $uploadDir = 'uploads/products/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate unique filename  
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFilename = uniqid() . '_' . time() . '.' . $extension;
        $filePath = $uploadDir . $newFilename;

        // Move file to upload directory  
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return [
                'file_name' => $newFilename,
                'file_path' => $filePath
            ];
        }

        return ['error' => "Failed to upload file '{$file['name']}'."];
    }

    private function validateProductData($data) {
        $errors = [];

        if (empty($data['name'])) {
            $errors[] = 'Product name is required.';
        }

        if (empty($data['description'])) {
            $errors[] = 'Product description is required.';
        }

        if (!is_numeric($data['price']) || $data['price'] <= 0) {
            $errors[] = 'Price must be a positive number.';
        }

        if (!is_numeric($data['stock']) || $data['stock'] < 0) {
            $errors[] = 'Stock must be a non-negative number.';
        }

        return $errors;
    }
}
