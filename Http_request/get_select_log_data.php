<?php
   session_start();
   require_once('../config/configDB.php');
   
   $conn = $DBconnect;
   $requestData= $_REQUEST; // get request

   $columns = array( // table attributes
      0 =>'log_id',
      1 => 'id_user_pea',
      2=> 'datetime',
      3=>'log_message'
   );
   
   // get all data
   $sql = "SELECT  `log_id`, `id_user_pea`, `datetime`, `log_message`";
   $sql.=" FROM `user_log`";
   $query = mysqli_query($conn, $sql);
   $totalData = mysqli_num_rows($query);
   $totalFiltered = $totalData;

   // get data where
   $sql = "SELECT  `log_id`, `id_user_pea`, `datetime`, `log_message`";
   $sql.= " FROM `user_log` WHERE id_user_pea = ".$_SESSION['id_user'];
   if( !empty($requestData['search']['value']) ) {  
      $sql.= " AND ( log_id LIKE '".$requestData['search']['value']."%' ";    
      $sql.= " OR datetime LIKE '%".$requestData['search']['value']."%' ";
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
    $nestedData[] = $row["id_user_pea"];
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
