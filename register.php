<?php
// عرض إن الصفحة تحت التطوير
echo "<h2>هذه الصفحة تحت التطوير</h2>";

// التحقق من إرسال النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';

    // مسار الملف
    $usersFile = "users.json";

    // لو الملف ما موجود، ننشئ ملف فاضي
    if (!file_exists($usersFile)) {
        file_put_contents($usersFile, json_encode([]));
    }

    // قراءة البيانات
    $usersData = json_decode(file_get_contents($usersFile), true);

    // التحقق إذا المستخدم مسجل قبل كده
    $alreadyExists = false;
    foreach ($usersData as $user) {
        if ($user["email"] === $email) {
            $alreadyExists = true;
            break;
        }
    }

    if ($alreadyExists) {
        echo "<p>هذا البريد مسجل مسبقاً.</p>";
    } else {
        // إضافة المستخدم الجديد
        $usersData[] = [
            "email" => $email,
            "password" => $password
        ];

        file_put_contents($usersFile, json_encode($usersData, JSON_PRETTY_PRINT));
        echo "<p>تم التسجيل بنجاح. يمكنك الآن تسجيل الدخول.</p>";
    }
}
?>

<!-- نموذج التسجيل -->
<form method="POST">
    <label>البريد الإلكتروني:</label><br>
    <input type="email" name="email" required><br><br>

    <label>كلمة المرور:</label><br>
    <input type="password" name="password" required><br><br>

    <input type="submit" value="إنشاء حساب">
</form>
