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
               <label><b>ชื่อ :'.$_SESSION['name'].'</b></label>
            </div>
         </div><br>
         <div class="row menu text-left">
            <div class="col-md-12">';
   
   if($_SESSION['leveltest'] == 1){  // admin
      $html.= '  
         <a href="">กำหนดสิทธิผู้ใช้</a><br>
         <a href="">เพิ่มข้อมูลงาน</a><br>
         <a href="">เพิ่มคอลัมน์งาน</a><br>
         <a href="">เพิ่มข้อมูลโทเคน</a><br>
         <a href="">อัพโหลดไฟล์</a><br>
         <a href="">ข้อมูลการส่งไลน์</a>';
   }
   else{
      $html.= ' 
         <a href="user"><i class="fas fa-user"></i></i>&nbsp;เพิ่มงาน</a><br>
         <a href="useruser">เพิ่มข้อมูลโทเคน</a><br>
         <a href="user">อัพโหลดไฟล์</a><br>
         <a href="user">เพิ่มคอลัมน์งาน</a><br>
         <a href="useruser">ข้อมูลการส่งไลน์</a>';
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