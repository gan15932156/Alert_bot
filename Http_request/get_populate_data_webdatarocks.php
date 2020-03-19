<?php
require_once('../config/configDB.php');

   $conn = $DBconnect; // ตัวแปรเชื่อมต่อฐานข้อมูล
   //$table = $_POST['table_nameeeeeeeee']; // ตัวแปรชื่อตาราง
   $response = array(); // ตัวแปร response ชนิด array

    if($_POST['sql_type'] == "array"){

    }
    else{

    }

   echo json_encode($response);
?>