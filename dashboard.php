<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$message = getMessage();

try {
    $conn = getDBConnection();

    // جلب بيانات المستخدم
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    // جلب المهارات المضافة من قبل المستخدم
    $stmt = $conn->prepare("SELECT * FROM skills WHERE instructor_id = ? ORDER BY created_at DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $user_skills = $stmt->fetchAll();

    // جلب المهارات المتاحة
    $stmt = $conn->prepare("SELECT s.*, u.full_name as instructor_name FROM skills s JOIN users u ON s.instructor_id = u.id WHERE s.instructor_id != ? ORDER BY s.created_at DESC LIMIT 10");
    $stmt->execute([$_SESSION['user_id']]);
    $available_skills = $stmt->fetchAll();

} catch (PDOException $e) {
    showMessage('حدث خطأ في تحميل البيانات.', 'error');
    $user = null;
    $user_skills = [];
    $available_skills = [];
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - شبكة المعلومات</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">شبكة المعلومات</div>
            <ul class="nav-links">
                <li><a href="dashboard.php">لوحة التحكم</a></li>
                <li><a href="skills.php">المهارات</a></li>
                <li><a href="exchange.php">تبادل المهارات</a></li>
                <li><a href="add_skill.php">إضافة مهارة</a></li>
                <li><a href="evaluate.php">التقييمات</a></li>
                <li><a href="logout.php">تسجيل الخروج</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h1>مرحباً بك، <?php echo htmlspecialchars($user['full_name'] ?? 'المستخدم'); ?>!</h1>
            <p>إدارة حسابك ومهاراتك في شبكة المعلومات</p>
        </section>

        <?php if ($message): ?>
            <div class="message message-<?php echo $message['type']; ?>">
                <?php echo $message['message']; ?>
            </div>
        <?php endif; ?>

        <section class="section fade-in">
            <h2>معلومات الحساب</h2>
            <div class="form-container">
                <div class="grid">
                    <div class="card">
                        <h3>الاسم الكامل</h3>
                        <p><?php echo htmlspecialchars($user['full_name'] ?? 'غير محدد'); ?></p>
                    </div>
                    <div class="card">
                        <h3>البريد الإلكتروني</h3>
                        <p><?php echo htmlspecialchars($user['email'] ?? 'غير محدد'); ?></p>
                    </div>
                    <div class="card">
                        <h3>رقم الهاتف</h3>
                        <p><?php echo htmlspecialchars($user['phone'] ?? 'غير محدد'); ?></p>
                    </div>
                    <div class="card">
                        <h3>المدينة</h3>
                        <p><?php echo htmlspecialchars($user['city'] ?? 'غير محدد'); ?></p>
                    </div>
                </div>
                <div class="card">
                    <h3>الاهتمامات</h3>
                    <p><?php echo htmlspecialchars($user['interests'] ?? 'غير محدد'); ?></p>
                </div>
            </div>
        </section>

        <section class="section fade-in">
            <h2>مهاراتي المضافة</h2>
            <?php if (empty($user_skills)): ?>
                <div class="card">
                    <p>لم تقم بإضافة أي مهارات بعد. <a href="add_skill.php">أضف مهارة جديدة</a></p>
                </div>
            <?php else: ?>
                <div class="grid">
                    <?php foreach ($user_skills as $skill): ?>
                        <div class="card">
                            <h3><?php echo htmlspecialchars($skill['name']); ?></h3>
                            <p><strong>الفئة:</strong> <?php echo htmlspecialchars($skill['category']); ?></p>
                            <p><strong>المستوى:</strong> <?php echo htmlspecialchars($skill['level']); ?></p>
                            <p><strong>السعر:</strong> <?php echo htmlspecialchars($skill['price']); ?> ريال</p>
                            <p><strong>الموقع:</strong> <?php echo htmlspecialchars($skill['location']); ?></p>
                            <p><?php echo htmlspecialchars(substr($skill['description'], 0, 100)); ?>...</p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section class="section fade-in">
            <h2>المهارات المتاحة</h2>
            <?php if (empty($available_skills)): ?>
                <div class="card">
                    <p>لا توجد مهارات متاحة حالياً.</p>
                </div>
            <?php else: ?>
                <div class="grid">
                    <?php foreach ($available_skills as $skill): ?>
                        <div class="card">
                            <h3><?php echo htmlspecialchars($skill['name']); ?></h3>
                            <p><strong>المدرب:</strong> <?php echo htmlspecialchars($skill['instructor_name']); ?></p>
                            <p><strong>الفئة:</strong> <?php echo htmlspecialchars($skill['category']); ?></p>
                            <p><strong>المستوى:</strong> <?php echo htmlspecialchars($skill['level']); ?></p>
                            <p><strong>السعر:</strong> <?php echo htmlspecialchars($skill['price']); ?> ريال</p>
                            <p><?php echo htmlspecialchars(substr($skill['description'], 0, 100)); ?>...</p>
                            <a href="mailto:<?php echo htmlspecialchars($skill['contact_info']); ?>" class="btn">تواصل</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 شبكة المعلومات لتبادل المهارات التكنولوجية. جميع الحقوق محفوظة.</p>
    </footer>
</body>
</html>
