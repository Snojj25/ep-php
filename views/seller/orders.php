<?php $title = 'Manage Orders'; require 'views/layouts/header.php'; ?>  

<div class="container mx-auto px-4 py-8">  
    <h1 class="text-2xl font-bold mb-6">Manage Orders</h1>  
    
    <div class="bg-white rounded-lg shadow overflow-hidden">  
        <table class="min-w-full">  
            <thead class="bg-gray-50">  
                <tr>  
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>  
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>  
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>  
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>  
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>  
                </tr>  
            </thead>  
            <tbody class="divide-y divide-gray-200">  
                <?php foreach ($orders as $order): ?>  
                <tr>  
                    <td class="px-6 py-4">#<?= $order['id'] ?></td>  
                    <td class="px-6 py-4">  
                        <?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?>  
                    </td>  
                    <td class="px-6 py-4">$<?= number_format($order['total_amount'], 2) ?></td>  
                    <td class="px-6 py-4">  
                        <span class="<?= $order['status'] === 'pending' ? 'text-yellow-600' :   
                                     ($order['status'] === 'confirmed' ? 'text-green-600' : 'text-red-600') ?>">  
                            <?= ucfirst($order['status']) ?>  
                        </span>  
                    </td>  
                    <td class="px-6 py-4">  
                        <?php if ($order['status'] === 'pending'): ?>  
                            <form action="index.php?controller=seller&action=updateOrderStatus" method="POST" class="inline">  
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">  
                                <input type="hidden" name="status" value="confirmed">  
                                <button type="submit" class="text-green-600 hover:text-green-900 mr-2">Confirm</button>  
                            </form>  
                            <form action="index.php?controller=seller&action=updateOrderStatus" method="POST" class="inline">  
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">  
                                <input type="hidden" name="status" value="cancelled">  
                                <button type="submit" class="text-red-600 hover:text-red-900">Cancel</button>  
                            </form>  
                        <?php endif; ?>  
                    </td>  
                </tr>  
                <?php endforeach; ?>  
            </tbody>  
        </table>  
    </div>  
</div>  