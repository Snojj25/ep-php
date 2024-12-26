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
        $pendingOrders = $this->orderModel->getPending();
        require 'views/seller/orders.php';
    }

    public function updateOrderStatus() {
        $this->orderModel->updateStatus($_POST['order_id'], $_POST['status']);
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

                $_SESSION['errors'] = ["ONE 1"];

                $data = [
                    'name' => cleanInput($params["name"]),
                    'description' => cleanInput($params["description"]),
                    'price' => cleanInput($params["price"]),
                    'stock' => cleanInput($params["stock"]),
                    'is_active' => 1
                ];

                $_SESSION['errors'] = ["TWO 2"];

                $this->validateProductData($data);

                // Handle image uploads  
                $uploadedImages = [];
                if (isset($_FILES['images']) && count($_FILES['images']["name"]) > 0 ) {
                    
                    $uploadedImages = $this->handleImageUploads($_FILES['images']);

                    if (isset($uploadedImages['errors'])) {
                        $_SESSION['errors'] = $uploadedImages['errors'];
                        $_SESSION['old'] = $_POST;
                        
                        $_SESSION['errors'] = $_FILES['images']; // TODO REMOVE
                        
                        header('Location: index.php?controller=seller&action=createProduct');
                        return;
                    }
                }

                $_SESSION['errors'] = ["THREE 3"];

                if (empty($data['name']) || empty($data['price'])) {
                    throw new Exception("Name and price are required");
                }

                if (count($uploadedImages) == 0) {
                    if ($this->productModel->create($data)) {
                        $_SESSION['success'] = "Product created successfully";
                        header('Location: index.php?controller=seller&action=manageProducts');
                        exit();
                    }
                } else {
                    $productId = $this->productModel->createWithImages($productData, $uploadedImages);

                    if ($productId) {
                        $_SESSION['success'] = 'Product created successfully!';
                        header('Location: index.php?controller=seller&action=manageProducts');
                        return;
                    }
                }

                $_SESSION['errors'] = ["Four 4"];
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                $_SESSION['form_data'] = $_POST;
                header('Location: index.php?controller=seller&action=createProduct');
                exit();
            }
        }

        require 'views/seller/create_product.php';
    }

    private function handleImageUploads($files) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB  
        $uploadedImages = [];
        $errors = [];

        // Reorganize files array  
        $filesCount = count($files['name']);
        for ($i = 1; $i < $filesCount; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $tmpName = $files['tmp_name'][$i];
                $name = $files['name'][$i];
                $type = $files['type'][$i];
                $size = $files['size'][$i];

                // Validate file  
                if (!in_array($type, $allowedTypes)) {
                    $errors[] = "File '$name' is not an allowed image type.";
                    continue;
                }

                if ($size > $maxSize) {
                    $errors[] = "File '$name' exceeds maximum size of 5MB.";
                    continue;
                }

                // Generate unique filename  
                $extension = pathinfo($name, PATHINFO_EXTENSION);
                $newFilename = uniqid() . '_' . time() . '.' . $extension;
                $filePath = $this->uploadDir . $newFilename;

                // Move file to upload directory  
                if (move_uploaded_file($tmpName, $filePath)) {
                    $uploadedImages[] = [
                        'file_name' => $newFilename,
                        'file_path' => $filePath
                    ];
                } else {
                    $errors[] = "Failed to upload file '$name'.";
                }
            } else {
                $errors[] = "Error uploading file '{$files['name'][$i]}'.";
            }
        }

        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        return $uploadedImages;
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

    public function editProduct($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
               

                $data = [
                    'name' => filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING),
                    'description' => filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING),
                    'price' => filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT),
                    'is_active' => isset($_POST['is_active']) ? 1 : 0
                ];

                if (empty($data['name']) || empty($data['price'])) {
                    throw new Exception("Name and price are required");
                }

                if ($this->productModel->update($id, $data)) {
                    $_SESSION['success'] = "Product updated successfully";
                    header('Location: index.php?controller=seller&action=manageProducts');
                    exit();
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                $_SESSION['form_data'] = $_POST;
                header("Location: index.php?controller=seller&action=editProduct&id=$id");
                exit();
            }
        }

        $product = $this->productModel->getById($id);
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

    public function viewOrder($id) {
        $order = $this->orderModel->getById($id);
        if (!$order) {
            $_SESSION['error'] = "Order not found";
            header('Location: index.php?controller=seller&action=manageOrders');
            exit();
        }

        $orderItems = $this->orderModel->getOrderItems($id);
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
}
