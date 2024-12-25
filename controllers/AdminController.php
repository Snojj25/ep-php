<?php

require_once 'controllers/utils.php';
require_once 'models/User.php';

class AdminController {

    private $userModel;

    public function __construct() {
        // Check both role and certificate  
        checkRole('admin');

        $this->userModel = new User();
    }

    public function index() {
        $adminData = $this->userModel->getById($_SESSION['user_id']);
        require 'views/admin/dashboard.php';
    }

    // Profile Management  
    public function profile($params) {
        $admin = $this->userModel->getById($_SESSION['user_id']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {


            $data = [
                'first_name' => cleanInput($params["first_name"]),
                'last_name' => cleanInput($params["last_name"]),
                'email' => cleanInput($params["email"]),
            ];

            if ($this->userModel->updateProfile($_SESSION['user_id'], $data)) {
                $_SESSION['success'] = "Profile updated successfully";
            } else {
                $_SESSION['error'] = "Failed to update profile";
            }
            header('Location: index.php?controller=admin&action=profile');
            exit();
        }
        require 'views/admin/profile.php';
    }

    public function changePassword($params) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = cleanInput($params["$currentPassword"]);
            $newPassword = cleanInput($params["$newPassword"]);
            $confirmPassword = cleanInput($params["$confirmPassword"]);

            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = "Passwords do not match";
            } elseif ($this->userModel->updatePassword($_SESSION['user_id'], $currentPassword, $newPassword)) {
                $_SESSION['success'] = "Password updated successfully";
            } else {
                $_SESSION['error'] = "Failed to update password";
            }
            header('Location: index.php?controller=admin&action=profile');
            exit();
        }
        require 'views/admin/change_password.php';
    }

    // Seller Management  
    public function manageSellers() {
        $sellers = $this->userModel->getAllByRole('seller');
        require 'views/admin/sellers.php';
    }

    public function createSeller($params) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'first_name' => cleanInput($params["first_name"]),
                'last_name' => cleanInput($params["last_name"]),
                'email' => cleanInput($params["email"]),
                'password' => cleanInput($params["password"])
            ];

            if ($this->userModel->createSeller($data)) {
                $_SESSION['success'] = "Seller created successfully";
            } else {
                $_SESSION['error'] = "Failed to create seller";
            }
            header('Location: index.php?controller=admin&action=manageSellers');
            exit();
        }
        require 'views/admin/create_seller.php';
    }

    public function editSeller($params) {
        // Validate ID  
        $id = (int) cleanInput($params["id"]);

        // Validate seller exists  
        $seller = $this->userModel->getById($id);
        if (!$seller) {
            $_SESSION['error'] = "Seller not found";
            header('Location: index.php?controller=admin&action=manageSellers');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token  
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $_SESSION['error'] = "Invalid security token";
                header('Location: index.php?controller=admin&action=manageSellers');
                exit();
            }


            $data = [
                'first_name' => cleanInput($params["first_name"]),
                'last_name' => cleanInput($params["last_name"]),
                'email' => cleanInput($params["email"])
            ];

            // Validate required fields  
            if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email'])) {
                $_SESSION['error'] = "All fields are required";
                $_SESSION['form_data'] = $data; // Preserve form data  
                header("Location: index.php?controller=admin&action=editSeller&id=$id");
                exit();
            }

            // Validate email format  
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Invalid email format";
                $_SESSION['form_data'] = $data;
                header("Location: index.php?controller=admin&action=editSeller&id=$id");
                exit();
            }

            if ($this->userModel->updateUser($id, $data)) {
                $_SESSION['success'] = "Seller updated successfully";
                header('Location: index.php?controller=admin&action=manageSellers');
            } else {
                $_SESSION['error'] = "Failed to update seller";
                $_SESSION['form_data'] = $data;
                header("Location: index.php?controller=admin&action=editSeller&id=$id");
            }
            exit();
        }

        // Generate CSRF token for the form  
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        // Load the view  
        require 'views/admin/edit_seller.php';
    }

    public function toggleSeller($params) {

        $id = cleanInput($params["id"]);

        if ($this->userModel->toggleActive($id)) {
            $_SESSION['success'] = "Seller status updated successfully";
        } else {
            $_SESSION['error'] = "Failed to update seller status";
        }
        header('Location: index.php?controller=admin&action=manageSellers');
        exit();
    }
}
