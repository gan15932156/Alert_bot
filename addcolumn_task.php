<?php   session_start(); require_once('config/configDB.php'); require_once('login_check.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>เพิ่มคอลัมน์หัวข้องาน</title>

   <?php require_once('config/include_lib.php'); ?>
</head>
<body>
   <div class="container-fluid">
      <div class="row">

         <?php include_once('config/navbar.php'); ?>

         <div class="work_space">
               <div class="inner_work_space">
                  <div class="row text-center">
                     
                     <div class="col-md-12 "><h2>เพิ่มคอลัมน์หัวข้องาน</h2></div>
                     <div class="col-md-12">
                        <form method="POST" action="javascript:void(0);" id="add_task" onSubmit="addtask()">

                        <input type="hidden" id="id_user"  name="id_user" value="<?php echo $_SESSION['id_user'];?>">

                           <div class="form_add_task">
                              <div class="row">
                                 <div class="col-md-2"><label>เลือกหัวข้องาน</label></div>
                                 <div class="col-md-2">
                                    <?php
                                        $sql = 'SELECT * FROM `task_user` WHERE `user_id` =' .$_SESSION['id_user'];

                                        $conn = $DBconnect;

                                        $result = mysqli_query($conn,$sql);
                                    ?>

                                    <select name="task_id" id="task_id" class="form-control form-control-sm">
                                       <option value="null">เลือกหัวข้องาน</option>
                                       <?php
                                          while($row = mysqli_fetch_row($result)){
                                             echo '<option value="'.$row[0].'">'.$row[2].'</option>';
                                          }
                                       ?>
                                    </select>
                                 </div>
                                 <input type="hidden" id="task_name" name="task_name">
                                 <div class="col-md-2"><label>เพิ่มหัวข้องาน</label></div>
                                 <div class="col-md-1"><input type="button" class="btn btn-success btn-sm" id="btn_add_header_task" value="+"></div>
                                 <div class="col-md-1"><input type="button" class="btn btn-danger btn-sm" id="btn_clear" value="เคลียร์"></div>
                                 <div class="col-md-1 text-center"><input type="submit" class="btn btn-success btn-sm" value="ยืนยัน"></div>
                              </div><br>
                              <div class="row">
                                
                              </div>
                           </div><br>
                           <div class="table_result">
                              <div class="row">
                                 <div class="col-md-1"></div>
                                 <div class="col-md-5 fields_div">
                                    <table style="color:black;" class="table table-sm table-bordered">
                                       <thead class="thead-light">
                                          <tr>
                                             <th width="60%">หัวข้อ</th>
                                             <th width="40%">ชนิดข้อมูล</th>
                                          </tr>
                                       </thead>
                                       <tbody id="tbody_task"></tbody>
                                    </table>
                                 </div>
                                 <div class="col-md-5 result_template text-center">

                                    <label><b>รูปแบบตารางงาน</b></label>

                                    <table class="table table-sm table-bordered ">
                                       <thead class="thead-light">
                                          <tr>
                                             <th width="60%">หัวข้อ</th>
                                             <th width="40%">ชนิดข้อมูล</th>
                                          </tr>
                                       </thead>
                                       <tbody class="bg-light text-left" id="tempate_table_body"></tbody>
                                    </table>
                                 </div>
                                 <div class="col-md-1"></div>
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
   .form_add_task{
      background-color:#e0abff;
      margin-left: 5px;
      margin-right: 5px;
      margin-top: 5px;
      padding:5px;
      
   }
   .table_result{
      background-color:#f8e0ff;
      margin-left: 5px;
      margin-right: 5px;
      margin-top: 5px;
      padding:5px;
      
   }
   .fields_div{
      border-right: 1px solid #8c7a91;
   }
</style>


<script>
   $(document).ready(function(){
      $("#btn_add_header_task").click(function(){
         let html = '';
         html += '<tr class="table-light">';
         html += '<td><input class="form-control form-control-sm header" name="header[]" required></td>';
         html += '<td><select class="form-control form-control-sm header_type" name="header_type[]"><option value="varchar(255)">ตัวอักษร</option><option value="int">ตัวเลข</option><option value="double">ทศนิยม</option><option value="date">วันที่</option></select></td>';
         html += '</tr>';
         $('#tbody_task').append(html);

      });

      $("#btn_clear").click(function(){
         $("#tbody_task").empty();
      });   

      $("#task_id").change(function(){    
         // HTML code
         let html= '';
         if ($(this).val() != "null") {
            fetch_fields($(this).val());
            $("#task_name").val($( "#task_id option:selected" ).text());
         }
         else{
            $("#task_name").val(null);
         }
      });
   })

   function fetch_fields(task_idd){
      let html;
      $.ajax({
         url: "Http_request/show_template_task_add_column_page.php",
         method: "POST",
         data: {
            task_id:task_idd
         },
         dataType: 'JSON',
         async: false,
         success: function(data) {
            if (!data.error) {
               html = data.result;
            } 
            else {
               Swal.fire({
                  icon: 'error',
                  title: 'ผิดพลาด',
                  text: data.message
               })
            }
         }
      });
      $("#tempate_table_body").html(html);
   }

   function addtask(){  
      if($(".header").val() == undefined){
         Swal.fire({
            icon: 'warning',
            title: 'กรุณาเพิ่มคอลัมน์หัวข้องาน'
         })
      }
      else{
         $.ajax({
            url: "Http_request/get_add_column_task.php", 
            method: "POST",
            async: false,
            data: $('#add_task').serialize(),
            error: function(jqXHR, text, error) {
               Swal.fire({
                  icon: 'error',
                  title: 'ผิดพลาด',
                  text: error
               })
            }
         })
         .done(function(data) {
            if(data == "fail"){
               Swal.fire({
                  icon: 'error',
                  title: 'ผิดพลาด',
                  text: 'ไม่สามารถเพิ่มคอลัมน์หัวข้องานได้'
               })
            }
            else{
               fetch_fields($("#task_id").val());
               Swal.fire({
                  icon: 'success',
                  title: 'สำเร็จ'
               })
            } 
         });
      }
     
   }
</script>