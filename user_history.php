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
   <style>
      .tb-result thead th { 
         position: sticky; 
         top: 0; 
         background-color:#007BFF;
         margin-top:2px;
         
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
                  <div class="col-md-12 "><h1><span class="badge badge-primary name_page"><b>ประวัติการใช้งาน</b></span></h1></div>
                  <!-- Div กรองข้อมูล -->
                  <div class="col-md-12">
                     <div class="row condition_builder_div">
                        <div class="col-md-12">
                           <div class="row">
                              <div class="col-md-3"></div>
                              <div class="col-md-2"><label>วันเวลา</label></div>
                              <div class="col-md-3"><input id="log_date_time" type="date" class="form-control form-control-sm"></div>
                              <div class="col-md-3"></div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <table class="table table-striped table-hover table-sm table-bordered" id="data_table" style="width:100%;">
                                    <thead class="text-center text-light tb_head">
                                       <tr>
                                       <th width="15%" scope="col">วันเวลา</th>
                                       <th width="75%" scope="col">บันทึก</th>
                                       </tr>
                                    </thead>
                                    <tbody class="tb_body" style="font-size:16px;"></tbody>
                                 </table>
                              </div>
                           </div>
                          
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
var table ;
   $(document).ready(function(){
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

      $("#log_date_time").change(function(){
         if($(this).val() != ""){
            $("tbody").empty();
            load_table(table,$(this).val())
         }
         else{
            table.ajax.reload();
         }
         
      })
   })

   function load_table(table,datetime){
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
         ajax: { 
            url:"Http_request/get_select_log_data_date.php",
            data : { datetime_post : datetime }
         },
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
      console.log(datetime)
   }
</script>