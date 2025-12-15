<?php
require_once '../config.php';

// Auth Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Delete Logic
if (isset($_GET['delete_feedback'])) {
    $id = intval($_GET['delete_feedback']);
    $conn->query("DELETE FROM feedbacks WHERE id = $id");
    header("Location: feedbacks.php");
    exit();
}
if (isset($_GET['delete_contact'])) {
    $id = intval($_GET['delete_contact']);
    $conn->query("DELETE FROM contacts WHERE id = $id");
    header("Location: feedbacks.php");
    exit();
}

// Reply to Contact
if (isset($_POST['reply_contact'])) {
    $id = intval($_POST['contact_id']);
    $reply = $_POST['reply_content'];
    $stmt = $conn->prepare("UPDATE contacts SET reply = ?, reply_at = NOW() WHERE id = ?");
    $stmt->bind_param("si", $reply, $id);
    $stmt->execute();
    header("Location: feedbacks.php");
    exit();
}

include 'header_layout.php';

// Ensure tables exist and have new columns (Manual update check)
$conn->query("CREATE TABLE IF NOT EXISTS feedbacks (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100), content TEXT, rating INT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
$conn->query("CREATE TABLE IF NOT EXISTS contacts (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT, name VARCHAR(100), email VARCHAR(100), message TEXT, reply TEXT, reply_at TIMESTAMP NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");

$feedbacks = $conn->query("SELECT * FROM feedbacks ORDER BY created_at DESC");
$contacts = $conn->query("SELECT * FROM contacts ORDER BY created_at DESC");
?>

<h1 class="section-title">Phản Hồi & Liên Hệ</h1>

<div style="margin-bottom: 4rem;">
    <h3><i class="fas fa-comment-dots"></i> Liên Hệ Từ Khách Hàng</h3>
    <?php if ($contacts->num_rows > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Người Gửi</th>
                    <th>Nội Dung</th>
                    <th>Trả Lời</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($c = $contacts->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $c['id']; ?></td>
                        <td>
                            <strong><?php echo $c['name']; ?></strong><br>
                            <small><?php echo $c['email']; ?></small><br>
                            <small class="text-muted"><?php echo $c['created_at']; ?></small>
                        </td>
                        <td><?php echo $c['message']; ?></td>
                        <td>
                            <?php if ($c['reply']): ?>
                                <div class="alert alert-success" style="background:#1e3a29; padding:5px; font-size:0.9rem;">
                                    <strong>Admin:</strong> <?php echo $c['reply']; ?><br>
                                    <small><?php echo $c['reply_at']; ?></small>
                                </div>
                            <?php else: ?>
                                <form method="POST">
                                    <input type="hidden" name="contact_id" value="<?php echo $c['id']; ?>">
                                    <textarea name="reply_content" class="form-control" rows="2" placeholder="Nhập câu trả lời..." style="margin-bottom:5px; font-size:0.9rem;"></textarea>
                                    <button type="submit" name="reply_contact" class="btn" style="padding: 2px 10px; font-size: 0.8rem;">Gửi Trả Lời</button>
                                </form>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="feedbacks.php?delete_contact=<?php echo $c['id']; ?>" class="text-danger" onclick="return confirm('Xóa liên hệ này?')">Xóa</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Chưa có liên hệ nào.</p>
    <?php endif; ?>
</div>

<div>
    <h3><i class="fas fa-star"></i> Đánh Giá Sản Phẩm / Dịch Vụ</h3>
    <?php if ($feedbacks->num_rows > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Khách Hàng</th>
                    <th>Đánh Giá</th>
                    <th>Nội Dung</th>
                    <th>Ngày Gửi</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($f = $feedbacks->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $f['id']; ?></td>
                        <td><?php echo $f['name']; ?></td>
                        <td>
                            <span style="color: #ffc107;">
                                <?php for ($i = 0; $i < $f['rating']; $i++) echo '<i class="fas fa-star"></i>'; ?>
                            </span>
                        </td>
                        <td><?php echo $f['content']; ?></td>
                        <td><?php echo $f['created_at']; ?></td>
                        <td>
                            <a href="feedbacks.php?delete_feedback=<?php echo $f['id']; ?>" class="text-danger" onclick="return confirm('Xóa đánh giá này?')">Xóa</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Chưa có đánh giá nào.</p>
    <?php endif; ?>
</div>

</main>
</div>
</body>

</html>