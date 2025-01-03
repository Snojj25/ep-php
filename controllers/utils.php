<?php

// controllers/utils.php  




function validateRequest($requiredRole) {
//    if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
//        header('HTTP/1.1 403 Forbidden');
//        exit('Secure connection required');
//    }

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
