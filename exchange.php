<?php
require_once 'config.php';

$message = getMessage();

try {
    $conn = getDBConnection();

    // جلب جميع المهارات للتبادل
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
    <title>تبادل المهارات - شبكة المعلومات</title>
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
            <h1>تبادل المهارات</h1>
            <p>مكان مثالي لتبادل المهارات والمعرفة بين الأعضاء، تعلم مهارة جديدة مقابل تدريس مهارة أخرى</p>
        </section>

        <?php if ($message): ?>
            <div class="message message-<?php echo $message['type']; ?>">
                <?php echo $message['message']; ?>
            </div>
        <?php endif; ?>

        <section class="section fade-in">
            <h2>كيف يعمل تبادل المهارات؟</h2>
            <div class="grid">
                <div class="card">
                    <h3>اختر مهارة تريد تعلمها</h3>
                    <p>تصفح المهارات المتاحة واختر ما يناسب احتياجاتك التعليمية.</p>
                </div>
                <div class="card">
                    <h3>قدم مهارة مقابلها</h3>
                    <p>شارك مهاراتك الخاصة كمقابل لتعلم المهارة المطلوبة.</p>
                </div>
                <div class="card">
                    <h3>ابدأ التبادل</h3>
                    <p>تواصل مع الشريك وابدأ رحلة التعلم المتبادل.</p>
                </div>
                <div class="card">
                    <h3>قيم التجربة</h3>
                    <p>أعطِ تقييماً للتبادل وشارك تجربتك مع المجتمع.</p>
                </div>
            </div>
        </section>

        <section class="section fade-in">
            <h2>البحث عن مهارة</h2>
            <div class="form-container">
                <form method="GET" action="exchange.php">
                    <div class="form-group">
                        <label for="search">البحث عن مهارة:</label>
                        <input type="text" id="search" name="search" placeholder="اكتب اسم المهارة أو الفئة..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="category_filter">تصفية حسب الفئة:</label>
                        <select id="category_filter" name="category">
                            <option value="">جميع الفئات</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category); ?>" <?php echo (isset($_GET['category']) && $_GET['category'] === $category) ? 'selected' : ''; ?>><?php echo htmlspecialchars($category); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="level_filter">تصفية حسب المستوى:</label>
                        <select id="level_filter" name="level">
                            <option value="">جميع المستويات</option>
                            <option value="مبتدئ" <?php echo (isset($_GET['level']) && $_GET['level'] === 'مبتدئ') ? 'selected' : ''; ?>>مبتدئ</option>
                            <option value="متوسط" <?php echo (isset($_GET['level']) && $_GET['level'] === 'متوسط') ? 'selected' : ''; ?>>متوسط</option>
                            <option value="متقدم" <?php echo (isset($_GET['level']) && $_GET['level'] === 'متقدم') ? 'selected' : ''; ?>>متقدم</option>
                        </select>
                    </div>
                    <button type="submit" class="btn">البحث</button>
                </form>
            </div>
        </section>

        <section class="section fade-in">
            <h2>المهارات المتاحة للتبادل</h2>
            <?php
            // تطبيق الفلاتر
            $filtered_skills = $skills;
            if (!empty($_GET['search'])) {
                $search_term = strtolower($_GET['search']);
                $filtered_skills = array_filter($filtered_skills, function($skill) use ($search_term) {
                    return strpos(strtolower($skill['name']), $search_term) !== false ||
                           strpos(strtolower($skill['category']), $search_term) !== false ||
                           strpos(strtolower($skill['description']), $search_term) !== false;
                });
            }
            if (!empty($_GET['category'])) {
                $filtered_skills = array_filter($filtered_skills, function($skill) {
                    return $skill['category'] === $_GET['category'];
                });
            }
            if (!empty($_GET['level'])) {
                $filtered_skills = array_filter($filtered_skills, function($skill) {
                    return $skill['level'] === $_GET['level'];
                });
            }
            ?>
            <?php if (empty($filtered_skills)): ?>
                <div class="card">
                    <p>لا توجد مهارات متاحة للتبادل حالياً. <?php if (isLoggedIn()): ?><a href="add_skill.php">أضف مهارة جديدة</a><?php endif; ?></p>
                </div>
            <?php else: ?>
                <div class="grid">
                    <?php foreach ($filtered_skills as $skill): ?>
                        <div class="card">
                            <h3><?php echo htmlspecialchars($skill['name']); ?></h3>
                            <p><strong>المدرب:</strong> <?php echo htmlspecialchars($skill['instructor_name']); ?></p>
                            <p><strong>الفئة:</strong> <?php echo htmlspecialchars($skill['category']); ?></p>
                            <p><strong>المستوى:</strong> <?php echo htmlspecialchars($skill['level']); ?></p>
                            <p><strong>المدة:</strong> <?php echo htmlspecialchars($skill['duration']); ?> أسبوع</p>
                            <p><strong>الموقع:</strong> <?php echo htmlspecialchars($skill['location']); ?></p>
                            <p><?php echo htmlspecialchars(substr($skill['description'], 0, 150)); ?>...</p>
                            <?php if (!empty($skill['prerequisites'])): ?>
                                <p><strong>المتطلبات:</strong> <?php echo htmlspecialchars($skill['prerequisites']); ?></p>
                            <?php endif; ?>
                            <div class="exchange-actions">
                                <a href="mailto:<?php echo htmlspecialchars($skill['contact_info']); ?>?subject=طلب تبادل مهارة: <?php echo htmlspecialchars($skill['name']); ?>&body=مرحباً، أنا مهتم بتبادل المهارة معك. مهاراتي: [اكتب مهاراتك هنا]" class="btn">اطلب التبادل</a>
                                <button class="btn btn-secondary" onclick="showExchangeModal('<?php echo $skill['id']; ?>', '<?php echo htmlspecialchars($skill['name']); ?>')">اقترح تبادلاً</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section class="section fade-in">
            <h2>نصائح لتبادل ناجح</h2>
            <div class="grid">
                <div class="card">
                    <h3>كن واضحاً في توقعاتك</h3>
                    <p>حدد بوضوح ما تريد تعلمه وما يمكنك تقديمه في المقابل.</p>
                </div>
                <div class="card">
                    <h3>ابدأ بجلسات قصيرة</h3>
                    <p>ابدأ بجلسات تعليمية قصيرة للتأكد من توافق الطرفين.</p>
                </div>
                <div class="card">
                    <h3>استخدم أدوات التواصل</h3>
                    <p>استخدم Zoom، Google Meet، أو Discord للجلسات عبر الإنترنت.</p>
                </div>
                <div class="card">
                    <h3>شارك المعرفة بحرية</h3>
                    <p>تذكر أن الهدف هو بناء مجتمع تعليمي قوي ومفيد للجميع.</p>
                </div>
            </div>
        </section>

        <!-- نافذة اقتراح التبادل -->
        <div id="exchangeModal" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close" onclick="closeExchangeModal()">&times;</span>
                <h2>اقتراح تبادل مهارة</h2>
                <form id="exchangeForm">
                    <div class="form-group">
                        <label for="offered_skill">المهارة التي تقدمها:</label>
                        <input type="text" id="offered_skill" name="offered_skill" placeholder="مثال: تطوير مواقع ويب" required>
                    </div>
                    <div class="form-group">
                        <label for="exchange_message">رسالة التبادل:</label>
                        <textarea id="exchange_message" name="exchange_message" rows="4" placeholder="اكتب رسالة توضح مهاراتك وكيف يمكن أن يكون التبادل مفيداً لكلا الطرفين..." required></textarea>
                    </div>
                    <button type="submit" class="btn">إرسال الاقتراح</button>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 شبكة المعلومات لتبادل المهارات التكنولوجية. جميع الحقوق محفوظة.</p>
    </footer>

    <script>
        function showExchangeModal(skillId, skillName) {
            document.getElementById('exchangeModal').style.display = 'block';
            document.getElementById('exchangeForm').action = 'mailto:?subject=اقتراح تبادل لمهارة: ' + skillName + '&body=' + encodeURIComponent(document.getElementById('exchange_message').value);
        }

        function closeExchangeModal() {
            document.getElementById('exchangeModal').style.display = 'none';
        }

        // إغلاق النافذة عند النقر خارجها
        window.onclick = function(event) {
            var modal = document.getElementById('exchangeModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
