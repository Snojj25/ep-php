<?php require_once 'views/layouts/header.php'; ?>  
<?php require_once 'views/layouts/navbar.php'; ?>  

<div class="container mx-auto px-4 py-8">  
    <div class="max-w-2xl mx-auto">  
        <h1 class="text-3xl font-bold mb-8">Profile Settings</h1>  

        <!-- Success Message -->  
        <?php if (isset($_SESSION['success'])): ?>  
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">  
                <span class="block sm:inline"><?= htmlspecialchars($_SESSION['success']) ?></span>  
            </div>  
            <?php unset($_SESSION['success']); ?>  
        <?php endif; ?>  

        <!-- Error Message -->  
        <?php if (isset($_SESSION['error'])): ?>  
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">  
                <span class="block sm:inline"><?= htmlspecialchars($_SESSION['error']) ?></span>  
            </div>  
            <?php unset($_SESSION['error']); ?>  
        <?php endif; ?>  

        <!-- Profile Information -->  
        <div class="bg-white shadow rounded-lg mb-6">  
            <div class="px-6 py-4 border-b border-gray-200">  
                <h2 class="text-lg font-medium">Profile Information</h2>  
                <p class="mt-1 text-sm text-gray-600">Update your account's profile information.</p>  
            </div>  
            
            <div class="p-6">  
                <form action="index.php?controller=user&action=updateProfile" method="POST">  
                    <div class="mb-4">  
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">  
                            Name  
                        </label>  
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  
                               id="name"  
                               type="text"  
                               name="name"  
                               value="<?= htmlspecialchars($user['first_name'] . " " . $user['last_name']) ?>"  
                               required>  
                    </div>  

                    <div class="mb-4">  
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">  
                            Email  
                        </label>  
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  
                               id="email"  
                               type="email"  
                               name="email"  
                               value="<?= htmlspecialchars($user['email']) ?>"  
                               required>  
                    </div>  

                    <div class="flex items-center justify-end">  
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"  
                                type="submit">  
                            Save Changes  
                        </button>  
                    </div>  
                </form>  
            </div>  
        </div>  

        <!-- Update Password -->  
        <div class="bg-white shadow rounded-lg">  
            <div class="px-6 py-4 border-b border-gray-200">  
                <h2 class="text-lg font-medium">Update Password</h2>  
                <p class="mt-1 text-sm text-gray-600">Ensure your account is using a secure password.</p>  
            </div>  
            
            <div class="p-6">  
                <form action="index.php?controller=user&action=updatePassword" method="POST">  
                    <div class="mb-4">  
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="current_password">  
                            Current Password  
                        </label>  
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  
                               id="current_password"  
                               type="password"  
                               name="current_password"  
                               required>  
                    </div>  

                    <div class="mb-4">  
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="new_password">  
                            New Password  
                        </label>  
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  
                               id="new_password"  
                               type="password"  
                               name="new_password"  
                               required>  
                    </div>  

                    <div class="mb-6">  
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_password">  
                            Confirm Password  
                        </label>  
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  
                               id="confirm_password"  
                               type="password"  
                               name="confirm_password"  
                               required>  
                    </div>  

                    <div class="flex items-center justify-end">  
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"  
                                type="submit">  
                            Update Password  
                        </button>  
                    </div>  
                </form>  
            </div>  
        </div>  
    </div>  
</div>  

<!-- Add this script for password validation -->  
<script>  
document.querySelector('form').addEventListener('submit', function(e) {  
    const newPassword = document.getElementById('new_password').value;  
    const confirmPassword = document.getElementById('confirm_password').value;  

    if (newPassword !== confirmPassword) {  
        e.preventDefault();  
        alert('New passwords do not match!');  
    }  
});  
</script>  

<?php require_once 'views/layouts/footer.php'; ?>