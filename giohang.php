<?php
require_once 'config.php';

// Add to cart
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    $id = intval($_GET['id']);
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
    header("Location: giohang.php");
    exit();
}

// Remove from cart
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    $id = intval($_GET['id']);
    unset($_SESSION['cart'][$id]);
    header("Location: giohang.php");
    exit();
}

// Update cart
if (isset($_POST['update_cart'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        if ($qty == 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id] = intval($qty);
        }
    }
    header("Location: giohang.php");
    exit();
}

// Checkout Logic
$checkout_success = false;
if (isset($_POST['checkout'])) {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Vui lòng đăng nhập để thanh toán!'); window.location.href='login.php';</script>";
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $total = $_POST['total'];

    // Create Order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, customer_phone, customer_address, total_money) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $phone, $address, $total);

    if ($stmt->execute()) {
        $order_id = $conn->insert_id;

        // Create Order Details
        $sql_detail = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt_detail = $conn->prepare($sql_detail);

        foreach ($_SESSION['cart'] as $p_id => $qty) {
            // Get current price
            $p_res = $conn->query("SELECT price FROM products WHERE id = $p_id");
            $p_row = $p_res->fetch_assoc();
            $price = $p_row['price'];

            $stmt_detail->bind_param("iiid", $order_id, $p_id, $qty, $price);
            $stmt_detail->execute();
        }

        unset($_SESSION['cart']);
        $checkout_success = true;
    }
}

// Fetch Cart Products
$cart_products = [];
$total_money = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $sql = "SELECT * FROM products WHERE id IN ($ids)";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $row['qty'] = $_SESSION['cart'][$row['id']];
        $cart_products[] = $row;
        $total_money += $row['price'] * $row['qty'];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Giỏ Hàng</title>
    <link rel="stylesheet" href="css/style.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <h2 class="section-title">Giỏ Hàng Của Bạn</h2>

        <?php if ($checkout_success): ?>
            <div style="text-align:center; padding: 2rem; background: var(--card-bg); border-radius: 10px;">
                <h3 style="color: var(--primary-color);">Đặt hàng thành công!</h3>
                <p>Cảm ơn bạn đã mua hàng. Chúng tôi sẽ sớm liên hệ.</p>
                <a href="sanpham.php" class="btn" style="display:inline-block; margin-top:1rem;">Tiếp tục mua sắm</a>
            </div>
        <?php elseif (empty($cart_products)): ?>
            <p class="text-center">Giỏ hàng trống.</p>
            <div class="text-center mt-5">
                <a href="sanpham.php" class="btn">Mua sắm ngay</a>
            </div>
        <?php else: ?>
            <form method="POST" action="giohang.php">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sản Phẩm</th>
                            <th>Giá</th>
                            <th>Số Lượng</th>
                            <th>Thành Tiền</th>
                            <th>Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_products as $p): ?>
                            <tr>
                                <td><?php echo $p['name']; ?></td>
                                <td><?php echo formatPrice($p['price']); ?></td>
                                <td>
                                    <input type="number" name="qty[<?php echo $p['id']; ?>]" value="<?php echo $p['qty']; ?>" min="1" style="width: 60px; padding: 5px;">
                                </td>
                                <td><?php echo formatPrice($p['price'] * $p['qty']); ?></td>
                                <td><a href="giohang.php?action=remove&id=<?php echo $p['id']; ?>" class="text-danger">Xóa</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div style="text-align: right; margin-top: 1rem;">
                    <h3>Tổng Tiền: <span style="color: var(--secondary-color);"><?php echo formatPrice($total_money); ?></span></h3>
                    <button type="submit" name="update_cart" class="btn" style="background: #444; margin-right: 1rem;">Cập Nhật</button>
                </div>
            </form>

            <div class="form-container" style="margin-top: 2rem; max-width: 600px;">
                <h3>Thông Tin Thanh Toán</h3>
                <form method="POST">
                    <input type="hidden" name="total" value="<?php echo $total_money; ?>">
                    <div class="form-group">
                        <label>Số Điện Thoại</label>
                        <input type="text" name="phone" class="form-control" required placeholder="Nhập số điện thoại">
                    </div>
                    <div class="form-group">
                        <label>Địa Chỉ Giao Hàng</label>
                        <textarea name="address" class="form-control" required placeholder="Nhập địa chỉ nhà"></textarea>
                    </div>
                    <button type="submit" name="checkout" class="btn" style="width: 100%;">Thanh Toán & Đặt Hàng</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>