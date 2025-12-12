<?php
require_once 'config.php';

$message = getMessage();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شبكة المعلومات - تبادل المهارات التكنولوجية</title>
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
            <h1>شبكة المعلومات</h1>
            <p>منصة شاملة لتبادل المهارات التكنولوجية وتطوير القدرات الرقمية في المملكة العربية السعودية</p>
            <a href="skills.php" class="btn">استكشف المهارات</a>
        </section>

        <?php if ($message): ?>
            <div class="message message-<?php echo $message['type']; ?>">
                <?php echo $message['message']; ?>
            </div>
        <?php endif; ?>

        <section class="section fade-in">
            <h2>ما نقدمه</h2>
            <div class="grid">
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1171&q=80" alt="تعلم">
                    <h3>تعلم مهارات جديدة</h3>
                    <p>استفد من دورات متنوعة مقدمة من خبراء في مجالاتهم المختلفة، مع تركيز على المهارات التكنولوجية الحديثة.</p>
                </div>
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1559136555-9303baea8ebd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="تدريس">
                    <h3>درّس وشارك معرفتك</h3>
                    <p>قدم دوراتك الخاصة واكسب دخلاً إضافياً من مهاراتك، وساهم في بناء مجتمع تكنولوجي قوي.</p>
                </div>
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="شبكة">
                    <h3>بناء شبكة مهنية</h3>
                    <p>تواصل مع متخصصين آخرين ووسع دائرة معارفك المهنية في مجال التكنولوجيا.</p>
                </div>
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1589330694653-ded6df03f754?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1171&q=80" alt="شهادات">
                    <h3>شهادات معترف بها</h3>
                    <p>احصل على شهادات تثبت مهاراتك وتساعدك في سوق العمل المحلي والدولي.</p>
                </div>
            </div>
        </section>

        <section class="section fade-in">
            <h2>المهارات الأكثر طلباً</h2>
            <div class="grid">
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1526379095098-d400fd0bf935?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1632&q=80" alt="Python">
                    <h3>برمجة Python</h3>
                    <p>لغة البرمجة الأكثر شعبية في العالم، تستخدم في الذكاء الاصطناعي، تحليل البيانات، والتطوير الويبي.</p>
                    <a href="exchange.php" class="btn">تبادل المهارات</a>
                </div>
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1627398242454-45a1465c2479?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="JavaScript">
                    <h3>تطوير الويب</h3>
                    <p>أتقن تطوير المواقع والتطبيقات الويبية باستخدام HTML، CSS، JavaScript والتقنيات الحديثة.</p>
                    <a href="exchange.php" class="btn">تبادل المهارات</a>
                </div>
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="AI">
                    <h3>الذكاء الاصطناعي</h3>
                    <p>استكشف عالم الذكاء الاصطناعي والتعلم الآلي، وتعلم كيفية بناء نماذج ذكية.</p>
                    <a href="exchange.php" class="btn">تبادل المهارات</a>
                </div>
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1614064641938-3bbee52942c7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Cybersecurity">
                    <h3>الأمن السيبراني</h3>
                    <p>تعلم كيفية حماية الأنظمة والشبكات من التهديدات الرقمية والحفاظ على أمن المعلومات.</p>
                    <a href="exchange.php" class="btn">تبادل المهارات</a>
                </div>
            </div>
        </section>

        <section class="section fade-in">
            <h2>لماذا تختار شبكة المعلومات؟</h2>
            <div class="grid">
                <div class="card">
                    <h3>محتوى عربي</h3>
                    <p>جميع الدورات والمحتوى باللغة العربية مع أمثلة عملية من السوق السعودي.</p>
                </div>
                <div class="card">
                    <h3>خبراء محليون</h3>
                    <p>مدربون سعوديون وخليجيون يفهمون احتياجات سوق العمل المحلي.</p>
                </div>
                <div class="card">
                    <h3>مرونة في التعلم</h3>
                    <p>دورات عبر الإنترنت وحضورية تناسب جدولك الزمني.</p>
                </div>
                <div class="card">
                    <h3>دعم مستمر</h3>
                    <p>مجتمع داعم ومتابعة مستمرة لتقدم المتعلمين.</p>
                </div>
            </div>
        </section>

        <section class="section fade-in">
            <h2>انضم إلينا اليوم</h2>
            <div class="form-container">
                <p>ابدأ رحلتك في تعلم المهارات التكنولوجية مع أفضل الخبراء في المملكة</p>
                <?php if (!isLoggedIn()): ?>
                    <a href="login.php" class="btn">إنشاء حساب مجاني</a>
                <?php else: ?>
                    <a href="dashboard.php" class="btn">لوحة التحكم</a>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 شبكة المعلومات لتبادل المهارات التكنولوجية. جميع الحقوق محفوظة.</p>
    </footer>
</body>
</html>
