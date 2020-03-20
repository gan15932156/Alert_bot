<?php
   require_once('../config/configDB.php');
 
   $conn = $DBconnect;

   $response = array();

   $username = intval($_POST['search_user_id']);
   $systemlevel = 0;

   $sql = 'INSERT INTO `userpea`(`username`, `system_level`) VALUES ('.$username.','.$systemlevel.')';


   echo json_encode($sql);
?>