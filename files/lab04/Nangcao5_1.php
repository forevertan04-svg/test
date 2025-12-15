<h1>In mảng một chiều trong bảng</h1>
<!-- <form action="" method="post">
    <label for="key">Nhập tên</label>
    <input type="text" name="yourname" id="key">
    <label for="value">Nhập tỉnh</label>
    <input type="text" name="province" id="value">
    <button type="submit" value="Thêm">Thêm</button>
</form> -->
<?php
// if(isset($_POST['yourname']) && isset($_POST['province'])){
    $arr=array('Phát'=>'Tiền Giang','Đạt'=>'Bình Thuận',"Khôi" => 'Phú Yên');
    // $arr[$_POST['yourname']]=[$_POST['province']];
    ?>
    <table border="2">
            <tr>
                <th>Index</th>
                <th>Value</th>
            </tr><?php
    function showArray($a){
        foreach($a as $key => $value){
            echo "<tr>
                <td>". $key ."</td>
                <td>".$value ."</td>
            </tr>"   ;              
        }
       echo "</table>";
    }
showArray($arr);
// }
?>
