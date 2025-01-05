<?php

require_once 'models/User.php';
require_once 'controllers/utils.php';

class UserController {

    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function profile() {
        // Check if user is logged in  
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $user = $this->userModel->getById($_SESSION['user']['id']);
        require 'views/user/profile.php';
    }

    public function updateProfile($params) {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {

                
                
                $name_arr = explode(" ", $params['name']);

                // Validate input  
                $data = [
                    'first_name' => cleanInput($name_arr[0]),
                    'last_name' =>  cleanInput($name_arr[1]),
                    'email' => cleanInput($params['email'])
                ];

                // Validate email format  
                if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('Invalid email format');
                }

                // Update profile  
                if ($this->userModel->updateUser($_SESSION['user']['id'], $data)) {
                    // Update session data  
                    $_SESSION['user']['first_name'] = $data['first_name'];
                    $_SESSION['user']['last_name'] = $data['last_name'];
                    $_SESSION['user']['email'] = $data['email'];

                    $_SESSION['success'] = 'Profile updated successfully';
                } else {
                    throw new Exception('Failed to update profile');
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }

        header('Location: index.php?controller=user&action=profile');
        exit();
    }

    public function updatePassword($params) {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $currentPassword = cleanInput($params['current_password']);
                $newPassword = cleanInput($params['new_password']);
                $confirmPassword = cleanInput($params['confirm_password']);

                // Validate passwords  
                if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                    throw new Exception('All password fields are required');
                }

                if ($newPassword !== $confirmPassword) {
                    throw new Exception('New passwords do not match');
                }

                if (strlen($newPassword) < 8) {
                    throw new Exception('Password must be at least 8 characters long');
                }

                // Update password  
                $result = $this->userModel->updatePassword(
                        $_SESSION['user']['id'],
                        $currentPassword,
                        $newPassword
                );

                if ($result['success']) {
                    $_SESSION['success'] = $result['message'];
                } else {
                    throw new Exception($result['message']);
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }

        header('Location: index.php?controller=user&action=profile');
        exit();
    }
}
