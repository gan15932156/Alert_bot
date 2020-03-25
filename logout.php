<?php
   session_start();
   require_once("Http_request/insert_log.php");
   require_once('config/configDB.php');
   $conn = $DBconnect;
   if($_SESSION['id_user'] != "admin"){
      insert_log($conn,$_SESSION['id_user'],'ออกจากระบบ');
   }
   session_destroy();
   echo '<script type="text/javascript">window.location.href="index.php"</script>';
?>