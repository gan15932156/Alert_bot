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
   $sql = "SELECT  *";
   $sql.=" FROM `alert` INNER JOIN task_user ON alert.task_id = task_user.task_user_id INNER JOIN token_line ON alert.token_line_id = token_line.id";
   $query = mysqli_query($conn, $sql);
   $totalData = mysqli_num_rows($query);
   $totalFiltered = $totalData;

   // get data where
   $sql = "SELECT  *";
   $sql.= " FROM `alert` INNER JOIN task_user ON alert.task_id = task_user.task_user_id INNER JOIN token_line ON alert.token_line_id = token_line.id WHERE alert.user_id = ".$_SESSION['id_user'];
   if( !empty($requestData['search']['value']) ) {  
        $sql.= " AND ( task_name LIKE '".$requestData['search']['value']."%' ";    
        $sql.= " OR namegroup_line LIKE '%".$requestData['search']['value']."%' ";
        $sql.= " )";
   }
   $query = mysqli_query($conn, $sql);
   $totalFiltered = mysqli_num_rows($query);

   // get data order and limit
   $sql.= " ORDER BY `alert`.`alert_id` ASC"." LIMIT ".$requestData['start']." ,".$requestData['length']." ";
   $query = mysqli_query($conn, $sql);
 
   // push data to array
   $data = array();
   while( $row = mysqli_fetch_array($query)) {  // preparing an array
    $nestedData = array(); 
    $nestedData[] = $row["alert_id"];
    $nestedData[] = $row["task_name"];
    $nestedData[] = $row["alert_date"];
    $nestedData[] = $row["alert_time"];
    $nestedData[] = $row["file_name"];
    $nestedData[] = $row["namegroup_line"];
    $nestedData[] = $row["insert_record_date"];
    $nestedData[] = $row["status"];
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
