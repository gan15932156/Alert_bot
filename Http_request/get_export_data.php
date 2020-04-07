<?php
   session_start();
   require_once('../config/configDB.php');
   require_once("insert_log.php");
   $conn = $DBconnect;
   date_default_timezone_set('Asia/Bangkok');
   $response = array();

   if(!empty($_POST['save_data'])){
      $folder_name = "user_save_setting/";
      $save_data = $_POST['save_data'];
      $task_name = $save_data["HTTP_post"]["table_nameeeeeeeee"];
      $user_id = $_SESSION['id_user'];
      $datetime = date('Y-m-d H:i:s');
      $timestamp = time();
      $file_name = $timestamp."_".$task_name;
      $newFileName = '../'.$folder_name.$file_name.".json"; // path ไฟล์ใหม่

      if(file_put_contents($newFileName,json_encode($save_data,JSON_UNESCAPED_UNICODE)) !== false){
         chmod($newFileName,0755); // แก้ไข permission

         $sql = 'INSERT INTO `user_save_setting`(`user_id`, `task_name`, `path`, `date_time`) VALUES ("'.$user_id.'","'.$task_name.'","'.$file_name.'.json","'.$datetime.'")';
         $query = mysqli_query($conn,$sql);
         if($query){
            insert_log($conn,$user_id,'บันทึกรูปแบบงาน '.$task_name.' ชื่อไฟล์ '.$file_name.'.json');
            $response['error'] = false;
            $response['message'] = "success";
         }
         else{
            $response['error'] = true;
            $response['message'] = "ไม่สามารถบันทึกข้อมูลลงฐานข้อมูลได้";
         }
      }
      else{
         $response['error'] = true;
         $response['message'] = "ไม่สามารถสร้างไฟล์ " . basename($newFileName) . " ได้";
      }
   }
   else{
      $response['error'] = true;
      $response['message'] = "ไม่สามารถบันทึกได้";
   }

   echo json_encode($response);
?>