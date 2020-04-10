<?php
   session_start();
   require_once('../config/configDB.php');
   
   $conn = $DBconnect;
   $requestData= $_REQUEST; // get request

   // get all data
   $sql = "SELECT *";
   $sql.=" FROM `task_user`";
   $query = mysqli_query($conn, $sql);
   $totalData = mysqli_num_rows($query);
   $totalFiltered = $totalData;

   // get data where
   $sql = "SELECT  *";
   $sql.= " FROM `task_user` WHERE user_id=".$_SESSION['id_user'];
   if( !empty($requestData['search']['value']) ) {  
        $sql.= " AND ( task_name LIKE '".$requestData['search']['value']."%' ";    
        $sql.= " )";
   }
   $query = mysqli_query($conn, $sql);
   $totalFiltered = mysqli_num_rows($query);

   // get data order and limit
   $sql.= " ORDER BY `task_user_id` ASC"." LIMIT ".$requestData['start']." ,".$requestData['length']." ";
   $query = mysqli_query($conn, $sql);
 
   // push data to array
   $data = array();
   while( $row = mysqli_fetch_array($query)) {  // preparing an array
    $nestedData = array(); 
    $nestedData[] = $row["task_user_id"];
    $nestedData[] = $row["task_name"];
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
