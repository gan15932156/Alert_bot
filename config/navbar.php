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
         <div class="row menu text-left">
            <div class="col-md-12">';

   if($_SESSION['username'] == "admin"){
      $html.= '  
      <a href="index_admin.php">หน้าแรก</a><br>
      <a href="set_user_page.php">กำหนดสิทธิผู้ใช้</a>';
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
            <a href="index_user.php">หน้าแรก</a><br>
            <a href="add_task.php"><i class="fas fa-user"></i></i>&nbsp;เพิ่มข้อมูลงาน</a><br>
            <a href="add_token_line.php">เพิ่มข้อมูลโทเคน</a><br>
            <a href="upload_file_page.php">อัพโหลดไฟล์</a><br>
            <a href="addcolumn_task.php">เพิ่มคอลัมน์งาน</a><br>
            <a href="useruser">ข้อมูลการส่งไลน์</a>';
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