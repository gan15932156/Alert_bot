<?php
   // $datetime = $_POST['alert_time_type_value'];
   // $datetime2 = new DateTime($datetime); // create obj datetime
   // $alert_date = $datetime2->format('Y-m-d'); // split date and time;
   // $alert_time = $datetime2->format('H:i:s'); // split date and time; 

   function insert_log($conn,$id_user_pea,$log_message){
      date_default_timezone_set('Asia/Bangkok');
      $datetime = date('Y-m-d H:i:s');
      $sql = 'INSERT INTO `user_log`(`id_user_pea`, `datetime`, `log_message`) VALUES ("'.$id_user_pea.'","'.$datetime.'","'.$log_message.'")';
      mysqli_query($conn,$sql);
   }
?>