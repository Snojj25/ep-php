<?php
$pageTitle = 'Products - Your Shop';
require_once 'views/layouts/header.php';
require_once 'views/layouts/navbar.php';
?>  

<main class="max-w-7xl mx-auto px-4 py-12">  
    <h1 class="text-center text-3xl font-bold text-gray-900 mb-12">Available Products</h1>  

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">  
        <?php foreach ($products as $product): ?>  
            <div class="bg-white border-2 border-gray-200 rounded-lg overflow-hidden hover:border-blue-500 transition-colors duration-200">  
                <div class="p-6">  
                    <!-- Product Header -->  
                    <div class="text-center mb-6">  
                        <h2 class="text-2xl font-semibold text-gray-900 mb-2">  
                            <?= htmlspecialchars($product['name']) ?>  
                        </h2>  
                        <div class="h-px bg-gray-200 my-4"></div>  
                    </div>  

                    <!-- Product Details -->  
                    <div class="space-y-4">  
                        <p class="text-gray-600 text-center leading-relaxed">  
                            <?= htmlspecialchars($product['description']) ?>  
                        </p>  

                        <div class="text-center">  
                            <span class="text-3xl font-bold text-gray-900">  
                                $<?= number_format($product['price'], 2) ?>  
                            </span>  
                        </div>  
                    </div>  

                    <!-- Add to Cart Form -->  
                    <form action="index.php?controller=customer&action=addToCart"   
                          method="POST"   
                          class="mt-8">  
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">  

                        <div class="flex flex-col items-center space-y-4">  
                            <div class="w-full max-w-xs">  
                                <label for="quantity-<?= $product['id'] ?>"   
                                       class="block text-sm font-medium text-gray-700 mb-1 text-center">  
                                    Quantity  
                                </label>  
                                <input type="number"   
                                       id="quantity-<?= $product['id'] ?>"  
                                       name="quantity"   
                                       value="1"   
                                       min="1"  
                                       class="block w-full rounded-md border-gray-300 text-center focus:border-blue-500 focus:ring-blue-500">  
                            </div>  

                            <button type="submit"   
                                    class="w-full max-w-xs bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">  
                                Add to Cart  
                            </button>  
                        </div>  
                    </form>  
                </div>  
            </div>  
        <?php endforeach; ?>  
    </div>  
</main>  


<?php require_once 'views/layouts/footer.php'; ?>  