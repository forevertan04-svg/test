<?php
require_once '../config.php';

// Auth Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: users.php");
    exit();
}

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    header("Location: users.php");
    exit();
}

// Handle Update
if (isset($_POST['update_user'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Check email uniqueness if changed
    if ($email !== $user['email']) {
        $check = $conn->query("SELECT id FROM users WHERE email = '$email' AND id != $id");
        if ($check->num_rows > 0) {
            $error = "Email đã tồn tại!";
        }
    }

    if (!isset($error)) {
        if (!empty($password)) {
            // Update with password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET fullname = ?, email = ?, role = ?, password = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $fullname, $email, $role, $hashed_password, $id);
        } else {
            // Update without password
            $stmt = $conn->prepare("UPDATE users SET fullname = ?, email = ?, role = ? WHERE id = ?");
            $stmt->bind_param("sssi", $fullname, $email, $role, $id);
        }

        if ($stmt->execute()) {
            header("Location: users.php");
            exit();
        } else {
            $error = "Có lỗi xảy ra!";
        }
    }
}

include 'header_layout.php';
?>

<div style="max-width: 600px; margin: 0 auto;">
    <h1 class="section-title">Cập Nhật Tài Khoản</h1>

    <div class="form-container" style="max-width: 100%; margin-top: 0;">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" disabled style="background: #333; cursor: not-allowed;">
                <small style="color: #666;">Username không thể thay đổi</small>
            </div>

            <div class="form-group">
                <label>Họ Tên</label>
                <input type="text" name="fullname" class="form-control" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="form-group">
                <label>Mặt Khẩu Mới</label>
                <input type="password" name="password" class="form-control" placeholder="Để trống nếu không muốn đổi">
            </div>

            <div class="form-group">
                <label>Vai Trò</label>
                <select name="role" class="form-control">
                    <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User (Khách hàng)</option>
                    <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin (Quản trị viên)</option>
                </select>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" name="update_user" class="btn">Lưu Thay Đổi</button>
                <a href="users.php" class="btn" style="background: #444; color: #fff;">Hủy</a>
            </div>
        </form>
    </div>
</div>


</main>
</div>
</body>

</html>