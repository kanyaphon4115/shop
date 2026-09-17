<?php
// Environment values take precedence over this machine's private configuration.
$localFile = __DIR__ . '/payments.local.php';
$local = is_file($localFile) ? require $localFile : [];
if (!is_array($local)) $local = [];
return [
    'secret_key' => trim(getenv('STRIPE_SECRET_KEY') ?: ($local['secret_key'] ?? '')),
    'publishable_key' => trim(getenv('STRIPE_PUBLISHABLE_KEY') ?: ($local['publishable_key'] ?? '')),
    'webhook_secret' => trim(getenv('STRIPE_WEBHOOK_SECRET') ?: ($local['webhook_secret'] ?? '')),
    'currency' => strtolower(getenv('SHOP_CURRENCY') ?: ($local['currency'] ?? 'usd')),
];
