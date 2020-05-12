<?php
    require_once('../config/configDB.php');
    require_once("insert_log.php");
    date_default_timezone_set('Asia/Bangkok');
    $conn = $DBconnect;
    $response = array();

    $alert_input_user_id = $_POST['alert_input_user_id'];
    $alert_input_file_name = $_POST['alert_input_file_name'];
    $alert_input_count_record = $_POST['alert_input_count_record'];
    $task_id =  $_POST['task_id'];
    $task_name = $_POST['alert_input_task_name'];
    $token_line_id = $_POST['token_line_id'];
    $alert_time_type = $_POST['alert_time_type'];
    
    $alert_time_type_value = $_POST['alert_time_type_value'];
    $alert_data_type = $_POST['alert_data_type'];  
    $datetime = date('Y-m-d H:i:s');
    $alert_text = '';

    insert_log($conn,$alert_input_user_id,'บันทึกข้อมูลแจ้งเตือนไลน์ งาน '.$task_name);

    if($alert_data_type == "0"){ // 0 = จากฐานข้อมูล
        $alert_text = "แจ้งเตือนงาน: ".$task_name." จำนวนรายการ: ".$alert_input_count_record." รายการ"; 
    }
    else{
        $alert_text = $_POST['alert_data_type_value'];
    }

    if($alert_time_type == "period"){ // แจ้งเตือนเป็นรอบ

        $alert_time_type_time_type = $_POST['alert_time_type_time_type']; // หน่วยเวลา
        $alert_date = get_alert_date(date('Y-m-d H:i'),$alert_time_type_time_type,$alert_time_type_value);
        $datetime2 = new DateTime($alert_date); // create obj datetime
        $date = $datetime2->format('Y-m-d'); // split date and time;
        $time = $datetime2->format('H:i:s'); // split date and time; 

        $sql_insert_alert = 'INSERT INTO `alert`(`user_id`, `token_line_id`, `task_id`, `alert_date`, `alert_time`, `alert_type`, `alert_data_type`, `file_name`,`alert_text`, `insert_record_date`) VALUES ('.$alert_input_user_id.','.$token_line_id.','.$task_id.',"'.$date.'","'.$time.'",0,'.intval($alert_data_type).',"'.$alert_input_file_name.'","'.$alert_text.'","'.$datetime.'")';
        $response['sql'] = $sql_insert_alert;
        $query_insert_alert = mysqli_query($conn,$sql_insert_alert);
        if($query_insert_alert){
            $alert_id = mysqli_insert_id($conn); // get last query id (auto increment)
                       
            $sql_insert_update_alert = 'INSERT INTO `alert_update_datetime`(`alert_id`, `time_type`, `time_value`) VALUES ('.$alert_id.',"'.$alert_time_type_time_type.'",'.intval($alert_time_type_value).')';
            $query_insert_update_alert = mysqli_query($conn,$sql_insert_update_alert);
            if($query_insert_update_alert){
                $response['error'] = false;
                $response['msg'] = "success";
            }
            else{
                $response['error'] = true;
                $response['msg'] = "ไม่สามารถบันทึกข้อมูลแจ้งเตือนในระบบได้";
            }
        }
        else{
            $response['error'] = true;
            $response['msg'] = "ไม่สามารถบันทึกข้อมูลแจ้งเตือนในระบบได้";
        }
    }
    else if($alert_time_type == "fix"){
        $datetime2 = new DateTime($alert_time_type_value); // create obj datetime
        $date = $datetime2->format('Y-m-d'); // split date and time;
        $time = $datetime2->format('H:i:s'); // split date and time; 
        $sql_insert_alert = 'INSERT INTO `alert`(`user_id`, `token_line_id`, `task_id`, `alert_date`, `alert_time`, `alert_type`, `alert_data_type`, `file_name`,`alert_text`, `insert_record_date`) VALUES ('.$alert_input_user_id.','.$token_line_id.','.$task_id.',"'.$date.'","'.$time.'",1,'.intval($alert_data_type).',"'.$alert_input_file_name.'","'.$alert_text.'","'.$datetime.'")';
        $query_insert_alert = mysqli_query($conn,$sql_insert_alert);
        if($query_insert_alert){
            $response['error'] = false;
            $response['msg'] = "success";
        }
        else{
            $response['error'] = true;
            $response['msg'] = "ไม่สามารถบันทึกข้อมูลแจ้งเตือนในระบบได้";
        }
        $response['sql'] = $sql_insert_alert;
    }
    echo json_encode($response);


function get_alert_date($now_date,$time_type,$time_value){
    $strtime = "";
    $new_date = "";
    if($time_type == "s"){
        $strtime = $time_value;
    }
    else if($time_type == "m"){
        $strtime = $time_value * 60;
    }
    else if($time_type == "h"){
        $strtime = $time_value * 60 * 60 ;
    }
    else{
        $strtime = $time_value * 60 * 60 * 24;
    }
    $new_date = strtotime($now_date) + $strtime;
    $new_date_time = date('Y-m-d H:i:s',$new_date);
    return $new_date_time;
}
?>