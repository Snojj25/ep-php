<?php
// Load configuration
require_once 'config/config.php';

// Basic routing
//$controller = isset($_GET['controller']) ? strtolower($_GET['controller']) : 'customer';
//$action = isset($_GET['action']) ? strtolower($_GET['action']) : 'index';

$controller = isset($_GET['controller']) ? htmlspecialchars(  
    strip_tags($_GET['controller']),  
    ENT_QUOTES,  
    'UTF-8'  
) : 'customer';
$action = isset($_GET['action']) ? htmlspecialchars(  
    strip_tags($_GET['action']),  
    ENT_QUOTES,  
    'UTF-8'  
) : 'index';
        
        

// For testing purposes, set a default role
if (!isset($_SESSION['user_role'])) {
    $_SESSION['user_role'] = 'customer';
    $_SESSION['user_id'] = 1;
}

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
        throw new Exception("Controller '$controllerName' not found");
    }
} catch (Exception $e) {
    if (DEBUG_MODE) {
        echo "Error: " . $e->getMessage();
    } else {
        // Show user-friendly error page
        include 'views/error.php';
    }
}


