<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $usersFile = 'users.txt';
    $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

    foreach ($users as $user) {
        if ($user['email'] === $email && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header('Location: chat.php');
            exit();
        }
    }

    echo "ایمیل یا رمز عبور اشتباه است.";
}
?>
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <div class="form-container">
        <h2>ورود</h2>
        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="ایمیل" required>
            <input type="password" name="password" placeholder="رمز عبور" required>
            <button type="submit">ورود</button>
        </form>
        <p>حساب کاربری ندارید؟ <a href="signup.php">ثبت‌نام کنید</a></p>
    </div>
</body>
</html>
