<?php
session_start();
include 'db_connection.php';

// التحقق من تسجيل دخول المستخدم
if (!isset($_SESSION['user_id'])) {
    echo "<p>يرجى تسجيل الدخول أولاً.</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // الحصول على البيانات من النموذج
    $itemName = $_POST['item_name'];
    $itemPrice = $_POST['item_price'];
    $userId = $_SESSION['user_id'];

    // التحقق من صحة البيانات
    if (empty($itemName) || empty($itemPrice)) {
        echo "<p>يرجى ملء جميع الحقول.</p>";
        exit;
    }

    // تحضير استعلام SQL لإدخال الطلب في قاعدة البيانات
    $stmt =$pdo->prepare("INSERT INTO `order` (user_id, item_name, item_price) VALUES (:user_id, :item_name, :item_price)");

    // تنفيذ الاستعلام
    if ($stmt->execute(['user_id' => $userId, 'item_name' => $itemName, 'item_price' => $itemPrice])) {
        // إعادة التوجيه بعد إضافة الطلب بنجاح
        echo "<p>تم تسجيل الطلب بنجاح!</p>";
        // يمكنك إعادة توجيه المستخدم هنا إلى صفحة أخرى
        // header("Location: order_confirmation.php"); 
    } else {
        echo "<p>حدث خطأ أثناء تسجيل الطلب.</p>";
    }
}
?>
