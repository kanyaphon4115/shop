<?php
session_start();
include __DIR__ . "/../config/connection.php";

$email = isset($_POST['email']) ? trim($_POST['email']) : '';

// Validate email
if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['status' => 'error', 'message' => 'รูปแบบอีเมลไม่ถูกต้อง']);
    exit();
}

// เช็ค email มีใน DB ไหม (ใช้ prepared statement)
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['status' => 'error', 'message' => 'ไม่พบอีเมลนี้ในระบบ']);
    exit();
}

// สร้าง OTP
$otp = rand(100000,999999);

// เก็บ OTP ใน session
$_SESSION['otp'] = $otp;
$_SESSION['reset_email'] = $email;
$_SESSION['otp_created_at'] = time();

// ===== ส่งเมล (ใช้ PHPMailer) =====
require __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer-master/src/SMTP.php';
require __DIR__ . '/../PHPMailer-master/src/Exception.php';

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'kanyaporn4115k@gmail.com'; // 🔥 เปลี่ยน
    $mail->Password = 'ofixsclzmhbesyof';   // 🔥 ใช้ app password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('kanyaporn4115k@gmail.com', 'Shopcrazy');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = "รหัส OTP ของคุณ";

    $mail->Body = "
<h2>OTP ของคุณ</h2>
<h1 style='color:red;'>$otp</h1>
<p>หมดอายุใน 5 นาที</p>
";
$mail->CharSet = 'UTF-8';
    $mail->AltBody = "OTP ของคุณคือ: $otp";

    $mail->send();

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['status' => 'success', 'message' => 'ส่ง OTP ไปที่อีเมลเรียบร้อยแล้ว']);
    exit();

} catch (Exception $e) {
    header('Content-Type: application/json; charset=utf-8');
    // ส่งข้อมูล error detail เพื่อ debug
    echo json_encode([
        'status' => 'error', 
        'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage(),
        'error_detail' => $mail->ErrorInfo
    ]);
    exit();
}
?>