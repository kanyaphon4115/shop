<?php
session_start();
include __DIR__ . "/../config/connection.php";

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// เช็ค email ซ้ำ
$check = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $check);

if(mysqli_num_rows($result) > 0){
    $_SESSION['register_error'] = "Email already exists";
    header("Location: ../index.php");
    exit();
}

// insert
$sql = "INSERT INTO users (name, email, password)
        VALUES ('$username', '$email', '$hashed_password')";

if(mysqli_query($conn, $sql)){
    $_SESSION['user_id'] = (int) mysqli_insert_id($conn);
    $_SESSION['user_name'] = $username;
    $_SESSION['email'] = $email;
    header("Location: ../index.php");
    exit();
}else{
    echo "Error: " . mysqli_error($conn);
}
?>
