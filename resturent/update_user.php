<?php
include("db_connection.php");

// التحقق إذا تم تمرير ID المستخدم لتحريره
if (isset($_GET['edit_user'])) {
    $id = intval($_GET['edit_user']); // تحويل إلى عدد صحيح لضمان الأمان
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found.";
        exit();
    }
}

// تحديث بيانات المستخدم عند إرسال النموذج
if (isset($_POST['update_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $privilege = $_POST['privilege'];

    // التحقق إذا كانت هناك صورة جديدة تم رفعها
    $photo = $_FILES['photo']['name'];
    if ($photo) { // إذا تم رفع صورة جديدة
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($photo);

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            $sql = "UPDATE users SET username='$username', email='$email', privilege='$privilege', photo='$photo' WHERE id=$id";
        } else {
            echo "Failed to upload the image.";
            exit();
        }
    } else {
        $sql = "UPDATE users SET username='$username', email='$email', privilege='$privilege' WHERE id=$id";
    }

    if ($conn->query($sql) === TRUE) {
        echo "User updated successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="form-container">
        <h1>Edit User</h1>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="username" value="<?php echo $user['username']; ?>" required><br>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br>
            <select name="privilege">
                <option value="user" <?php echo $user['privilege'] == 'user' ? 'selected' : ''; ?>>User</option>
                <option value="admin" <?php echo $user['privilege'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select><br>
            <input type="file" name="photo"><br>
            <button type="submit" name="update_user">Update User</button>
        </form>
    </div>

</body>
</html>
