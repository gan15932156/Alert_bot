<?php

   require_once('../config/configDB.php');
   require_once("insert_log.php");
   $conn = $DBconnect;
   $task_id=$_POST['task_id'];
   $user_id = $_POST['id_user'];
   $task_name = $_POST['task_name'];
   $sql_select_count_template_task = "SELECT count(template_id) as count_task_fields FROM template_tb WHERE task_user_id='".$task_id."'";
   $result_task_fields_count = mysqli_query($conn,$sql_select_count_template_task);
   $count_fields;
   insert_log($conn,$user_id,'เพิ่มคอลัมน์หัวข้องาน งาน '.$task_name);
   while($row = mysqli_fetch_row($result_task_fields_count)){
      $count_fields = $row[0];
   }
   if(empty($_POST['header'])){
      echo "fail";
   }
   else{
      for($i = 1 ; $i <= count($_POST['header']) ; $i++){
         $sql_new_fields = '';
         $field_name = $_POST['header'][$i-1];
         $dataType = $_POST['header_type'][$i-1];
         $tb_attr_count = $count_fields+$i;
         $new_tb_attr_name = 'h'.$tb_attr_count;
         $sql_insert_new_field_template_tb = "INSERT INTO `template_tb`(`task_user_id`, `colum_name`, `datatype`) VALUES ('$task_id','$field_name','$dataType')";
         mysqli_query($conn,$sql_insert_new_field_template_tb);
         if($field_name == "รายการ"){
            $sql_new_fields ="ALTER TABLE "."`".$task_name."`". "ADD `".$new_tb_attr_name."_1`" ."VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;";
            mysqli_query($conn,$sql_new_fields);     
            $sql_new_fields ="ALTER TABLE "."`".$task_name."`". "ADD `".$new_tb_attr_name."_2`" ."VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;";
            mysqli_query($conn,$sql_new_fields);
         }
         else{
            if($dataType == "varchar(255)"){
               $str = ' VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci';
               $sql_new_fields ="ALTER TABLE `$task_name` ADD `$new_tb_attr_name` $str NOT NULL;";
            }
            else{
               $sql_new_fields ="ALTER TABLE `$task_name` ADD `$new_tb_attr_name` $dataType NOT NULL;";
            }
            mysqli_query($conn,$sql_new_fields);
         }
      }   
      mysqli_close($conn);
      echo "success";
   }
?>