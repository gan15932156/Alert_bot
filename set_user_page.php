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
   <title>เพิ่มผู้ใช้</title>

   <?php require_once('config/include_lib.php'); ?>
 

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
                        
                     <div class="col-md-12 "><h2>เพิ่มผู้ใช้</h2></div>
                     <div class="col-md-12">
                        <div class="form_insert_user">
                           <div class="row">  
                              <div class="col-md-2"></div>
                              <div class="col-md-2"><label><b>กรอกรหัสพนักงาน</b></label></div>
                              <div class="col-md-2"><input type="text" id="user_code" name="user_code" required class="form-control"></div>
                              <div class="col-md-2"><input value="ค้นหา" type="button" class="btn btn-success btn-sm" id="btn_search"></div>
                              <div class="col-md-2"></div>
                           </div>
                        </div>
                     </div>

                     <div class="col-md-12">
                        <form onSubmit="insert_user()" action="javascript:void(0);" id="form_user_search">
                           <input type="hidden" id="search_user_id" name="search_user_id">
                           <div class="row row_result_user_info">
                              <div class="col-md-12"><h5>ข้อมูลพนักงาน</h5></div>
                              <div class="col-md-12">
                                 <table class="table table-sm table-bordered">
                                    <thead class="text-center bg-primary">
                                       <tr>
                                          <th width="25%">ชื่อจริง</th>
                                          <th width="25%">นามสกุล</th>
                                          <th width="25%">แผนก</th>
                                          <th width="15%">ตำแหน่ง</th>
                                          <th width="10%">ระดับ</th>
                                       </tr>
                                    </thead>
                                    <tbody class="text-left" id="search_result"></tbody>
                                 </table>
                              </div>
                              <div class="col-md-12">
                                 <input type="submit" class="btn btn-success btn-sm" value="ยืนยัน">
                              </div>
                           </div>
                        </form>
                     </div>

                  </div>
               </div>
            </div>
         </div> 
      </div>
   </div>
 
</body>
</html>

<style>
   .form_insert_user{
      background-color:#e0abff;
      margin-left: 5px;
      margin-right: 5px;
      margin-top: 5px;
      padding:5px;
      
   }
   .row_result_user_info{
      background-color:#f8e0ff;
      height: 100%;
      margin-left: 5px;
      margin-right: 5px;
      padding:5px;
   }
</style>

<script>
   $(document).ready(function(){
      $(".loading_page").hide();
      $("#btn_search").click(function(){
         if($("#user_code").val() == ""){
            Swal.fire({
                  icon: 'warning',
                  title: 'กรุณากรอกรหัสพนักงาน'
               })
         }
         else{
            $(".loading_page").show();
            $(".root_div").css({
               "position": "absolute", 
               "top": "0px",  
               "left": "0px",  
               "width": "100%",   
               "height": "100%",   
               "overflow": "hidden"
            });
            $.ajax({
               url: "Http_request/get_user_id_insert_user_form.php",
               method: "POST",
               async: false,
               dataType: "JSON",
               data: {user_id:$("#user_code").val()},
               error: function(jqXHR, text, error) {
                  alert("error:" + error);
               }
            })
            .done(function(data) {
 
               $('.loading_page').hide();
               $('.root_div').removeAttr('style');
 
               if(!data.error){
                  let html ='';
                  $("#search_user_id").val($("#user_code").val());
                  html+='<tr style="background-color:lightblue;">';
                  html+= '<td><input type="text" class="form-control"  name="first_name" required readonly value="'+data.user_info['FirstName']+'"></td>';
                  html+= '<td><input type="text" class="form-control"  name="last_name" required readonly value="'+data.user_info['LastName']+'"></td>';
                  html+= '<td><input type="text" class="form-control"  name="dps" required readonly value="'+data.user_info['DepartmentShort']+'"></td>';
                  html+= '<td><input type="text" class="form-control"  name="pds" required readonly value="'+data.user_info['PositionDescShort']+'"></td>';
                  html+= '<td><input type="text" class="form-control"  name="levelpea" required readonly value="'+data.user_info['LevelDesc']+'"></td>';
                  html+='</tr>';
 
                  $("#search_result").append(html);
               }
               else{
                  Swal.fire({
                     icon: 'error',
                     title: 'ไม่พบข้อมูลผู้ใช้'
                  })
               }
            });
         }
      });
   })

   function insert_user(){

      if($("#user_code").val() == ""){
         Swal.fire({
            icon: 'warning',
            title: 'กรุณากรอกรหัสพนักงาน'
         })
      }
      else{
         if($("#search_user_id").val() == ""){
            Swal.fire({
               icon: 'warning',
               title: 'ไม่พบข้อมูลผู้ใช้'
            })
         }
         else{
            $.ajax({
               url: "Http_request/get_insert_user_info.php",
               method: "POST",
               async: false,
               data: $("#form_user_search").serialize(),
               error: function(jqXHR, text, error) {
                  alert("error:" + error);
               }
            })
            .done(function(data) {
               if(data){
                  Swal.fire({
                     icon: 'success',
                     title: 'เพิ่มข้อมูลสำเร็จ'
                  })
                  $("#search_result").empty();
                  $("#user_code").val("");
                  $("#search_user_id").val("");
               }
               else{
                  Swal.fire({
                     icon: 'error',
                     title: 'ไม่สามารถเพิ่มข้อมูลได้'
                  })
               }
            });
         }
      }
   }
</script>
