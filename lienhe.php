<?php
require_once 'config.php';

// Handle Contact Form Submission
$success = "";
$error = "";

if (isset($_POST['send_contact'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;

    // Create table if not exists (Lazy init)
    $conn->query("CREATE TABLE IF NOT EXISTS contacts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT DEFAULT NULL,
        name VARCHAR(100),
        email VARCHAR(100),
        message TEXT,
        reply TEXT,
        reply_at TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    $stmt = $conn->prepare("INSERT INTO contacts (user_id, name, email, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $name, $email, $message);

    if ($stmt->execute()) {
        $success = "Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi sớm nhất!";
    } else {
        $error = "Có lỗi xảy ra. Vui lòng thử lại.";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Liên Hệ - Football Shop</title>
    <link rel="stylesheet" href="css/style.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <h2 class="section-title">Liên Hệ Với Chúng Tôi</h2>

        <div class="form-container" style="max-width: 600px;">
            <?php if ($success) echo "<p style='color:var(--primary-color); text-align:center; margin-bottom:1rem;'>$success</p>"; ?>
            <?php if ($error) echo "<p class='text-danger text-center'>$error</p>"; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Họ Tên</label>
                    <input type="text" name="name" class="form-control" required placeholder="Nhập tên của bạn">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="Nhập email">
                </div>
                <div class="form-group">
                    <label>Nội Dung</label>
                    <textarea name="message" class="form-control" rows="5" required placeholder="Bạn cần hỗ trợ gì?"></textarea>
                </div>
                <button type="submit" name="send_contact" class="btn" style="width: 100%;">Gửi Liên Hệ</button>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>