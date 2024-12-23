<?php
$conn = new mysqli("localhost", "root", "", "user_management");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
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
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
        <h2>Create Account</h2>
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="privilege">
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
        <input type="file" name="photo" required>
        <button type="submit">Register</button>
    </form>
</body>