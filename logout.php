<?php
require_once 'config.php';

// تدمير الجلسة
session_destroy();

// إعادة توجيه إلى صفحة تسجيل الدخول
redirect('login.php');
?>
