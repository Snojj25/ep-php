<?php

class Cart {
    public function __construct() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public static function getInstance() {
        static $instance = null;
        if ($instance === null) {
            $instance = new Cart();
        }
        return $instance;
    }

    public function addItem($product_id, $quantity) {
        $product = (new Product())->getById($product_id);
        
        if (!$product) {
            throw new Exception("Product not found");
        }

        if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = [
                'product_id' => $product_id,
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 0
            ];
        }
        
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }

    public function updateQuantity($product_id, $quantity) {
        if (isset($_SESSION['cart'][$product_id])) {
            if ($quantity <= 0) {
                $this->removeItem($product_id);
            } else {
                $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            }
        }
    }

    public function removeItem($product_id) {
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }
    

    public function getItems() {
        return $_SESSION['cart'] ?? [];
    }

    public function getTotal() {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public function getItemCount() {
        $count = 0;
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }

    public function clear() {
        $_SESSION['cart'] = [];
    }
}