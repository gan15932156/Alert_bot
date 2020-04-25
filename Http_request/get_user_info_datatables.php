<?php
    session_start();
    require_once('../config/configDB.php');
    require_once("../idm-service.php");
    $service = new IDMService();
    //    data: []
    //    draw: 3
    //    recordsFiltered: 0
    //    recordsTotal: 7
    $conn = $DBconnect;
    $requestData= $_REQUEST; // get request

    // get all data
    $sql = "SELECT *";
    $sql.=" FROM `userpea`";
    $query = mysqli_query($conn, $sql);
    $totalData = mysqli_num_rows($query);
    $totalFiltered = $totalData;

    // get data where
    $sql = "SELECT  *";
    $sql.= " FROM `userpea` WHERE 1+1";
    if( !empty($requestData['search']['value']) ) {  
        $sql.= " AND ( username LIKE '".$requestData['search']['value']."%' ";    
        $sql.= " )";
    }
    $query = mysqli_query($conn, $sql);
    $totalFiltered = mysqli_num_rows($query);

    // get data order and limit
    $sql.= " ORDER BY `id_pea` ASC"." LIMIT ".$requestData['start']." ,".$requestData['length']." ";
    $query = mysqli_query($conn, $sql);
 
    // push data to array
    $data = array();

    while( $row = mysqli_fetch_array($query)) {  // preparing an array
        $user_info_result = $service->getEmployeeInfoByUsername("93567815-dfbb-4727-b4da-ce42c046bfca",$row['username']);
        $nestedData = array(); 
        if($user_info_result["Status"] != "Fail"){
            $nestedData[] = $user_info_result["GetEmployeeInfoByUsernameResult"]["ResultObject"]["FirstName"];
            $nestedData[] = $user_info_result["GetEmployeeInfoByUsernameResult"]["ResultObject"]["LastName"];
            $nestedData[] = $user_info_result["GetEmployeeInfoByUsernameResult"]["ResultObject"]["PositionDescShort"];
            $nestedData[] = $user_info_result["GetEmployeeInfoByUsernameResult"]["ResultObject"]["LevelDesc"];
            $nestedData[] = $user_info_result["GetEmployeeInfoByUsernameResult"]["ResultObject"]["DepartmentShortName"];
            $nestedData[] = $row['status'];
            $data[] = $nestedData;
        }
        
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
