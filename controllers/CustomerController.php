<?php

require_once 'controllers/utils.php';
require_once 'models/Product.php';
require_once 'models/Order.php';
require_once 'models/Cart.php';

class CustomerController {

    private $productModel;
    private $orderModel;
    private $cartModel;

    public function __construct() {
        checkRole('customer');

        $this->productModel = new Product();
        $this->orderModel = new Order();
        $this->cartModel = new Cart();
    }

    public function index() {
        $products = $this->productModel->getAll();

        // Create a map of product images  
        $productImages = [];
        foreach ($products as $product) {

            $productImages[$product['id']] = $this->productModel->getProductImage($product['id']);
        }

        $data = [
            'products' => $products,
            'productImages' => $productImages
        ];

        require 'views/customer/products.php';
    }

    public function cart() {
        $cartItems = $this->cartModel->getItems();
        require 'views/customer/cart.php';
    }

    public function addToCart() {

        $this->cartModel->addItem($_POST['product_id'], $_POST['quantity']);
        header('Location: index.php?controller=customer&action=cart');
    }

    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Validate required fields  
            $requiredFields = ['shipping_address', 'postal_code', 'city', 'phone'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    $_SESSION['error'] = 'Please fill in all required fields';
                    header('Location: index.php?controller=customer&action=checkout');
                    return;
                }
            }

            // Collect shipping details from POST data  
            $shippingDetails = [
                'shipping_address' => $_POST['shipping_address'],
                'postal_code' => $_POST['postal_code'],
                'city' => $_POST['city'],
                'phone' => $_POST['phone'],
                'notes' => $_POST['notes'] ?? null
            ];

            $cartItems = $this->cartModel->getItems();
            $orderId = $this->orderModel->create(
                    $_SESSION['user_id'],
                    $cartItems,
                    $this->cartModel->getTotal(),
                    $shippingDetails
            );
            $this->cartModel->clear();
            header('Location: index.php?controller=customer&action=orderConfirmation&id=' . $orderId);
        } else {
            $cartItems = $this->cartModel->getItems();
            require 'views/customer/checkout.php';
        }
    }

    public function search($params) {
        $searchTerm = isset($params['q']) ? cleanInput($params['q']) : '';
        $excludeTerm = isset($params['x']) ? cleanInput($params['x']) : '';

        $results = [];

        if (!empty($searchTerm)) {
            $results = $this->productModel->binarySearch($searchTerm, $excludeTerm);
        }

        if (isset($params['ajax'])) {
            // Return JSON for AJAX requests  
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'results' => $results
            ]);
            exit;
        }

        // Regular page render  
        require 'views/customer/search.php';
    }

    public function orderConfirmation() {
        if (!isset($_GET['id'])) {
            header('Location: index.php?controller=customer&action=index');
            return;
        }

        $orderId = $_GET['id'];
        $order = $this->orderModel->getById($orderId);

        // Verify order belongs to current user  
        if (!$order || $order['user_id'] != $_SESSION['user_id']) {
            header('Location: index.php?controller=customer&action=index');
            return;
        }

        $orderItems = $this->orderModel->getOrderItems($orderId);
        require 'views/customer/order_confirmation.php';
    }

    public function orderHistory() {
        $orders = $this->orderModel->getByUser($_SESSION['user_id']);
        require 'views/customer/order_history.php';
    }

    public function removeFromCart() {
        // Check if product_id is provided  
        if (!isset($_POST['product_id'])) {
            $_SESSION['error'] = 'Invalid request';
            header('Location: index.php?controller=customer&action=cart');
            return;
        }

        try {
            // Remove item from cart  
            $this->cartModel->removeItem($_POST['product_id']);
            $_SESSION['success'] = 'Item removed from cart successfully';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Failed to remove item from cart';
        }

        // Redirect back to cart  
        header('Location: index.php?controller=customer&action=cart');
    }
}
