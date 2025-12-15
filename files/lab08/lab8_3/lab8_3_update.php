<?php
try {
    $pdh = new PDO("mysql:host=localhost;dbname=bookstore","root","");
    $pdh->query("set names 'utf8'");
} catch (Exception $e) {
    die($e->getMessage());
}

// Lấy mã loại cần sửa
$cat_id = $_GET["cat_id"];

// Lấy thông tin loại sách cũ
$stm = $pdh->prepare("SELECT * FROM category WHERE cat_id = :id");
$stm->execute([":id" => $cat_id]);
$row = $stm->fetch(PDO::FETCH_ASSOC);

// Khi bấm nút cập nhật
if (isset($_POST["save"])) {
    $new_name = $_POST["cat_name"];

    $sql = "UPDATE category SET cat_name = :name WHERE cat_id = :id";
    $stm2 = $pdh->prepare($sql);
    $stm2->execute([":name" => $new_name, ":id" => $cat_id]);

    echo "<script>
            alert('✔ Sửa loại sách thành công!');
            window.location='lab8_3.php';
          </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8">
<title>Chỉnh Sửa Loại Sách</title>

<style>
#container {
    width: 600px;
    margin: 20px auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

td {
    padding: 10px;
    border: 1px solid #ddd;
    font-size: 16px;
}

input[type=text] {
    width: 98%;
    padding: 6px;
    font-size: 15px;
}

input[type=submit] {
    padding: 7px 15px;
    background: #007bff;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 15px;
}

a {
    margin-left: 10px;
    font-size: 15px;
}
</style>

</head>

<body>
<div id="container">

<h2>Chỉnh Sửa Loại Sách</h2>

<form action="" method="post">
<table>
    <tr>
        <td width="150">Mã loại:</td>
        <td><input type="text" value="<?php echo $row['cat_id']; ?>" disabled></td>
    </tr>

    <tr>
        <td>Tên loại mới:</td>
        <td><input type="text" name="cat_name" value="<?php echo $row['cat_name']; ?>" required></td>
    </tr>

    <tr>
        <td colspan="2">
            <input type="submit" name="save" value="Lưu Sửa Đổi">
            <a href="lab8_3.php">Quay lại</a>
        </td>
    </tr>
</table>
</form>

</div>
</body>
</html>
