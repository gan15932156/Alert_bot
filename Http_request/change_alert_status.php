<?php
    require_once('../config/configDB.php');
    $conn = $DBconnect;

    $alert_id = $_GET['alert_id'];
    $status = $_GET['status'];
    $insert_status;
    if($status == 1){
        $insert_status = 0;
    }
    else if($status == 0){
        $insert_status = 1;
    }

    $sql = 'UPDATE `alert` SET `status`='.$insert_status.' WHERE alert_id ='.$alert_id;
    mysqli_query($conn,$sql);
    mysqli_close($conn);
    header( "location: ../alert_display.php" );
    exit(0);
?>