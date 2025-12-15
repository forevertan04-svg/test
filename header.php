<?php
// Handle Logout if requested
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
<header>
    <div class="logo">Football<span style="color:white">Shop</span></div>
    <nav>
        <ul>
            <li><a href="index.php">Trang Chủ</a></li>
            <li><a href="sanpham.php">Sản Phẩm</a></li>
            <li><a href="danhgia.php">Đánh Giá</a></li>
            <li><a href="lienhe.php">Liên Hệ</a></li>
            <li><a href="lab.php">Lab</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="profile.php" style="color: #ffc107;"><i class="fas fa-user-circle"></i> <?php echo $_SESSION['username']; ?></a></li>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li><a href="admin/index.php" style="color:var(--primary-color)">Admin</a></li>
                <?php endif; ?>

            <?php else: ?>
                <li><a href="login.php?mode=register">Đăng Ký</a></li>
                <li><a href="login.php">Đăng Nhập</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="header-icons">
        <a href="giohang.php"><i class="fas fa-shopping-cart"></i></a>
        <a href="#"><i class="fas fa-search"></i></a>
    </div>
</header>