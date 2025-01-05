<?php
require_once 'views/layouts/header.php';
require_once 'views/layouts/navbar.php';
?>  


<div class="container mx-auto px-4 py-8">  
    <!-- Header -->  
    <div class="flex justify-between items-center mb-6">  
        <h1 class="text-2xl font-bold">Manage Orders</h1>  
        <a href="index.php?controller=seller&action=index"   
           class="text-gray-600 hover:text-gray-900">  
            ← Back to Dashboard  
        </a>  
    </div> 
    

    <!-- Messages -->  
    <?php if (isset($_SESSION['success'])): ?>  
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">  
            <span class="block sm:inline"><?= htmlspecialchars($_SESSION['success']) ?></span>  
            <?php unset($_SESSION['success']); ?>  
        </div>  
    <?php endif; ?>  

    <!-- Orders Table -->  
    <div class="bg-white shadow-md rounded my-6">  
        <table class="min-w-full table-auto">  
            <thead>  
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">  
                    <th class="py-3 px-6 text-left">Order ID</th>  
                    <th class="py-3 px-6 text-left">Customer</th>  
                    <th class="py-3 px-6 text-right">Total</th>  
                    <th class="py-3 px-6 text-center">Status</th>  
                    <th class="py-3 px-6 text-center">Date</th>  
                    <th class="py-3 px-6 text-center">Actions</th>  
                </tr>  
            </thead>  

            <tbody class="text-gray-600 text-sm font-light">  
                <?php foreach ($allOrders as $order): ?>  
                    <tr class="border-b border-gray-200 hover:bg-gray-100">  
                        <td class="py-3 px-6 text-left">  
                            #<?= htmlspecialchars($order['user_id']) ?>  
                        </td>  
                        <td class="py-3 px-6 text-left">  
                            <?= htmlspecialchars($order['first_name'] . " " .  $order['last_name']) ?>  
                        </td>  
                        <td class="py-3 px-6 text-right">  
                            €<?= number_format($order['total_amount'], 2) ?>  
                        </td>  
                        <td class="py-3 px-6 text-center">  
                            <span class="<?= getStatusClass($order['status']) ?> py-1 px-3 rounded-full text-xs">  
                                <?= ucfirst(htmlspecialchars($order['status'])) ?>  
                            </span>  
                        </td>  
                        <td class="py-3 px-6 text-center">  
                            <?= htmlspecialchars(date('Y-m-d H:i', strtotime($order['created_at']))) ?>  
                        </td>  
                        <td class="py-3 px-6 text-center">  
                            <div class="flex item-center justify-center">  
                                <a href="index.php?controller=seller&action=viewOrder&id=<?= $order['id'] ?>"   
                                   class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">  
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">  
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />  
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />  
                                    </svg>  
                                </a>  
                                <?php if ($order['status'] === 'pending'): ?>  
                                    <form action="index.php?controller=seller&action=updateOrderStatus" method="POST" class="inline">  
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">  
                                        <input type="hidden" name="status" value="confirmed">  
                                        <button type="submit" class="w-4 mr-2 transform hover:text-green-500 hover:scale-110">  
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">  
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />  
                                            </svg>  
                                        </button>  
                                    </form>  
                                    <form action="index.php?controller=seller&action=updateOrderStatus" method="POST" class="inline">  
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">  
                                        <input type="hidden" name="status" value="cancelled">  
                                        <button type="submit" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110">  
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">  
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />  
                                            </svg>  
                                        </button>  
                                    </form>  
                                <?php endif; ?>  
                            </div>  
                        </td>  
                    </tr>  
                <?php endforeach; ?>  
            </tbody>  
        </table>  
    </div>  
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

<?php require_once 'views/layouts/footer.php'; ?>