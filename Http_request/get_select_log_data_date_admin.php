<?php
   session_start();
   require_once('../config/configDB.php');
   
   $conn = $DBconnect;
   $requestData= $_REQUEST; // get request

   
   // get all data
   $sql = "SELECT  `log_id`, `userpea`.`username`, `datetime`, `log_message`";
   $sql.=" FROM `user_log` INNER JOIN userpea ON user_log.id_user_pea = userpea.id_pea";
   $query = mysqli_query($conn, $sql);
   $totalData = mysqli_num_rows($query);
   $totalFiltered = $totalData;

   // get data where
   $sql = "SELECT  `log_id`, `userpea`.`username`, `datetime`, `log_message`";
   $sql.= " FROM `user_log` INNER JOIN userpea ON user_log.id_user_pea = userpea.id_pea WHERE SUBSTR(datetime,1,10) ="."'".$requestData['datetime_post']."'";
   if( !empty($requestData['search']['value']) ) {  
      $sql.= " AND ( log_id LIKE '".$requestData['search']['value']."%' ";    
      $sql.= " OR datetime LIKE '%".$requestData['search']['value']."%' ";
      $sql.= " OR username LIKE '%".$requestData['search']['value']."%' ";
      $sql.= " OR log_message LIKE '".$requestData['search']['value']."%' )";
   }
   $query = mysqli_query($conn, $sql);
   $totalFiltered = mysqli_num_rows($query);

   // get data order and limit
   $sql.= " ORDER BY `datetime` DESC"." LIMIT ".$requestData['start']." ,".$requestData['length']." ";
   $query = mysqli_query($conn, $sql);
 
   // push data to array
   $data = array();
   while( $row = mysqli_fetch_array($query)) {  // preparing an array
    $nestedData = array(); 
    $nestedData[] = $row["log_id"];
    $nestedData[] = $row["username"];
    $nestedData[] = $row["datetime"];
    $nestedData[] = $row["log_message"];
    $data[] = $nestedData;
   }
 
   $json_data = array(
      "draw" => intval( $requestData['draw'] ),
      "recordsTotal" => intval( $totalData ),  // total number of records
      "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
      "data" => $data   // total data array
      );
   
   // encode to JSON
   echo json_encode($json_data);  // send data as json format
?>
