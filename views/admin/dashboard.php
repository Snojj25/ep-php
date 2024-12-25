<?php
require_once 'views/layouts/header.php';
require_once 'views/layouts/navbar.php';
?>  

<div class="container mx-auto px-4 py-8">  
    <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>  
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">  
        <div class="bg-white p-6 rounded-lg shadow">  
            <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>  
            <ul class="space-y-2">  
                <li>  
                    <a href="index.php?controller=admin&action=manageSellers"   
                       class="text-blue-600 hover:underline">Manage Sellers</a>  
                </li>  
                <li>  
                    <a href="index.php?controller=admin&action=profile"   
                       class="text-blue-600 hover:underline">Update Profile</a>  
                </li>  
                <li>  
                    <a href="index.php?controller=admin&action=changePassword"   
                       class="text-blue-600 hover:underline">Change Password</a>  
                </li>  
            </ul>  
        </div>  
        
        <div class="bg-white p-6 rounded-lg shadow">  
            <h2 class="text-xl font-semibold mb-4">Profile Information</h2>  
            <div class="space-y-2">  
                <p><strong>Name:</strong> <?= htmlspecialchars($adminData['first_name'] . ' ' . $adminData['last_name']) ?></p>  
                <p><strong>Email:</strong> <?= htmlspecialchars($adminData['email']) ?></p>  
            </div>  
        </div>  
    </div>  
</div>  

<?php require_once 'views/layouts/footer.php'; ?>  