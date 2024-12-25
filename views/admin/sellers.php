<?php
require_once 'views/layouts/header.php';
require_once 'views/layouts/navbar.php';
?>  
 

<div class="container mx-auto px-4 py-8">  
    <div class="flex justify-between items-center mb-6">  
        <h1 class="text-2xl font-bold">Manage Sellers</h1>  
        <a href="index.php?controller=admin&action=createSeller"   
           class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">  
            Add New Seller  
        </a>  
    </div>  

    <?php if (isset($_SESSION['success'])): ?>  
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">  
            <?= htmlspecialchars($_SESSION['success']) ?>  
            <?php unset($_SESSION['success']); ?>  
        </div>  
    <?php endif; ?>  

    <div class="bg-white shadow-md rounded-lg overflow-hidden">  
        <table class="min-w-full">  
            <thead class="bg-gray-50">  
                <tr>  
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>  
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>  
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>  
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>  
                </tr>  
            </thead>  
            <tbody class="bg-white divide-y divide-gray-200">  
                <?php foreach ($sellers as $seller): ?>  
                <tr>  
                    <td class="px-6 py-4 whitespace-nowrap">  
                        <?= htmlspecialchars($seller['first_name'] . ' ' . $seller['last_name']) ?>  
                    </td>  
                    <td class="px-6 py-4 whitespace-nowrap">  
                        <?= htmlspecialchars($seller['email']) ?>  
                    </td>  
                    <td class="px-6 py-4 whitespace-nowrap">  
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full   
                            <?= $seller['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">  
                            <?= $seller['is_active'] ? 'Active' : 'Inactive' ?>  
                        </span>  
                    </td>  
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">  
                        <a href="index.php?controller=admin&action=editSeller&id=<?= $seller['id'] ?>"   
                           class="text-blue-600 hover:text-blue-900 mr-4">Edit</a>  
                        
                        <form action="index.php?controller=admin&action=toggleSeller&id=<?= $seller['id'] ?>"   
                              method="POST" class="inline">  
                            <input type="hidden" name="active" value="<?= $seller['is_active'] ? '0' : '1' ?>">  
                            <button type="submit" class="text-<?= $seller['is_active'] ? 'red' : 'green' ?>-600   
                                    hover:text-<?= $seller['is_active'] ? 'red' : 'green' ?>-900">  
                                <?= $seller['is_active'] ? 'Deactivate' : 'Activate' ?>  
                            </button>  
                        </form>  
                    </td>  
                </tr>  
                <?php endforeach; ?>  
            </tbody>  
        </table>  
    </div>  
</div>  

<?php require_once 'views/layouts/footer.php'; ?>  