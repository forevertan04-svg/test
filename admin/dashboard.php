<?php
require_once '../config.php';

// Auth Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include 'header_layout.php';

// Stats logic
$p_count = $conn->query("SELECT COUNT(*) as c FROM products")->fetch_assoc()['c'];
$o_count = $conn->query("SELECT COUNT(*) as c FROM orders")->fetch_assoc()['c'];
$u_count = $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'];
$r_revenue = $conn->query("SELECT SUM(total_money) as c FROM orders WHERE status = 'completed'")->fetch_assoc()['c'];
?>

<h1 class="section-title">Tổng Quan Hệ Thống</h1>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-info">
            <h3>Tổng Sản Phẩm</h3>
            <div class="stat-number"><?php echo $p_count; ?></div>
        </div>
        <div class="stat-icon"><i class="fas fa-tshirt"></i></div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <h3>Tổng Đơn Hàng</h3>
            <div class="stat-number"><?php echo $o_count; ?></div>
        </div>
        <div class="stat-icon"><i class="fas fa-shopping-bag"></i></div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <h3>Tổng Thành Viên</h3>
            <div class="stat-number"><?php echo $u_count; ?></div>
        </div>
        <div class="stat-icon"><i class="fas fa-users"></i></div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <h3>Doanh Thu</h3>
            <div class="stat-number" style="font-size: 1.5rem;"><?php echo formatPrice($r_revenue ?? 0); ?></div>
        </div>
        <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
    </div>
</div>

<div class="recent-orders" style="margin-top: 3rem;">
    <h3>Đơn Hàng Mới Nhất</h3>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Khách Hàng</th>
                <th>Tổng Tiền</th>
                <th>Trạng Thái</th>
                <th>Ngày Đặt</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $recent = $conn->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");
            if ($recent->num_rows > 0) {
                while ($row = $recent->fetch_assoc()) {
                    echo "<tr>
                        <td>#{$row['id']}</td>
                        <td>{$row['customer_phone']}</td>
                        <td>" . formatPrice($row['total_money']) . "</td>
                        <td><span class='badge badge-{$row['status']}'>{$row['status']}</span></td>
                        <td>{$row['created_at']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>Chưa có đơn hàng nào</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <div class="text-center" style="margin-top: 1rem;">
        <a href="orders.php" class="btn">Xem Tất Cả Đơn Hàng</a>
    </div>
</div>

</main>
</div>
</body>

</html>