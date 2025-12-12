<?php
require_once 'config.php';

$message = getMessage();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        showMessage('يرجى ملء جميع الحقول المطلوبة.', 'error');
        redirect('login.php');
    }

    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            showMessage('تم تسجيل الدخول بنجاح!', 'success');
            redirect('dashboard.php');
        } else {
            showMessage('البريد الإلكتروني أو كلمة المرور غير صحيحة.', 'error');
            redirect('login.php');
        }
    } catch (PDOException $e) {
        showMessage('حدث خطأ في النظام. يرجى المحاولة لاحقاً.', 'error');
        redirect('login.php');
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - شبكة المعلومات</title>
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
                <li><a href="login.php">تسجيل الدخول</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h1>تسجيل الدخول</h1>
            <p>ادخل إلى حسابك للوصول إلى جميع ميزات المنصة</p>
        </section>

        <?php if ($message): ?>
            <div class="message message-<?php echo $message['type']; ?>">
                <?php echo $message['message']; ?>
            </div>
        <?php endif; ?>

        <section class="section fade-in">
            <div class="form-container">
                <h2>تسجيل الدخول</h2>
                <form method="POST" action="login.php">
                    <div class="form-group">
                        <label for="email">البريد الإلكتروني</label>
                        <input type="email" id="email" name="email" placeholder="example@email.com" required>
                    </div>
                    <div class="form-group">
                        <label for="password">كلمة المرور</label>
                        <input type="password" id="password" name="password" placeholder="كلمة المرور" required>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="remember"> تذكرني
                        </label>
                    </div>
                    <button type="submit" class="btn">تسجيل الدخول</button>
                </form>
                <p style="text-align: center; margin-top: 1rem;">
                    <a href="#forgot-password">نسيت كلمة المرور؟</a>
                </p>
            </div>
        </section>

        <section class="section fade-in">
            <h2>إنشاء حساب جديد</h2>
            <div class="form-container">
                <form method="POST" action="register.php">
                    <div class="form-group">
                        <label for="full-name">الاسم الكامل</label>
                        <input type="text" id="full-name" name="full_name" placeholder="الاسم الكامل" required>
                    </div>
                    <div class="form-group">
                        <label for="new-email">البريد الإلكتروني</label>
                        <input type="email" id="new-email" name="email" placeholder="example@email.com" required>
                    </div>
                    <div class="form-group">
                        <label for="new-password">كلمة المرور</label>
                        <input type="password" id="new-password" name="password" placeholder="كلمة مرور قوية" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">تأكيد كلمة المرور</label>
                        <input type="password" id="confirm-password" name="confirm_password" placeholder="تأكيد كلمة المرور" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">رقم الهاتف</label>
                        <input type="tel" id="phone" name="phone" placeholder="+966 50 000 0000">
                    </div>
                    <div class="form-group">
                        <label for="city">المدينة</label>
                        <input type="text" id="city" name="city" placeholder="الرياض، جدة، إلخ">
                    </div>
                    <div class="form-group">
                        <label for="interests">الاهتمامات (اختر أكثر من واحد)</label>
                        <select id="interests" name="interests[]" multiple>
                            <option value="web">تطوير الويب</option>
                            <option value="ai">الذكاء الاصطناعي</option>
                            <option value="security">أمن المعلومات</option>
                            <option value="data">تحليل البيانات</option>
                            <option value="mobile">تطوير التطبيقات</option>
                            <option value="iot">إنترنت الأشياء</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="terms" required> أوافق على <a href="#terms">الشروط والأحكام</a>
                        </label>
                    </div>
                    <button type="submit" class="btn">إنشاء الحساب</button>
                </form>
            </div>
        </section>

        <section class="section fade-in">
            <h2>لماذا تنضم إلينا؟</h2>
            <div class="grid">
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1171&q=80" alt="تعلم">
                    <h3>تعلم مهارات جديدة</h3>
                    <p>استفد من دورات متنوعة مقدمة من خبراء في مجالاتهم.</p>
                </div>
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1559136555-9303baea8ebd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="تدريس">
                    <h3>درّس وشارك معرفتك</h3>
                    <p>قدم دوراتك الخاصة واكسب دخلاً إضافياً من مهاراتك.</p>
                </div>
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="شبكة">
                    <h3>بناء شبكة مهنية</h3>
                    <p>تواصل مع متخصصين آخرين ووسع دائرة معارفك المهنية.</p>
                </div>
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1589330694653-ded6df03f754?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1171&q=80" alt="شهادات">
                    <h3>شهادات معترف بها</h3>
                    <p>احصل على شهادات تثبت مهاراتك وتساعدك في سوق العمل.</p>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 شبكة المعلومات لتبادل المهارات التكنولوجية. جميع الحقوق محفوظة.</p>
    </footer>
</body>
</html>
