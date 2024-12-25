<?php  

require_once 'controllers/utils.php';
require_once 'models/Product.php';
require_once 'models/Order.php';
require_once 'models/User.php';

class SellerController {  
    private $productModel;  
    private $orderModel;  
    private $userModel;  
    
    public function __construct() {  
        checkRole('seller');
        
        $this->productModel = new Product();  
        $this->orderModel = new Order();  
        $this->userModel = new User();  
    }  
    
    public function index() {  
        // Dashboard for seller  
        require 'views/seller/dashboard.php';  
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
}  