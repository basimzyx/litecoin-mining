<?php
$email = $_POST['email'] ?? '';

if (!empty($email)) {
    // نحفظ البريد في ملف
    $file = fopen("emails.txt", "a");
    fwrite($file, $email . PHP_EOL);
    fclose($file);
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>شكراً لزيارتكم</title>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            text-align: center;
            background-color: #f9f9f9;
            padding: 50px;
        }
        .message {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="message">
        <h2>الصفحة تحت التطوير</h2>
        <p>شكرًا لزيارتكم، سنقوم بإشعاركم عبر البريد الإلكتروني عند اكتمال الموقع.</p>
    </div>
</body>
</html>