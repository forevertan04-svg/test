<?php
// ------------------- KẾT NỐI CSDL -------------------
try {
    $pdh = new PDO("mysql:host=localhost;dbname=bookstore", "root", "");
    $pdh->query("set names utf8");
} catch (Exception $e) {
    exit($e->getMessage());
}

// ------------------- LẤY TỪ KHÓA -------------------
$search = isset($_GET["search"]) ? trim($_GET["search"]) : "";
$hasSearch = ($search != "");

// ------------------- NẾU CÓ TỪ KHÓA THÌ TÌM -------------------
$books = [];

if ($hasSearch) {

    // KHÔNG dùng bind cho LIMIT
    $sql = "SELECT * FROM book 
            WHERE book_name LIKE :search";

    $stm = $pdh->prepare($sql);
    $stm->execute([":search" => "%$search%"]);
    $books = $stm->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Tìm Kiếm Thông Tin Sách</title>
<style>
    body { font-family: Arial; background: #fff; padding: 20px; }
    h1 { font-size: 32px; border-bottom: 2px solid #ccc; padding-bottom: 10px; }
    input[type="text"] {
        width: 250px; padding: 6px; border: 1px solid #aaa; border-radius: 4px;
    }
    input[type="submit"] {
        padding: 7px 15px; background: #333; color: white; border: 0; cursor: pointer;
        border-radius: 4px;
    }
    table {
        width: 100%; border-collapse: collapse; margin-top: 18px;
    }
    th, td {
        border: 1px solid #ccc; padding: 8px; text-align: left;
    }
    th { background: #eee; font-weight: bold; }
</style>
</head>
<body>

<h1>TÌM KIẾM THÔNG TIN SÁCH</h1>

<form method="get">
    <input type="text" name="search" placeholder="từ, nhật, điển, thi..." 
           value="<?php echo htmlspecialchars($search); ?>">
    <input type="submit" value="Tìm">
</form>

<?php if ($hasSearch): ?>

    <p><b>Các dữ liệu đã tìm được:</b></p>

    <table>
        <tr>
            <th>Id</th>
            <th>Tên sách</th>
            <th>Mô tả</th>
            <th>Giá</th>
            <th>Hình ảnh</th>
            <th>Mã NXB</th>
            <th>Mã loại</th>
        </tr>

        <?php foreach ($books as $b): ?>
        <tr>
            <td><?php echo $b["book_id"]; ?></td>
            <td><?php echo $b["book_name"]; ?></td>
            <td><?php echo $b["description"]; ?></td>
            <td><?php echo $b["price"]; ?></td>
            <td><?php echo $b["img"]; ?></td>
            <td><?php echo $b["pub_id"]; ?></td>
            <td><?php echo $b["cat_id"]; ?></td>
        </tr>
        <?php endforeach; ?>

    </table>

    <?php if (count($books) == 0): ?>
        <p>Không tìm thấy sách nào.</p>
    <?php endif; ?>

<?php endif; ?>

</body>
</html>
