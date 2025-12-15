<?php
require_once '../config.php';

// Auth Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Update Order Status
if (isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
    header("Location: orders.php");
    exit();
}

// Delete Order
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM orders WHERE id = $id");
    header("Location: orders.php");
    exit();
}

include 'header_layout.php';
$orders = $conn->query("SELECT o.*, u.username FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
?>

<style>
    @media print {
        @page {
            size: auto;
            margin: 0;
        }

        body {
            background: white;
            color: black;
        }

        .sidebar,
        .btn,
        .no-print,
        header,
        footer {
            display: none !important;
        }

        .admin-wrapper {
            display: block !important;
        }

        .main-content {
            margin: 0 !important;
            padding: 20px !important;
            width: 100% !important;
            overflow: visible !important;
        }

        table {
            width: 100% !important;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            color: black !important;
        }

        th:last-child,
        td:last-child {
            display: none !important;
        }

        /* Hide select arrows in print */
        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            border: none;
            background: none;
        }
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 class="section-title" style="margin-bottom: 0;">Quản Lý Đơn Hàng</h1>
    <button type="button" onclick="window.print();" class="btn"><i class="fas fa-print"></i> In Hóa Đơn</button>
</div>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Khách Hàng</th>
            <th>Thông Tin Giao Hàng</th>
            <th>Tổng Tiền</th>
            <th>Ngày Đặt</th>
            <th>Trạng Thái</th>
            <th>Hành Động</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($o = $orders->fetch_assoc()): ?>
            <tr>
                <td><?php echo $o['id']; ?></td>
                <td>
                    <strong><?php echo $o['username'] ?? 'Khách lẻ'; ?></strong>
                </td>
                <td>
                    <?php echo $o['customer_phone']; ?><br>
                    <small style="color:#888;"><?php echo $o['customer_address']; ?></small>
                </td>
                <td><?php echo formatPrice($o['total_money']); ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($o['created_at'])); ?></td>
                <td>
                    <form method="POST" style="display:flex; align-items:center; gap:5px;">
                        <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                        <select name="status" class="form-control" style="padding: 5px; width: auto; font-size: 0.9rem; height: 30px;" onchange="this.form.submit()">
                            <option value="pending" <?php echo $o['status'] == 'pending' ? 'selected' : ''; ?>>Chờ xử lý</option>
                            <option value="processing" <?php echo $o['status'] == 'processing' ? 'selected' : ''; ?>>Đang giao</option>
                            <option value="completed" <?php echo $o['status'] == 'completed' ? 'selected' : ''; ?>>Hoàn thành</option>
                            <option value="cancelled" <?php echo $o['status'] == 'cancelled' ? 'selected' : ''; ?>>Hủy</option>
                        </select>
                        <input type="hidden" name="update_status" value="1">
                    </form>
                </td>
                <td>
                    <!-- View Details could be added here later -->
                    <a href="orders.php?delete=<?php echo $o['id']; ?>" class="text-danger" onclick="return confirm('Xóa đơn hàng này?')"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</main>
</div>
</body>

</html>