<?php
$pageTitle = 'Cart - Your Shop';
require_once 'views/layouts/header.php';
require_once 'views/layouts/navbar.php';
?>  

<main class="max-w-7xl mx-auto px-4 py-12">  


    <div class="container mx-auto px-4 py-8">  
        <div class="flex justify-between items-center mb-6">  
            <h1 class="text-2xl font-bold">Shopping Cart</h1>  
            <a href="index.php"   
               class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors"> Home  
            </a>  
        </div>  

        <?php if (empty($cartItems)): ?>  
            <p class="text-gray-600">Your cart is empty.</p>  
        <?php else: ?>  
            <div class="bg-white rounded-lg shadow">  
                <?php foreach ($cartItems as $item): ?>  
                    <div class="border-b p-4 flex items-center justify-between">  
                        <div>  
                            <h3 class="font-semibold"><?= htmlspecialchars($item['name']) ?></h3>  
                            <p class="text-gray-600">  
                                $<?= number_format($item['price'], 2) ?> x <?= $item['quantity'] ?>  
                            </p>  
                        </div>  

                        <form action="index.php?controller=customer&action=removeFromCart" method="POST">  
                            <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">  
                            <button type="submit"   
                                    class="text-red-500 hover:text-red-600">Remove</button>  
                        </form>  
                    </div>  
                <?php endforeach; ?>  

                <div class="p-4 border-t">  
                    <div class="text-xl font-bold">  
                        Total: $<?= number_format($this->cartModel->getTotal(), 2) ?>  
                    </div>  

                    <a href="index.php?controller=customer&action=checkout"   
                       class="mt-4 inline-block bg-green-500 text-white px-6 py-2 rounded   
                       hover:bg-green-600">  
                        Proceed to Checkout  
                    </a>  
                </div>  
            </div>  
        <?php endif; ?>  
    </div>  


</main>

<?php require_once 'views/layouts/footer.php'; ?> 