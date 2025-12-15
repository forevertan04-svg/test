<?php
require_once 'config.php';

// Handle Feedback Submission
if (isset($_POST['submit_feedback'])) {
    $name = $_POST['name'];
    $content = $_POST['content'];
    $rating = $_POST['rating'];

    // Create table if not exists
    $conn->query("CREATE TABLE IF NOT EXISTS feedbacks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100),
        content TEXT,
        rating INT DEFAULT 5,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    $stmt = $conn->prepare("INSERT INTO feedbacks (name, content, rating) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $name, $content, $rating);
    $stmt->execute();
    header("Location: danhgia.php");
    exit();
}

// Fetch all feedbacks
$conn->query("CREATE TABLE IF NOT EXISTS feedbacks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    content TEXT,
    rating INT DEFAULT 5,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

$result = $conn->query("SELECT * FROM feedbacks ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đánh Giá Khách Hàng - Football Shop</title>
    <link rel="stylesheet" href="css/style.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .feedback-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .feedback-card {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 10px;
            border: 1px solid #333;
        }

        .feedback-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .customer-name {
            font-weight: bold;
            color: var(--primary-color);
        }

        .star-rating-display {
            color: #ffc107;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <h2 class="section-title">Khách Hàng Nói Gì Về Chúng Tôi</h2>

        <div class="form-container" style="max-width: 600px; margin-bottom: 3rem;">
            <h3 class="text-center" style="margin-bottom: 1rem;">Gửi Đánh Giá Của Bạn</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Tên của bạn</label>
                    <input type="text" name="name" class="form-control" required placeholder="Nhập tên">
                </div>
                <div class="form-group">
                    <label>Đánh giá</label>
                    <select name="rating" class="form-control">
                        <option value="5">Excellent (5 Sao)</option>
                        <option value="4">Good (4 Sao)</option>
                        <option value="3">Normal (3 Sao)</option>
                        <option value="2">Bad (2 Sao)</option>
                        <option value="1">Terrible (1 Sao)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nội dung</label>
                    <textarea name="content" class="form-control" rows="3" required placeholder="Chia sẻ trải nghiệm..."></textarea>
                </div>
                <button type="submit" name="submit_feedback" class="btn" style="width: 100%;">Gửi Đánh Giá</button>
            </form>
        </div>

        <div class="feedback-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="feedback-card">
                        <div class="feedback-header">
                            <span class="customer-name"><?php echo htmlspecialchars($row['name']); ?></span>
                            <span class="star-rating-display">
                                <?php for ($i = 0; $i < $row['rating']; $i++) echo '<i class="fas fa-star"></i>'; ?>
                            </span>
                        </div>
                        <p><?php echo htmlspecialchars($row['content']); ?></p>
                        <small style="color:#666; display:block; margin-top:1rem;"><?php echo $row['created_at']; ?></small>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">Chưa có đánh giá nào. Hãy là người đầu tiên!</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>