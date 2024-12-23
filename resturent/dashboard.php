<?php
session_start();
if ($_SESSION['privilege'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}
$conn = new mysqli("localhost", "root", "", "user_management");

// إضافة مستخدم جديد
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $privilege = $_POST['privilege'];
    $photo = $_FILES['photo']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($photo);
    move_uploaded_file($_FILES['photo']['tmp_name'], $target_file);

    $conn->query("INSERT INTO users (username, email, password, privilege, photo) 
                  VALUES ('$username', '$email', '$password', '$privilege', '$photo')");
}

// تحديث تفاصيل المستخدم
if (isset($_POST['update_user'])) {
    $id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $privilege = $_POST['privilege'];
    $photo = $_FILES['photo']['name'];

    // إذا تم رفع صورة جديدة
    if ($photo) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($photo);
        move_uploaded_file($_FILES['photo']['tmp_name'], $target_file);
        $conn->query("UPDATE users SET username='$username', email='$email', privilege='$privilege', photo='$photo' WHERE id=$id");
    } else {
        $conn->query("UPDATE users SET username='$username', email='$email', privilege='$privilege' WHERE id=$id");
    }
}

// تغيير كلمة السر
if (isset($_POST['change_password'])) {
    $id = $_POST['user_id'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $conn->query("UPDATE users SET password='$new_password' WHERE id=$id");
}

// حذف مستخدم
if (isset($_POST['delete'])) {
    $id = $_POST['user_id'];
    $conn->query("DELETE FROM users WHERE id=$id");
}

// جلب فقط المستخدمين العاديين
$users = $conn->query("SELECT * FROM users WHERE privilege='user'");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Admin Dashboard</h2>

    <!-- نموذج إضافة مستخدم جديد -->
    <h3>Add New User</h3>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="privilege">
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
        <input type="file" name="photo" required>
        <button type="submit" name="add_user">Add User</button>
    </form>

    <!-- عرض قائمة المستخدمين العاديين فقط -->
    <h3>User List</h3>
    <table>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Privilege</th>
            <th>Photo</th>
            <th>Actions</th>
        </tr>
        <?php while ($user = $users->fetch_assoc()): ?>
        <tr>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo $user['privilege']; ?></td>
            <td><img src="uploads/<?php echo $user['photo']; ?>" width="50" height="50"></td>
            <td>
                <!-- تعديل تفاصيل المستخدم -->
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
                    <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
                    <select name="privilege">
                        <option value="admin" <?php echo ($user['privilege'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                        <option value="user" <?php echo ($user['privilege'] == 'user') ? 'selected' : ''; ?>>User</option>
                    </select>
                    <input type="file" name="photo">
                    <button type="submit" name="update_user">Update User</button>
                </form>

                <!-- تغيير كلمة السر -->
                <form method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <input type="password" name="new_password" placeholder="New Password" required>
                    <button type="submit" name="change_password">Change Password</button>
                </form>

                <!-- حذف المستخدم -->
                <form method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <button type="submit" name="delete">Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>