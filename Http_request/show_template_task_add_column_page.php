<?php
   require_once('../config/configDB.php');
   $conn = $DBconnect;
   $response = array();
   $task_id = $_POST['task_id'];
   $sql = 'SELECT * FROM `template_tb` WHERE `task_user_id` = '.$task_id;
   $query = mysqli_query($conn,$sql);
   $html = '';
   $datatype_name ;
   if($query){
      while($row = mysqli_fetch_row($query)){
         if($row[3] == "varchar(255)"){
            $datatype_name = "ตัวอักษร";
         }
         else if($row[3] == "int"){
            $datatype_name = "ตัวเลข";
         }
         else if($row[3] == "double"){
            $datatype_name = "ทศนิยม";
         }
         else{
            $datatype_name = "วันที่";
         }
         $html.= '<tr><td><b>'.$row[2].'</b></td><td>'.$datatype_name.'</td></tr>';
      }
      $response['result'] = $html;
      $response['error'] = false;
      $response['message'] = "success";
   }
   else{
      $response['error'] = true;
      $response['message'] = "ไม่พบข้อมูล";
   }
   echo json_encode($response);