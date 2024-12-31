<?php
require_once 'views/layouts/header.php';
require_once 'views/layouts/navbar.php';
?>  


<div class="container mx-auto px-4 py-8">  
    
    
    <!-- Header -->  
    <div class="flex justify-between items-center mb-6">  
        <h1 class="text-2xl font-bold">Order #<?= htmlspecialchars($order['id']) ?></h1>  
        <a href="index.php?controller=seller&action=manageOrders"   
           class="text-gray-600 hover:text-gray-900">  
            ← Back to Orders  
        </a>  
    </div>  

    <!-- Order Information -->  
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">  
        <!-- Order Details -->  
        <div class="bg-white rounded-lg shadow p-6">  
            <h2 class="text-lg font-semibold mb-4">Order Details</h2>  
            <div class="space-y-3">  
                <div class="flex justify-between">  
                    <span class="text-gray-600">Status:</span>  
                    <span class="<?= getStatusClass($order['status']) ?> py-1 px-3 rounded-full text-xs">  
                        <?= ucfirst(htmlspecialchars($order['status'])) ?>  
                    </span>  
                </div>  
                <div class="flex justify-between">  
                    <span class="text-gray-600">Order Date:</span>  
                    <span><?= htmlspecialchars(date('Y-m-d H:i', strtotime($order['created_at']))) ?></span>  
                </div>  
                <div class="flex justify-between">  
                    <span class="text-gray-600">Total Amount:</span>  
                    <span class="font-semibold">€<?= number_format($order['total_amount'], 2) ?></span>  
                </div>  
            </div>  
        </div>  

        <!-- Customer Information -->  
        <div class="bg-white rounded-lg shadow p-6">  
            <h2 class="text-lg font-semibold mb-4">Customer Information</h2>  
            <div class="space-y-3">  
                 <div>  
                    <span class="text-gray-600">Phone:</span>  
                    <span class="ml-2"><?= htmlspecialchars($order['phone']) ?></span>  
                </div> 
                <div>  
                    <span class="text-gray-600">Address:</span>  
                    <span class="ml-2"><?= htmlspecialchars($order['shipping_address']) ?></span>  
                </div>  
                <div>  
                    <span class="text-gray-600">City:</span>  
                    <span class="ml-2"><?= htmlspecialchars($order['city']) ?></span>  
                </div>  
                <div>  
                    <span class="text-gray-600">Postal Code:</span>  
                    <span class="ml-2"><?= htmlspecialchars($order['postal_code']) ?></span>  
                </div>  
            </div>  
        </div>  
    </div>  

    <!-- Order Items -->  
    <div class="bg-white rounded-lg shadow overflow-hidden">  
        <h2 class="text-lg font-semibold p-6 bg-gray-50 border-b">Order Items</h2>  
        <table class="min-w-full divide-y divide-gray-200">  
            <thead class="bg-gray-50">  
                <tr>  
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>  
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>  
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>  
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>  
                </tr>  
            </thead>  
            <tbody class="bg-white divide-y divide-gray-200">  
                <?php foreach ($orderItems as $item): ?>  
                    <tr>  
                        <td class="px-6 py-4 whitespace-nowrap">  
                            <div class="text-sm font-medium text-gray-900">  
                                <?= htmlspecialchars($item['name']) ?>  
                            </div>  
                        </td>  
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">  
                            €<?= number_format($item['price'], 2) ?>  
                        </td>  
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">  
                            <?= htmlspecialchars($item['quantity']) ?>  
                        </td>  
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">  
                            €<?= number_format($item['price'] * $item['quantity'], 2) ?>  
                        </td>  
                    </tr>  
                <?php endforeach; ?>  
            </tbody>  
            <tfoot class="bg-gray-50">  
                <tr>  
                    <td colspan="3" class="px-6 py-4 text-right font-medium">Total:</td>  
                    <td class="px-6 py-4 text-right font-bold">€<?= number_format($order['total_amount'], 2) ?></td>  
                </tr>  
            </tfoot>  
        </table>  
    </div>  

    <!-- Order Actions -->  
    <?php if ($order['status'] === 'pending'): ?>  
        <div class="mt-6 flex justify-end space-x-4">  
            <form action="index.php?controller=seller&action=updateOrderStatus" method="POST" class="inline">  
                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">  
                <input type="hidden" name="status" value="confirmed">  
                <button type="submit"   
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"  
                        onclick="return confirm('Are you sure you want to confirm this order?')">  
                    Confirm Order  
                </button>  
            </form>  
            <form action="index.php?controller=seller&action=updateOrderStatus" method="POST" class="inline">  
                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">  
                <input type="hidden" name="status" value="cancelled">  
                <button type="submit"   
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"  
                        onclick="return confirm('Are you sure you want to cancel this order?')">  
                    Cancel Order  
                </button>  
            </form>  
        </div>  
    <?php elseif ($order['status'] === 'confirmed'): ?>  
        <div class="mt-6 flex justify-end">  
            <form action="index.php?controller=seller&action=updateOrderStatus" method="POST" class="inline">  
                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">  
                <input type="hidden" name="status" value="storno">  
                <button type="submit"   
                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded"  
                        onclick="return confirm('Are you sure you want to stornate this order?')">  
                    Stornate Order  
                </button>  
            </form>  
        </div>  
    <?php endif; ?>  
</div>  

<?php   
function getStatusClass($status) {  
    switch ($status) {  
        case 'pending':  
            return 'bg-yellow-200 text-yellow-600';  
        case 'confirmed':  
            return 'bg-green-200 text-green-600';  
        case 'cancelled':  
            return 'bg-red-200 text-red-600';  
        case 'stornated':  
            return 'bg-gray-200 text-gray-600';  
        default:  
            return 'bg-gray-200 text-gray-600';  
    }  
}  
?>  

<?php require 'views/layouts/footer.php'; ?>