<?php
   $html ='';

   $html.= ' 
      <div class="side_navbar text-center">
         <br>
         <div class="row profile_img">
            <div class="col-md-12">
               <img src="https://plms.pea.co.th/Personal/EmployeeImage?EmpCode='.$_SESSION['username'].'" style="width:100px;height:130px;" >
            </div> 
         </div><br>
         <div class="row profile_info">
            <div class="col-md-12">
               <label><b>ชื่อ : '.$_SESSION['FirstName'].'</b></label>
            </div>
            <div class="col-md-12">
               <label>ตำแหน่ง : '.$_SESSION['PositionDescShort'].'</label>
            </div>
            <div class="col-md-12">
            <label>แผนก : '.$_SESSION['DepartmentShort'].'</label>
            </div>
         </div><br>
         <div class="row menu ">
            <div class="col-md-12 text-left">
            <style>
            #link_icon{
               font-size : 25px;
               color: #5400a3;
            }
            #link{
               color: black;
               text-decoration: none;
            }
            #link:hover {
               color: #5400a3;
               background-color: transparent;
               
            }
         </style>
            ';

   if($_SESSION['username'] == "admin"){
      $html.= '  
      <a id="link" href="index_admin.php"><i id="link_icon" class="fas fa-home"></i>&nbsp;<span id="link_text">หน้าแรก</span></a><br>
      <a id="link" href="set_user_page.php"><i id="link_icon" class="fas fa-user"></i>&nbsp;<span id="link_text">กำหนดสิทธิผู้ใช้</span></a>';
   }
   else{
      if($_SESSION['leveltest'] == 1){  // admin
         $html.= '  
            <a href="index_admin.php">หน้าแรก</a><br>
            <a href="set_user_page.php">กำหนดสิทธิผู้ใช้</a><br>
            <a href="add_task.php">เพิ่มข้อมูลงาน</a><br>
            <a href="addcolumn_task.php">เพิ่มคอลัมน์งาน</a><br>
            <a href="add_token_line.php">เพิ่มข้อมูลโทเคน</a><br>
            <a href="upload_file_page.php">อัพโหลดไฟล์</a><br>
            <a href="">ข้อมูลการส่งไลน์</a>';
      }
      else{
         $html.= ' 
            <a id="link" href="index_user.php"><i id="link_icon" class="fas fa-home"></i>&nbsp;&nbsp;&nbsp;<span id="link_text">หน้าแรก</span></a><br>
            <a id="link" href="add_task.php"><i id="link_icon" class="fas fa-file"></i>&nbsp;&nbsp;&nbsp;<span id="link_text">เพิ่มข้อมูลงาน</span></a><br>
            <a id="link" href="add_token_line.php"><i id="link_icon" class="fas fa-key"></i>&nbsp;&nbsp;&nbsp;<span id="link_text">เพิ่มข้อมูลโทเคน</span></a><br>
            <a id="link" href="upload_file_page.php"><i id="link_icon" class="fas fa-upload"></i>&nbsp;&nbsp;&nbsp;<span id="link_text">อัพโหลดไฟล์</span></a><br>
            <a id="link" href="addcolumn_task.php"><i id="link_icon" class="fas fa-plus"></i>&nbsp;&nbsp;&nbsp;<span id="link_text">เพิ่มคอลัมน์งาน</span></a><br>
            <a id="link" href="useruser"><i id="link_icon" class="fab fa-line"></i>&nbsp;&nbsp;&nbsp;<span id="link_text">ข้อมูลการส่งไลน์</span></a><br>
            <a id="link" href="user_history.php"><i id="link_icon" class="fas fa-history"></i>&nbsp;&nbsp;&nbsp;<span id="link_text">ประวัติการใช้งาน</span></a>';
      }
   }
   

   $html.= ' 
   </div>
      </div>
      <br><br><br>
      <div class="row logout">
         <div class="col-md-12">
            <a class="btn btn-danger btn-sm" href="logout.php">ออกจากระบบ</a>
         </div>
      </div>
   </div>';

echo $html;
?>