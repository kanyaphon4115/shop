<?php
session_start();
require_once __DIR__ . '/../config/connection.php';

$orderNumber = trim($_GET['order'] ?? '');
$lastOrderId = (int) ($_SESSION['last_order_id'] ?? 0);
$stmt = mysqli_prepare($conn, 'SELECT * FROM orders WHERE order_number = ? AND id = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'si', $orderNumber, $lastOrderId);
mysqli_stmt_execute($stmt);
$order = mysqli_stmt_get_result($stmt)->fetch_assoc();
if (!$order) {
    http_response_code(404);
    exit('Order not found.');
}
$itemStmt = mysqli_prepare($conn, 'SELECT * FROM order_items WHERE order_id = ? ORDER BY id');
mysqli_stmt_bind_param($itemStmt, 'i', $lastOrderId);
mysqli_stmt_execute($itemStmt);
$items = mysqli_stmt_get_result($itemStmt);
$shipping = json_decode($order['shipping_json'] ?? '{}', true) ?: [];
$paymentLabels = ['card' => 'Credit / Debit Card', 'promptpay' => 'PromptPay QR', 'bank_transfer' => 'Bank Transfer', 'cod' => 'Cash on Delivery'];
function e($value): string { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Order <?php echo e($orderNumber); ?> | SPARK</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-gray-100 text-gray-900"><header class="bg-white shadow"><div class="flex items-center justify-between px-5 py-4 md:px-10"><a href="../index.php" class="text-3xl font-bold">SPARK</a><span class="text-sm text-gray-500">Order confirmation</span></div></header><nav class="overflow-x-auto border-t bg-white"><div class="min-w-[720px]"><?php include __DIR__ . '/../includes/navbar.php'; ?></div></nav>
<main class="mx-auto max-w-4xl px-4 py-10"><section class="rounded bg-white p-5 shadow-sm md:p-8"><div class="text-center"><div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-3xl text-green-600">✓</div><h1 class="mt-4 text-3xl font-bold">Order placed successfully</h1><p class="mt-2 text-gray-500">Your order has been created. Online payment remains unconfirmed until a payment provider verifies it.</p></div>
<div class="mt-8 grid gap-4 rounded bg-gray-50 p-5 sm:grid-cols-2"><p><span class="block text-xs text-gray-500">Order ID</span><b><?php echo e($order['order_number']); ?></b></p><p><span class="block text-xs text-gray-500">Order date</span><b><?php echo e(date('F j, Y, g:i a', strtotime($order['created_at']))); ?></b></p><p><span class="block text-xs text-gray-500">Payment method</span><b><?php echo e($paymentLabels[$order['payment_method']] ?? $order['payment_method']); ?></b></p><p><span class="block text-xs text-gray-500">Payment status</span><b class="capitalize"><?php echo e(str_replace('_', ' ', $order['payment_status'])); ?></b></p></div>
<h2 class="mt-8 text-xl font-bold">Ordered products</h2><div class="mt-4 divide-y rounded border"><?php while ($item = mysqli_fetch_assoc($items)): ?><div class="flex items-center gap-4 p-4"><img src="../assets/images/<?php echo e($item['product_image']); ?>" class="h-20 w-20 rounded object-contain" alt=""><div class="min-w-0 flex-1"><p class="font-semibold"><?php echo e($item['product_name']); ?></p><p class="text-sm text-gray-500">Size <?php echo e($item['size']); ?> · Quantity <?php echo (int)$item['quantity']; ?></p></div><b>$<?php echo number_format((float)$item['price'] * (int)$item['quantity'], 2); ?></b></div><?php endwhile; ?></div>
<div class="mt-6 grid gap-6 sm:grid-cols-2"><div><h2 class="font-bold">Shipping address</h2><p class="mt-2 text-sm leading-6 text-gray-600"><?php echo e($shipping['name'] ?? ''); ?><br><?php echo nl2br(e($shipping['address'] ?? '')); ?><br><?php echo e(($shipping['city'] ?? '') . ', ' . ($shipping['province'] ?? '') . ' ' . ($shipping['postal_code'] ?? '')); ?><br><?php echo e($shipping['phone'] ?? ''); ?></p></div><div class="rounded bg-gray-50 p-5"><div class="flex justify-between"><span>Shipping</span><span>$<?php echo number_format((float)$order['shipping_fee'], 2); ?></span></div><div class="mt-3 flex justify-between"><span>Discount</span><span>− $<?php echo number_format((float)$order['discount'], 2); ?></span></div><div class="mt-4 flex justify-between border-t pt-4 text-xl"><b>Total</b><b class="text-orange-600">$<?php echo number_format((float)$order['total'], 2); ?></b></div></div></div>
<a href="../index.php" class="mx-auto mt-8 block w-fit rounded bg-orange-500 px-7 py-3 font-semibold text-white hover:bg-orange-600">Continue Shopping</a></section></main></body></html>
