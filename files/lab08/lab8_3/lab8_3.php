<?php
// ------------------- KẾT NỐI CSDL -------------------
try {
    $pdh = new PDO("mysql:host=localhost;dbname=bookstore", "root", "");
    $pdh->query("set names 'utf8'");
} catch (Exception $e) {
    die($e->getMessage());
}

// ------------------- XỬ LÝ THÊM LOẠI SÁCH -------------------
if (isset($_POST["sm_insert"])) {

    $id = $_POST["cat_id"];
    $name = $_POST["cat_name"];

    // Kiểm tra mã trùng
    $check = $pdh->prepare("SELECT * FROM category WHERE cat_id = :id");
    $check->execute([":id" => $id]);

    if ($check->rowCount() > 0) {
        echo "<script>alert('❌ Mã loại đã tồn tại!');</script>";
    } else {
        $sql = "INSERT INTO category(cat_id, cat_name) VALUES(:id, :name)";
        $stm = $pdh->prepare($sql);
        $stm->execute([":id" => $id, ":name" => $name]);

        echo "<script>alert('✔ Thêm loại sách thành công!');window.location='lab8_3.php';</script>";
    }
}

// ------------------- PHÂN TRANG -------------------
$limit = 10; // mỗi trang 10 dòng
$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

// tổng số dòng
$total_sql = $pdh->query("SELECT COUNT(*) FROM category");
$total_rows = $total_sql->fetchColumn();

$total_pages = ceil($total_rows / $limit);

// lấy 10 dòng theo trang
$sql = "SELECT * FROM category LIMIT :offset, :limit";
$stm = $pdh->prepare($sql);
$stm->bindValue(':offset', $offset, PDO::PARAM_INT);
$stm->bindValue(':limit', $limit, PDO::PARAM_INT);
$stm->execute();

$rows = $stm->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý loại sách</title>
    <style>
        #container {
            width: 700px;
            margin: 0 auto;
            font-family: Arial;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ccc;
        }

        .pagination {
    margin-top: 20px; /* đẩy xuống dưới */
    text-align: center; /* căn giữa cho đẹp */
        }

        .pagination a, .pagination strong {
            margin: 0 4px;
            padding: 6px 12px;
            text-decoration: none;
            border: 1px solid #007bff;
            color: #007bff;
            border-radius: 6px;
            font-size: 14px;
        }

        .pagination strong {
            background: #007bff;
            color: white;
        }

        .pagination a:hover {
            background: #007bff;
            color: white;
        }

    </style>
</head>

<body>
<div id="container">

    <h3>Thêm Loại Sách Mới</h3>

    <form method="post">
        <table>
            <tr>
                <td>Mã loại:</td>
                <td><input type="text" name="cat_id" required></td>
            </tr>
            <tr>
                <td>Tên loại:</td>
                <td><input type="text" name="cat_name" required></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="sm_insert" value="Thêm"></td>
            </tr>
        </table>
    </form>

    <h3>Danh Sách Loại Sách</h3>

    <table>
        <tr>
            <th>STT</th>
            <th>Mã loại</th>
            <th>Tên loại</th>
            <th>Thao tác</th>
        </tr>

        <?php
        $stt = $offset + 1;
        foreach ($rows as $r) {
            echo "<tr>
                    <td>$stt</td>
                    <td>{$r['cat_id']}</td>
                    <td>{$r['cat_name']}</td>
                    <td>
                        <a href='lab8_3_del.php?cat_id={$r['cat_id']}'
                           onclick=\"return confirm('Xóa loại này?');\">Xóa</a> |
                        <a href='lab8_3_update.php?cat_id={$r['cat_id']}'>Sửa</a>
                    </td>
                 </tr>";
            $stt++;
        }
        ?>
    </table>

    <!-- PHÂN TRANG -->
    <div class="pagination">
        <?php
        if ($page > 1) {
            echo "<a href='?page=" . ($page - 1) . "'>« Trước</a>";
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page)
                echo "<strong>$i</strong>";
            else
                echo "<a href='?page=$i'>$i</a>";
        }

        if ($page < $total_pages) {
            echo "<a href='?page=" . ($page + 1) . "'>Sau »</a>";
        }
        ?>
    </div>

</div>
</body>
</html>
