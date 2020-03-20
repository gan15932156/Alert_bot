<?php   

   session_start();

   require_once('config/configDB.php');

   $conn = $DBconnect;

   require_once('login_check.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>เพิ่มโทเคนไลน์</title>

   <?php require_once('config/include_lib.php'); ?>
</head>
<body>
   <div class="container-fluid">
      <div class="row">

         <?php include_once('config/navbar.php'); ?>

         <div class="work_space">
            <div class="inner_work_space">
               <div class="row text-center">
                      
                  <div class="col-md-12 "><h2>เพิ่มโทเคนไลน์</h2></div>
                  <div class="col-md-12">
                     <form method="POST" action="javascript:void(0);" id="add_token_line" onSubmit="add_token_line()">
                        <div class="form_add_token_line">
                           <div class="row">
                              <div class="col-md-2"><label>โทเคนไลน์(Line token)</label></div>
                              <div class="col-md-3"><input type="text" name="token_line" required class="form-control"></div>
                              <div class="col-md-1"><label>กลุ่มไลน์</label></div>
                              <div class="col-md-2"><input type="text" name="group_line_name" required class="form-control"></div>
                              <div class="col-md-1"><label>เลือกงาน</label></div>
                              <div class="col-md-2">
                                 <?php

                                    $user_id = $_SESSION['id_user'];
                                    
                                    $sql = "SELECT * FROM `task_user` WHERE `user_id`=$user_id"; 
                                    
                                    $result = mysqli_query($conn,$sql);
         
                                 ?>
                                 <select name="task_id" id="task_id" class="form-control">
                                    <option value="null">เลือกงาน</option>
                                    <?php
                                       while($row = mysqli_fetch_array($result)){ 
                                          echo '<option value="'.$row['task_user_id'].'">'.$row['task_name'].'</option>'; 
                                       }  
                                    ?>
                                   
                                 </select>
                              </div>
                           </div><br>
                           <div class="row">
                              <div class="col-md-12 text-center"><input type="submit" class="btn btn-success btn-sm" value="ยืนยัน"></div>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div> 
   </div>
</body>
</html>

<style>
   .form_add_token_line{
      background-color:#e0abff;
      margin-left: 5px;
      margin-right: 5px;
      margin-top: 5px;
      padding:5px;
      
   }
  
</style>

<script>
 
   function add_token_line(){

      if($("#task_id").val() == "null"){
         Swal.fire({
            icon: 'warning',
            title: 'กรุณาเลือกหัวข้องาน'
         })
      }
      else{
         $.ajax({
            url: "Http_request/get_add_token_line.php", 
            method: "POST",
            async: false,
            datatype:'json',
            data: $('#add_token_line').serialize(),
            error: function(jqXHR, text, error) {
               Swal.fire({
                  icon: 'error',
                  title: 'ผิดพลาด',
                  text: error
               })
            }
         })
         .done(function(data) {
 
            Swal.fire({
               title: 'สำเร็จ ต้องการเข้าสู่หน้าอัพโหลดไฟล์หรือไม่',
               icon: 'warning',
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#d33',
               confirmButtonText: 'ใช่',
               cancelButtonText: 'ไม่',
            }).then((result) => {
               if (result.value) {
                  window.location.href="upload_file_page.php"
               }
            })
         });
      }  
   }
</script>