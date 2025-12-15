<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    header("Location: sanpham.php");
    exit();
}

$id = intval($_GET['id']);
$sql = "SELECT p.*, c.name as category_name FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Sản phẩm không tồn tại.";
    exit();
}

$product = $result->fetch_assoc();

// Handle Comment
if (isset($_POST['submit_comment'])) {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Vui lòng đăng nhập để bình luận!');</script>";
    } else {
        $user_id = $_SESSION['user_id'];
        $content = $_POST['content'];
        $rating = $_POST['rating'];

        $stmt = $conn->prepare("INSERT INTO comments (user_id, product_id, content, rating) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iisi", $user_id, $id, $content, $rating);
        $stmt->execute();
        header("Location: chitiet.php?id=$id");
    }
}

// Fetch Comments
$sql_comments = "SELECT c.*, u.fullname FROM comments c 
                 JOIN users u ON c.user_id = u.id 
                 WHERE c.product_id = $id ORDER BY c.created_at DESC";
$comments = $conn->query($sql_comments);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title><?php echo $product['name']; ?> - Football Shop</title>
    <link rel="stylesheet" href="css/style.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .product-detail-container {
            display: flex;
            gap: 3rem;
            margin-top: 2rem;
        }

        .detail-img {
            flex: 1;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #333;
        }

        .detail-img img {
            width: 100%;
            display: block;
        }

        .detail-info {
            flex: 1;
        }

        .detail-price {
            font-size: 2rem;
            color: var(--secondary-color);
            margin: 1rem 0;
            font-weight: bold;
        }

        .detail-desc {
            margin-bottom: 2rem;
            color: #ccc;
        }

        .rating-stars {
            color: #ffc107;
            margin-bottom: 1rem;
        }

        /* Comments */
        .comment-section {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #333;
        }

        .comment-item {
            background: var(--card-bg);
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 5px;
        }

        .comment-user {
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .star-rating {
            display: inline-flex;
            flex-direction: row-reverse;
            gap: 5px;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            color: #555;
            cursor: pointer;
            font-size: 1.5rem;
        }

        .star-rating input:checked~label,
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #ffc107;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="product-detail-container">
            <div class="detail-img">
                <img src="product/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
            </div>
            <div class="detail-info">
                <span class="badge badge-completed" style="margin-bottom:1rem; display:inline-block;"><?php echo $product['category_name']; ?></span>
                <h1 style="color:white; margin-bottom: 0.5rem;"><?php echo $product['name']; ?></h1>

                <div class="rating-stars">
                    <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star-half-alt"></i>
                    <span style="color:#aaa; font-size: 0.9rem;">(4.5/5 đánh giá)</span>
                </div>

                <div class="detail-price"><?php echo formatPrice($product['price']); ?></div>
                <p class="detail-desc"><?php echo $product['description']; ?></p>

                <a href="giohang.php?action=add&id=<?php echo $product['id']; ?>" class="btn" style="padding: 1rem 3rem; font-size: 1.1rem;">
                    <i class="fas fa-shopping-cart" style="margin-right: 10px;"></i> THÊM VÀO GIỎ
                </a>
            </div>
        </div>

        <div class="comment-section">
            <h3 style="margin-bottom: 1.5rem; border-left: 4px solid var(--primary-color); padding-left: 10px;">Đánh Giá & Bình Luận</h3>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="form-container" style="max-width: 100%; margin: 0 0 2rem 0;">
                    <form method="POST">
                        <div class="form-group">
                            <label>Đánh giá của bạn:</label>
                            <div class="star-rating">
                                <input type="radio" id="star5" name="rating" value="5" checked><label for="star5" title="5 sao"><i class="fas fa-star"></i></label>
                                <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="4 sao"><i class="fas fa-star"></i></label>
                                <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="3 sao"><i class="fas fa-star"></i></label>
                                <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="2 sao"><i class="fas fa-star"></i></label>
                                <input type="radio" id="star1" name="rating" value="1"><label for="star1" title="1 sao"><i class="fas fa-star"></i></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea name="content" class="form-control" rows="3" placeholder="Viết bình luận của bạn..." required></textarea>
                        </div>
                        <button type="submit" name="submit_comment" class="btn">Gửi Đánh Giá</button>
                    </form>
                </div>
            <?php else: ?>
                <p style="margin-bottom: 2rem;">Vui lòng <a href="login.php" style="color:var(--primary-color)">đăng nhập</a> để đánh giá.</p>
            <?php endif; ?>

            <div class="comment-list">
                <?php if ($comments->num_rows > 0): ?>
                    <?php while ($cmt = $comments->fetch_assoc()): ?>
                        <div class="comment-item">
                            <div class="comment-user">
                                <?php echo $cmt['fullname']; ?>
                                <span style="color: #ffc107; font-size: 0.8rem; margin-left: 10px;">
                                    <?php for ($i = 0; $i < $cmt['rating']; $i++) echo '<i class="fas fa-star"></i>'; ?>
                                </span>
                            </div>
                            <p><?php echo $cmt['content']; ?></p>
                            <small style="color: #666; display:block; margin-top:0.5rem;"><?php echo $cmt['created_at']; ?></small>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Chưa có đánh giá nào.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>