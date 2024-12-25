<?php
include("db_connection.php");

// الحصول على ID المستخدم الذي سيتم تغيير كلمة المرور له
$user_id = isset($_GET['change_password']) ? intval($_GET['change_password']) : null;

if (!$user_id) {
    echo "No user ID provided.";
    exit();
}

// تنفيذ تغيير كلمة المرور
if (isset($_POST['change_password'])) {
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $sql = "UPDATE users SET password = '$new_password' WHERE id = $user_id";
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Password updated successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="form-container">
        <h2>Change Password</h2>
        <form action="" method="post">
            <input type="password" name="new_password" placeholder="New Password" required>
            <button type="submit" name="change_password">Change Password</button>
        </form>
    </div>

</body>
</html>
