<?php

// controllers/utils.php  


function cleanInput($input) {
    return htmlspecialchars(
            strip_tags($input),
            ENT_QUOTES,
            'UTF-8'
    );
}

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

    if (in_array($requiredRole, ["admin", "seller"])) {
        // Add certificate verification  
        $certAuth = new CertificateAuthMiddleware();
        try {
            $certData = $certAuth->authenticate($requiredRole);
            // Optionally store certificate data in session for later use  
            $_SESSION['cert_data'] = $certData;
        } catch (Exception $e) {
            header('HTTP/1.1 403 Forbidden');
            exit('Invalid certificate: ' . $e->getMessage());
        }
    }
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

class CertificateAuthMiddleware {

    private $allowedRoles = [
        'admin' => ['ana'],
        'seller' => ['bob']
    ];

    public function authenticate($requiredRole) {
        // Check if HTTPS is being used  
        if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
            throw new Exception('Secure connection required');
        }

        // Check if client certificate was provided and verified  
        if (!isset($_SERVER['SSL_CLIENT_VERIFY']) || $_SERVER['SSL_CLIENT_VERIFY'] !== 'SUCCESS') {
            throw new Exception('Valid client certificate required');
        }

        // Get certificate information  
        $certData = $this->getCertificateData();

        // Verify certificate role  
        if (!$this->verifyRole($certData['commonName'], $requiredRole)) {
            throw new Exception('Invalid certificate role for this resource');
        }

        return $certData;
    }

    private function getCertificateData() {
        // Get certificate details from Apache environment  
        $data = [
            'commonName' => $_SERVER['SSL_CLIENT_S_DN_CN'] ?? null,
            'issuer' => $_SERVER['SSL_CLIENT_I_DN'] ?? null,
            'validFrom' => $_SERVER['SSL_CLIENT_V_START'] ?? null,
            'validTo' => $_SERVER['SSL_CLIENT_V_END'] ?? null,
            'serialNumber' => $_SERVER['SSL_CLIENT_M_SERIAL'] ?? null
        ];

        if (!$data['commonName']) {
            throw new Exception('Unable to extract certificate data');
        }

        return $data;
    }

    private function verifyRole($commonName, $requiredRole) {
        if (!isset($this->allowedRoles[$requiredRole])) {
            return false;
        }

        return in_array($commonName, $this->allowedRoles[$requiredRole]);
    }
}
