<?php
session_start();

require __DIR__ . '/../vendor/autoload.php';

use Google\Client;
use Google\Service\Oauth2;

// 🔗 เชื่อมต่อ database
$conn = new mysqli("localhost", "root", "", "shop");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 🔐 ตั้งค่า Google
$client = new Client();
$client->setClientId('616178819731-g7b7thejbuei41d6bi13vkc9okg0i61p.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/shop/shopping/google_callback.php');

if (isset($_GET['code'])) {

    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (isset($token['error'])) {
        echo "Error: " . $token['error_description'];
        exit();
    }

    $client->setAccessToken($token);

    // 📥 ดึงข้อมูล user จาก Google
    $google_service = new Oauth2($client);
    $data = $google_service->userinfo->get();

    $name = $conn->real_escape_string($data->name);
    $email = $conn->real_escape_string($data->email);

    // 🔍 เช็คว่ามี user นี้แล้วหรือยัง
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");

    if ($check->num_rows == 0) {
        // ➕ เพิ่ม user ใหม่
        $conn->query("INSERT INTO users (name, email, password) VALUES ('$name', '$email', '')");
        $userId = (int) $conn->insert_id;
    } else {
        $userId = (int) $check->fetch_assoc()['id'];
    }

    // 🧠 เก็บ session
    $_SESSION['user_name'] = $name;
    $_SESSION['email'] = $email;
    $_SESSION['user_id'] = $userId;

    // 🔁 redirect
    header("Location: ../index.php");
    exit();
}
