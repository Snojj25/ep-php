<?php

require_once 'models/User.php';
require_once 'controllers/utils.php';

class AuthController {

    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login($params) {
        
        
//        echo '<pre>';
//        echo "\n session user:\n";
//        print_r($_SESSION['user']);
//        echo '</pre>';
//        exit(); // Stop execution here to see the debug data  
//        header('Location: index.php?controller=customer&action=register');
//        return;
        
        // Check if already logged in  
        if (isset($_SESSION['user']['id'])) {
            
            $this->redirectBasedOnRole($_SESSION['user']['role']);
        }

        // Handle login form submission  
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = cleanInput($params["email"]);
            $password = $_POST['password'];
            $role = cleanInput($params["role"]);

            // Validate required fields  
            if (empty($email) || empty($password) || empty($role)) {
                $_SESSION['error'] = "All fields are required.";
                return;
            }

//            // Check if it's admin/seller trying to login with certificate  
//            if (in_array($role, ['admin', 'seller'])) {  
//                // Verify SSL certificate  
//                if (!$this->verifyClientCertificate()) {  
//                    $_SESSION['error'] = "Valid certificate required for admin/seller login.";  
//                    return;  
//                }  
//            }  
            // Attempt login  
            $result = $this->userModel->login($email, $password, $role);

            if ($result['success']) {
                // Start session and store user data  
                $_SESSION['user'] = $result['user'];
                $_SESSION['logged_in'] = true;

                
               $this->redirectBasedOnRole($result['user']['role']); 
            } else {
                $_SESSION['error'] = $result['message'];
                $_SESSION['old_email'] = $email;
                $_SESSION['old_role'] = $role;
                header('Location: index.php?controller=auth&action=login');
                exit();
            }

        }

        // Display login form  
        require_once 'views/auth/login.php';
    }

    public function register($params) {
        // Handle registration form submission  
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'first_name' => cleanInput($params["first_name"]),
                'last_name' => cleanInput($params["last_name"]),
                'email' => cleanInput($params["email"]),
                'password' => $_POST['password'],
                'role' => "customer",
                'address' => cleanInput($params["street"]) . " " . cleanInput($params["house_number"]),
                'postal_code' => cleanInput($params["postal_code"]),
                'city' => cleanInput($params["city"])
            ];

            // Validate required fields  
            foreach ($data as $key => $value) {
                if (empty($value)) {
                    $_SESSION['error'] = "All fields are required.";
                    require_once 'views/auth/register.php';
                    return;
                }
            }

            // Validate email format  
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Invalid email format.";
                require_once 'views/auth/register.php';
                return;
            }

            // Check if email already exists  
            if ($this->userModel->emailExists($data['email'])) {
                $_SESSION['error'] = "Email already registered.";
                require_once 'views/auth/register.php';
                return;
            }



            // Create customer account  
            if ($this->userModel->create($data)) {
                $_SESSION['success'] = "Registration successful. Please login.";
                header('Location: index.php?controller=auth&action=login');
                exit;
            } else {
                $_SESSION['error'] = "Registration failed.";
            }
        }

        // Display registration form  
        require_once 'views/auth/register.php';
    }

    public function logout() {
        // Clear all session data  
        session_destroy();

        // Redirect to login page  
        header('Location: index.php?controller=auth&action=login');
        exit;
    }

    private function verifyClientCertificate() {
        // Verify SSL client certificate  
        if (!isset($_SERVER['SSL_CLIENT_VERIFY']) ||
                $_SERVER['SSL_CLIENT_VERIFY'] !== 'SUCCESS') {
            return false;
        }

        // Additional certificate validation can be added here  
        return true;
    }

    private function redirectBasedOnRole($role) {
        switch ($role) {
            case 'admin':
                header('Location: index.php?controller=admin&action=dashboard');
                break;
            case 'seller':
                header('Location: index.php?controller=seller&action=dashboard');
                break;
            case 'customer':
                header('Location: index.php?controller=customer&action=index');
                break;
            default:
                header('Location: index.php');
        }
        exit;
    }
}
