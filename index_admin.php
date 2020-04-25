<?php   session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>หน้าหลัก</title>

   <?php require_once('config/include_lib.php'); ?>
   <style>
      .jumbotron{
         text-decoration: none;
         position: relative;
         width:20vw;
         height:30vh;
      }
   </style>
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
                        <div align="center" class="col-md-6 "><br><br><br><br>
                           <div class="jumbotron manage_user">
                              <h3><span>จำนวนผู้ใช้ : <span class="badge badge-info" id="user_count">#</span></span></h3>
                              <span style="position: absolute;top: -10px;left: -10px;font-size:20px;background-color:#5400a3;border: 1px solid #5400a3;" class="btn btn-success"><h3><i class="fas fa-user"></i></h3></span>   
                              <hr class="my-3">
                              <button id="manage_user_btn" class="btn btn-success btn-lg my-2">จัดการข้อมูลผู้ใช้</button>
                              <a class="btn btn-success btn-lg" href="set_user_page.php">กำหนดสิทธิผู้ใช้</a>
                           </div>
                        </div>
                        <div align="center" class="col-md-6"><br><br><br><br>
                           <div class="jumbotron see_log">
                              <h3><span>จำนวนผู้ใช้ : <span class="badge badge-info" id="user_count">#</span></span></h3>
                              <span style="position: absolute;top: -10px;left: -10px;font-size:20px;background-color:#5400a3;border: 1px solid #5400a3;" class="btn btn-success"><h3><i class="fas fa-history"></i></h3></span>   
                              <hr class="my-3">
                              <a class="btn btn-success btn-lg my-2" href="#">ประวัติการใช้งานผู้ใช้</a>
                           </div>
                     </div>
                  </div>
                  <!-- End div -->  

               </div>
            </div>
         </div>
      </div>
   </div>
   
   <!-- Modal -->
   <div class="modal fade" id="user_info_modal" tabindex="-1" role="dialog" aria-labelledby="user_info_user_label" aria-hidden="true">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="user_info_user_label">จัดการข้อมูลผู้ใช้</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body" id="modal_div_body">
               <div style="overflow:auto;height:100%;width:100%;" class="div_modal_table">
                  <table class="table table-striped table-hover table-sm" id="user_info_table" style="width:100%;">
                     <thead class="thead-light table-bordered text-center">
                        <tr>
                           <th width="20%" scope="col">ชื่อจริง</th>
                           <th width="25%" scope="col">นามสกุล</th>
                           <th width="20%" scope="col">แผนก</th>
                           <th width="20%" scope="col">ตำแหน่ง</th>
                           <th width="5%" scope="col">ระดับ</th>
                           <th width="10%" scope="col">สถานะ</th>
                        </tr>
                     </thead>
                     <tbody class="table-bordered" style="font-size:16px;"></tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>


   
</body>
</html>

<style>
   .modal-dialog {height:85vh;max-width:87vw;}  
   .modal-body{height:85vh;width:100%;align:center;}    
   .condition_builder_div{
      background-color:#f8e0ff;
      height: 90vh;
      margin-left: 5px;
      margin-right: 5px;  
      padding:5px;
    }
</style>

<script>
   $(document).ready(function(){
      $.ajax({
         url: "Http_request/get_count_user.php",
         method: "POST",
         async: false,
         error: function(jqXHR, text, error) {
            alert("error:" + error);
         }
      })
      .done(function(data) {
         $("#user_count").html(data);
      });
      var user_info_table ;
      user_info_table = $('#user_info_table').DataTable({
         columnDefs: [
            {targets: [0,1,2,3,4],className: 'dt-body-left'},
            { orderable: false, targets: '_all' }
         ],     
         "searching": true,
         "lengthChange": false,
         pageLength: 11,
         destroy: true,
         serverSide: true,
         processing: true,
         "language": {
            "search":"ค้นหา:",
            "zeroRecords": "ไม่พบข้อมูล",
            "info": "แสดงหน้า _PAGE_ จาก _PAGES_",
            "infoEmpty": "ไม่พบข้อมูล",
            "infoFiltered": "(กรองจาก _MAX_ รายการทั้งหมด)",
            "paginate": {
               "first":      "หน้าแรก",
               "last":       "หน้าสุดท้าย",
               "next":       "ถัดไป",
               "previous":   "ก่อนหน้า"
            }
         },      
         ajax: { url:"Http_request/get_user_info_datatables.php"},
         'columns':[
            {
               data:'FirstName'
            },
            {
               data:'LastName'
            },
            {
               data:'PositionDescShort'
            },
            {
               data:'LevelDesc'
            },
            {
               data:'DepartmentShortName'
            },
            {
               data:'status',
               render: function (data,type,row){
                  let result = '';
                  if(row[5] == 1){
                     result = '<h5><a id="record_status" style="color:black;" href="Http_request/change_alert_status.php?alert_id='+row[5]+'&status='+row[7]+'" ><span class="badge badge-success">เปิด</span></h5>';
                  }
                  else{
                     result = '<h5><a id="record_status" style="color:black;" href="Http_request/change_alert_status.php?alert_id='+row[5]+'&status='+row[7]+'" ><span class="badge badge-danger">ปิด</span></h5>';
                  }
                  return result;
               }
            }
         ]
      });
      $("#user_info_table tbody").on('click','#record_status',function(){
         return confirm('ต้องการเปลี่ยนสถานะการใช้งานผู้ใช้หรือไม่');
      })
      $("#manage_user_btn").click(function(){
         $("#user_info_modal").modal();
      })
   })
</script>