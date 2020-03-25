<?php   session_start(); require_once('login_check.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>เพิ่มหัวข้องาน</title>

   <?php require_once('config/include_lib.php'); ?>
</head>
<body>
   <div class="container-fluid">
      <div class="row">
         <?php include_once('config/navbar.php'); ?>
         <div class="work_space">
               <div class="inner_work_space">
                  <div class="row text-center">
                     <div class="col-md-12 "><h2>เพิ่มหัวข้องาน</h2></div>
                     <div class="col-md-12">
                        <form method="POST" action="javascript:void(0);" id="add_task" onSubmit="addtask()">
                        <input type="hidden" id="id_user"  name="id_user" value="<?php echo $_SESSION['id_user'];?>" >
                           <div class="form_add_task">
                              <div class="row">
                                 <div class="col-md-2"><label>หัวข้องาน</label></div>
                                 <div class="col-md-2"><input type="text" name="task_name" required class="form-control form-control-sm"></div>
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
                                 <div class="col-md-3"></div>
                                 <div class="col-md-6">
                                    <table class="table table-sm">
                                       <thead class="thead-light">
                                          <tr>
                                             <th width="60%">หัวข้อ</th>
                                             <th width="40%">ชนิดข้อมูล</th>
                                          </tr>
                                       </thead>
                                       <tbody id="tbody_task"></tbody>
                                    </table>

                                 </div>
                                 <div class="col-md-3"></div>
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
      background-color:#e0abff;
      margin-left: 5px;
      margin-right: 5px;
      margin-top: 5px;
      padding:5px;
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
   })
   function addtask(){
      $.ajax({
         url: "Http_request/get_add_task.php", 
         method: "POST",
         async: false,
         datatype:'json',
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
         Swal.fire({
            title: 'สำเร็จ ต้องการเข้าสู่หน้าเพิ่มโทเคนไลน์(Line token) หรือไม่',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่',
            cancelButtonText: 'ไม่',
         }).then((result) => {
            if (result.value) {
               window.location.href="add_token_line.php"
            }
         })
      });
   }
</script>