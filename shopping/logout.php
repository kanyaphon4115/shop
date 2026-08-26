<?php
session_start();

$_SESSION = [];
session_destroy();

// ✅ ต้องชี้ไป project ของคุณ
header("Location: /shop/index.php");
exit();