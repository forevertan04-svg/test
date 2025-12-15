<?php
require_once 'config.php';

// Fetch featured products
$sql = "SELECT * FROM products ORDER BY created_at DESC LIMIT 4";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Shop - Áo Đấu Bóng Đá</title>
    <link rel="stylesheet" href="css/style.css?v=2">
    <link rel="preload" href="images/banner.jpg" as="image">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <h1> Chiều Thứ Hai_Ca 3</h1>
    <section class="hero" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('images/banner.png');">
        <h1>ĐAM MÊ BẤT TẬN</h1>
        <p style="margin-bottom: 2rem; font-size: 1.2rem;">Sở hữu ngay những mẫu áo đấu mới nhất 2024</p>
        <a href="sanpham.php" class="btn">MUA NGAY</a>
    </section>

    <div class="container">
        <h2 class="section-title">SẢN PHẨM MỚI</h2>
        <div class="product-grid">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="product-card">';
                    echo '<a href="chitiet.php?id=' . $row["id"] . '">';
                    echo '<img src="product/' . $row["image"] . '" alt="' . $row["name"] . '" class="product-img">';
                    echo '</a>';
                    echo '<div class="product-info">';
                    echo '<a href="chitiet.php?id=' . $row["id"] . '"><h3 class="product-name">' . $row["name"] . '</h3></a>';
                    echo '<div class="product-price">' . formatPrice($row["price"]) . '</div>';
                    echo '<a href="giohang.php?action=add&id=' . $row["id"] . '" class="btn" style="width:100%; display:block;">Thêm vào giỏ</a>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-center">Chưa có sản phẩm nào.</p>';
            }
            ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>