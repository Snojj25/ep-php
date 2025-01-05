<?php

// Load configuration
require_once 'config/config.php';
require_once 'models/Product.php';

// Basic routing
//$controller = isset($_GET['controller']) ? strtolower($_GET['controller']) : 'customer';
//$action = isset($_GET['action']) ? strtolower($_GET['action']) : 'index';

$controller = "";
if (isset($_GET['controller'])) {
    $controller = htmlspecialchars(
            strip_tags($_GET['controller']),
            ENT_QUOTES,
            'UTF-8');
} else if (isset($_SESSION['user']['role'])) {
    $controller = $_SESSION['user']['role'];
} 
$action = isset($_GET['action']) ? htmlspecialchars(
                strip_tags($_GET['action']),
                ENT_QUOTES,
                'UTF-8'
        ) : 'index';

// Force HTTPS for secure areas  
//$secure_areas = ['admin', 'seller', 'customer'];
//$current_area = isset($_GET['controller']) ? $_GET['controller'] : 'customer';
//
//if (in_array($current_area, $secure_areas) &&
//        (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on')) {
//    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//    header('Location: ' . $redirect);
//    exit();
//}


// Map controllers
try {
    // Collect all parameters from both GET and POST  
    $params = array_merge($_GET, $_POST);
    

    $controllerName = ucfirst($controller) . 'Controller';

    if (class_exists($controllerName)) {
        $controllerInstance = new $controllerName();

        if (method_exists($controllerInstance, $action)) {

            $controllerInstance->$action($params);
        } else {
            throw new Exception("Action '$action' not found in controller '$controllerName'");
        }
    } else {
        default_index();
    }
} catch (Exception $e) {
    if (DEBUG_MODE) {
        echo "Error: " . $e->getMessage();
    } else {
        // Show user-friendly error page
        include 'views/error.php';
    }
}



function default_index() {
        $productModel = new Product();
        $products = $productModel->getAll();

        // Create a map of product images  
        $productImages = [];
        foreach ($products as $product) {
           
            $productImages[$product['id']] = $productModel->getProductImage($product['id']);
        }
 
        $data = [
            'products' => $products,
            'productImages' => $productImages
        ];

        require 'views/customer/products.php';
    }
