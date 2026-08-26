<?php
require __DIR__ . '/../vendor/autoload.php';

echo "<h3>Test Google API</h3><hr>";

// เช็ค class
var_dump(class_exists('Google\Service\Oauth2'));