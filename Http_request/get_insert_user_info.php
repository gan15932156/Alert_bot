<?php
   require_once('../config/configDB.php');
   $conn = $DBconnect;
   $response;
   $username = intval($_POST['search_user_id']);
   $systemlevel = 0;
   $sql = 'INSERT INTO `userpea`(`username`, `system_level`) VALUES ('.$username.','.$systemlevel.')';
   $query = mysqli_query($conn,$sql);
   if($query){
      $response = true;
   }
   else{
      $response = false;
   }
   echo $response;
?>