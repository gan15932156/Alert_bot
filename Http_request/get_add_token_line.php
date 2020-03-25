<?php
   session_start();
   require_once('../config/configDB.php');
   require_once("insert_log.php");

   $conn = $DBconnect;
   $id_user=$_SESSION['id_user'];
   $token=$_POST['token_line'];
   $namegroup_line=$_POST['group_line_name'];
   $task_id=$_POST['task_id'];
   $task_name = $_POST['task_name'];
   insert_log($conn,$id_user,'เพิ่มโทเคนไลน์ งาน '.$task_name);
   $sql ="INSERT INTO `token_line`(`token`,`task_id`,`id_user`,`namegroup_line`) VALUES ('$token','$task_id','$id_user','$namegroup_line')"; 
   mysqli_query($conn,$sql);
   mysqli_close($conn);
?>