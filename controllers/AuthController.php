<?php  
require_once 'models/User.php';  

class AuthController {  
    private $userModel;  

    public function __construct() {  
        $this->userModel = new User();  
    }  

    public function login() {  
        // Check if already logged in  
        if (isset($_SESSION['user_id'])) {  
            //$this->redirectBasedOnRole($_SESSION['role']);  
        }  

        // Handle login form submission  
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {  
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);  
            $password = $_POST['password'];  
            $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);  

            // Validate required fields  
            if (empty($email) || empty($password) || empty($role)) {  
                $_SESSION['error'] = "All fields are required.";  
                return;  
            }  

            // Check if it's admin/seller trying to login with certificate  
            if (in_array($role, ['admin', 'seller'])) {  
                // Verify SSL certificate  
                if (!$this->verifyClientCertificate()) {  
                    $_SESSION['error'] = "Valid certificate required for admin/seller login.";  
                    return;  
                }  
            }  

            // Attempt login  
            $user = $this->userModel->login($email, $password, $role);  
            
            if ($user) {  
                // Start session and store user data  
                $_SESSION['user_id'] = $user['id'];  
                $_SESSION['role'] = $user['role'];  
                $_SESSION['email'] = $user['email'];  
                $_SESSION['name'] = $user['first_name'] . ' ' . $user['last_name'];  

                $this->redirectBasedOnRole($user['role']);  
            } else {  
                $_SESSION['error'] = "Invalid credentials.";  
            }  
        }  

        // Display login form  
        require_once 'views/auth/login.php';  
    }  

    public function register() {  
        // Handle registration form submission  
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {  
            $data = [  
                'first_name' => filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING),  
                'last_name' => filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING),  
                'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),  
                'password' => $_POST['password'],  
                'street' => filter_input(INPUT_POST, 'street', FILTER_SANITIZE_STRING),  
                'house_number' => filter_input(INPUT_POST, 'house_number', FILTER_SANITIZE_STRING),  
                'post_code' => filter_input(INPUT_POST, 'post_code', FILTER_SANITIZE_STRING),  
                'city' => filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING)  
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
            if ($this->userModel->register($data)) {  
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