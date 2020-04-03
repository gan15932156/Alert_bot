<?php
    $file_name = $_POST['html_file'];
    $folder_name = "user_export_file_alert/";
    $newFileName = '../'.$folder_name.$file_name;
    if(unlink($newFileName)){
        echo "success";
    }
    else{
        echo "fail";
    }

?>