<?php
require_once 'views/layouts/header.php';
require_once 'views/layouts/navbar.php';

?>  

<main class="max-w-7xl mx-auto px-4 py-12">  
    <h1 class="text-center text-3xl font-bold text-gray-900 mb-12">Available Products</h1>  


    <div class="flex justify-center mb-8">  
        <a href="index.php?controller=customer&action=search"   
           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">  
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">  
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />  
            </svg>  
            Search Products  
        </a>  
    </div>

    <div class="container mx-auto px-4 py-8">  
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">  
            <?php foreach ($data['products'] as $product): ?>  
                <div class="bg-white rounded-lg shadow-md overflow-hidden">  
                    <!-- Product Images -->  
                    <?php if ($data['productImages'] && $data['productImages'][$product['id']] != null): ?>  
                        <div class="relative h-64">  
                            <!-- Show primary image first -->  
                            <img src="<?= htmlspecialchars($data['productImages'][$product['id']]['file_path']) ?>"  
                                 alt="<?= htmlspecialchars($product['name']) ?>"  
                                 class="w-full h-full object-cover">  


                        </div>  
                    <?php else: ?>  
                        <!-- Placeholder for products without images -->  
                        <div class="h-64 bg-gray-100 flex items-center justify-center">  
                            <span class="text-gray-400">No image available</span>  
                        </div>  
                    <?php endif; ?>  

                    <!-- Product Information -->  
                    <div class="p-4">  
                        <h3 class="text-lg font-semibold mb-2">  
                            <?= htmlspecialchars($product['name']) ?>  
                        </h3>  
                        <p class="text-gray-600 mb-4 line-clamp-2">  
                            <?= htmlspecialchars($product['description']) ?>  
                        </p>  
                        <div class="flex justify-between items-center">  
                            <span class="text-lg font-bold">  
                                €<?= number_format($product['price'], 2) ?>  
                            </span>  
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
                                        <button type="submit" disabled=<?= isset($_SESSION['user']) && $_SESSION['user']['role'] == "customer" ?>
                                                class="w-full max-w-xs bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">  
                                            Add to Cart  
                                        </button>  
                                    </div>  
                            </form>  
        <!--                            <button onclick="addToCart(<?= $product['id'] ?>)"   
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">  
                                        Add to Cart  
                                    </button>  -->
                        </div>  
                    </div>  
                </div>  
            <?php endforeach; ?>  
        </div>  
    </div>  

</main>  


<?php require_once 'views/layouts/footer.php'; ?>  