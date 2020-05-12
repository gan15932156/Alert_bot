<?php   
   session_start(); 
   require_once('login_check.php'); 
   require_once('config/configDB.php');
   $conn = $DBconnect;
   date_default_timezone_set('Asia/Bangkok');
   function DateThai($strDate){ 
      $strYear = date("Y",strtotime($strDate))+543;
      $thaiyear = "พ.ศ. ". $strYear;
      $strMonth = date("n",strtotime($strDate));
      $strDay = date("j",strtotime($strDate));
      $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
      $strMonthThai = $strMonthCut[$strMonth];
      return "$strDay $strMonthThai $thaiyear";
   } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>หน้าหลัก</title>

   <?php require_once('config/include_lib.php'); ?>
   <script type="text/javascript" src="lib/project_js/condition_builder.js"></script>
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
      .tb-result thead th { 
         position: sticky; 
         top: 0; 
         background-color:#007BFF;
         margin-top:2px;
         
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

                     <div class="col-md-12 "><h1><span class="badge badge-primary"><b>เพิ่มข้อมูลการแจ้งเตือน</b></span></h1></div>
                     <!-- Div form -->
                     <div class="col-md-12">
                        <div class="form_upload_file">
                           <form id="form_alert_input" >
                              <input type="hidden" id="alert_input_user_id" name="alert_input_user_id" value="<?php echo $_SESSION['id_user'];?>">
                              <input type="hidden" id="alert_input_file_name" name="alert_input_file_name">
                              <input type="hidden" id="alert_input_count_record" name="alert_input_count_record">
                              <input type="hidden" id="alert_input_task_name" name="alert_input_task_name">
                              <div class="row text-left">  
                                 <div class="col-md-2"><label>หัวข้องาน</label></div>
                                 <div class="col-md-3">
                                    <?php
                                       $user_id = $_SESSION['id_user'];                
                                       $sql = "SELECT * FROM `task_user` WHERE `user_id`=$user_id";                                    
                                       $result = mysqli_query($conn,$sql);         
                                    ?>
                                    <select name="task_id" name="task_id" id="task_id" class="form-control form-control-sm">
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
                                    <select class="form-control form-control-sm" name="token_line_id" id="token_line_id"></select>
                                 </div>
                                 <div class="col-md-1"> 
                                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal">
                                       <i class="fa fa-info-circle"></i>
                                    </button>
                                 </div>
                              </div>

                              <div class="row text-left">
                                 <div class="col-md-2"><label>ประเภทเวลาแจ้งแตือน</label></div>
                                 <div class="col-md-3">
                                    <select class="form-control form-control-sm" name="alert_time_type" id="alert_time_type">
                                       <option value="null_time_type">เลือกประเภทแจ้งเตือน</option>
                                       <option value="period">รอบ</option>
                                       <option value="fix">ระบุวันที่และเวลา</option>
                                    </select>
                                 </div>
                                 <div class="col-md-7 alert_time_type_input"></div>
                              </div>

                              <div class="row text-left mt-1">
                                 <div class="col-md-2"><label>ประเภทข้อมูล</label></div>
                                 <div class="col-md-3">
                                    <select class="form-control form-control-sm" name="alert_data_type" id="alert_data_type">
                                       <option value="null">เลือกประเภทข้อมูล</option>
                                       <option value="0">จากฐานข้อมูล</option>
                                       <option value="1">กำหนดเอง</option>
                                    </select>
                                 </div>
                                 <div class="col-md-7 alert_data_type_input"></div>
                              </div>
                           </form>
                           
                        </div>
                     </div>
                     <!-- End div -->

                     <!-- Div กรองข้อมูล -->
                     <div class="col-md-12">
                        <div class="row condition_builder_div">
                           <div class="col-md-12"> 
                              <div class="row">
                                 <div class="col-md-2">
                                 <h3 class="float-left"><span class="badge badge-success"><b>กรองข้อมูล</b></span></h3>
                                 </div>
                                 <div class="col-md-6 form-inline">
                                    <label class="col-md-5">รูปแบบงานที่บันทึกไว้</label>
                                    <?php
                                       $select_save_sql = 'SELECT * FROM `user_save_setting` WHERE user_id = '.$user_id.' ORDER BY `user_save_setting`.`date_time` DESC';
                                       $result = mysqli_query($conn,$select_save_sql);
                                    ?>
                                    <select class="form-control form-control-sm col-md-7" id="save_select_box">
                                       <option value="null">เลือกรูปแบบ</option>
                                       <?php
                                          while($row = mysqli_fetch_array($result)){ 
                                             $datetime2 = new DateTime($row['date_time']); // create obj datetime
                                             $date = $datetime2->format('Y-m-d'); // split date and time;
                                             $time = $datetime2->format('H:i:s'); // split date and time; 
                                             echo '<option value="'.$row['path'].'">'.$time." ".DateThai($date)." ".$row['task_name'].'</option>'; 
                                          }  
                                       ?>   
                                    </select>
                                 </div>
                                 <div class="col-md-4"></div>
                              </div>
                              
                              
                           </div>
                           <div class="col-md-12">
                              
                              <!-- Form condition builder -->
                              <form id="condition_builder_form">
                                 <input type="hidden" id="id_user" value="<?php echo $_SESSION['id_user']; ?>">
                                 <input type="hidden" name="sub_row_data_count" id="sub_row_data_count" >
                                 <input type="hidden" id="table_nameeeeeeeee" name="table_nameeeeeeeee">
                                 <input type="hidden" id="fields_count" name="fields_count">
                                 <input type="hidden" id="webdatarocks_setting" name="webdatarocks_setting">
                                 <input type="hidden" id="sql_select" name="sql_select">

                                 <input type="file" id="open_file" accept=" .json" style="display:none;">
                                 <div class="row">
                                    <div class="col-md-12 text-center"> 
                                       <input style="display:none;" type="button" value="ยืนยันการส่งข้อมูลไลน์" class="btn btn-success btn-sm" name="btn_submit_alert" id="btn_submit_alert">
                                       <input style="display:none  ;" type="button" value="ย้อนกลับ" class="btn btn-warning btn-sm" name="btn_back" id="btn_back">
                                    </div>
                                 </div>
                                 <div class="row condition_form_div">
                                    
                                    <!-- div btn -->
                                    <div class="col-md-12 text-center"> 
                                       <input type="button" value="ยืนยัน" class="btn btn-success btn-sm" id="send_query">
                                       <input type="button" value="เคลียร์เงื่อนไข" class="btn btn-danger btn-sm" id="reset_condition">
                                       <input type="button" value="เคลียร์ทั้งหมด" class="btn btn-danger btn-sm" id="reset_all">
                                    </div>
                                    <!-- End div -->

                                    <!-- div condition builder -->
                                    <div class="col-md-12 mt-1">
                                       <!-- ตาราง กำหนดเงื่อนไข -->
                                       <table class="table table-sm table-bordered text-center condition_table">
                                          <thead class="bg-primary">
                                             <tr>
                                                <th width="5%"> 
                                                <div class="dropdown" >
                                                      <input type="button" value="+" class="dropbtn btn btn-success btn-sm">
                                                   <div class="dropdown-content" style="left:0;">
                                                      <span id="add_condition" condition_type="main_condition">เพิ่มเงื่อนไข</span>
                                                      <span id="add_sub_condition" condition_type="sub_condition">เพิ่มเงื่อนไขย่อย</span>
                                                   </div>
                                                </div>
                                                </th>
                                                <th width="5%">ประเภท</th>
                                                <th width="15%">หัวตาราง</th>
                                                <th width="10%">เงื่อนไข</th>
                                                <th width="15%">ค่า/หัวตาราง</th>
                                             </tr>
                                          </thead>
                                          <tbody class="table-primary" id="append_condition">
                                          </tbody>
                                       </table> 
                                       <!-- End table -->
                                    </div>
                                    <!-- End div -->
                                    
                                    <!-- div result_query -->
                                    
                                    <!-- <button type="button" class="btn btn-secondary btn-sm" id="table_fullscreen"><i class="fas fa-arrows-alt"></i></button> -->
                                    
                                    <div class="col-md-12 result_table"></div>
                                    <!-- End div -->

                                    <!-- div result_query -->
                                    <div class="col-md-12 mt-1"><input id="send_to_webdatarocks" class="btn btn-success btn-sm" type="button" value="ส่งข้อมูลไลน์"></div>
                                    <!-- End div -->
                                    
            
                                       
                                 </div>
                                 <div class="row div_result_webdatarocks">

                                    <!-- div result_webdatarocks -->
                                    <div class="col-md-12 mt-1"><div id="webdatarocks"></div></div>
                                    <!-- End div --> 

                                    <link href="/Alert_bot/lib/Webdatarocks/webdatarocks.min.css"  rel="stylesheet"/>
                                    <script src="/Alert_bot/lib/Webdatarocks/webdatarocks.toolbar.min.js" ></script>
                                    <script src="/Alert_bot/lib/Webdatarocks/webdatarocks.js" ></script>

                                 </div>

                              </form>
                              <!-- End form -->


                           </div>
                        </div>
                     </div>
                     <!-- End div -->

                     
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
   .dropbtn {
      color: white;
      border: none;
      cursor: pointer;
   }
   .dropdown {
      position: relative;
      display: inline-block;
   }
   .dropdown-content {
      display: none;
      position: absolute;
      right: 0;
      background-color: #f9f9f9;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
      z-index: 1;
   }
   .dropdown-content span {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
   }
   .dropdown-content span:hover {
      cursor: pointer;
      background-color: #f1f1f1;
   }
   .dropdown:hover .dropdown-content {
      display: block;
   }
   .dropdown:hover .dropbtn {
      background-color: #3e8e41;
   }
</style>
