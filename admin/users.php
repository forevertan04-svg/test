<?php
require_once '../config.php';

// Auth Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Delete User
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Check if trying to delete self
    if ($id == $_SESSION['user_id']) {
        echo "<script>alert('Không thể xóa chính mình!'); window.location.href='users.php';</script>";
        exit();
    }
    $conn->query("DELETE FROM users WHERE id = $id");
    header("Location: users.php");
    exit();
}

// Create User
if (isset($_POST['create_user'])) {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $check = $conn->query("SELECT * FROM users WHERE username = '$username' OR email = '$email'");
    if ($check->num_rows > 0) {
        echo "<script>alert('Username hoặc Email đã tồn tại!');</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, password, fullname, email, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $password, $fullname, $email, $role);
        $stmt->execute();
        header("Location: users.php");
        exit();
    }
}

include 'header_layout.php';
$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>

<h1 class="section-title">Quản Lý Khách Hàng</h1>

<div class="form-container" style="max-width: 100%; margin-bottom: 2rem;">
    <h3>Thêm Tài Khoản Mới</h3>
    <form method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <div class="form-group">
            <label>Họ Tên</label>
            <input type="text" name="fullname" class="form-control" required placeholder="Nhập họ tên">
        </div>
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required placeholder="Nhập username">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required placeholder="email@example.com">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required placeholder="Nhập password">
        </div>
        <div class="form-group">
            <label>Vai Trò</label>
            <select name="role" class="form-control">
                <option value="user">User (Khách hàng)</option>
                <option value="admin">Admin (Quản trị viên)</option>
            </select>
        </div>
        <button type="submit" name="create_user" class="btn" style="height: 43px; margin-top: 29px;">Thêm Tài Khoản</button>
    </form>
</div>


<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Họ Tên</th>
            <th>Username</th>
            <th>Email</th>
            <th>Vai Trò</th>
            <th>Ngày Đăng Ký</th>
            <th>Hành Động</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($u = $users->fetch_assoc()): ?>
            <tr>
                <td><?php echo $u['id']; ?></td>
                <td><?php echo $u['fullname']; ?></td>
                <td><?php echo $u['username']; ?></td>
                <td><?php echo $u['email']; ?></td>
                <td>
                    <?php if ($u['role'] == 'admin'): ?>
                        <span style="color:red; font-weight:bold;">Admin</span>
                    <?php else: ?>
                        <span style="color:var(--success);">User</span>
                    <?php endif; ?>
                </td>
                <td><?php echo date('d/m/Y', strtotime($u['created_at'])); ?></td>
                <td style="display: flex; gap: 10px; align-items: center;">
                    <a href="edit_user.php?id=<?php echo $u['id']; ?>" style="color: var(--secondary-color);">
                        <i class="fas fa-edit"></i> Sửa
                    </a>
                    <?php if ($u['id'] != $_SESSION['user_id']): ?>
                        <a href="users.php?delete=<?php echo $u['id']; ?>" class="text-danger" onclick="return confirm('Xóa người dùng này?')">
                            <i class="fas fa-trash"></i> Xóa
                        </a>
                    <?php else: ?>
                        <span style="color:#666; font-size: 0.9rem;">(Bạn)</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</main>
</div>
</body>

</html>