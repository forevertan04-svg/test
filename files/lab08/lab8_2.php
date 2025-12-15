<?php
// ------------------- KẾT NỐI CSDL -------------------
try {
    $pdh = new PDO("mysql:host=localhost;dbname=bookstore", "root", "");
    $pdh->query("set names 'utf8'");
} catch (Exception $e) {
    exit($e->getMessage());
}

/* ============================================================
   PHẦN 1 — XỬ LÝ THÊM LOẠI SÁCH
   ============================================================ */
$insert_message = "";

if (isset($_POST["sm_insert"])) {
    $id = trim($_POST["cat_id"]);
    $name = trim($_POST["cat_name"]);

    // Kiểm tra mã loại trùng
    $check = $pdh->prepare("SELECT * FROM category WHERE cat_id = :id");
    $check->execute([":id" => $id]);

    if ($check->rowCount() > 0) {
        $insert_message = "<p style='color:red;'>❌ Mã loại đã tồn tại!</p>";
    } else {
        $sql = "INSERT INTO category(cat_id, cat_name) VALUES(:id, :name)";
        $stm = $pdh->prepare($sql);
        $stm->execute([":id" => $id, ":name" => $name]);

        $insert_message = "<p style='color:green;'>✔ Thêm loại sách thành công!</p>";
    }
}

/* ============================================================
   PHẦN 2 — TÌM KIẾM SÁCH
   ============================================================ */
$search = isset($_GET["search"]) ? trim($_GET["search"]) : "";
$results = [];

if ($search != "") {
    $sql = "SELECT * FROM book WHERE book_name LIKE :search";
    $stm = $pdh->prepare($sql);
    $stm->execute([":search" => "%$search%"]);
    $results = $stm->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Lab8_2 TIM KIEM SACH</title>

<style>
    body { font-family: Arial; background: #fff; padding: 20px; }

    h1 { font-size: 32px; margin-bottom: 10px; }
    h2 { margin-top: 40px; }

    input[type="text"] {
        padding: 6px; width: 240px; border: 1px solid #777; border-radius: 4px;
    }
    input[type="submit"] {
        padding: 6px 14px; background: #333; color: #fff; border: none; cursor: pointer;
        border-radius: 4px;
    }
    input[type="submit"]:hover { background: #000; }

    table {
        width: 100%; border-collapse: collapse; margin-top: 15px;
    }
    th, td {
        border: 1px solid #ccc; padding: 8px; text-align: left;
    }
    th { background: #eee; }
</style>
</head>

<body>
<h2>TÌM KIẾM THÔNG TIN SÁCH</h2>

<form method="get">
    <input type="text" name="search" placeholder="Nhập tên sách..." 
           value="<?php echo htmlspecialchars($search); ?>">
    <input type="submit" value="Tìm">
</form>

<?php if ($search != ""): ?>

    <p><b>Kết quả tìm kiếm:</b></p>

    <table>
        <tr>
            <th>ID</th>
            <th>Tên sách</th>
            <th>Mô tả</th>
            <th>Giá</th>
            <th>Hình</th>
            <th>Mã NXB</th>
            <th>Mã loại</th>
        </tr>

        <?php foreach ($results as $b): ?>
        <tr>
            <td><?php echo $b["book_id"]; ?></td>
            <td><?php echo $b["book_name"]; ?></td>
            <td><?php echo $b["description"]; ?></td>
            <td><?php echo number_format($b["price"]); ?></td>
            <td><?php echo $b["img"]; ?></td>
            <td><?php echo $b["pub_id"]; ?></td>
            <td><?php echo $b["cat_id"]; ?></td>
        </tr>
        <?php endforeach; ?>

    </table>

    <?php if (count($results) == 0): ?>
        <p>❌ Không tìm thấy sách nào.</p>
    <?php endif; ?>

<?php endif; ?>

</body>
</html>
