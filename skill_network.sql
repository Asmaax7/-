-- إنشاء قاعدة البيانات
CREATE DATABASE skill_network;
USE skill_network;

-- جدول المستخدمين مع البيانات داخله
CREATE TABLE users AS
SELECT 1 AS id, 'أحمد محمد علي' AS full_name, 'ahmed.mohamed@example.com' AS email, 
       'Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' AS password, '+966501234567' AS phone, 
       'الرياض' AS city, 'تطوير الويب,الذكاء الاصطناعي' AS interests, CURRENT_TIMESTAMP AS created_at
UNION ALL
SELECT 2, 'فاطمة سالم حسن', 'fatima.salem@example.com', 'Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
       '+966507654321', 'جدة', 'أمن المعلومات,تحليل البيانات', CURRENT_TIMESTAMP
UNION ALL
SELECT 3, 'محمد عبدالله خالد', 'mohamed.abdullah@example.com', 'Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
       '+966509876543', 'الدمام', 'تطوير التطبيقات,إنترنت الأشياء', CURRENT_TIMESTAMP
UNION ALL
SELECT 4, 'سارة أحمد يوسف', 'sara.ahmed@example.com', 'Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
       '+966503456789', 'مكة', 'الذكاء الاصطناعي,تطوير الويب', CURRENT_TIMESTAMP
UNION ALL
SELECT 5, 'علي حسن محمد', 'ali.hassan@example.com', 'Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
       '+966502468135', 'المدينة المنورة', 'أمن المعلومات,تطوير التطبيقات', CURRENT_TIMESTAMP;

-- جدول المهارات مع البيانات داخله
CREATE TABLE skills AS
SELECT 1 AS id, 'دورة Python الشاملة' AS name, 'تطوير الويب' AS category, 'مبتدئ' AS level, 
       'تعلم Python من الصفر إلى الاحتراف مع مشاريع عملية' AS description, 8 AS duration, 
       500.00 AS price, 'عبر الإنترنت' AS location, 1 AS instructor_id, 
       'لا توجد متطلبات مسبقة' AS prerequisites, 'حاسوب شخصي، اتصال إنترنت' AS materials, 
       'ahmed.mohamed@example.com' AS contact_info, CURRENT_TIMESTAMP AS created_at
UNION ALL
SELECT 2, 'أساسيات الأمن السيبراني', 'أمن المعلومات', 'متوسط', 
       'تعلم كيفية حماية الأنظمة والشبكات من التهديدات الرقمية', 5, 550.00, 'جدة', 2, 
       'معرفة أساسية بالحواسيب', 'حاسوب شخصي، أدوات أمنية', 'fatima.salem@example.com', CURRENT_TIMESTAMP
UNION ALL
SELECT 3, 'تطوير تطبيقات Android', 'تطوير التطبيقات', 'متوسط', 
       'أتقن تطوير تطبيقات Android باستخدام Java وKotlin', 10, 800.00, 'الرياض', 3, 
       'معرفة أساسية بالبرمجة', 'حاسوب مع Android Studio', 'mohamed.abdullah@example.com', CURRENT_TIMESTAMP
UNION ALL
SELECT 4, 'مقدمة في الذكاء الاصطناعي', 'الذكاء الاصطناعي', 'مبتدئ', 
       'استكشف عالم الذكاء الاصطناعي والتعلم الآلي', 6, 600.00, 'عبر الإنترنت', 4, 
       'لا توجد متطلبات مسبقة', 'حاسوب شخصي، اتصال إنترنت', 'sara.ahmed@example.com', CURRENT_TIMESTAMP
UNION ALL
SELECT 5, 'تحليل البيانات باستخدام Python', 'تحليل البيانات', 'متوسط', 
       'تعلم تحليل البيانات واستخراج الرؤى باستخدام Python', 8, 650.00, 'الدمام', 5, 
       'معرفة أساسية بـ Python', 'حاسوب شخصي، مكتبات Python', 'ali.hassan@example.com', CURRENT_TIMESTAMP;

-- جدول التقييمات (حالياً بدون بيانات)
CREATE TABLE evaluations AS
SELECT 1 AS id, 1 AS user_id, 1 AS skill_id, 0.00 AS score, 
       NULL AS certificate_level, CURRENT_TIMESTAMP AS created_at
WHERE FALSE;
