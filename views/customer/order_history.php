
<?php
$pageTitle = 'Products - Your Shop';
require_once 'views/layouts/header.php';
require_once 'views/layouts/navbar.php';
?>    
<main class="py-10">  
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">   

        <div class="flex justify-between items-center mb-6">  
            <h1 class="text-2xl font-bold">Order History</h1>  
            <a href="index.php"   
               class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors"> Home  
            </a>  
        </div> 

        <?php if (empty($orders)): ?>  
            <div class="bg-white shadow sm:rounded-lg">  
                <div class="px-4 py-5 sm:p-6 text-center">  
                    <h3 class="text-lg leading-6 font-medium text-gray-900">No orders found</h3>  
                    <div class="mt-2 max-w-xl text-sm text-gray-500">  
                        <p>You haven't placed any orders yet.</p>  
                    </div>  
                    <div class="mt-5">  
                        <a href="index.php?controller=customer&action=index"   
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">  
                            Start Shopping  
                        </a>  
                    </div>  
                </div>  
            </div>  
        <?php else: ?>  
            <div class="space-y-6">  
                <?php foreach ($orders as $order): ?>  
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">  
                        <!-- Order Header -->  
                        <div class="px-4 py-5 sm:px-6 flex justify-between items-center bg-gray-50">  
                            <div>  
                                <h3 class="text-lg leading-6 font-medium text-gray-900">  
                                    Order #<?= htmlspecialchars($order['id']) ?>  
                                </h3>  
                                <p class="mt-1 max-w-2xl text-sm text-gray-500">  
                                    Placed on <?= date('F j, Y', strtotime($order['created_at'])) ?>  
                                </p>  
                            </div>  
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full   
                            <?php
                            switch ($order['status']) {
                                case 'pending':
                                    echo 'bg-yellow-100 text-yellow-800';
                                    break;
                                case 'confirmed':
                                    echo 'bg-blue-100 text-blue-800';
                                    break;
                                case 'completed':
                                    echo 'bg-green-100 text-green-800';
                                    break;
                                case 'cancelled':
                                case 'storno':
                                    echo 'bg-red-100 text-red-800';
                                    break;
                                default:
                                    echo 'bg-gray-100 text-gray-800';
                            }
                            ?>">  
                                      <?= ucfirst(htmlspecialchars($order['status'])) ?>  
                            </span>  
                        </div>  

                        <!-- Order Details -->  
                        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">  
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">  
                                <div class="sm:col-span-2">  
                                    <dt class="text-sm font-medium text-gray-500">Shipping Address</dt>  
                                    <dd class="mt-1 text-sm text-gray-900">  
                                        <?= htmlspecialchars($order['shipping_address'] ?? '') ?><br>  
                                        <?= htmlspecialchars($order['city'] ?? '') ?>, <?= htmlspecialchars($order['postal_code'] ?? '') ?>  
                                    </dd>  
                                </div>  

                                <div>  
                                    <dt class="text-sm font-medium text-gray-500">Phone Number</dt>  
                                    <dd class="mt-1 text-sm text-gray-900">  
                                        <?= htmlspecialchars($order['phone'] ?? '') ?>  
                                    </dd>  
                                </div>  

                                <div>  
                                    <dt class="text-sm font-medium text-gray-500">Total Amount</dt>  
                                    <dd class="mt-1 text-sm text-gray-900">  
                                        $<?= number_format($order['total_amount'], 2) ?>  
                                    </dd>  
                                </div>  

                                <?php if (!empty($order['notes'])): ?>  
                                    <div class="sm:col-span-2">  
                                        <dt class="text-sm font-medium text-gray-500">Notes</dt>  
                                        <dd class="mt-1 text-sm text-gray-900">  
                                            <?= htmlspecialchars($order['notes']) ?>  
                                        </dd>  
                                    </div>  
                                <?php endif; ?>  
                            </dl>  
                        </div>  

                        <!-- Order Items -->  
                        <div class="border-t border-gray-200">  
                            <div class="px-4 py-5 sm:px-6">  
                                <h4 class="text-sm font-medium text-gray-900 mb-4">Order Items</h4>  
                                <div class="space-y-4">  
                                    <!-- <foreach ($this->orderModel->getOrderItems($order['id']) as $item):  --> 
                                    <?php foreach ($order['items'] as $item): ?>  
                                        <div class="flex justify-between items-center">  
                                            <div class="flex-1">  
                                                <h5 class="text-sm font-medium text-gray-900">  
                                                    <?= htmlspecialchars($item['product_name']) ?>  
                                                </h5>  
                                                <p class="text-sm text-gray-500">  
                                                    Quantity: <?= htmlspecialchars($item['quantity']) ?>  
                                                </p>  
                                            </div>  
                                            <p class="text-sm font-medium text-gray-900">  
                                                $<?= number_format($item['price'] * $item['quantity'], 2) ?>  
                                            </p>  
                                        </div>  
                                    <?php endforeach; ?>  
                                </div>  
                            </div>  
                        </div>  
                    </div>  
                <?php endforeach; ?>  
            </div>  
        <?php endif; ?>  
    </div>  
</main>  


<?php require_once 'views/layouts/footer.php'; ?> 