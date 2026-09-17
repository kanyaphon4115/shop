<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/account.php';
require_once __DIR__ . '/../includes/stripe.php';

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
if (!hash_equals(account_csrf(), (string)($data['csrf'] ?? ''))) reply(false,'Please refresh the page and try again.',[],403);
$quote = ($data['action'] ?? '') === 'quote';

$shipping = $data['shipping'] ?? [];
if(!is_array($shipping)) reply(false,'Invalid shipping information.',[],422);
$requiredShipping = ['name', 'email', 'phone', 'address', 'city', 'province', 'postal_code'];
foreach ($quote ? [] : $requiredShipping as $field) {
    if (!is_string($shipping[$field] ?? null) || trim($shipping[$field]) === '') {
        reply(false, 'Shipping information is incomplete.', [], 422);
    }
    $shipping[$field] = trim($shipping[$field]);
}
if (!$quote && !filter_var($shipping['email'], FILTER_VALIDATE_EMAIL)) {
    reply(false, 'Please enter a valid shipping email.', [], 422);
}

$paymentMethod = $data['payment_method'] ?? 'card';
if(!is_string($paymentMethod)) reply(false,'Invalid payment method.',[],422);
if (!$quote && !in_array($paymentMethod, ['card','cod'], true)) reply(false,'This payment method is currently unavailable.',[],422);
$requestKey = (string)($data['request_key'] ?? '');
if (!$quote && !preg_match('/^[a-zA-Z0-9-]{16,64}$/',$requestKey)) reply(false,'Invalid checkout request.',[],422);
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
    if(!is_array($item)) reply(false,'Invalid cart item.',[],422);
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
    if ($quote) {
        mysqli_rollback($conn);
        reply(true,'Cart verified.',['items'=>$verifiedItems,'total'=>$total,'currency'=>spark_payment_config()['currency']]);
    }
    $existing=$conn->prepare('SELECT o.*,s.intent_id FROM stripe_orders s JOIN orders o ON o.id=s.order_id WHERE s.user_id=? AND s.request_key=?');
    $existing->bind_param('is',$userId,$requestKey);$existing->execute();$previous=$existing->get_result()->fetch_assoc();
    if($previous){
        mysqli_rollback($conn);
        $_SESSION['last_order_id']=$previous['id'];
        if($previous['payment_method']==='cod') reply(true,'Order created.',['order_number'=>$previous['order_number']]);
        if(!$previous['intent_id']) reply(false,'Payment is being prepared. Please try again.',[],409);
        $pi=spark_stripe('payment_intents/'.rawurlencode($previous['intent_id']),[],'GET');
        reply(true,'Payment ready.',['order_number'=>$previous['order_number'],'clientSecret'=>$pi['client_secret'],'paymentPaid'=>$pi['status']==='succeeded']);
    }
    if($paymentMethod==='card') {
        $customer=spark_customer($conn,$userId);
        $savedMethod=null;
        if(!empty($data['saved_card'])){
            $cardId=(int)$data['saved_card'];$s=$conn->prepare("SELECT payment_method_id FROM payment_methods WHERE id=? AND user_id=? AND provider='stripe'");$s->bind_param('ii',$cardId,$userId);$s->execute();$card=$s->get_result()->fetch_assoc();
            if(!$card)throw new RuntimeException('This saved card is unavailable.');
            $pm=spark_stripe('payment_methods/'.rawurlencode($card['payment_method_id']),[],'GET');
            if(($pm['customer']??'')!==$customer)throw new RuntimeException('This saved card is unavailable.');
            $savedMethod=$pm['id'];
        }
    }

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

    $currency=spark_payment_config()['currency'];
    $s=$conn->prepare('INSERT INTO stripe_orders(order_id,user_id,request_key,currency) VALUES(?,?,?,?)');$s->bind_param('iiss',$orderId,$userId,$requestKey,$currency);$s->execute();
    $clientSecret=null;
    if($paymentMethod==='card'){
        $params=['amount'=>(int)round($total*100),'currency'=>$currency,'customer'=>$customer,'payment_method_types'=>['card'],'metadata'=>['order_id'=>$orderId,'user_id'=>$userId]];
        if($savedMethod)$params['payment_method']=$savedMethod;
        if(!empty($data['save_card']))$params['setup_future_usage']='off_session';
        $intent=spark_stripe('payment_intents',$params,'POST','spark-order-'.$userId.'-'.$requestKey);
        $s=$conn->prepare('UPDATE stripe_orders SET intent_id=? WHERE order_id=?');$s->bind_param('si',$intent['id'],$orderId);$s->execute();$clientSecret=$intent['client_secret'];
    }
    mysqli_commit($conn);
    $_SESSION['last_order_id'] = $orderId;
    reply(true, 'Order created.', ['order_number' => $orderNumber,'clientSecret'=>$clientSecret]);
} catch (Throwable $error) {
    mysqli_rollback($conn);
    reply(false, $error instanceof RuntimeException ? $error->getMessage() : 'Could not create the order. Please try again.', [], 500);
}
