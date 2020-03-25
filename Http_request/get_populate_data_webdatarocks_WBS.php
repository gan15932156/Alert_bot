<?php
   function populate_object($fields){
      $result_obj = new stdClass();
      foreach($fields as $field){
         $result_obj->{$field} = '';
      }
      return $result_obj;
   }
   require_once('../config/configDB.php');
   $conn = $DBconnect; // ตัวแปรเชื่อมต่อฐานข้อมูล
   $response = array(); // ตัวแปร response ชนิด array
   $count_sql_row; // นับจำนวนคำสั่ง sql กรณีงานที่มี WBS
   $query_data = array(); // result query is row
   $fields = $_POST['fields'];
   $query = mysqli_query($conn,$_POST['sql']);
   while($row = mysqli_fetch_row($query)){
      $data_obj = new stdClass();
      $i = 0;
      $data_obj->{'primary_key'} = $row[$i];
      foreach($fields as $field){
         $i++;
         if($field == "รายการ"){
            $data_obj->{$field} = $row[$i]."".$row[$i+1];
            $i++;
         }
         else{
            $data_obj->{$field} = $row[$i];
         }
         
      }
      array_push($query_data,$data_obj);
   }
   $response['data'] = $query_data;
   $response['sql'] = $_POST['sql'];
   echo json_encode($response);
?>