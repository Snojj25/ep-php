<?php
require_once 'views/layouts/header.php';
require_once 'views/layouts/navbar.php';
?>  
 

<div class="container mx-auto px-4 py-8">  
    <div class="max-w-2xl mx-auto">  
        <!-- Header -->  
        <div class="flex items-center justify-between mb-6">  
            <h1 class="text-2xl font-bold text-gray-900">Create New Seller</h1>  
            <a href="index.php?controller=admin&action=manageSellers"   
               class="text-gray-600 hover:text-gray-900">  
                <span>← Back to Sellers</span>  
            </a>  
        </div>  

        <!-- Error/Success Messages -->  
        <?php if (isset($_SESSION['error'])): ?>  
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">  
                <span class="block sm:inline"><?= htmlspecialchars($_SESSION['error']) ?></span>  
                <?php unset($_SESSION['error']); ?>  
            </div>  
        <?php endif; ?>  

        <!-- Create Seller Form -->  
        <form action="index.php?controller=admin&action=createSeller"   
              method="POST"   
              class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4"  
              id="createSellerForm">  
            
            <!-- CSRF Token -->  
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">  

            <!-- Personal Information -->  
            <div class="mb-6">  
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Personal Information</h2>  
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">  
                    <!-- First Name -->  
                    <div>  
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="first_name">  
                            First Name *  
                        </label>  
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  
                               id="first_name"  
                               type="text"  
                               name="first_name"  
                               value="<?= htmlspecialchars($_SESSION['form_data']['first_name'] ?? '') ?>"  
                               required>  
                    </div>  

                    <!-- Last Name -->  
                    <div>  
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="last_name">  
                            Last Name *  
                        </label>  
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  
                               id="last_name"  
                               type="text"  
                               name="last_name"  
                               value="<?= htmlspecialchars($_SESSION['form_data']['last_name'] ?? '') ?>"  
                               required>  
                    </div>  
                </div>  
            </div>  

            <!-- Contact Information -->  
            <div class="mb-6">  
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Contact Information</h2>  
                
                <!-- Email -->  
                <div class="mb-4">  
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">  
                        Email Address *  
                    </label>  
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  
                           id="email"  
                           type="email"  
                           name="email"  
                           value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? '') ?>"  
                           required>  
                </div>  

                <!-- Address -->  
                <div class="mb-4">  
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="address">  
                        Address  
                    </label>  
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  
                           id="address"  
                           type="text"  
                           name="address"  
                           value="<?= htmlspecialchars($_SESSION['form_data']['address'] ?? '') ?>">  
                </div>  

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">  
                    <!-- Postal Code -->  
                    <div>  
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="postal_code">  
                            Postal Code  
                        </label>  
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  
                               id="postal_code"  
                               type="text"  
                               name="postal_code"  
                               value="<?= htmlspecialchars($_SESSION['form_data']['postal_code'] ?? '') ?>">  
                    </div>  

                    <!-- City -->  
                    <div>  
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="city">  
                            City  
                        </label>  
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  
                               id="city"  
                               type="text"  
                               name="city"  
                               value="<?= htmlspecialchars($_SESSION['form_data']['city'] ?? '') ?>">  
                    </div>  
                </div>  
            </div>  

            <!-- Password -->  
            <div class="mb-6">  
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Security</h2>  
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">  
                    <!-- Password -->  
                    <div>  
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">  
                            Password *  
                        </label>  
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  
                               id="password"  
                               type="password"  
                               name="password"  
                               required>  
                    </div>  

                    <!-- Confirm Password -->  
                    <div>  
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_password">  
                            Confirm Password *  
                        </label>  
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  
                               id="confirm_password"  
                               type="password"  
                               name="confirm_password"  
                               required>  
                    </div>  
                </div>  
            </div>  

            <!-- Submit Buttons -->  
            <div class="flex items-center justify-between">  
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"  
                        type="submit">  
                    Create Seller  
                </button>  
                <a href="index.php?controller=admin&action=manageSellers"  
                   class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">  
                    Cancel  
                </a>  
            </div>  
        </form>  
    </div>  
</div>  

<!-- Client-side validation -->  
<script>  
document.getElementById('createSellerForm').addEventListener('submit', function(e) {  
    // Reset previous error states  
    clearErrors();  
    
    let hasErrors = false;  
    const errors = [];  

    // Validate required fields  
    const required = ['first_name', 'last_name', 'email', 'password', 'confirm_password'];  
    required.forEach(field => {  
        const value = document.getElementById(field).value.trim();  
        if (!value) {  
            showError(field, `${field.replace('_', ' ')} is required`);  
            hasErrors = true;  
        }  
    });  

    // Validate email format  
    const email = document.getElementById('email').value.trim();  
    if (email && !isValidEmail(email)) {  
        showError('email', 'Please enter a valid email address');  
        hasErrors = true;  
    }  

    // Validate password match  
    const password = document.getElementById('password').value;  
    const confirmPassword = document.getElementById('confirm_password').value;  
    if (password !== confirmPassword) {  
        showError('confirm_password', 'Passwords do not match');  
        hasErrors = true;  
    }  

    // Validate password strength  
    if (password && !isStrongPassword(password)) {  
        showError('password', 'Password must be at least 8 characters long and contain uppercase, lowercase, number and special character');  
        hasErrors = true;  
    }  

    if (hasErrors) {  
        e.preventDefault();  
    }  
});  

function isValidEmail(email) {  
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);  
}  

function isStrongPassword(password) {  
    return true;
    // TODO: return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(password);  
}  

function showError(fieldId, message) {  
    const field = document.getElementById(fieldId);  
    field.classList.add('border-red-500');  
    
    const errorDiv = document.createElement('div');  
    errorDiv.className = 'text-red-500 text-xs italic mt-1';  
    errorDiv.textContent = message;  
    
    field.parentNode.appendChild(errorDiv);  
}  

function clearErrors() {  
    // Remove all error messages  
    document.querySelectorAll('.text-red-500').forEach(el => el.remove());  
    // Remove all error borders  
    document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));  
}  
</script>  

<?php   
// Clear form data after display  
unset($_SESSION['form_data']);  
?>  

<?php require_once 'views/layouts/footer.php'; ?>  