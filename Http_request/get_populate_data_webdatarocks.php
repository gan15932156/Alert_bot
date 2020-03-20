<?php
   function reconstruct_obj($datas,$fields){
      $arr_result = array();
      foreach($datas as $data){
         
      }
      return $arr_result;
   }
   require_once('../config/configDB.php');

   $conn = $DBconnect; // ตัวแปรเชื่อมต่อฐานข้อมูล
   $response = array(); // ตัวแปร response ชนิด array
   $count_sql_row; // นับจำนวนคำสั่ง sql กรณีงานที่มี WBS
   $query_data = array(); // result query is row

   $fields = $_POST['fields'];
   
   if($_POST['sql_type'] == "array"){
      $count_sql_row = count($_POST['sql']);
      $sql = array();
      for($i=0; $i<=$count_sql_row-1;$i++){
         $sql_select = $_POST['sql'][$i];
         $query = mysqli_query($conn,$sql_select);
         
         if($query){
            $row = mysqli_fetch_row($query);
            while($row = mysqli_fetch_row($query)){
               // $row_data = new stdClass();
               // $row_data->{'primary_key'} = $row[0];
                
               // $i = 0;
               // foreach($fields as $field){
               //    $i++;
               //    if($field == "รายการ"){
               //       $row_data->{$field} = $row[$i]."".$row[$i+1];
               //       $i++;
               //    }
               //    else{
               //       $row_data->{$field} = $row[$i];
               //    }
               // }
               array_push($query_data,$row);
            }
         }

      }

      $data = reconstruct_obj($query_data);
      $response['data'] = $data;
      $response['count_sql'] = $count_sql_row;
   }
   else{

   }

  
   echo json_encode($response);
?>