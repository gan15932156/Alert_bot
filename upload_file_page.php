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
   <title>อัพโหลดงาน</title>

   <?php require_once('config/include_lib.php'); ?>

   <script src="lib/project_js/upload_page.js"></script>

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
                        
                     <dir class="col-md-12 "><h2>อัพโหลดงาน</h2></dir>
                     <div class="col-md-12">
                        <form id="upload_file_form">
                           <div class="form_upload_file">
                              <div class="row">  
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
                                 <div class="col-md-2"><label>เลือกไฟล์งาน</label></div>
                                 <div class="col-md-3"><input type="file" id="file_input" name="file_input" required class="form-control"></div>
                                 <div class="col-md-2"> <input value="อัพโหลดไฟล์" type="button" name="btn_submit" class="btn btn-success" id="btn_submit"></div>
                                 <div class="col-md-1"> 
                                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal">
                                       <i class="fa fa-info-circle"></i>
                                    </button>
                                 </div>
                              </div><br>
                           </div>
                        </form>
                     </div>
                     <div class="col-md-12">
                        <div class="row row_result_upload">

                           <div class="col-md-10 upload_result" id="upload_result">
                              
                              <div class="row">
                                 <div class="col-md-5 text-right"><label><b>ผลลัพธ์</b></label></div>
                                 <div class="col-md-3 text-left"><input type="checkbox" id="checkall" />เลือกหัวข้อทั้งหมด</div>
                                 <div class="col-md-4 text-right">
                                    <button type="button" id="btn_fullscreen" class="btn btn-secondary">
                                       <i class="fas fa-arrows-alt"></i>
                                    </button>
                                 </div>
                              </div>&nbsp;
                              <div class="row">
                                 <div class="col-md-12"><div class="result_file_upload text-left"></div></div>
                              </div>                  
                           </div>

                           <div class="col-md-2 result_template">

                              <label><b>รูปแบบตารางงาน</b></label>

                              <div class="show_template text-left"></div>     

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
   .row_result_upload{
      background-color:#f8e0ff;
      height: 73vh;
      margin-left: 5px;
      margin-right: 5px;
      
      padding:5px;
   }
   .upload_result{
      border-right: 1px solid #8c7a91;
   }
   .result_file_upload{
      overflow: auto;
      width: 66vw;
      height: 64vh;
   }  

      /* THIS DOES NOT WORK */
   /* #tb_result:-webkit-full-screen,
   #tb_result:-moz-full-screen,
   #tb_result:-ms-full-screen,
   #tb_result:-o-full-screen, */
   #tb_result:full-screen{
      width:100vw;
      height:100vh;
      background-color:#red;
   }

 
</style>

