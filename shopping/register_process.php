<?php
session_start();
include __DIR__ . "/../config/connection.php";

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
if ($username === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
    $_SESSION['register_error'] = 'Enter a name, valid email, and password of at least 8 characters.';
    header('Location: ../index.php'); exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// เช็ค email ซ้ำ
$stmt = $conn->prepare('SELECT id FROM users WHERE email=? LIMIT 1');
$stmt->bind_param('s', $email); $stmt->execute(); $result = $stmt->get_result();

if(mysqli_num_rows($result) > 0){
    $_SESSION['register_error'] = "Email already exists";
    header("Location: ../index.php");
    exit();
}

// insert
$stmt = $conn->prepare('INSERT INTO users (name,email,password) VALUES(?,?,?)');
$stmt->bind_param('sss', $username, $email, $hashed_password);
if($stmt->execute()){
    $_SESSION['user_id'] = (int) mysqli_insert_id($conn);
    $_SESSION['user_name'] = $username;
    $_SESSION['email'] = $email;
    header("Location: ../index.php");
    exit();
}else{
    $_SESSION['register_error'] = 'Could not create account.';
    header('Location: ../index.php'); exit;
}
?>
