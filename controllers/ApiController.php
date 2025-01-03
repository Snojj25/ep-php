<?php

require_once 'controllers/utils.php';
require_once 'models/Product.php';

class ApiController {

    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    /**
     * Get all products  
     */
    public function index() {
        try {

            $products = $this->productModel->getAll();

            // Create a map of product images  
            $productImages = [];
            foreach ($products as $product) {

                $productImage[$product['id']] = $this->productModel->getProductImage($product['id']);
            }

            // Format response  
            $response = [
                'products' => $products,
                'productImages' => $productImages
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
