<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    $avatar = $_FILES['avatar'];

    $usersFile = 'users.txt';
    $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

    foreach ($users as $user) {
        if ($user['email'] === $email) {
            echo "این ایمیل قبلاً ثبت شده است.";
            exit();
        }
    }

    $targetDir = "uploads/";
    $targetFile = $targetDir . time() . '_' . basename($avatar['name']);
    move_uploaded_file($avatar['tmp_name'], $targetFile);

    $users[] = ['email' => $email, 'password' => $password, 'avatar' => $targetFile];
    file_put_contents($usersFile, json_encode($users));

    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>ثبت‌نام</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <div class="form-container">
        <h2>ثبت‌نام</h2>
        <form action="signup.php" method="POST" enctype="multipart/form-data">
            <input type="email" name="email" placeholder="ایمیل" required>
            <input type="password" name="password" placeholder="رمز عبور" required>
            <label>آپلود عکس پروفایل:</label>
            <input type="file" name="avatar" required>
            <button type="submit">ثبت‌نام</button>
        </form>
    </div>
</body>
</html>