<?php
$pageTitle = 'Products - Your Shop';
require_once 'views/layouts/header.php';
require_once 'views/layouts/navbar.php';
?>  

<main class="max-w-7xl mx-auto px-4 py-12">  
    <h1 class="text-center text-3xl font-bold text-gray-900 mb-12">Available Products</h1>  

    <div class="container mx-auto px-4 py-8">  
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">  
            <?php foreach ($data['products'] as $product): ?>  
                <div class="bg-white rounded-lg shadow-md overflow-hidden">  
                    <!-- Product Images -->  
                    <?php if (!empty($data['productImages'][$product['id']])): ?>  
                        <div class="relative h-64">  
                            <!-- Show primary image first -->  
                            <img src="<?= htmlspecialchars($data['productImages'][$product['id']][0]['file_path']) ?>"  
                                 alt="<?= htmlspecialchars($product['name']) ?>"  
                                 class="w-full h-full object-cover">  

                            <!-- If there are multiple images, show indicator -->  
                            <?php if (count($data['productImages'][$product['id']]) > 1): ?>  
                                <span class="absolute bottom-2 right-2 bg-gray-800 text-white px-2 py-1 rounded-full text-xs">  
                                    +<?= count($data['productImages'][$product['id']]) - 1 ?> more  
                                </span>  
                            <?php endif; ?>  
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
                            <button onclick="addToCart(<?= $product['id'] ?>)"   
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">  
                                Add to Cart  
                            </button>  
                        </div>  
                    </div>  
                </div>  
            <?php endforeach; ?>  
        </div>  
    </div>  

</main>  


<?php require_once 'views/layouts/footer.php'; ?>  