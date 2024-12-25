<?php $title = 'Manage Products'; require 'views/layouts/header.php'; ?>  

<div class="container mx-auto px-4 py-8">  
    <div class="flex justify-between items-center mb-6">  
        <h1 class="text-2xl font-bold">Manage Products</h1>  
        <a href="index.php?controller=seller&action=createProduct"   
           class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">  
            Add New Product  
        </a>  
    </div>  
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">  
        <?php foreach ($products as $product): ?>  
        <div class="bg-white rounded-lg shadow p-6">  
            <h2 class="text-xl font-semibold"><?= htmlspecialchars($product['name']) ?></h2>  
            <p class="text-gray-600 mt-2"><?= htmlspecialchars($product['description']) ?></p>  
            <p class="text-lg font-bold mt-4">$<?= number_format($product['price'], 2) ?></p>  
            
            <div class="mt-4 flex gap-2">  
                <a href="index.php?controller=seller&action=editProduct&id=<?= $product['id'] ?>"   
                   class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">  
                    Edit  
                </a>  
                
                <form action="index.php?controller=seller&action=toggleProduct" method="POST" class="inline">  
                    <input type="hidden" name="id" value="<?= $product['id'] ?>">  
                    <input type="hidden" name="active" value="<?= $product['is_active'] ? '0' : '1' ?>">  
                    <button type="submit"   
                            class="<?= $product['is_active'] ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' ?> text-white px-4 py-2 rounded">  
                        <?= $product['is_active'] ? 'Deactivate' : 'Activate' ?>  
                    </button>  
                </form>  
            </div>  
        </div>  
        <?php endforeach; ?>  
    </div>  
</div>  