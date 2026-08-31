<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/connection.php';

function account_user_id(): int { return (int)($_SESSION['user_id'] ?? 0); }
function account_require_login(): int {
    $id = account_user_id();
    if (!$id) { $script=str_replace('\\','/',$_SERVER['SCRIPT_NAME']??'');$base=strstr($script,'/profile/',true)?:'';header('Location: '.$base.'/index.php'); exit; }
    return $id;
}
function account_e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function account_csrf(): string {
    if (empty($_SESSION['account_csrf'])) $_SESSION['account_csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['account_csrf'];
}
function account_check_csrf(): void {
    if (!hash_equals($_SESSION['account_csrf'] ?? '', (string)($_POST['csrf'] ?? ''))) {
        http_response_code(403); exit('Invalid request token.');
    }
}
function account_user(mysqli $conn, int $id): array {
    $stmt=$conn->prepare('SELECT id,name,email,password,phone,birthday,gender FROM users WHERE id=? LIMIT 1');
    $stmt->bind_param('i',$id); $stmt->execute(); return $stmt->get_result()->fetch_assoc() ?: [];
}
function account_redirect(string $page, string $message): void {
    $_SESSION['account_flash'] = $message;
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $siteBase = strstr($script, '/profile/', true);
    if ($siteBase === false) $siteBase = '';
    header('Location: ' . $siteBase . '/profile/' . ltrim($page, '/'));
    exit;
}
