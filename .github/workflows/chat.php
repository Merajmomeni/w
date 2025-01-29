<?php
session_start();

// اگر کاربر وارد نشده باشد، به صفحه ورود هدایت شود
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$messagesFile = 'messages.txt';
$messages = file_exists($messagesFile) ? json_decode(file_get_contents($messagesFile), true) : [];

// ارسال پیام جدید
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = htmlspecialchars($_POST['message']);
    $user = $_SESSION['user'];
    $filePath = '';

    // اگر فایلی ارسال شده باشد
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        $fileName = time() . '_' . basename($_FILES['file']['name']);
        $filePath = $targetDir . $fileName;

        // نوع فایل‌های مجاز
        $allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/avi', 'video/mov'];
        
        // اگر نوع فایل مجاز بود
        if (in_array($_FILES['file']['type'], $allowedFileTypes)) {
            move_uploaded_file($_FILES['file']['tmp_name'], $filePath);
        }
    }

    // افزودن پیام جدید به آرایه
    $messages[] = [
        'email' => $user['email'],
        'avatar' => $user['avatar'],
        'message' => $message,
        'file' => $filePath,
        'time' => date('Y-m-d H:i:s'),
    ];

    // ذخیره پیام‌ها در فایل
    file_put_contents($messagesFile, json_encode($messages));

    // بازگشت به صفحه چت
    header('Location: chat.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>چت</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <div class="chat-container">
        <div class="header">
            <h2>پیام‌رسان</h2>
            <a href="logout.php">خروج</a>
        </div>
        <div class="messages">
            <?php foreach ($messages as $msg): ?>
                <div class="message">
                    <img src="<?php echo htmlspecialchars($msg['avatar']); ?>" alt="Avatar">
                    <div>
                        <strong><?php echo htmlspecialchars($msg['email']); ?></strong>
                        <p><?php echo htmlspecialchars($msg['message']); ?></p>
                        <small><?php echo htmlspecialchars($msg['time']); ?></small>
                        <!-- نمایش فایل ارسال شده -->
                        <?php if ($msg['file']): ?>
                            <div class="file-preview">
                                <?php if (strpos($msg['file'], '.jpg') || strpos($msg['file'], '.jpeg') || strpos($msg['file'], '.png') || strpos($msg['file'], '.gif')): ?>
                                    <img src="<?php echo $msg['file']; ?>" alt="File Preview" style="max-width: 300px;">
                                <?php elseif (strpos($msg['file'], '.mp4') || strpos($msg['file'], '.avi') || strpos($msg['file'], '.mov')): ?>
                                    <video controls style="max-width: 300px;">
                                        <source src="<?php echo $msg['file']; ?>" type="video/<?php echo pathinfo($msg['file'], PATHINFO_EXTENSION); ?>">
                                    </video>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <textarea name="message" placeholder="پیام خود را بنویسید..." required></textarea>
            <input type="file" name="file">
            <button type="submit">ارسال</button>
        </form>
    </div>
</body>
</html>
