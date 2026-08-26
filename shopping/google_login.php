

<?php
session_start();

require __DIR__ . '/../vendor/autoload.php';

use Google\Client;

$client = new Client();

$client->setClientId('616178819731-g7b7thejbuei41d6bi13vkc9okg0i61p.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/shop/shopping/google_callback.php');

$client->addScope("email");
$client->addScope("profile");

// บังคับเลือกบัญชี
$client->setPrompt('select_account');

header('Location: ' . $client->createAuthUrl());
exit;
;