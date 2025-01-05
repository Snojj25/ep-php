<?php
require_once 'views/layouts/header.php';
require_once 'views/layouts/navbar.php';
?>  
 

<div class="container mx-auto px-4 py-8">  
    <div class="max-w-2xl mx-auto">  
        <!-- Header with back button -->  
        <div class="flex items-center justify-between mb-6">  
            <h1 class="text-2xl font-bold">Edit Seller</h1>  
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

        <?php if (isset($_SESSION['success'])): ?>  
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">  
                <span class="block sm:inline"><?= htmlspecialchars($_SESSION['success']) ?></span>  
                <?php unset($_SESSION['success']); ?>  
            </div>  
        <?php endif; ?>  

        <!-- Edit Form -->  
        <form action="index.php?controller=admin&action=editSeller&id=<?= htmlspecialchars($seller['id']) ?>"   
              method="POST"   
              class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">  
            
            <!-- CSRF Token -->  
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">  

            <!-- First Name -->  
            <div class="mb-4">  
                <label class="block text-gray-700 text-sm font-bold mb-2" for="first_name">  
                    First Name  
                </label>  
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  
                       id="first_name"  
                       type="text"  
                       name="first_name"  
                       value="<?= htmlspecialchars($seller['first_name']) ?>"  
                       required  
                       pattern="[A-Za-z\s]+"  
                       title="Please enter a valid first name (letters and spaces only)"  
                >  
            </div>  

            <!-- Last Name -->  
            <div class="mb-4">  
                <label class="block text-gray-700 text-sm font-bold mb-2" for="last_name">  
                    Last Name  
                </label>  
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  
                       id="last_name"  
                       type="text"  
                       name="last_name"  
                       value="<?= htmlspecialchars($seller['last_name']) ?>"  
                       required  
                       pattern="[A-Za-z\s]+"  
                       title="Please enter a valid last name (letters and spaces only)"  
                >  
            </div>  

            <!-- Email -->  
            <div class="mb-6">  
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">  
                    Email  
                </label>  
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  
                       id="email"  
                       type="email"  
                       name="email"  
                       value="<?= htmlspecialchars($seller['email']) ?>"  
                       required  
                >  
            </div>  

            <!-- Buttons -->  
            <div class="flex items-center justify-between">  
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"  
                        type="submit">  
                    Update Seller  
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
document.addEventListener('DOMContentLoaded', function() {  
    const form = document.querySelector('form');  
    
    form.addEventListener('submit', function(e) {  
        const firstName = document.getElementById('first_name').value.trim();  
        const lastName = document.getElementById('last_name').value.trim();  
        const email = document.getElementById('email').value.trim();  
        
        let isValid = true;  
        let errorMessage = '';  
        
        // Validate First Name  
        if (!/^[A-Za-z\s]+$/.test(firstName)) {  
            errorMessage += 'First name should only contain letters and spaces\n';  
            isValid = false;  
        }  
        
        // Validate Last Name  
        if (!/^[A-Za-z\s]+$/.test(lastName)) {  
            errorMessage += 'Last name should only contain letters and spaces\n';  
            isValid = false;  
        }  
        
        // Validate Email  
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {  
            errorMessage += 'Please enter a valid email address\n';  
            isValid = false;  
        }  
        
        if (!isValid) {  
            e.preventDefault();  
            alert(errorMessage);  
        }  
    });  
});  
</script>  

<?php require_once 'views/layouts/footer.php'; ?>  