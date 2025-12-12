<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$message = getMessage();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name'] ?? '');
    $category = sanitizeInput($_POST['category'] ?? '');
    $level = sanitizeInput($_POST['level'] ?? '');
    $description = sanitizeInput($_POST['description'] ?? '');
    $duration = (int)($_POST['duration'] ?? 0);
    $price = (float)($_POST['price'] ?? 0);
    $location = sanitizeInput($_POST['location'] ?? '');
    $prerequisites = sanitizeInput($_POST['prerequisites'] ?? '');
    $materials = sanitizeInput($_POST['materials'] ?? '');
    $contact_info = sanitizeInput($_POST['contact_info'] ?? '');

    if (empty($name) || empty($category) || empty($level) || empty($description)) {
        showMessage('يرجى ملء جميع الحقول المطلوبة.', 'error');
        redirect('add_skill.php');
    }

    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("INSERT INTO skills (name, category, level, description, duration, price, location, instructor_id, prerequisites, materials, contact_info) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $category, $level, $description, $duration, $price, $location, $_SESSION['user_id'], $prerequisites, $materials, $contact_info]);

        showMessage('تم إضافة المهارة بنجاح!', 'success');
        redirect('dashboard.php');

    } catch (PDOException $e) {
        showMessage('حدث خطأ في إضافة المهارة. يرجى المحاولة لاحقاً.', 'error');
        redirect('add_skill.php');
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة مهارة - شبكة المعلومات</title>
    <link rel="stylesheet" href="style.css">
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
            <h1>إضافة مهارة جديدة</h1>
            <p>شارك معرفتك ومهاراتك مع الآخرين</p>
        </section>

        <?php if ($message): ?>
            <div class="message message-<?php echo $message['type']; ?>">
                <?php echo $message['message']; ?>
            </div>
        <?php endif; ?>

        <section class="section fade-in">
            <div class="form-container">
                <h2>تفاصيل المهارة</h2>
                <form method="POST" action="add_skill.php">
                    <div class="form-group">
                        <label for="name">اسم المهارة *</label>
                        <input type="text" id="name" name="name" placeholder="مثال: دورة Python الشاملة" required>
                    </div>
                    <div class="form-group">
                        <label for="category">الفئة *</label>
                        <select id="category" name="category" required>
                            <option value="">اختر الفئة</option>
                            <option value="تطوير الويب">تطوير الويب</option>
                            <option value="الذكاء الاصطناعي">الذكاء الاصطناعي</option>
                            <option value="أمن المعلومات">أمن المعلومات</option>
                            <option value="تحليل البيانات">تحليل البيانات</option>
                            <option value="تطوير التطبيقات">تطوير التطبيقات</option>
                            <option value="إنترنت الأشياء">إنترنت الأشياء</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="level">المستوى *</label>
                        <select id="level" name="level" required>
                            <option value="">اختر المستوى</option>
                            <option value="مبتدئ">مبتدئ</option>
                            <option value="متوسط">متوسط</option>
                            <option value="متقدم">متقدم</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">وصف المهارة *</label>
                        <textarea id="description" name="description" rows="4" placeholder="وصف مفصل للمهارة والمحتوى المقدم..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="duration">المدة (بالأسابيع)</label>
                        <input type="number" id="duration" name="duration" min="1" placeholder="8">
                    </div>
                    <div class="form-group">
                        <label for="price">السعر (ريال سعودي)</label>
                        <input type="number" id="price" name="price" min="0" step="0.01" placeholder="500.00">
                    </div>
                    <div class="form-group">
                        <label for="location">الموقع</label>
                        <input type="text" id="location" name="location" placeholder="عبر الإنترنت، الرياض، إلخ">
                    </div>
                    <div class="form-group">
                        <label for="prerequisites">المتطلبات المسبقة</label>
                        <textarea id="prerequisites" name="prerequisites" rows="3" placeholder="المعرفة أو المهارات المطلوبة مسبقاً..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="materials">المواد والأدوات</label>
                        <textarea id="materials" name="materials" rows="3" placeholder="المواد المطلوبة أو الأدوات اللازمة..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="contact_info">معلومات التواصل</label>
                        <input type="text" id="contact_info" name="contact_info" placeholder="البريد الإلكتروني أو رقم الهاتف">
                    </div>
                    <button type="submit" class="btn">إضافة المهارة</button>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 شبكة المعلومات لتبادل المهارات التكنولوجية. جميع الحقوق محفوظة.</p>
    </footer>
</body>
</html>
