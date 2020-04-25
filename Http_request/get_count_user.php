<?php
    require_once('../config/configDB.php');
    $conn = $DBconnect;
    $sql = 'SELECT COUNT(id_pea) as count_user FROM `userpea`';
    $query = mysqli_query($conn,$sql);
    $count;
    while($row = mysqli_fetch_row($query)){
        $count = $row[0];
    }
    echo $count;
?>