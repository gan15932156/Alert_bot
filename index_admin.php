<?php   session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>หน้าหลัก</title>

   <?php require_once('config/include_lib.php'); ?>
</head>
<body>
   <div class="container-fluid">
      <div class="row">
         <?php include_once('config/navbar.php'); ?>
         
         <div class="work_space">
            <div class="inner_work_space">
               <div class="row text-center">
                  <div class="col-md-12 "><h1><span class="badge badge-primary"><b>หน้าหลักผู้ดูแลระบบ</b></span></h1></div>

                  <!-- Dashboard div -->
                  <div class="col-md-12">
                     <div class="row condition_builder_div">

                     </div>
                  </div>
                  <!-- End div -->  

               </div>
            </div>
         </div>
      </div>
   </div>
   
</body>
</html>

<style>
   .condition_builder_div{
      background-color:#f8e0ff;
      height: 90vh;
      margin-left: 5px;
      margin-right: 5px;  
      padding:5px;
    }
</style>