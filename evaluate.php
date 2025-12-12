<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$message = getMessage();

try {
    $conn = getDBConnection();

    // جلب المهارات المتاحة للتقييم
    $stmt = $conn->prepare("SELECT * FROM skills ORDER BY name");
    $stmt->execute();
    $skills = $stmt->fetchAll();

    // جلب تقييمات المستخدم
    $stmt = $conn->prepare("
        SELECT e.*, s.name as skill_name
        FROM evaluations e
        JOIN skills s ON e.skill_id = s.id
        WHERE e.user_id = ?
        ORDER BY e.created_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $user_evaluations = $stmt->fetchAll();

} catch (PDOException $e) {
    showMessage('حدث خطأ في تحميل البيانات.', 'error');
    $skills = [];
    $user_evaluations = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $skill_id = (int)($_POST['skill_id'] ?? 0);
    $score = (float)($_POST['score'] ?? 0);

    if ($skill_id <= 0 || $score < 0 || $score > 100) {
        showMessage('يرجى ملء جميع الحقول بشكل صحيح.', 'error');
        redirect('evaluate.php');
    }

    // تحديد مستوى الشهادة
    $certificate_level = '';
    if ($score >= 85) {
        $certificate_level = 'متقدم';
    } elseif ($score >= 75) {
        $certificate_level = 'متوسط';
    } elseif ($score >= 60) {
        $certificate_level = 'مبتدئ';
    } else {
        $certificate_level = 'غير ناجح';
    }

    try {
        $conn = getDBConnection();

        // التحقق من عدم وجود تقييم سابق
        $stmt = $conn->prepare("SELECT id FROM evaluations WHERE user_id = ? AND skill_id = ?");
        $stmt->execute([$_SESSION['user_id'], $skill_id]);
        if ($stmt->fetch()) {
            showMessage('لقد قمت بتقييم هذه المهارة مسبقاً.', 'error');
            redirect('evaluate.php');
        }

        // إدراج التقييم
        $stmt = $conn->prepare("INSERT INTO evaluations (user_id, skill_id, score, certificate_level) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $skill_id, $score, $certificate_level]);

        showMessage('تم تسجيل التقييم بنجاح! مستواك: ' . $certificate_level, 'success');
        redirect('evaluate.php');

    } catch (PDOException $e) {
        showMessage('حدث خطأ في تسجيل التقييم.', 'error');
        redirect('evaluate.php');
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقييم المهارات - شبكة المعلومات</title>
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
            <h1>تقييم المهارات</h1>
            <p>قيم مهاراتك وقدراتك التكنولوجية واحصل على شهادة معترف بها</p>
        </section>

        <?php if ($message): ?>
            <div class="message message-<?php echo $message['type']; ?>">
                <?php echo $message['message']; ?>
            </div>
        <?php endif; ?>

        <section class="section fade-in">
            <div class="form-container">
                <h2>اختبار جديد</h2>
                <form method="POST" action="evaluate.php">
                    <div class="form-group">
                        <label for="skill_id">اختر المهارة المراد تقييمها</label>
                        <select id="skill_id" name="skill_id" required>
                            <option value="">اختر المهارة</option>
                            <?php foreach ($skills as $skill): ?>
                                <option value="<?php echo $skill['id']; ?>"><?php echo htmlspecialchars($skill['name']); ?> (<?php echo htmlspecialchars($skill['category']); ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="score">درجتك في الاختبار (0-100)</label>
                        <input type="number" id="score" name="score" min="0" max="100" placeholder="75" required>
                    </div>
                    <button type="submit" class="btn">تسجيل التقييم</button>
                </form>
            </div>
        </section>

        <section class="section fade-in">
            <h2>تقييماتي السابقة</h2>
            <?php if (empty($user_evaluations)): ?>
                <div class="card">
                    <p>لم تقم بأي تقييمات بعد. ابدأ بتقييم مهارة للحصول على شهادة!</p>
                </div>
            <?php else: ?>
                <div class="grid">
                    <?php foreach ($user_evaluations as $evaluation): ?>
                        <div class="card">
                            <h3><?php echo htmlspecialchars($evaluation['skill_name']); ?></h3>
                            <p><strong>الدرجة:</strong> <?php echo htmlspecialchars($evaluation['score']); ?>/100</p>
                            <p><strong>مستوى الشهادة:</strong> <?php echo htmlspecialchars($evaluation['certificate_level']); ?></p>
                            <p><strong>تاريخ التقييم:</strong> <?php echo htmlspecialchars(date('Y-m-d', strtotime($evaluation['created_at']))); ?></p>
                            <?php if ($evaluation['certificate_level'] !== 'غير ناجح'): ?>
                                <div class="certificate">
                                    <h4>شهادة <?php echo htmlspecialchars($evaluation['certificate_level']); ?></h4>
                                    <p>تم منح هذه الشهادة لـ <?php echo htmlspecialchars($_SESSION['user_name']); ?> لإكمال اختبار <?php echo htmlspecialchars($evaluation['skill_name']); ?> بنجاح.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section class="section fade-in">
            <h2>مستويات الشهادات</h2>
            <div class="grid">
                <div class="card">
                    <h3>شهادة المبتدئ</h3>
                    <p>للأشخاص الذين يمتلكون معرفة أساسية في المهارة المختبرة.</p>
                    <p><strong>درجة النجاح:</strong> 60% - 74%</p>
                </div>
                <div class="card">
                    <h3>شهادة المتوسط</h3>
                    <p>للأشخاص ذوي الخبرة المتوسطة والقدرة على التطبيق العملي.</p>
                    <p><strong>درجة النجاح:</strong> 75% - 84%</p>
                </div>
                <div class="card">
                    <h3>شهادة المتقدم</h3>
                    <p>للخبراء والمحترفين في المجال مع قدرة على حل المشكلات المعقدة.</p>
                    <p><strong>درجة النجاح:</strong> 85% فما فوق</p>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 شبكة المعلومات لتبادل المهارات التكنولوجية. جميع الحقوق محفوظة.</p>
    </footer>
</body>
</html>
