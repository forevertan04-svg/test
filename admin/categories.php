<?php
require_once '../config.php';

// Auth Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Add Category
if (isset($_POST['add_cat'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $stmt = $conn->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $desc);
    $stmt->execute();
    header("Location: categories.php");
    exit();
}

// Delete Category
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Note: Products with this category might need handling (set to null usually in DB FK)
    $conn->query("DELETE FROM categories WHERE id = $id");
    header("Location: categories.php");
    exit();
}

include 'header_layout.php';
$cats = $conn->query("SELECT * FROM categories");
?>

<h1 class="section-title">Quản Lý Danh Mục</h1>

<div class="form-container" style="max-width: 600px; margin-bottom: 2rem;">
    <h3>Thêm Danh Mục Mới</h3>
    <form method="POST">
        <div class="form-group">
            <label>Tên Danh Mục</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Mô Tả</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <button type="submit" name="add_cat" class="btn">Thêm Danh Mục</button>
    </form>
</div>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên Danh Mục</th>
            <th>Mô Tả</th>
            <th>Hành Động</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($c = $cats->fetch_assoc()): ?>
            <tr>
                <td><?php echo $c['id']; ?></td>
                <td><?php echo $c['name']; ?></td>
                <td><?php echo $c['description']; ?></td>
                <td>
                    <a href="categories.php?delete=<?php echo $c['id']; ?>" class="text-danger" onclick="return confirm('Xóa danh mục này?')">Xóa</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</main>
</div>
</body>

</html>