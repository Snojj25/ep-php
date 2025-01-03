<?php
require_once 'views/layouts/header.php';
require_once 'views/layouts/navbar.php';
?>  

<div class="container mx-auto px-4 py-8">  
    <div class="max-w-4xl mx-auto">  
        <h1 class="text-3xl font-bold mb-8">Search Products</h1>  

        <!-- Search Form -->  
        <div class="mb-8">  
            <form id="searchForm" action="index.php?controller=customer&action=search" class="space-y-4" method="POST">  
                <div class="flex gap-4">  
                    <div class="flex-1">  
                        <input type="text"   
                               id="searchInput"   
                               name="q"   
                               value="<?= htmlspecialchars($searchTerm ?? '') ?>"  
                               placeholder="Search products..."   
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">  
                    </div>   
                    <div class="flex-1">  
                        <input type="text"   
                               id="excludeInput"   
                               name="x"   
                               value="<?= htmlspecialchars($excludeTerm ?? '') ?>"  
                               placeholder="Exclude products..."   
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">  
                    </div> 
                    <button type="submit"   
                            class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">  
                        Search  
                    </button>  
                </div>  
            </form>  

            
        </div>  

        <!-- Results Section -->  
        <div id="searchResults" class="space-y-4">  
            <?php if (isset($results)): ?>  
                <?php if (empty($results)): ?>  
                    <div class="text-center py-8 text-gray-500">  
                        No products found matching your search criteria.  
                    </div>  
                <?php else: ?>  
                    <?php foreach ($results as $product): ?>  
                        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">  
                            <div class="flex justify-between items-start">  
                                <div>  
                                    <h3 class="text-xl font-semibold text-gray-800">  
                                        <?= htmlspecialchars($product['name']) ?>  
                                    </h3>  
                                    <p class="mt-2 text-gray-600">  
                                        <?= htmlspecialchars($product['description']) ?>  
                                    </p>  
                                    <p class="mt-2 text-lg font-bold text-blue-600">  
                                        $<?= number_format($product['price'], 2) ?>  
                                    </p>  
                                </div>  
                                <div class="text-sm text-gray-500">  
                                    Stock: <?= $product['stock'] ?>  
                                </div>  
                            </div>  
                        </div>  
                    <?php endforeach; ?>  
                <?php endif; ?>  
            <?php endif; ?>  
        </div>  
    </div>  
</div>  

<script>  
//document.getElementById('searchForm').addEventListener('submit', function(e) {  
//    e.preventDefault();  
//    performSearch(new FormData(this));  
//});  


function performSearch(formData) {  
    const searchTerm = formData.get('q');  
    const searchBy = formData.get('searchBy');  
    
    
    fetch(`index.php?controller=product&action=search&q=${encodeURIComponent(searchTerm)}&ajax=1`)  
        .then(response => response.json())  
        .then(data => {  
            console.log("DATA12: ", data);
            updateResults(data.results);  
        })  
        .catch(error => {  
            console.error('Error:', error);  
        });  
}  


function updateResults(results) {  
    const resultsContainer = document.getElementById('searchResults');  
    
    if (!results || results.length === 0) {  
        resultsContainer.innerHTML = `  
            <div class="text-center py-8 text-gray-500">  
                No products found matching your search criteria.  
            </div>  
        `;  
        return;  
    }  

    resultsContainer.innerHTML = results.map(product => `  
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">  
            <div class="flex justify-between items-start">  
                <div>  
                    <h3 class="text-xl font-semibold text-gray-800">  
                        ${escapeHtml(product.name)}  
                    </h3>  
                    <p class="mt-2 text-gray-600">  
                        ${escapeHtml(product.description)}  
                    </p>  
                    <p class="mt-2 text-lg font-bold text-blue-600">  
                        $${parseFloat(product.price).toFixed(2)}  
                    </p>  
                </div>  
                <div class="text-sm text-gray-500">  
                    Stock: ${product.stock}  
                </div>  
            </div>  
        </div>  
    `).join('');  
}  

function escapeHtml(unsafe) {  
    return unsafe  
        .replace(/&/g, "&amp;")  
        .replace(/</g, "&lt;")  
        .replace(/>/g, "&gt;")  
        .replace(/"/g, "&quot;")  
        .replace(/'/g, "&#039;");  
}  
</script>  

<?php require_once 'views/layouts/footer.php'; ?>