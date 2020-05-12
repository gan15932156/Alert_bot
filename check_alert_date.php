<?php
    date_default_timezone_set('Asia/Bangkok');
    require_once('config/configDB.php');
    $conn = $DBconnect;

    $select_sql = 'SELECT * FROM `alert` INNER JOIN `token_line` ON alert.token_line_id = token_line.id WHERE status = 1';

    $query = mysqli_query($conn,$select_sql);

    $format = 'Y-m-d H:i:s';
    $now = date('Y-m-d H:i:s');
    $now_date_time = new DateTime($now);
    // echo $now_date_time->format('Y-m-d H:i:s')."<br><br>";
    while($row = mysqli_fetch_array($query)){
        if($row['alert_type'] == 0){ // เป็นรอบ
            $select_date = $row['alert_date']." ".$row['alert_time'];
            $alert_update = 'SELECT * FROM `alert_update_datetime` WHERE alert_id = '.$row['alert_id'];
            $query2 = mysqli_query($conn,$alert_update);
            while($row2 = mysqli_fetch_array($query2)){
                $date = DateTime::createFromFormat($format, $select_date);
                if($date->format('Y-m-d H:i:s') <= $now_date_time->format('Y-m-d H:i:s')){
                    $alert_date = get_alert_date($now_date_time->format('Y-m-d H:i:s'),$row2['time_type'],$row2['time_value']);
                    $alert_date2 = new DateTime($alert_date);
                    $update_sql = 'UPDATE `alert` SET `alert_date`= "'.$alert_date2->format('Y-m-d').'",`alert_time`= "'.$alert_date2->format('H:i:s').'",`insert_record_date`= "'.$now_date_time->format('Y-m-d H:i:s').'" WHERE alert_id = '.$row['alert_id'];
                    notify_message($row['alert_text'],$row['token']);
                    notify_message("http://127.0.0.1/Alert_bot/user_export_file_alert/".$row['file_name'],$row['token']);    
                    // echo $update_sql."<br>";
                    mysqli_query($conn,$update_sql);
                }
            }
        }
        else{
            $select_date = $row['alert_date']." ".$row['alert_time'];
            $date = DateTime::createFromFormat($format, $select_date);
            if($date->format('Y-m-d H:i:s') <= $now_date_time->format('Y-m-d H:i:s')){
                notify_message($row['alert_text'],$row['token']);
                notify_message("http://127.0.0.1/Alert_bot/user_export_file_alert/".$row['file_name'],$row['token']);    
                // echo $date->format('Y-m-d H:i:s')." alert!!!<br>";
            }
        }
    }


    function notify_message($message,$token){

        $line_api = "https://notify-api.line.me/api/notify";
     
        $queryData = array('message' => $message);
        $queryData = http_build_query($queryData,'','&');
        $headerOptions = array( 
                'http'=>array(
                    'method'=>'POST',
                    'header'=> "Content-Type: application/x-www-form-urlencoded\r\n"
                            ."Authorization: Bearer ".$token."\r\n"
                            ."Content-Length: ".strlen($queryData)."\r\n",
                    'content' => $queryData
                ),
        );
        $context = stream_context_create($headerOptions);
        $result = file_get_contents($line_api,FALSE,$context);
        $res = json_decode($result);
        return $res;
    }

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