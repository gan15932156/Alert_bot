<?php
    require_once('../config/configDB.php');
    $conn = $DBconnect;
    require_once("insert_log.php");

    $insert_code = $_POST['insert_code'];
    $user_id = $_POST['user_id'];

    switch($insert_code){
        case 1 :
            insert_log($conn,$user_id,"เปิดรูปแบบงานที่บันทึกไว้ชื่อ ".$_POST['file_save_name']);
            break;
        default :
    }
    // insert code
    // 1 = เปิดไฟล์รูปแบบงาน
    // insert_log($conn,$user_id,'บันทึกรูปแบบงาน '.$task_name.' ชื่อไฟล์ '.$file_name.'.json');

    
?>