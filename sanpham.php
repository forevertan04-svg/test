<?php
require_once 'config.php';

// Filter by category
$cat_id = isset($_GET['category']) ? $_GET['category'] : 0;
$where = "";
if ($cat_id > 0) {
    $where = "WHERE category_id = $cat_id";
}

// Fetch categories for sidebar
$cats = $conn->query("SELECT * FROM categories");

// Fetch products
$sql = "SELECT * FROM products $where ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Sản Phẩm - Football Shop</title>
    <link rel="stylesheet" href="css/style.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .shop-layout {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 2rem;
            margin-top: 2rem;
        }

        .sidebar {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 10px;
            height: fit-content;
        }

        .sidebar h3 {
            margin-bottom: 1rem;
            color: var(--primary-color);
            border-bottom: 1px solid #333;
            padding-bottom: 0.5rem;
        }

        .cat-list li {
            margin-bottom: 0.8rem;
        }

        .cat-list a {
            display: block;
            padding: 0.5rem;
            border-radius: 5px;
        }

        .cat-list a:hover,
        .cat-list a.active {
            background: #333;
            color: var(--primary-color);
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container shop-layout">
        <aside class="sidebar">
            <h3>Danh Mục</h3>
            <ul class="cat-list">
                <li><a href="sanpham.php" class="<?php echo $cat_id == 0 ? 'active' : ''; ?>">Tất cả</a></li>
                <?php while ($c = $cats->fetch_assoc()): ?>
                    <li>
                        <a href="sanpham.php?category=<?php echo $c['id']; ?>"
                            class="<?php echo $cat_id == $c['id'] ? 'active' : ''; ?>">
                            <?php echo $c['name']; ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </aside>

        <main>
            <h2 class="section-title">Danh Sách Sản Phẩm</h2>
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
                    echo '<p>Không tìm thấy sản phẩm nào.</p>';
                }
                ?>
            </div>
        </main>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>