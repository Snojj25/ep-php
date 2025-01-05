
<?php
$pageTitle = 'Cart - Your Shop';
require_once 'views/layouts/header.php';
require_once 'views/layouts/navbar.php';
?>  
 

            <main class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                    <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

                    <!-- Order Summary -->
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
                        <div class="px-4 py-5 sm:px-6">
                            <h2 class="text-lg font-medium text-gray-900">Order Summary</h2>
                        </div>

                        <div class="border-t border-gray-200">
                            <div class="px-4 py-5 sm:p-6">
                                <?php if (empty($cartItems)): ?>
                                    <p class="text-gray-500">Your cart is empty.</p>
                                <?php else: ?>
                                    <div class="space-y-4">
                                        <?php foreach ($cartItems as $item): ?>
                                            <div class="flex justify-between items-center border-b border-gray-100 pb-4">
                                                <div>
                                                    <h3 class="text-lg font-medium text-gray-900">
                                                        <?= htmlspecialchars($item['name']) ?>
                                                    </h3>
                                                    <p class="text-gray-500">
                                                        Quantity: <?= htmlspecialchars($item['quantity']) ?>
                                                    </p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-lg font-medium text-gray-900">
                                                        $<?= number_format($item['price'] * $item['quantity'], 2) ?>
                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        $<?= number_format($item['price'], 2) ?> each
                                                    </p>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>

                                        <!-- Total -->
                                        <div class="pt-4">
                                            <div class="flex justify-between items-center">
                                                <h3 class="text-lg font-bold text-gray-900">Order Total</h3>
                                                <p class="text-xl font-bold text-gray-900">
                                                    $<?= number_format($this->cartModel->getTotal(), 2) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($cartItems)): ?>
                        <!-- Checkout Form -->
                        <form method="POST" action="index.php?controller=customer&action=checkout" class="bg-white shadow sm:rounded-lg">

                            <?php if (isset($_SESSION['error'])): ?>  
                                <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4">  
                                    <div class="flex">  
                                        <div class="flex-shrink-0">  
                                            <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">  
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />  
                                            </svg>  
                                        </div>  
                                        <div class="ml-3">  
                                            <p class="text-sm text-red-700"><?= htmlspecialchars($_SESSION['error']) ?></p>  
                                        </div>  
                                    </div>  
                                </div>  
                                <?php unset($_SESSION['error']); ?>  
                            <?php endif; ?>  

                            <div class="px-4 py-5 sm:p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-6">Shipping Information</h2>

                                <!-- Shipping Details -->
                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <div>
                                        <label for="shipping_address" class="block text-sm font-medium text-gray-700">
                                            Shipping Address
                                        </label>
                                        <input type="text" 
                                               name="shipping_address" 
                                               id="shipping_address" 
                                               value="<?= htmlspecialchars($_SESSION['shipping_address'] ?? '') ?>"
                                               required
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>

                                    <div>
                                        <label for="postal_code" class="block text-sm font-medium text-gray-700">
                                            Postal Code
                                        </label>
                                        <input type="text" 
                                               name="postal_code" 
                                               id="postal_code" 
                                               value="<?= htmlspecialchars($_SESSION['postal_code'] ?? '') ?>"
                                               required
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>

                                    <div>
                                        <label for="city" class="block text-sm font-medium text-gray-700">
                                            City
                                        </label>
                                        <input type="text" 
                                               name="city" 
                                               id="city" 
                                               value="<?= htmlspecialchars($_SESSION['city'] ?? '') ?>"
                                               required
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>

                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700">
                                            Phone Number
                                        </label>
                                        <input type="tel" 
                                               name="phone" 
                                               id="phone" 
                                               value="<?= htmlspecialchars($_SESSION['phone'] ?? '') ?>"
                                               required
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>
                                </div>

                                <!-- Additional Notes -->
                                <div class="mt-6">
                                    <label for="notes" class="block text-sm font-medium text-gray-700">
                                        Order Notes (Optional)
                                    </label>
                                    <textarea name="notes" 
                                              id="notes" 
                                              rows="3" 
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-6 flex items-center justify-end space-x-4">
                                    <a href="index.php?controller=customer&action=cart" 
                                       class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                        Return to Cart
                                    </a>
                                    <button type="submit" 
                                            class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        Place Order
                                    </button>
                                </div>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="text-center">
                            <a href="index.php?controller=customer&action=index" 
                               class="inline-flex items-center text-blue-600 hover:text-blue-500">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Continue Shopping
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </main>



<?php require_once 'views/layouts/footer.php'; ?> 