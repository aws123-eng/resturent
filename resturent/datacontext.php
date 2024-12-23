<?php
$host = 'localhost';  // اسم المضيف
$db = 'user_management';  // اسم قاعدة البيانات
$user = 'root';  // اسم المستخدم لقاعدة البيانات
$pass = '';  // كلمة المرور لقاعدة البيانات

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "خطأ في الاتصال: " . $e->getMessage();
}
?>
