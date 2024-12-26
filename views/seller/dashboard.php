<?php
require_once 'views/layouts/header.php';
require_once 'views/layouts/navbar.php';
?>  


<div class="container mx-auto px-4 py-8">  
    <h1 class="text-3xl font-bold mb-8">Seller Dashboard</h1>  

    <!-- Stats Grid -->  
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">  
        <!-- Pending Orders -->  
        <div class="bg-white rounded-lg shadow p-6">  
            <div class="flex items-center">  
                <div class="p-3 rounded-full bg-orange-100 text-orange-500">  
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">  
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>  
                    </svg>  
                </div>  
                <div class="ml-4">  
                    <p class="mb-2 text-sm font-medium text-gray-600">Pending Orders</p>  
                    <p class="text-lg font-semibold text-gray-700"><?= $pendingOrdersCount ?></p>  
                </div>  
            </div>  
        </div>  

        <!-- Confirmed Orders -->  
        <div class="bg-white rounded-lg shadow p-6">  
            <div class="flex items-center">  
                <div class="p-3 rounded-full bg-green-100 text-green-500">  
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">  
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>  
                    </svg>  
                </div>  
                <div class="ml-4">  
                    <p class="mb-2 text-sm font-medium text-gray-600">Confirmed Orders</p>  
                    <p class="text-lg font-semibold text-gray-700"><?= $confirmedOrdersCount ?></p>  
                </div>  
            </div>  
        </div>  

        <!-- Products -->  
        <div class="bg-white rounded-lg shadow p-6">  
            <div class="flex items-center">  
                <div class="p-3 rounded-full bg-blue-100 text-blue-500">  
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">  
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>  
                    </svg>  
                </div>  
                <div class="ml-4">  
                    <p class="mb-2 text-sm font-medium text-gray-600">Total Products</p>  
                    <p class="text-lg font-semibold text-gray-700"><?= $productsCount ?></p>  
                </div>  
            </div>  
        </div>  

        <!-- Customers -->  
        <div class="bg-white rounded-lg shadow p-6">  
            <div class="flex items-center">  
                <div class="p-3 rounded-full bg-purple-100 text-purple-500">  
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">  
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>  
                    </svg>  
                </div>  
                <div class="ml-4">  
                    <p class="mb-2 text-sm font-medium text-gray-600">Total Customers</p>  
                    <p class="text-lg font-semibold text-gray-700"><?= $customersCount ?></p>  
                </div>  
            </div>  
        </div>  
    </div>  

    <!-- Quick Actions -->  
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">  

        <!-- Quick Links --> 
        <div class="bg-white rounded-lg shadow">  
            <div class="p-6">  
                <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>  
                <div class="space-y-4">  
                    <a href="index.php?controller=seller&action=createProduct"   
                       class="block p-4 border rounded hover:bg-gray-50">  
                        <div class="flex items-center">  
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">  
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>  
                            </svg>  
                            <span class="ml-3">Add New Product</span>  
                        </div>  
                    </a>  
                    <a href="index.php?controller=seller&action=manageCustomers"   
                       class="block p-4 border rounded hover:bg-gray-50">  
                        <div class="flex items-center">  
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">  
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>  
                            </svg>  
                            <span class="ml-3">Manage Customers</span>  
                        </div>  
                    </a>  
                </div>  
            </div>  
        </div>  

        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow p-6">  
            <h2 class="text-xl font-semibold mb-4">Recent Orders</h2>  
            <div class="space-y-4">  
                <?php foreach ($this->orderModel->getRecent(5) as $order): ?>  
                    <div class="flex items-center justify-between border-b pb-4">  
                        <div>  
                            <p class="font-medium">Order #<?= htmlspecialchars($order['id']) ?></p>  
                            <p class="text-sm text-gray-600"><?= htmlspecialchars($order['created_at']) ?></p>  
                        </div>  
                        <span class="px-3 py-1 text-sm rounded-full <?= getStatusClass($order['status']) ?>">  
                            <?= ucfirst(htmlspecialchars($order['status'])) ?>  
                        </span>  
                    </div>  
                <?php endforeach; ?>  
            </div>  
            <a href="index.php?controller=seller&action=manageOrders"   
               class="mt-4 inline-block text-blue-600 hover:text-blue-800">  
                View all orders →  
            </a>  

        </div>  

    </div>  
</div>  


<?php require_once 'views/layouts/footer.php'; ?>  