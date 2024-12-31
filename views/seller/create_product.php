<?php
require_once 'views/layouts/header.php';
require_once 'views/layouts/navbar.php';

$_SESSION['errors'] = null;
var_dump($_SESSION['errors']);
?>  



<div class="container mx-auto px-4 py-8">  
    <div class="max-w-2xl mx-auto">  
        <!-- Header -->  
        <div class="mb-8">  
            <h1 class="text-2xl font-bold mb-2">Create New Product</h1>  
            <p class="text-gray-600">Add a new product to your inventory</p>  
        </div>  

        <!-- Error Messages -->  
        <?php if (isset($_SESSION['errors'])): ?>  

            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">  
                <ul class="list-disc list-inside">  
                    <?php foreach ($_SESSION['errors'] as $error): ?>  
                        <li><?= htmlspecialchars($error) ?></li>  
                    <?php endforeach; ?>  
                </ul>  
            </div>  
            <?php unset($_SESSION['errors']); ?>  
        <?php endif; ?>  

        <!-- Success Message -->  
        <?php if (isset($_SESSION['success'])): ?>  
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">  
                <span class="block sm:inline"><?= htmlspecialchars($_SESSION['success']) ?></span>  
            </div>  
            <?php unset($_SESSION['success']); ?>  
        <?php endif; ?>  

        <!-- Product Form -->  
        <form action="index.php?controller=seller&action=createProduct" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">  
            <!-- Product Name -->  
            <div class="mb-6">  
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">  
                    Product Name *  
                </label>  
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"   
                       id="name"   
                       type="text"   
                       name="name"  
                       value="<?= isset($_SESSION['old']['name']) ? htmlspecialchars($_SESSION['old']['name']) : '' ?>"  
                       required>  
            </div>  

            <!-- Product Description -->  
            <div class="mb-6">  
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">  
                    Description *  
                </label>  
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"   
                          id="description"   
                          name="description"   
                          rows="4"  
                          required><?= isset($_SESSION['old']['description']) ? htmlspecialchars($_SESSION['old']['description']) : '' ?></textarea>  
            </div>  

            <!-- Price and Stock -->  
            <div class="flex flex-wrap -mx-3 mb-6">  
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">  
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="price">  
                        Price (€) *  
                    </label>  
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"   
                           id="price"   
                           type="number"   
                           name="price"  
                           step="0.01"   
                           min="0"  
                           value="<?= isset($_SESSION['old']['price']) ? htmlspecialchars($_SESSION['old']['price']) : '' ?>"  
                           required>  
                </div>  
                <div class="w-full md:w-1/2 px-3">  
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="stock">  
                        Stock *  
                    </label>  
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"   
                           id="stock"   
                           type="number"   
                           name="stock"  
                           min="0"  
                           value="<?= isset($_SESSION['old']['stock']) ? htmlspecialchars($_SESSION['old']['stock']) : '' ?>"  
                           required>  
                </div>  
            </div>  


            <!-- Product Images -->  
            <div class="mb-6">  
                <label class="block text-gray-700 text-sm font-bold mb-2" for="pimage">  
                    Product Image  
                </label>  
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">  
                    <div class="space-y-1 text-center">  
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">  
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"   
                              stroke-width="2"   
                              stroke-linecap="round"   
                              stroke-linejoin="round" />  
                        </svg>  
                        <div class="flex text-sm text-gray-600">  
                            <label for="pimage" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">  
                                <span>Upload files</span>  
                                <input id="pimage"   
                                       name="pimage"   
                                       type="file"   
                                       class="sr-only"  
                                       accept="image/*" 
                                       >  
                                
                            </label>  
                            <p class="pl-1">or drag and drop</p>  
                        </div>  
                        <p class="text-xs text-gray-500">  
                            PNG, JPG, GIF up to 5MB each  
                        </p>  
                    </div>  
                </div>  
            </div>  

            <!-- Submit Button -->  
            <div class="flex items-center justify-between">  
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"   
                        type="submit">  
                    Create Product  
                </button>  
                <a href="index.php?controller=seller&action=manageProducts"   
                   class="text-gray-600 hover:text-gray-800">  
                    Cancel  
                </a>  
            </div>  
        </form>  
    </div>  
</div>  

<!-- Preview Images Script -->  
<script>
    document.getElementById('pimage').addEventListener('change', function (event) {
        // Remove any existing preview  
        const existingPreview = document.getElementById('image-preview');
        if (existingPreview) {
            existingPreview.remove();
        }

        // Create preview container  
        const previewContainer = document.createElement('div');
        previewContainer.id = 'image-preview';
        previewContainer.className = 'grid grid-cols-2 md:grid-cols-3 gap-4 mt-4';

        // Preview each selected file  
        for (const file of event.target.files) {
            if (file) {
                const reader = new FileReader();
                const preview = document.createElement('div');
                preview.className = 'relative';

                reader.onload = function (e) {
                    preview.innerHTML = `  
                    <img src="${e.target.result}" alt="Preview" class="w-full h-32 object-cover rounded">  
                    <div class="absolute top-0 right-0 m-1">  
                        <button type="button" class="bg-red-500 text-white rounded-full p-1 hover:bg-red-600"   
                                onclick="this.closest('.relative').remove();">  
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">  
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />  
                            </svg>  
                        </button>  
                    </div>  
                `;
                }

                reader.readAsDataURL(file);
                previewContainer.appendChild(preview);
            }
        }

        // Add preview container after the upload section  
        event.target.closest('.mb-6').appendChild(previewContainer);
    });
</script>  

<?php
// Clear old form data  
unset($_SESSION['old']);
?>  

<?php require 'views/layouts/footer.php'; ?>