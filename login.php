<?php
echo "<h2>هذه الصفحة تحت التطوير</h2>";

// إعدادات GitHub
github_token = "ghp_11BF7DJAY0ygBqKQaXYmFJ_MF8eIy044GRgRyqI0Rw3A7bF0FvS1hapl23mUDYvMcZFTCNK5LWdH0jtA86"; // حط التوكن هنا
$repo_owner = "basimzyx"; // اسمك في GitHub
$repo_name = "litecoin-mining"; // اسم الريبو
$file_path = "users.json"; // المسار داخل الريبو
$api_url = "https://api.github.com/repos/basimzyx/litecoin-mining/contents/users.json";

// بيانات النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';

    // تجهيز headers
    $headers = [
        "Authorization: token $github_token",
        "User-Agent: basimzyx-Agent"
    ];

    // جلب ملف المستخدمين من GitHub
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if (isset($data["content"])) {
        $decoded = base64_decode($data["content"]);
        $users = json_decode($decoded, true);

        $found = false;

        foreach ($users as $user) {
            if ($user["email"] === $email && $user["password"] === $password) {
                $found = true;
                break;
            }
        }

        if ($found) {
            echo "<p>تم تسجيل الدخول بنجاح. مرحباً بك!</p>";
        } else {
            echo "<p>ليس لديك حساب. الرجاء التسجيل أولاً.</p>";
        }
    } else {
        echo "<p>تعذر تحميل بيانات المستخدمين من GitHub.</p>";
    }
}
?>

<!-- نموذج تسجيل الدخول -->
<form method="POST">
    <label>البريد الإلكتروني:</label><br>
    <input type="email" name="email" required><br><br>

    <label>كلمة المرور:</label><br>
    <input type="password" name="password" required><br><br>

    <input type="submit" value="تسجيل الدخول">
</form>
