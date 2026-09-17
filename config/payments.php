<?php
// Set these in the web server environment. Never commit secret keys.
return [
    'secret_key' => getenv('STRIPE_SECRET_KEY') ?: '',
    'publishable_key' => getenv('STRIPE_PUBLISHABLE_KEY') ?: '',
    'webhook_secret' => getenv('STRIPE_WEBHOOK_SECRET') ?: '',
    'currency' => strtolower(getenv('SHOP_CURRENCY') ?: 'usd'),
];
