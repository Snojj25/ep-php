
<?php
$pageTitle = 'Products - Your Shop';
require_once 'views/layouts/header.php';
require_once 'views/layouts/navbar.php';
?>  

<main class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Success Message -->
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-8">
            <div class="flex">
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">
                        Order Successfully Placed!
                    </h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>Thank you for your order. Your order number is: #<?= htmlspecialchars($orderId) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h2 class="text-lg font-medium text-gray-900">Order Details</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Order placed on <?= date('F j, Y', strtotime($order['created_at'])) ?>
                </p>
            </div>

            <div class="border-t border-gray-200">
                <!-- Shipping Information -->
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Shipping Information</h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">  
                        <div>  
                            <dt class="text-sm font-medium text-gray-500">Shipping Address</dt>  
                            <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($order['shipping_address'] ?? '') ?></dd>  
                        </div>  
                        <div>  
                            <dt class="text-sm font-medium text-gray-500">City</dt>  
                            <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($order['city'] ?? '') ?></dd>  
                        </div>  
                        <div>  
                            <dt class="text-sm font-medium text-gray-500">Postal Code</dt>  
                            <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($order['postal_code'] ?? '') ?></dd>  
                        </div>  
                        <div>  
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>  
                            <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($order['phone'] ?? '') ?></dd>  
                        </div>  
                    </dl>  
                </div>

                <!-- Order Items -->
                <div class="px-4 py-5 sm:p-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Order Items</h3>
                    <div class="space-y-4">
                        <?php foreach ($orderItems as $item): ?>
                            <div class="flex justify-between items-center border-b border-gray-100 pb-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </h4>
                                    <p class="text-sm text-gray-500">
                                        Quantity: <?= htmlspecialchars($item['quantity']) ?>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">
                                        $<?= number_format($item['price'] * $item['quantity'], 2) ?>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        $<?= number_format($item['price'], 2) ?> each
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Order Total -->
                        <div class="pt-4">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-medium text-gray-900">Order Total</h3>
                                <p class="text-lg font-medium text-gray-900">
                                    $<?= number_format($order['total_amount'], 2) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-center space-x-4">
            <a href="index.php?controller=customer&action=orderHistory" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                View Order History
            </a>
            <br>
            <a href="index.php?controller=customer&action=index" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Continue Shopping
            </a>
        </div>
    </div>
</main>


<?php require_once 'views/layouts/footer.php'; ?> 