<?php
// ------------------- KẾT NỐI CSDL -------------------
try {
    $pdh = new PDO("mysql:host=localhost;dbname=bookstore", "root", "");
    $pdh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdh->query("set names 'utf8'");
} catch (Exception $e) {
    die($e->getMessage());
}

// ------------------- LẤY MÃ LOẠI -------------------
$cat_id = isset($_GET["cat_id"]) ? $_GET["cat_id"] : "";

// Nếu không có mã loại → quay về
if ($cat_id == "") {
    echo "<script>alert('Không có mã loại để xóa!'); window.location='lab8_3.php';</script>";
    exit;
}

// ------------------- KIỂM TRA FOREIGN KEY -------------------
// Xem loại này có sách hay không
$check = $pdh->prepare("SELECT COUNT(*) FROM book WHERE cat_id = :id");
$check->execute([":id" => $cat_id]);
$soSach = $check->fetchColumn();

if ($soSach > 0) {
    // Không cho xóa nếu còn sách liên quan
    echo "<script>
            alert('❌ Không thể xóa! Loại sách này còn $soSach quyển sách.');
            window.location = 'lab8_3.php';
          </script>";
    exit;
}

// ------------------- TIẾN HÀNH XÓA -------------------
try {
    $sql = "DELETE FROM category WHERE cat_id = :id";
    $stm = $pdh->prepare($sql);
    $stm->execute([":id" => $cat_id]);

    if ($stm->rowCount() > 0) {
        $msg = "✔ Đã xóa loại sách thành công!";
    } else {
        $msg = "❌ Không thể xóa!";
    }

} catch (PDOException $e) {
    $msg = "❌ Lỗi SQL: " . $e->getMessage();
}
?>

<script>
alert("<?php echo $msg; ?>");
window.location = "lab8_3.php";
</script>
