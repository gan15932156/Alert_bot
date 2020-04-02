<?php
    require_once('../config/configDB.php');
    $conn = $DBconnect;
    $response = array();
    $folder_name = "user_export_file_alert/";
    $file_name = $_POST['file_name'];
    $save_data = $_POST['data'];
    $newFileName = '../'.$folder_name.$file_name.".html"; // path ไฟล์ใหม่
    $javascript_file_path = 'user_export_file_alert/'.$file_name.'.html'; // path ไฟล์สำหรับแสดงใน javascript
    if(file_put_contents($newFileName,$save_data) !== false){
        chmod($newFileName,0755); // แก้ไข permission
        $response['error'] = false;
        $response['file_path'] = $javascript_file_path;
        $response['file_name'] = $file_name.".html";
     }
     else{
        $response['error'] = true;
        $response['message'] = "ไม่สามารถสร้างไฟล์ " . basename($newFileName) . " ได้";
     }

    echo json_encode($response);
?>