<?php
session_start();
include __DIR__ . "/../config/connection.php";

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit();
}

$otp = trim($_POST['otp'] ?? '');
$new_password = trim($_POST['new_password'] ?? '');

if ($otp === '' || $new_password === '') {
    echo json_encode(['status' => 'error', 'message' => 'กรุณากรอก OTP และรหัสผ่านใหม่']);
    exit();
}

if (empty($_SESSION['otp']) || empty($_SESSION['reset_email'])) {
    echo json_encode(['status' => 'error', 'message' => 'ไม่มีคำขอรีเซ็ตรหัสผ่าน กรุณาส่ง OTP ใหม่']);
    exit();
}

$expires = 300; // 5 นาที
if (!empty($_SESSION['otp_created_at']) && (time() - $_SESSION['otp_created_at']) > $expires) {
    unset($_SESSION['otp'], $_SESSION['reset_email'], $_SESSION['otp_created_at']);
    echo json_encode(['status' => 'error', 'message' => 'OTP หมดอายุแล้ว กรุณาขอใหม่']);
    exit();
}

// ตรวจสอบ OTP อย่างชัดเจน
$sessionOtp = (string)$_SESSION['otp'];
if ((string)$otp !== $sessionOtp) {
    error_log("OTP Mismatch - Input: '$otp', Session: '$sessionOtp'");
    echo json_encode([
        'status' => 'error', 
        'message' => 'OTP ไม่ถูกต้อง',
        'debug' => "Input: '{$otp}' vs Session: '{$sessionOtp}'"
    ]);
    exit();
}

$email = $_SESSION['reset_email'];
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

$stmt = $conn->prepare('UPDATE users SET password = ? WHERE email = ?');
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
    exit();
}

$stmt->bind_param('ss', $hashed_password, $email);
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        unset($_SESSION['otp'], $_SESSION['reset_email'], $_SESSION['otp_created_at']);
        echo json_encode(['status' => 'success', 'message' => 'รีเซ็ตรหัสผ่านเรียบร้อยแล้ว กรุณาเข้าสู่ระบบใหม่']);
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ไม่พบผู้ใช้ที่มีอีเมลนี้ หรืออีเมลไม่ถูกต้อง']);
        exit();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Execute failed: ' . $stmt->error]);
    exit();
}
