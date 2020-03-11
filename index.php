<!DOCTYPE html>
<html lang="en">
<head>

   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>เข้าสู่ระบบ</title>

   <?php require_once('config/include_lib.php') ?>

   <style>
      html, body{
         background-color :#b070ff;
      }
   </style>
</head>
<body>
   <div class="container">

      <br><br><br><br><br><br>

      <div class="row">
         <div class="col-md-12 text-center">
            <center>
               <div class="card" style="width: 40vw;"><br>
                  <img src="lib\Picture\login.svg" class="card-img-top" height="100" width="120">
                  <div class="card-body">
                     <h2>เข้าสู่ระบบ</h2>  
                     <br>
                     <form method="POST" action="javascript:void(0);" id="form_login" onSubmit="login()">
                        <div class="row">
                           <div class="col-md-3"><label><b>ชื่อผู้ใช้</b></label></div>
                           <div class="col-md-9"><input type="text" name="username" class="form-control" required></div>                 
                        </div><br>
                        <div class="row">
                           <div class="col-md-3"><label><b>รหัสผ่าน</b></label></div>
                           <div class="col-md-9"><input type="password" name="password" class="form-control" required></div>
                        </div><br>
                        <div class="row">
                           <div class="col-md-12"><button type="submit" class="btn btn-success" style="background-color:#821cff;border-color:#821cff">เข้าสู่ระบบ</button></div>
                        </div><br>
                        <div class="row">
                           <div class="col-md-12 result"></div>
                        </div> 
                     </form>
                  </div>
               </div>
            </div>
         </center>
      </div>  
   </div>   
</body>
</html>


<script>
 
   function login(){
         $.ajax({
            url: "Http_request/get_login_data.php",
            method: "POST",
            async: false,
            data: $("#form_login").serialize(),
            error: function(jqXHR, text, error) {
               alert("error:" + error);
            }
         })
         .done(function(data) {
            $(".result").html(data);
         });
   }
</script>