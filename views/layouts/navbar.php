<?php  
function isActive($controller, $action) {  
    return ($_GET['controller'] ?? '') == $controller &&   
           ($_GET['action'] ?? '') == $action;  
}  
?>  



<nav class="bg-white shadow-md">  
    <div class="max-w-7xl mx-auto px-4">  
        <div class="flex justify-between items-center h-16">  
            <!-- Logo/Brand -->  
            <div class="flex-shrink-0">  
                <a href="index.php" class="flex items-center">  
                    <span class="text-2xl font-bold text-primary">JS-EP Store</span>  
                </a>  
            </div>  

            <!-- Desktop Navigation -->  
            <div class="hidden md:flex md:items-center md:space-x-8">  
                <a href="index.php"   
                   class="nav-link <?= isActive('customer', 'index') ? 'nav-link-active' : 'nav-link-default' ?>">  
                    Products  
                </a>  
                
               
                <a href="index.php?controller=customer&action=cart"   
                   class="nav-link <?= isActive('customer', 'cart') ? 'nav-link-active' : 'nav-link-default' ?>">  
                    Cart   
                    <?php if (!empty($_SESSION['cart'])): ?>  
                        <span class="ml-2 bg-primary text-white text-xs px-2 py-1 rounded-full">  
                            <?= count($_SESSION['cart']) ?>  
                        </span>  
                    <?php endif; ?>  
                </a>  

                <?php if (isset($_SESSION['user']['id'])): ?>  
                    <div class="relative group">  
                        <button class="nav-link nav-link-default inline-flex items-center">  
                            <span>Account</span>  
                            <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">  
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />  
                            </svg>  
                        </button>  
                        
                        <!-- Dropdown -->  
                        <div class="absolute right-0 w-48 mt-2 origin-top-right bg-white border border-gray-200 divide-y divide-gray-100 rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">  
                            <div class="py-1">  
                                <a href="index.php?controller=customer&action=orderHistory"   
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">  
                                    My Orders  
                                </a>  
                                <a href="index.php?controller=user&action=profile"   
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">  
                                    Profile  
                                </a>  
                                <a href="index.php?controller=auth&action=logout"   
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">  
                                    Logout  
                                </a>  
                            </div>  
                        </div>  
                    </div>  
                
                <?php else: ?>     
                    <a href="index.php?controller=auth&action=login"   
                       class="nav-link nav-link-default">  
                        Login  
                    </a>  
                <?php endif; ?>  
            </div>  

            <!-- Mobile menu button -->  
            <div class="md:hidden">  
                <button type="button"   
                        onclick="document.getElementById('mobile-menu').classList.toggle('hidden')"  
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-primary hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary">  
                    Menu  
                </button>  
            </div>  
        </div>  
    </div>  

    <!-- Mobile menu -->  
    <div class="hidden md:hidden" id="mobile-menu">  
        <div class="px-2 pt-2 pb-3 space-y-1">  
            <a href="index.php"   
               class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50  
                      <?= isActive('customer', 'index') ? 'bg-gray-50 text-primary' : '' ?>">  
                Products  
            </a>  
            
            
            <a href="index.php?controller=customer&action=cart"   
               class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50  
                      <?= isActive('customer', 'cart') ? 'bg-gray-50 text-primary' : '' ?>">  
                Cart  
            </a>  
            
            <?php if (isset($_SESSION['user']['id'])): ?>  
                <a href="index.php?controller=customer&action=orderHistory"   
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">  
                    My Orders  
                </a>  
                <a href="index.php?controller=auth&action=logout"   
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">  
                    Logout  
                </a>  
            <?php else: ?>  
                <a href="index.php?controller=auth&action=login"   
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">  
                    Login  
                </a>  
            <?php endif; ?>  
        </div>  
    </div>  
</nav>