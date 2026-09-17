<?php
require_once __DIR__.'/../includes/account.php';
require_once __DIR__.'/../includes/stripe.php';
header('Content-Type: application/json');
function payment_reply(array $data,int $status=200): void {http_response_code($status);echo json_encode($data);exit;}
if(!account_user_id()) payment_reply(['error'=>'Please sign in to continue.'],401);
if($_SERVER['REQUEST_METHOD']!=='POST') payment_reply(['error'=>'Method not allowed.'],405);
$data=json_decode(file_get_contents('php://input'),true)?:[];
if(!hash_equals(account_csrf(),(string)($data['csrf']??''))) payment_reply(['error'=>'Your session expired. Refresh the page.'],403);
$uid=account_user_id();
try {
    switch($data['action']??'') {
        case 'cancel':
            $number=(string)($data['order']??'');
            $s=$conn->prepare('SELECT o.id,s.intent_id FROM orders o JOIN stripe_orders s ON s.order_id=o.id WHERE o.order_number=? AND o.user_id=?');$s->bind_param('si',$number,$uid);$s->execute();$order=$s->get_result()->fetch_assoc();
            if(!$order||!$order['intent_id'])throw new RuntimeException('Pending payment not found.');
            $intent=spark_stripe('payment_intents/'.rawurlencode($order['intent_id']),[],'GET');
            if($intent['status']==='succeeded')throw new RuntimeException('This payment already succeeded. Return to checkout to confirm your order.');
            if($intent['status']!=='canceled')spark_stripe('payment_intents/'.rawurlencode($order['intent_id']).'/cancel');
            $s=$conn->prepare("UPDATE orders SET payment_status='cancelled' WHERE id=? AND user_id=?");$s->bind_param('ii',$order['id'],$uid);$s->execute();payment_reply(['ok'=>true]);
        case 'setup':
            $intent=spark_stripe('setup_intents',['customer'=>spark_customer($conn,$uid),'payment_method_types'=>['card'],'usage'=>'off_session','metadata'=>['user_id'=>$uid]]);
            payment_reply(['clientSecret'=>$intent['client_secret']]);
        case 'save':
            $intent=spark_stripe('setup_intents/'.rawurlencode($data['intent']??''),[],'GET');
            if(($intent['status']??'')!=='succeeded'||($intent['customer']??'')!==spark_customer($conn,$uid)) throw new RuntimeException('Card setup has not completed.');
            spark_save_card($conn,$uid,$intent['payment_method'],!empty($data['default']));payment_reply(['ok'=>true]);
        case 'verify':
            $number=(string)($data['order']??'');
            $s=$conn->prepare('SELECT o.*,s.intent_id,s.currency FROM orders o JOIN stripe_orders s ON s.order_id=o.id WHERE o.order_number=? AND o.user_id=?');$s->bind_param('si',$number,$uid);$s->execute();$order=$s->get_result()->fetch_assoc();
            if(!$order||!$order['intent_id']) throw new RuntimeException('Payment not found.');
            $intent=spark_stripe('payment_intents/'.rawurlencode($order['intent_id']),[],'GET');
            if($intent['status']!=='succeeded') payment_reply(['ok'=>false,'status'=>$intent['status'],'error'=>'Payment is not complete. You can retry from checkout.']);
            if($intent['amount_received']!==(int)round($order['total']*100)||$intent['currency']!==$order['currency']||($intent['metadata']['order_id']??'')!=(string)$order['id']) throw new RuntimeException('Payment verification failed.');
            $s=$conn->prepare("UPDATE orders SET payment_status='paid' WHERE id=? AND user_id=?");$s->bind_param('ii',$order['id'],$uid);$s->execute();
            if(!empty($intent['setup_future_usage'])) spark_save_card($conn,$uid,$intent['payment_method']);
            $_SESSION['last_order_id']=$order['id'];payment_reply(['ok'=>true,'order'=>$number]);
        default: payment_reply(['error'=>'Unknown action.'],422);
    }
} catch(Throwable $e){error_log('SPARK payment: '.$e->getMessage());payment_reply(['error'=>$e instanceof RuntimeException?$e->getMessage():'Payment service unavailable. Please try again.'],503);}
