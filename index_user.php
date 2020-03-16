<?php   
   session_start(); 

   require_once('login_check.php'); 
   require_once('config/configDB.php');

   $conn = $DBconnect;
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>หน้าหลัก</title>

   <?php require_once('config/include_lib.php'); ?>
   <script src="lib/project_js/condition_builder.js"></script>
   <style>
      .loading_page{
         position: absolute;  
         top: 0px;   
         left: 0px;  
         background: #ccc;   
         width: 100%;   
         height: 100%;   
         opacity: .75;   
         filter: alpha(opacity=75);   
         -moz-opacity: .75;  
         z-index: 999;  
         background: #fff url(lib/Picture/loading_page.gif) 50% 50% no-repeat;
      }
   </style>
</head>
<body>
   <div class="root_div">
      <div class="loading_page"></div>
      <div class="container-fluid">
         <div class="row">

            <?php include_once('config/navbar.php'); ?>

            <div class="work_space">
               <div class="inner_work_space">
                  <div class="row text-center">
                        
                     <dir class="col-md-12 "><h2>เพิ่มข้อมูลการแจ้งเตือน</h2></dir>
                     <div class="col-md-12">
                        <form id="upload_file_form">
                           <div class="form_upload_file">
                              <div class="row text-left">  
                                 <div class="col-md-2"><label>หัวข้องาน</label></div>
                                 <div class="col-md-3">
                                    <?php

                                       $user_id = $_SESSION['id_user'];
                                       
                                       $sql = "SELECT * FROM `task_user` WHERE `user_id`=$user_id"; 
                                       
                                       $result = mysqli_query($conn,$sql);
            
                                    ?>
                                    <select name="task_id" name="task_id" id="task_id" class="form-control">
                                       <option value="null">เลือกหัวข้องาน</option>
                                       <?php
                                          while($row = mysqli_fetch_array($result)){ 
                                             echo '<option value="'.$row['task_user_id'].'">'.$row['task_name'].'</option>'; 
                                          }  
                                       ?>
                                    
                                    </select>
                                 </div>
                                 <div class="col-md-2"><label>กลุ่มไลน์</label></div>
                                 <div class="col-md-3">
                                    <select class="form-control" name="token_line_id" id="token_line_id">
                                    
                                    </select>
                                 </div>
                                 <div class="col-md-1"> 
                                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal">
                                       <i class="fa fa-info-circle"></i>
                                    </button>
                                 </div>
                              </div><br>
                              <div class="row text-left">
                                 <div class="col-md-2"><label>ประเภทเวลาแจ้งแตือน</label></div>
                                 <div class="col-md-3">
                                    <select class="form-control" name="alert_time_type" id="alert_time_type">
                                       <option value="null">เลือกประเภทแจ้งเตือน</option>
                                       <option value="period">รอบ</option>
                                       <option value="fix">ระบุวันที่และเวลา</option>
                                    </select>
                                 </div>
                                 <div class="col-md-7 alert_time_type_input"></div>
                              </div><br>
                              <div class="row text-left">
                                 <div class="col-md-2"><label>ประเภทเวลาแจ้งแตือน</label></div>
                                 <div class="col-md-3">
                                    <select class="form-control" name="alert_time_type" id="alert_time_type">
                                       <option value="null">เลือกประเภทแจ้งเตือน</option>
                                       <option value="period">รอบ</option>
                                       <option value="fix">ระบุวันที่และเวลา</option>
                                    </select>
                                 </div>
                                 <div class="col-md-7 alert_time_type_input"></div>
                              </div>
                           </div>
                        </form>
                     </div>
                     <div class="col-md-12">
                        <div class="row condition_builder_div">
                           <div class="col-md-12"><h4>กรองข้อมูล</h4></div>
                           <div class="col-md-12">
                              <form id="condition_builder_form">
                                 <input type="hidden" id="table_nameeeeeeeee" name="table_nameeeeeeeee">
                                 <input type="hidden" id="fields_count" name="fields_count">
                                 <input type="hidden" id="sql_hidden" name="sql_hidden">
                              </form>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div> 
      </div>
   </div>


      <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">ช่วยเหลือ</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <p>ถ้าท่านต้องการเพิ่มคอลัมน์ในข้อมูล กดที่ปุ่ม << ย้อนกลับ เพื่อเลือกเมนู "เพิ่มข้อมูลคอลัมน์" ในการอัพโหลดไฟล์นั้น กรุณา เลือกไฟล์ให้ตรงตามงานที่ท่านได้ทำการเพิ่มไว้และเลือก คอลัมน์ให้ตรงตามงานที่ท่านได้เพิ่มไว้</p>
      </div>
    </div>
  </div>
</div>
</body>
</html>

<style>
   .modal-dialog {max-height:100vh;max-width:150vh;}  
   .modal-body{height:100%;width:100%;align:center;}    

   .form_upload_file{
      background-color:#e0abff;
      margin-left: 5px;
      margin-right: 5px;
      margin-top: 5px;
      padding:5px;
      
   }
   .condition_builder_div{
      background-color:#f8e0ff;
      height: 100%;
      margin-left: 5px;
      margin-right: 5px;  
      padding:5px;
   }
 
   .result_file_upload{
      overflow: auto;
      width: 66vw;
      height: 64vh;
   }  
   .result_file_upload:fullscreen{
      overflow-x: scroll;
      overflow-y: scroll;
      background-color: #feebff;
      margin: 0;
      padding: 0;
   }

 
</style>
