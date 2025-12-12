<?php
require_once 'config.php';

$message = getMessage();

try {
    $conn = getDBConnection();

    // جلب جميع المهارات
    $stmt = $conn->prepare("SELECT s.*, u.full_name as instructor_name FROM skills s JOIN users u ON s.instructor_id = u.id ORDER BY s.created_at DESC");
    $stmt->execute();
    $skills = $stmt->fetchAll();

    // جلب الفئات المتاحة
    $stmt = $conn->prepare("SELECT DISTINCT category FROM skills ORDER BY category");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

} catch (PDOException $e) {
    showMessage('حدث خطأ في تحميل المهارات.', 'error');
    $skills = [];
    $categories = [];
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المهارات التكنولوجية - شبكة المعلومات</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">شبكة المعلومات</div>
            <ul class="nav-links">
                <li><a href="index.php">الرئيسية</a></li>
                <li><a href="skills.php">المهارات التكنولوجية</a></li>
                <li><a href="exchange.php">تبادل المهارات</a></li>
                <li><a href="add_skill.php">إضافة مهارة</a></li>
                <li><a href="evaluate.php">تقييم المهارات</a></li>
                <?php if (isLoggedIn()): ?>
                    <li><a href="dashboard.php">لوحة التحكم</a></li>
                    <li><a href="logout.php">تسجيل الخروج</a></li>
                <?php else: ?>
                    <li><a href="login.php">تسجيل الدخول</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h1>المهارات التكنولوجية</h1>
            <p>استكشف مجموعة واسعة من المهارات التكنولوجية المقدمة من خبراء المنصة</p>
        </section>

        <?php if ($message): ?>
            <div class="message message-<?php echo $message['type']; ?>">
                <?php echo $message['message']; ?>
            </div>
        <?php endif; ?>

        <section class="section fade-in">
            <h2>الفئات المتاحة</h2>
            <div class="grid">
                <?php foreach ($categories as $category): ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($category); ?></h3>
                        <p>استكشف المهارات المتاحة في هذه الفئة</p>
                        <a href="#<?php echo urlencode($category); ?>" class="btn">عرض المهارات</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="section fade-in">
            <h2>جميع المهارات</h2>
            <?php if (empty($skills)): ?>
                <div class="card">
                    <p>لا توجد مهارات متاحة حالياً. <?php if (isLoggedIn()): ?><a href="add_skill.php">أضف مهارة جديدة</a><?php endif; ?></p>
                </div>
            <?php else: ?>
                <div class="grid">
                    <?php foreach ($skills as $skill): ?>
                        <div class="card" id="<?php echo urlencode($skill['category']); ?>">
                            <h3><?php echo htmlspecialchars($skill['name']); ?></h3>
                            <p><strong>المدرب:</strong> <?php echo htmlspecialchars($skill['instructor_name']); ?></p>
                            <p><strong>الفئة:</strong> <?php echo htmlspecialchars($skill['category']); ?></p>
                            <p><strong>المستوى:</strong> <?php echo htmlspecialchars($skill['level']); ?></p>
                            <p><strong>المدة:</strong> <?php echo htmlspecialchars($skill['duration']); ?> أسبوع</p>
                            <p><strong>السعر:</strong> <?php echo htmlspecialchars($skill['price']); ?> ريال</p>
                            <p><strong>الموقع:</strong> <?php echo htmlspecialchars($skill['location']); ?></p>
                            <p><?php echo htmlspecialchars(substr($skill['description'], 0, 150)); ?>...</p>
                            <?php if (!empty($skill['prerequisites'])): ?>
                                <p><strong>المتطلبات:</strong> <?php echo htmlspecialchars($skill['prerequisites']); ?></p>
                            <?php endif; ?>
                            <a href="mailto:<?php echo htmlspecialchars($skill['contact_info']); ?>" class="btn">تواصل مع المدرب</a>
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
