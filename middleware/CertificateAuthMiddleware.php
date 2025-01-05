<?php  
// app/middleware/CertificateAuthMiddleware.php  

class CertificateAuthMiddleware {  
    private $allowedRoles = [  
        'admin' => ['admin'],  
        'seller' => ['seller'],  
        'customer' => ['customer']  
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