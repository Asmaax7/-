<?php
require_once 'config.php';

$message = getMessage();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitizeInput($_POST['full_name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $city = sanitizeInput($_POST['city'] ?? '');
    $interests = isset($_POST['interests']) ? implode(',', $_POST['interests']) : '';

    // التحقق من صحة البيانات
    if (empty($full_name) || empty($email) || empty($password)) {
        showMessage('يرجى ملء جميع الحقول المطلوبة.', 'error');
        redirect('login.php');
    }

    if ($password !== $confirm_password) {
        showMessage('كلمة المرور وتأكيدها غير متطابقين.', 'error');
        redirect('login.php');
    }

    if (strlen($password) < 6) {
        showMessage('كلمة المرور يجب أن تكون 6 أحرف على الأقل.', 'error');
        redirect('login.php');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        showMessage('البريد الإلكتروني غير صحيح.', 'error');
        redirect('login.php');
    }

    try {
        $conn = getDBConnection();

        // التحقق من وجود البريد الإلكتروني
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            showMessage('البريد الإلكتروني مسجل مسبقاً.', 'error');
            redirect('login.php');
        }

        // إدراج المستخدم الجديد
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, phone, city, interests) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$full_name, $email, $hashed_password, $phone, $city, $interests]);

        // تسجيل الدخول تلقائياً
        $user_id = $conn->lastInsertId();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $full_name;

        showMessage('تم إنشاء الحساب بنجاح! مرحباً بك في شبكة المعلومات.', 'success');
        redirect('dashboard.php');

    } catch (PDOException $e) {
        showMessage('حدث خطأ في النظام. يرجى المحاولة لاحقاً.', 'error');
        redirect('login.php');
    }
} else {
    redirect('login.php');
}
?>
