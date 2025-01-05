<?php

// controllers/utils.php  




function validateRequest($requiredRole) {
    enforceHTTPS();

    // First check session-based authentication  
    if (!isset($_SESSION['user']['id']) || !isset($_SESSION["user"]['role'])) {
        header('Location: index.php?controller=auth&action=login');
        exit;
    }

    if ($_SESSION['user']["role"] !== $requiredRole) {
        header('HTTP/1.1 403 Forbidden');
        exit('Unauthorized access');
    }
}

function checkRole($requiredRole) {

    validateRequest($requiredRole);
    
//  
//    // Add certificate verification  
//    $certAuth = new CertificateAuthMiddleware();  
//    try {  
//        $certData = $certAuth->authenticate($requiredRole);  
//        // Optionally store certificate data in session for later use  
//        $_SESSION['cert_data'] = $certData;  
//    } catch (Exception $e) {  
//        header('HTTP/1.1 403 Forbidden');  
//        exit('Invalid certificate: ' . $e->getMessage());  
//    }  
}

function cleanInput($input) {
    return htmlspecialchars(
            strip_tags($input),
            ENT_QUOTES,
            'UTF-8'
    );
}

function enforceHTTPS() {  
    // If already on HTTPS, no need to redirect  
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {  
        return;  
    }  
    
    // Force HTTPS for secure areas  
    $secure_areas = ['admin', 'seller', 'customer', 'auth'];  
    $current_area = isset($_GET['controller']) ? $_GET['controller'] : '';  

    if (in_array($current_area, $secure_areas)) {  
        // Strip the port number from HTTP_HOST  
        $host = $_SERVER['HTTP_HOST'];  
        if (strpos($host, ':') !== false) {  
            $host = substr($host, 0, strpos($host, ':'));  
        }  
        
        $redirect = 'https://' . $host . $_SERVER['REQUEST_URI'];  
        header('Location: ' . $redirect);  
        exit();  
    }  
}