<aside class="sidebar">
    <h2 style="color:#fff; margin-bottom:2rem; text-align:center;">ADMIN PANEL</h2>
    <div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 2rem;">
        <?php
        $avatarPath = '../images/user-avatar.png';
        if (file_exists($avatarPath)): ?>
            <img src="<?php echo $avatarPath; ?>" style="width: 80px; height: 80px; border-radius: 50%; margin-bottom: 10px; object-fit: cover;">
        <?php else: ?>
            <div style="width: 80px; height: 80px; border-radius: 50%; margin-bottom: 10px; background: #333; display: flex; align-items: center; justify-content: center; border: 2px solid #555;">
                <i class="fas fa-user-shield" style="font-size: 35px; color: #fff;"></i>
            </div>
        <?php endif; ?>
        <p style="color: #fff; text-align: center; margin: 0;">Xin chào, <?php echo $_SESSION['fullname'] ?? 'Admin'; ?></p>
        <a href="../index.php" class="btn" style="margin-top: 15px; font-size: 0.8rem; padding: 5px 15px; background: var(--secondary-color); color: #fff; text-decoration: none; border-radius: 20px;">
            <i class="fas fa-arrow-left"></i> Về Trang Chủ
        </a>
    </div>

    <nav>
        <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i> Tổng Quan
        </a>
        <a href="categories.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>">
            <i class="fas fa-list"></i> Danh Mục
        </a>
        <a href="products.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>">
            <i class="fas fa-tshirt"></i> Sản Phẩm
        </a>
        <a href="orders.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : ''; ?>">
            <i class="fas fa-shopping-cart"></i> Đơn Hàng
        </a>
        <a href="users.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>">
            <i class="fas fa-users"></i> Khách Hàng
        </a>
        <a href="feedbacks.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'feedbacks.php' ? 'active' : ''; ?>">
            <i class="fas fa-comment-dots"></i> Đánh Giá & LH
        </a>
        <a href="../logout.php" style="color: var(--danger); margin-top: 2rem;">
            <i class="fas fa-sign-out-alt"></i> Đăng Xuất
        </a>
    </nav>
</aside>