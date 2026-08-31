<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/connection.php';

function reply(bool $ok, string $message, array $extra = [], int $status = 200): void
{
    http_response_code($status);
    echo json_encode(array_merge(['ok' => $ok, 'message' => $message], $extra));
    exit;
}

if (empty($_SESSION['user_id'])) {
    reply(false, 'Please login to continue shopping.', ['login_required' => true, 'login_url' => '../index.php?login=1'], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    reply(false, 'Method not allowed.', [], 405);
}

$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data)) {
    reply(false, 'Invalid order data.', [], 422);
}

$shipping = $data['shipping'] ?? [];
$requiredShipping = ['name', 'email', 'phone', 'address', 'city', 'province', 'postal_code'];
foreach ($requiredShipping as $field) {
    if (!is_string($shipping[$field] ?? null) || trim($shipping[$field]) === '') {
        reply(false, 'Shipping information is incomplete.', [], 422);
    }
    $shipping[$field] = trim($shipping[$field]);
}
if (!filter_var($shipping['email'], FILTER_VALIDATE_EMAIL)) {
    reply(false, 'Please enter a valid shipping email.', [], 422);
}

$paymentMethod = $data['payment_method'] ?? '';
$paymentMetadata = null;
if (preg_match('/^saved:(\d+)$/', $paymentMethod, $match)) {
    $sessionUserId=(int)($_SESSION['user_id']??0);
    if(!$sessionUserId) reply(false,'Please log in to use a saved payment method.',[],403);
    $savedId=(int)$match[1];$savedStmt=mysqli_prepare($conn,'SELECT id,provider,brand,last4,expiry_month,expiry_year FROM payment_methods WHERE id=? AND user_id=? LIMIT 1');mysqli_stmt_bind_param($savedStmt,'ii',$savedId,$sessionUserId);mysqli_stmt_execute($savedStmt);$saved=mysqli_stmt_get_result($savedStmt)->fetch_assoc();
    if(!$saved)reply(false,'That saved payment method is unavailable.',[],422);
    $paymentMetadata=json_encode(['provider'=>$saved['provider'],'brand'=>$saved['brand'],'last4'=>$saved['last4'],'expiry_month'=>(int)$saved['expiry_month'],'expiry_year'=>(int)$saved['expiry_year']],JSON_UNESCAPED_SLASHES);
    $paymentMethod='saved_card';
}
$statuses = [
    'card' => 'pending',
    'promptpay' => 'pending',
    'bank_transfer' => 'awaiting_verification',
    'cod' => 'pending',
    'saved_card' => 'pending',
];
if (!isset($statuses[$paymentMethod])) {
    reply(false, 'Please select a valid payment method.', [], 422);
}

$rawItems = $data['items'] ?? [];
if (!is_array($rawItems) || !$rawItems) {
    reply(false, 'Your cart is empty.', [], 422);
}

$items = [];
foreach ($rawItems as $item) {
    $productId = (int) ($item['id'] ?? 0);
    $size = strtoupper(trim((string) ($item['size'] ?? '')));
    $quantity = max(1, min(99, (int) ($item['quantity'] ?? 1)));
    if ($productId < 1 || $size === '' || strlen($size) > 20) {
        reply(false, 'A cart item is invalid.', [], 422);
    }
    $key = $productId . ':' . $size;
    if (isset($items[$key])) {
        $items[$key]['quantity'] = min(99, $items[$key]['quantity'] + $quantity);
    } else {
        $items[$key] = ['product_id' => $productId, 'size' => $size, 'quantity' => $quantity];
    }
}

mysqli_begin_transaction($conn);
try {
    $productStmt = mysqli_prepare($conn, 'SELECT id, name, image, price FROM products WHERE id = ?');
    $verifiedItems = [];
    $total = 0.0;
    foreach ($items as $item) {
        mysqli_stmt_bind_param($productStmt, 'i', $item['product_id']);
        mysqli_stmt_execute($productStmt);
        $product = mysqli_stmt_get_result($productStmt)->fetch_assoc();
        if (!$product) {
            throw new RuntimeException('A product in your cart is no longer available.');
        }
        $item['name'] = $product['name'];
        $item['image'] = $product['image'];
        $item['price'] = (float) $product['price'];
        $total += $item['price'] * $item['quantity'];
        $verifiedItems[] = $item;
    }

    $userId = (int) $_SESSION['user_id'];

    $shippingJson = json_encode($shipping, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $paymentStatus = $statuses[$paymentMethod];
    $orderStatus = 'pending';
    $orderStmt = mysqli_prepare($conn, 'INSERT INTO orders (user_id, total, status, shipping_json, payment_method, payment_status, payment_metadata_json, shipping_fee, discount) VALUES (NULLIF(?, 0), ?, ?, ?, ?, ?, ?, 0.00, 0.00)');
    mysqli_stmt_bind_param($orderStmt, 'idsssss', $userId, $total, $orderStatus, $shippingJson, $paymentMethod, $paymentStatus, $paymentMetadata);
    mysqli_stmt_execute($orderStmt);
    $orderId = mysqli_insert_id($conn);
    $orderNumber = 'SPK-' . date('Y') . str_pad((string) $orderId, 6, '0', STR_PAD_LEFT);

    $numberStmt = mysqli_prepare($conn, 'UPDATE orders SET order_number = ? WHERE id = ?');
    mysqli_stmt_bind_param($numberStmt, 'si', $orderNumber, $orderId);
    mysqli_stmt_execute($numberStmt);

    $itemStmt = mysqli_prepare($conn, 'INSERT INTO order_items (order_id, product_id, quantity, price, product_name, product_image, size) VALUES (?, ?, ?, ?, ?, ?, ?)');
    foreach ($verifiedItems as $item) {
        mysqli_stmt_bind_param($itemStmt, 'iiidsss', $orderId, $item['product_id'], $item['quantity'], $item['price'], $item['name'], $item['image'], $item['size']);
        mysqli_stmt_execute($itemStmt);
    }

    mysqli_commit($conn);
    $_SESSION['last_order_id'] = $orderId;
    reply(true, 'Order created.', ['order_number' => $orderNumber]);
} catch (Throwable $error) {
    mysqli_rollback($conn);
    reply(false, $error instanceof RuntimeException ? $error->getMessage() : 'Could not create the order. Please try again.', [], 500);
}
