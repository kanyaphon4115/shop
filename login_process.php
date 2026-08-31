<?php
session_start();
require __DIR__ . '/config/connection.php';

function safe_login_redirect(string $target): string
{
    $target = trim($target);
    if ($target === '' || preg_match('/[\r\n]/', $target) || str_starts_with($target, '//') || parse_url($target, PHP_URL_HOST) !== null || parse_url($target, PHP_URL_SCHEME) !== null) {
        return 'index.php';
    }
    return $target;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $redirect = safe_login_redirect((string) ($_POST['redirect'] ?? 'index.php'));

    if ($email === '' || $password === '') {
        $_SESSION['login_error'] = 'กรุณากรอกอีเมลและรหัสผ่าน';
        header('Location: index.php?login=1&redirect=' . rawurlencode($redirect));
        exit;
    }

    $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if ($user['password'] !== '' && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $stmt->close();
            header('Location: ' . $redirect);
            exit;
        }

        // ผู้ใช้ Google login มักจะไม่มี password
        if ($user['password'] === '' && $password === '') {
            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $stmt->close();
            header('Location: ' . $redirect);
            exit;
        }

        $_SESSION['login_error'] = 'อีเมลหรือรหัสผ่านไม่ถูกต้อง';
        $stmt->close();
        header('Location: index.php?login=1&redirect=' . rawurlencode($redirect));
        exit;
    }

    $stmt->close();
    $_SESSION['login_error'] = 'อีเมลหรือรหัสผ่านไม่ถูกต้อง';
    header('Location: index.php?login=1&redirect=' . rawurlencode($redirect));
    exit;
} else {
    header('Location: index.php');
    exit;
}
