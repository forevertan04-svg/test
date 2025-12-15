<h1>In mảng 2 chiều ra bảng</h1>
<?php
    $arr= array(); 
    $r = array("id"=> "sp1", "name "=> "Sản phẩm 1 ");  
    $arr[] = $r; 
    $r = array("id"=> "sp2", "name "=> "Sản phẩm 2 ");  
    $arr[] = $r; 
    $r = array("id"=> "sp3", "name "=> "Sản phẩm 3 ");  
    $arr[] = $r; 
    ?>
    <table border="2">
            <tr>
                <th>STT</th>
                <th>Mã Sản Phẩm</th>
                <th>Tên Sản Phẩm</th>
            </tr><?php
            
            function show2SideArray($a){
               for($i=0;$i<count($a);$i++){
                echo "<tr>
                <td>". $i+1 ."</td>
                <td>".$a[$i]['id'] ."</td>
                <td>".$a[$i]['name ']  ."</td>
            </tr>"   ;              
        }
       echo "</table>";
               
            }
            show2SideArray($arr);
?>