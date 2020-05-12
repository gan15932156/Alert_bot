<?php
    session_start();
    date_default_timezone_set('Asia/Bangkok');
    require_once('../config/configDB.php');
    $conn = $DBconnect;
    $id_user = $_POST['id_user'];
    $select_save_sql = 'SELECT * FROM `user_save_setting` WHERE user_id = '.$id_user.' ORDER BY `user_save_setting`.`date_time` DESC';
    $query = mysqli_query($conn,$select_save_sql);
    $result = '';
    if($query){
        while($row = mysqli_fetch_row($query)){
            $datetime2 = new DateTime($row[4]); // create obj datetime
            $date = $datetime2->format('Y-m-d'); // split date and time;
            $time = $datetime2->format('H:i:s'); // split date and time; 
            $result.= '<option value="'.$row[3].'">'.$time." ".DateThai($date)." ".$row[2].'</option>';
        }
    }
    else{
        $result = 'fail';
    }
    echo $result;


    function DateThai($strDate){ 
        $strYear = date("Y",strtotime($strDate))+543;
        $thaiyear = "พ.ศ. ". $strYear;
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("j",strtotime($strDate));
        $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strDay $strMonthThai $thaiyear";
    } 
?>