<?php
require_once 'views/layouts/header.php';
require_once 'views/layouts/navbar.php';
?>  


<div class="container mx-auto px-4 py-8">  
    <!-- Header -->  
    <div class="flex justify-between items-center mb-6">  
        <h1 class="text-2xl font-bold">Manage Products</h1>  
        <a href="index.php?controller=seller&action=createProduct"   
           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">  
            Add New Product  
        </a>  
    </div>  

    <!-- Messages -->  
    <?php if (isset($_SESSION['success'])): ?>  
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">  
            <span class="block sm:inline"><?= htmlspecialchars($_SESSION['success']) ?></span>  
            <?php unset($_SESSION['success']); ?>  
        </div>  
    <?php endif; ?>  

    <?php if (isset($_SESSION['error'])): ?>  
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">  
            <span class="block sm:inline"><?= htmlspecialchars($_SESSION['error']) ?></span>  
            <?php unset($_SESSION['error']); ?>  
        </div>  
    <?php endif; ?>  

    <!-- Products Table -->  
    <div class="bg-white shadow-md rounded my-6">  
        <table class="min-w-full table-auto">  
            <thead>  
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">  
                    <th class="py-3 px-6 text-left">Name</th>  
                    <th class="py-3 px-6 text-left">Description</th>  
                    <th class="py-3 px-6 text-right">Price</th>  
                    <th class="py-3 px-6 text-center">Status</th>  
                    <th class="py-3 px-6 text-center">Actions</th>  
                </tr>  
            </thead>  
            <tbody class="text-gray-600 text-sm font-light">  
                <?php foreach ($products as $product): ?>  
                    <tr class="border-b border-gray-200 hover:bg-gray-100">  
                        <td class="py-3 px-6 text-left">  
                            <?= htmlspecialchars($product['name']) ?>  
                        </td>  
                        <td class="py-3 px-6 text-left">  
                            <?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...  
                        </td>  
                        <td class="py-3 px-6 text-right">  
                            €<?= number_format($product['price'], 2) ?>  
                        </td>  
                        <td class="py-3 px-6 text-center">  
                            <span class="<?= $product['is_active'] ? 'bg-green-200 text-green-600' : 'bg-red-200 text-red-600' ?> py-1 px-3 rounded-full text-xs">  
                                <?= $product['is_active'] ? 'Active' : 'Inactive' ?>  
                            </span>  
                        </td>  
                        <td class="py-3 px-6 text-center">  
                            <div class="flex item-center justify-center">  
                                <a href="index.php?controller=seller&action=editProduct&id=<?= $product['id'] ?>"   
                                   class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">  
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">  
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />  
                                    </svg>  
                                </a>  
                                <a href="index.php?controller=seller&action=toggleProductStatus&id=<?= $product['id'] ?>"   
                                   class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110"  
                                   onclick="return confirm('Are you sure you want to <?= $product['is_active'] ? 'deactivate' : 'activate' ?> this product?')">  
                                    <?php if ($product['is_active']): ?>  
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">  
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />  
                                        </svg>  
                                    <?php else: ?>  
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">  
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />  
                                        </svg>  
                                    <?php endif; ?>  
                                </a>  
                            </div>  
                        </td>  
                    </tr>  
                <?php endforeach; ?>  
            </tbody>  
        </table>  
    </div>  
</div>  

                                      
<?php require_once 'views/layouts/footer.php'; ?>