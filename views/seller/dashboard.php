<?php $title = 'Seller Dashboard'; require 'views/layouts/header.php'; ?>  

<div class="container mx-auto px-4 py-8">  
    <h1 class="text-2xl font-bold mb-6">Seller Dashboard</h1>  
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">  
        <div class="bg-white rounded-lg shadow p-6">  
            <h2 class="text-xl font-semibold mb-4">Products</h2>  
            <a href="index.php?controller=seller&action=manageProducts"   
               class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">  
                Manage Products  
            </a>  
        </div>  
        
        <div class="bg-white rounded-lg shadow p-6">  
            <h2 class="text-xl font-semibold mb-4">Orders</h2>  
            <a href="index.php?controller=seller&action=manageOrders"   
               class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">  
                Manage Orders  
            </a>  
        </div>  
        
        <div class="bg-white rounded-lg shadow p-6">  
            <h2 class="text-xl font-semibold mb-4">Customers</h2>  
            <a href="index.php?controller=seller&action=manageCustomers"   
               class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">  
                Manage Customers  
            </a>  
        </div>  
    </div>  
</div>  