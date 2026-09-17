<?php
function spark_payment_config(): array { static $config; return $config ??= require __DIR__.'/../config/payments.php'; }
function spark_stripe(string $path, array $data = [], string $method = 'POST', string $key = ''): array {
    $config = spark_payment_config();
    if (!$config['secret_key'] || !$config['publishable_key']) throw new RuntimeException('Card payments are currently unavailable. Please try again later.');
    $ch = curl_init('https://api.stripe.com/v1/'.$path);
    $headers = ['Authorization: Bearer '.$config['secret_key']];
    if ($key) $headers[] = 'Idempotency-Key: '.$key;
    curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER=>true, CURLOPT_TIMEOUT=>25, CURLOPT_HTTPHEADER=>$headers, CURLOPT_CUSTOMREQUEST=>$method]);
    if ($method !== 'GET') curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $raw = curl_exec($ch); $status = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
    $result = json_decode($raw ?: '', true);
    if ($status < 200 || $status >= 300 || !is_array($result)) throw new RuntimeException('The payment service could not complete this request. Please try again.');
    return $result;
}
function spark_customer(mysqli $conn, int $uid): string {
    $s=$conn->prepare('SELECT customer_id FROM stripe_customers WHERE user_id=?');$s->bind_param('i',$uid);$s->execute();
    if ($row=$s->get_result()->fetch_assoc()) return $row['customer_id'];
    $customer=spark_stripe('customers',['metadata'=>['user_id'=>$uid]],'POST','spark-customer-'.$uid);
    $s=$conn->prepare('INSERT INTO stripe_customers(user_id,customer_id) VALUES(?,?) ON DUPLICATE KEY UPDATE customer_id=VALUES(customer_id)');$s->bind_param('is',$uid,$customer['id']);$s->execute();return $customer['id'];
}
function spark_save_card(mysqli $conn,int $uid,string $method,bool $default=false): void {
    $pm=spark_stripe('payment_methods/'.rawurlencode($method),[],'GET');
    if (($pm['customer']??'')!==spark_customer($conn,$uid)||($pm['type']??'')!=='card') throw new RuntimeException('This card is not available for your account.');
    $c=$pm['card'];$provider='stripe';$conn->begin_transaction();
    try {
        $s=$conn->prepare('SELECT id FROM users WHERE id=? FOR UPDATE');$s->bind_param('i',$uid);$s->execute();$s->store_result();
        $s=$conn->prepare('SELECT COUNT(*) n FROM payment_methods WHERE user_id=?');$s->bind_param('i',$uid);$s->execute();$def=($default||!$s->get_result()->fetch_assoc()['n'])?1:0;
        if($def){$s=$conn->prepare('UPDATE payment_methods SET is_default=0 WHERE user_id=?');$s->bind_param('i',$uid);$s->execute();}
        $s=$conn->prepare('INSERT INTO payment_methods(user_id,provider,payment_method_id,brand,last4,expiry_month,expiry_year,is_default) VALUES(?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE brand=VALUES(brand),last4=VALUES(last4),expiry_month=VALUES(expiry_month),expiry_year=VALUES(expiry_year),is_default=IF(VALUES(is_default)=1,1,is_default)');
        $s->bind_param('issssiii',$uid,$provider,$method,$c['brand'],$c['last4'],$c['exp_month'],$c['exp_year'],$def);$s->execute();$conn->commit();
    } catch(Throwable $e){$conn->rollback();throw $e;}
}
