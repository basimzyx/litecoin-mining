<?php
echo "<h2>هذه الصفحة تحت التطوير</h2>";

// إعدادات GitHub
$github_token = "11BF7DJAY0ygBqKQaXYmFJ_MF8eIy044GRgRyqI0Rw3A7bF0FvS1hapl23mUDYvMcZFTCNK5LWdH0jtA86"; // حط توكنك هنا
$repo_owner = "basimzyx"; // اسم المستخدم في GitHub
$repo_name = "register-data"; // اسم الريبو
$file_path = "users.json"; // المسار داخل الريبو
$api_url = "https://api.github.com/repos/$repo_owner/$repo_name/contents/$file_path";

// بيانات النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';

    // تجهيز request headers
    $headers = [
        "Authorization: token $github_token",
        "User-Agent: Cyber-Agent"
    ];

    // 1. جلب محتوى users.json الحالي من GitHub
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    $data = json_decode($response, true);
    curl_close($ch);

    if (isset($data["content"])) {
        $decoded = base64_decode($data["content"]);
        $users = json_decode($decoded, true);

        // تحقق إذا الإيميل موجود
        foreach ($users as $user) {
            if ($user["email"] === $email) {
                echo "<p>هذا البريد مسجل مسبقاً.</p>";
                exit();
            }
        }

        // أضف المستخدم الجديد
        $users[] = [
            "email" => $email,
            "password" => $password
        ];

        // إعادة تشفير المحتوى
        $updated_content = base64_encode(json_encode($users, JSON_PRETTY_PRINT));

        // تجهيز البيانات لرفعها
        $update_data = json_encode([
            "message" => "Add new user: $email",
            "content" => $updated_content,
            "sha" => $data["sha"]
        ]);

        // 2. إرسال تحديث إلى GitHub
        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($headers, [
            "Content-Type: application/json"
        ]));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $update_data);
        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status == 200 || $status == 201) {
            echo "<p>تم التسجيل بنجاح وتم حفظ بياناتك في GitHub.</p>";
        } else {
            echo "<p>حدث خطأ أثناء رفع البيانات إلى GitHub.</p>";
        }
    } else {
        echo "<p>تعذر الوصول إلى ملف المستخدمين على GitHub.</p>";
    }
}
?>

<form method="POST">
    <label>البريد الإلكتروني:</label><br>
    <input type="email" name="email" required><br><br>

    <label>كلمة المرور:</label><br>
    <input type="password" name="password" required><br><br>

    <input type="submit" value="إنشاء حساب">
</form>
