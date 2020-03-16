<?php
   session_start();
   
   require_once('../config/configDB.php');

   $conn = $DBconnect;

   $response = array();

   $task_id = $_POST['task_id'];

   $response['token_line'] = get_token_line($task_id,$conn);
   $response['fields'] = get_fields($task_id,$conn);

   echo json_encode($response);

   function get_token_line($task_id,$conn){

      $result = '';

      $sql = "SELECT * FROM token_line  WHERE  task_id ='$task_id' ";
 
      $query = mysqli_query($conn,$sql);

      if($query){

         $rowcount=mysqli_num_rows($query);

         if($rowcount > 0){
            while($row = mysqli_fetch_row($query)){
               $result.= '<option value="'.$row[0].'">'.$row[4].'</option>';
            }
         }
         else{
            $result = "fail";
         }
        
      }  
      else{
         $result = "fail";
      }

      return $result;
   }

   function get_fields($task_id,$conn){
     
      $result = '';

      $result2 = array();

      $result= "fail";
 
      $sql = "SELECT * FROM template_tb WHERE task_user_id = '$task_id'";

      $query = mysqli_query($conn,$sql);
      
      if($query){

         $rowcount=mysqli_num_rows($query);

         if($rowcount > 0){
            while($row = mysqli_fetch_row($query)){
               $result2[] =  $row[2];
            }
            
            $result = $result2;
         }
         else{
            $result = "fail";
         }
        
      }  
      else{
         $result = "fail";
      }

      return $result;
   }



?>