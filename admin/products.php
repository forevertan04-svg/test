<?php
require_once '../config.php';

// Auth Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Add Product
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $cat_id = $_POST['category_id'];
    $desc = $_POST['description'];
    $stock = $_POST['stock'];

    // Image Upload Handling
    $image = "default.png";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        if (in_array(strtolower($filetype), $allowed)) {
            $newname = uniqid() . "." . $filetype;
            move_uploaded_file($_FILES['image']['tmp_name'], "../product/" . $newname);
            $image = $newname;
        }
    }

    $stmt = $conn->prepare("INSERT INTO products (name, price, category_id, description, image, stock) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdissi", $name, $price, $cat_id, $desc, $image, $stock);
    $stmt->execute();
    header("Location: products.php");
    exit();
}

// Delete Product
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM products WHERE id = $id");
    header("Location: products.php");
    exit();
}

include 'header_layout.php';
$products = $conn->query("SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
$cats = $conn->query("SELECT * FROM categories");
?>

<h1 class="section-title">Quản Lý Sản Phẩm</h1>

<div class="form-container" style="max-width: 100%; margin-bottom: 2rem;">
    <h3>Thêm Sản Phẩm Mới</h3>
    <form method="POST" enctype="multipart/form-data" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <div class="form-group">
            <label>Tên Sản Phẩm</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Giá (VNĐ)</label>
            <input type="number" name="price" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Danh Mục</label>
            <select name="category_id" class="form-control">
                <?php while ($c = $cats->fetch_assoc()): ?>
                    <option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Số Lượng Kho</label>
            <input type="number" name="stock" class="form-control" value="100">
        </div>
        <div class="form-group">
            <label>Hình Ảnh</label>
            <input type="file" name="image" class="form-control" style="padding-top: 5px;">
        </div>
        <div class="form-group" style="grid-column: 1 / -1;">
            <label>Mô Tả</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" name="add_product" class="btn" style="grid-column: 1 / -1;">Thêm Sản Phẩm</button>
    </form>
</div>

<div style="overflow-x: auto;">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ảnh</th>
                <th>Tên Sản Phẩm</th>
                <th>Danh Mục</th>
                <th>Giá</th>
                <th>Kho</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($p = $products->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $p['id']; ?></td>
                    <td><img src="../product/<?php echo $p['image']; ?>" alt="Img" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;"></td>
                    <td><?php echo $p['name']; ?></td>
                    <td><?php echo $p['cat_name']; ?></td>
                    <td><?php echo formatPrice($p['price']); ?></td>
                    <td><?php echo $p['stock']; ?></td>
                    <td>
                        <a href="products.php?delete=<?php echo $p['id']; ?>" class="text-danger" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</main>
</div>
</body>

</html>