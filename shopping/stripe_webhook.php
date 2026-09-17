<?php
require_once __DIR__.'/../config/connection.php';
require_once __DIR__.'/../includes/stripe.php';
$secret=spark_payment_config()['webhook_secret'];
$payload=file_get_contents('php://input');$signature=$_SERVER['HTTP_STRIPE_SIGNATURE']??'';
$timestamp=0;$signatures=[];
foreach(explode(',',$signature) as $part){$pair=explode('=',$part,2);if(count($pair)!==2)continue;if($pair[0]==='t')$timestamp=(int)$pair[1];if($pair[0]==='v1')$signatures[]=$pair[1];}
$valid=false;
if($secret&&abs(time()-$timestamp)<=300){$expected=hash_hmac('sha256',$timestamp.'.'.$payload,$secret);foreach($signatures as $candidate)if(hash_equals($expected,$candidate))$valid=true;}
if(!$valid){http_response_code(400);exit('Invalid signature');}
$event=json_decode($payload,true);
if(($event['type']??'')!=='payment_intent.succeeded'){http_response_code(200);exit('Ignored');}
try {
    $intent=$event['data']['object'];$id=(string)($intent['id']??'');
    $s=$conn->prepare('SELECT o.*,s.currency FROM orders o JOIN stripe_orders s ON s.order_id=o.id WHERE s.intent_id=?');$s->bind_param('s',$id);$s->execute();$order=$s->get_result()->fetch_assoc();
    if(!$order)throw new RuntimeException('Order not available yet');
    if($intent['status']!=='succeeded'||$intent['amount_received']!==(int)round($order['total']*100)||$intent['currency']!==$order['currency']||($intent['metadata']['order_id']??'')!=(string)$order['id']){http_response_code(400);exit('Payment mismatch');}
    $s=$conn->prepare("UPDATE orders SET payment_status='paid' WHERE id=?");$s->bind_param('i',$order['id']);$s->execute();
    if(!empty($intent['setup_future_usage']))spark_save_card($conn,(int)$order['user_id'],$intent['payment_method']);
    echo 'Received';
}catch(Throwable $e){error_log('Stripe webhook: '.$e->getMessage());http_response_code(500);echo 'Please retry';}
