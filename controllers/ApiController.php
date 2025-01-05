<?php

require_once 'controllers/utils.php';
require_once 'models/Product.php';
require_once 'models/User.php';
require_once 'models/Order.php';

class ApiController {

    private $productModel;
    private $userModel;
    private $orderModel;

    public function __construct() {
        $this->productModel = new Product();
        $this->userModel = new User();
        $this->orderModel = new Order();
    }

    /**
     * Get all products  
     */
    public function index() {
        try {

            $products = $this->productModel->getAll();

            for ($i = 0; $i < count($products); $i++) {

                // Convert the price to a float
                $products[$i]["price"] = floatval($products[$i]["price"]);

                // Get and base64 encode the file contents of the image
                $image_info = $this->productModel->getProductImage($products[$i]['id']);

                if ($image_info == null) {
                    continue;
                }

                $filepath = __DIR__ . "/../uploads/products/" . $image_info["file_name"];
                $imageData = base64_encode(file_get_contents($filepath));

                $products[$i]["imageBase64"] = $imageData;
            }


            // Format response  
            $response = [
                'products' => $products,
            ];

            ApiResponse::send($response);
        } catch (Exception $e) {
            error_log("API Error: " . $e->getMessage());
            ApiResponse::error('Internal server error', 500);
        }
    }

    /**
     * Get single product  
     */
    public function show($id) {
        try {

            // Validate ID  
            if (!is_numeric($id) || $id <= 0) {
                ApiResponse::error('Invalid product ID', 400);
            }

            // Get product  
            $product = $this->productModel->getById($id);

            if (!$product) {
                ApiResponse::error('Product not found', 404);
            }

            $productImage = $this->productModel->getProductImage($id);

            // Format response  
            $response = [
                'product' => $product,
                'productImage' => $productImage
            ];

            ApiResponse::send($response);
        } catch (Exception $e) {
            error_log("API Error: " . $e->getMessage());
            ApiResponse::error('Internal server error', 500);
        }
    }

    public function authenticate($params) {

        // NOTE: enforceHTTPS() -To bi moral klicat, ampak potem, bi moral nalozit nas certificate na android napravo, ampak to se ne splaca, ker je emulator in je bolj smotan.
        // example: https://localhost/?controller=api&action=authenticate&email=customer@example.com&password=password

        try {

            $email = $params["email"];
            $password = $params["password"];
            $role = "customer";

            // Validate required fields  
            if (empty($email) || empty($password) || empty($role)) {
                $_SESSION['error'] = "All fields are required.";
                return;
            }



            // Attempt login  
            $result = $this->userModel->login($email, $password, $role);

            if ($result['success']) {

                // Change is_active to boolean
                $result['user']['is_active'] = $result['user']['is_active'] == 1;

                // Format response  
                $response = [
                    'authenticated' => true,
                    "user" => $result['user']
                ];

                ApiResponse::send($response);
            } else {
                // Format response  
                $response = [
                    'authenticated' => false,
                    "user" => null
                ];

                ApiResponse::send($response);
            }
        } catch (Exception $e) {
            error_log("API Error: " . $e->getMessage());
            ApiResponse::error('Internal server error', 500);
        }
    }

    public function getOrderHistory($params) {

        // NOTE: enforceHTTPS() -To bi moral klicat, ampak potem, bi moral nalozit nas certificate na android napravo, ampak to se ne splaca, ker je emulator in je bolj smotan.
        // example: http://localhost:8000/?controller=api&action=getOrderHistory&userId=3

        try {

            $userId = $params["userId"];
            if (!isset($userId)) {
                ApiResponse::error('Invalid user ID', 500);
            }

            $orders = $this->orderModel->getByUser($userId);

            // Format response  
            $response = [
                "orders" => $orders
            ];

            ApiResponse::send($response);
        } catch (Exception $e) {
            error_log("API Error: " . $e->getMessage());
            ApiResponse::error('Internal server error', 500);
        }
    }

    public function placeOrder() {

        // NOTE: enforceHTTPS() -To bi moral klicat, ampak potem, bi moral nalozit nas certificate na android napravo, ampak to se ne splaca, ker je emulator in je bolj smotan.
        // NOTE: We would also need more checks because the api can be called by anyone right now, to prevent malicious inputs
        // example: http://localhost:8000/?controller=api&action=placeOrder$



        $jsonInput = file_get_contents('php://input');
        $data = json_decode($jsonInput, true);
        
        

        try {

            $userId = $data["user_id"];
            if (!isset($userId)) {
                return ApiResponse::error('Invalid user ID', 500);
            }



            $requiredFields = ['shipping_address', 'postal_code', 'city', 'phone'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    return ApiResponse::error('Invalid shipping information fields', 500);
                }
            }



            $shipping_info = [
                'shipping_address' => cleanInput($data['shipping_address']),
                'postal_code' => cleanInput($data['postal_code']),
                'city' => cleanInput($data["city"]),
                'phone' => cleanInput($data["phone"]),
                'notes' => cleanInput($data["notes"]) ?? null
            ];

            $cartItems = $data["cart_items"];
            // TODO: validate_cart_items() - check for malformed/improper input
            if (empty($cartItems)) {
                return ApiResponse::error('Invalid Cart Items', 500);
            }

            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item['price'] * $item['quantity'];
            }

           

            $orderId = $this->orderModel->create(
                    $userId,
                    $cartItems,
                    $total,
                    $shipping_info
            );

            // Format response
            $response = [
                "order_id" => $orderId
            ];
            ApiResponse::send($response);
        } catch (Exception $ex) {

            // Format response  
            $response = [
                "orderId" => $ex->getMessage()
            ];
            ApiResponse::send($response);

            error_log("API Error: " . $ex->getMessage());
            ApiResponse::error('Internal server error', 500);
        }
    }
}

class ApiResponse {

    public static function send($data, $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);

        echo json_encode([
            'status' => $statusCode >= 200 && $statusCode < 300 ? 'success' : 'error',
            'data' => $data
        ]);
        exit;
    }

    public static function error($message, $statusCode = 400) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode([
            'status' => 'error',
            'message' => $message
        ]);
        exit;
    }
}
