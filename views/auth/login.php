<?php   
$pageTitle = 'Login';  
require_once 'views/layouts/header.php';  
require_once 'views/layouts/navbar.php';  
?>  

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">  
    <div class="max-w-md w-full space-y-8">  
        <!-- Header -->  
        <div>  
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">  
                Sign in to your account  
            </h2>  
        </div>  

        <!-- Error Messages -->  
        <?php if (isset($_SESSION['error'])): ?>  
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">  
                <span class="block sm:inline"><?= $_SESSION['error'] ?></span>  
                <?php unset($_SESSION['error']); ?>  
            </div>  
        <?php endif; ?>  

        <!-- Success Messages -->  
        <?php if (isset($_SESSION['success'])): ?>  
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">  
                <span class="block sm:inline"><?= $_SESSION['success'] ?></span>  
                <?php unset($_SESSION['success']); ?>  
            </div>  
        <?php endif; ?>  

        <!-- Login Form -->  
        <form class="mt-8 space-y-6" action="index.php?controller=auth&action=login" method="POST">  
            <!-- Role Selection -->  
            <div>  
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>  
                <select id="role" name="role" required  
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">  
                    <option value="customer">Customer</option>  
                    <option value="seller">Seller</option>  
                    <option value="admin">Administrator</option>  
                </select>  
            </div>  

            <!-- Email -->  
            <div>  
                <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>  
                <input id="email" name="email" type="email" required  
                       class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">  
            </div>  

            <!-- Password -->  
            <div>  
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>  
                <input id="password" name="password" type="password" required  
                       class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">  
            </div>  

            <!-- Submit Button -->  
            <div>  
                <button type="submit"  
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">  
                    Sign in  
                </button>  
            </div>  

            <!-- Register Link -->  
            <div class="text-sm text-center">  
                <a href="index.php?controller=auth&action=register" class="font-medium text-blue-600 hover:text-blue-500">  
                    Don't have an account? Register here  
                </a>  
            </div>  
        </form>  
    </div>  
</div>  

<?php require_once 'views/layouts/footer.php'; ?>