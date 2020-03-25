<?php   
   session_start(); 
   require_once("Http_request/insert_log.php");
   require_once('login_check.php'); 
   require_once('config/configDB.php');
   $conn = $DBconnect;
   insert_log($conn,$_SESSION['id_user'],'เรียกดูประวัติการใช้งาน');
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>หน้าหลัก</title>

   <?php require_once('config/include_lib.php'); ?>
   <script src="lib/project_js/condition_builder.js"></script>
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
                     <div class="col-md-12 "><h2>ประวัติการใช้งาน</h2></div>
   
                     <!-- Div กรองข้อมูล -->
                     <div class="col-md-12">
                        <div class="row condition_builder_div">
                           <div class="col-md-12">
                           <table class="table table-striped table-hover table-sm" id="data_table" style="width:100%;">
                              <thead class="thead-light table-bordered text-center">
                                 <tr>
                                 <th width="15%" scope="col">วันเวลา</th>
                                 <th width="75%" scope="col">บันทึก</th>
                                 </tr>
                              </thead>
                              <tbody class="table-bordered" style="font-size:16px;"></tbody>
                           </table>
                                 <!-- SELECT * FROM `user_log` ORDER BY `datetime` DESC -->
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

<script>
   $(document).ready(function(){
      var table ;
      table = $('#data_table').DataTable({
         columnDefs: [
            {targets: [1],className: 'dt-body-left'},
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
         ajax: { url:"Http_request/get_select_log_data.php"},
            'columns':[
            {
               data:'datetime',
               render: function (data,type,row){
                  let datetime =  row[2]
                  let t_year =  parseInt(datetime.substring(0,4))+543;
                  let t_month = new Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
                  let t_day = datetime.substring(8,10);
                  let th_dateeee = t_day+" "+t_month[parseInt(datetime.substring(5,7))]+" "+t_year;
                  let time = datetime.substring(10);
                  return th_dateeee+' '+time;
               }
            },
            {
               data:'log_message',
               render: function (data,type,row){
                  return row[3];
               }
            }
         ]
      });
   })
</script>