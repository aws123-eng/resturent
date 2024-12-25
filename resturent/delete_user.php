<?php
include("db_connection.php");

// التحقق من وجود ID المستخدم للحذف
if (isset($_GET['delete_user'])) {
    $user_id = intval($_GET['delete_user']); // تحويل القيمة إلى عدد صحيح لتجنب مشاكل الأمان

    // تنفيذ عملية الحذف
    $sql = "DELETE FROM users WHERE id = $user_id";
    if ($conn->query($sql) === TRUE) {
        echo "User deleted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "No user ID provided for deletion.";
}
?>
