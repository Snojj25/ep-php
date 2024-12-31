<?php   
$pageTitle = 'Register';  
require_once 'views/layouts/header.php';  
require_once 'views/layouts/navbar.php';  
?>  

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">  
    <div class="max-w-md w-full space-y-8">  
        <!-- Header -->  
        <div>  
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">  
                Create new account  
            </h2>  
            <p class="mt-2 text-center text-sm text-gray-600">  
                Or  
                <a href="index.php?controller=auth&action=login" class="font-medium text-blue-600 hover:text-blue-500">  
                    sign in to your existing account  
                </a>  
            </p>  
        </div>  

        <!-- Error Messages -->  
        <?php if (isset($_SESSION['error'])): ?>  
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">  
                <span class="block sm:inline"><?= $_SESSION['error'] ?></span>  
                <?php unset($_SESSION['error']); ?>  
            </div>  
        <?php endif; ?>  

        <!-- Registration Form -->  
        <form class="mt-8 space-y-6" action="index.php?controller=auth&action=register" method="POST">  
            <!-- Personal Information Section -->  
            <div class="bg-white p-6 rounded-lg shadow-sm space-y-6">  
                <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>  
                
                <div class="grid grid-cols-2 gap-4">  
                    <!-- First Name -->  
                    <div>  
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>  
                        <input id="first_name"   
                               name="first_name"   
                               type="text"   
                               required  
                               value="<?= $_POST['first_name'] ?? '' ?>"  
                               class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">  
                    </div>  

                    <!-- Last Name -->  
                    <div>  
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>  
                        <input id="last_name"   
                               name="last_name"   
                               type="text"   
                               required  
                               value="<?= $_POST['last_name'] ?? '' ?>"  
                               class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">  
                    </div>  
                </div>  

                <!-- Email -->  
                <div>  
                    <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>  
                    <input id="email"   
                           name="email"   
                           type="email"   
                           required  
                           value="<?= $_POST['email'] ?? '' ?>"  
                           class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">  
                </div>  

                <!-- Password -->  
                <div>  
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>  
                    <input id="password"   
                           name="password"   
                           type="password"   
                           required  
                           minlength="8"  
                           class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">  
                    <p class="mt-1 text-sm text-gray-500">Password must be at least 8 characters long</p>  
                </div>  

                <!-- Confirm Password -->  
                <div>  
                    <label for="password_confirm" class="block text-sm font-medium text-gray-700">Confirm Password</label>  
                    <input id="password_confirm"   
                           name="password_confirm"   
                           type="password"   
                           required  
                           minlength="8"  
                           class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">  
                </div>  
            </div>  

            <!-- Address Section -->  
            <div class="bg-white p-6 rounded-lg shadow-sm space-y-6">  
                <h3 class="text-lg font-medium text-gray-900">Address Information</h3>  

                <!-- Street -->  
                <div>  
                    <label for="street" class="block text-sm font-medium text-gray-700">Street</label>  
                    <input id="street"   
                           name="street"   
                           type="text"   
                           required  
                           value="<?= $_POST['street'] ?? '' ?>"  
                           class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">  
                </div>  

                <div class="grid grid-cols-2 gap-4">  
                    <!-- House Number -->  
                    <div>  
                        <label for="house_number" class="block text-sm font-medium text-gray-700">House Number</label>  
                        <input id="house_number"   
                               name="house_number"   
                               type="text"   
                               required  
                               value="<?= $_POST['house_number'] ?? '' ?>"  
                               class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">  
                    </div>  

                    <!-- Post Code -->  
                    <div>  
                        <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>  
                        <input id="postal_code"   
                               name="postal_code"   
                               type="text"   
                               required  
                               pattern="[0-9]{4}"  
                               value="<?= $_POST['postal_code'] ?? '' ?>"  
                               class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">  
                        <p class="mt-1 text-sm text-gray-500">Format: 1000</p>  
                    </div>  
                </div>  

                <!-- City -->  
                <div>  
                    <label for="city" class="block text-sm font-medium text-gray-700">City</label>  
                    <input id="city"   
                           name="city"   
                           type="text"   
                           required  
                           value="<?= $_POST['city'] ?? '' ?>"  
                           class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">  
                </div>  
            </div>  

            <!-- Submit Button -->  
            <div>  
                <button type="submit"  
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">  
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">  
                        <!-- Hero icon name: solid/user-add -->  
                        <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">  
                            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />  
                        </svg>  
                    </span>  
                    Create Account  
                </button>  
            </div>  

            <!-- Login Link -->  
            <div class="text-sm text-center">  
                Already have an account?  
                <a href="index.php?controller=auth&action=login" class="font-medium text-blue-600 hover:text-blue-500">  
                    Sign in here  
                </a>  
            </div>  
        </form>  
    </div>  
</div>  

<!-- Client-side validation -->  
<script>  
document.querySelector('form').addEventListener('submit', function(e) {  
    const password = document.getElementById('password');  
    const passwordConfirm = document.getElementById('password_confirm');  
    const postCode = document.getElementById('postal_code');  

    // Password validation  
    if (password.value !== passwordConfirm.value) {  
        e.preventDefault();  
        alert('Passwords do not match!');  
        return;  
    }  

    // Post code validation  
    if (!/^[0-9]{4}$/.test(postCode.value)) {  
        e.preventDefault();  
        alert('Post code must be exactly 4 digits!');  
        return;  
    }  
});  
</script>  

<?php require_once 'views/layouts/footer.php'; ?>