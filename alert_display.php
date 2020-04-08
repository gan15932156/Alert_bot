<?php   
    session_start(); 
    require_once('login_check.php'); 
    require_once("Http_request/insert_log.php");
    require_once('config/configDB.php');
    $conn = $DBconnect;
    insert_log($conn,$_SESSION['id_user'],'เรียกดูข้อมูลส่งไลน์');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานแจ้งเตือนไลน์</title>

    <?php require_once('config/include_lib.php'); ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
        <?php include_once('config/navbar.php'); ?>
            <div class="work_space">
                <div class="inner_work_space">
                    <div class="row text-center">
                        <div class="col-md-12 "><h2>รายงานแจ้งเตือนไลน์</h2></div>
   
                        <!-- Div กรองข้อมูล -->
                        <div class="col-md-12">
                            <div class="row condition_builder_div">
                                <div class="col-md-12">
                                    <table class="table table-striped table-hover table-sm" id="data_table" style="width:100%;">
                                        <thead class="thead-light table-bordered text-center">
                                            <tr>
                                                <th width="15%" scope="col">ชื่องาน</th>
                                                <th width="25%" scope="col">ชื่อกลุ่มกลุ่มไลน์/ชื่อไลน์</th>
                                                <th width="25%" scope="col">วันเวลาแจ้งเตือน</th>
                                                <th width="15%" scope="col">ตัวอย่างรายงาน</th>
                                                <th width="20%" scope="col">สถานะรายการ</th>
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
                {targets: [0,1],className: 'dt-body-left'},
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
            ajax: { url:"Http_request/get_alert_datatable.php"},
            'columns':[
            {
                data:'task_name',
                render: function (data,type,row){
                    return row[1];
                }
             },
             {
                data:'group_line_name',
                render: function (data,type,row){
                    return row[5];
                }
             },
             {
                data:'alert_date_time',
                render: function (data,type,row){
                    let datetime =  row[2]
                    let t_year =  parseInt(datetime.substring(0,4))+543;
                    let t_month = new Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
                    let t_day = datetime.substring(8,10);
                    let th_dateeee = t_day+" "+t_month[parseInt(datetime.substring(5,7))]+" "+t_year;
                    return th_dateeee+' '+row[3];
                }
             },
             {
                data:'file_ex',
                render: function (data,type,row){
                     
                    return '<button class="btn btn-primary btn-sm" file_name="'+row[4]+'" type="button" id="btn_show_ex"><i class="fas fa-file-alt"></i></button>';
                }
             },
             {
                data:'record_status',
                render: function (data,type,row){
                    let result = '';
                    if(row[7] == 1){
                        result = '<h5><a id="record_status" style="color:black;" href="Http_request/change_alert_status.php?alert_id='+row[0]+'&status='+row[7]+'" ><span class="badge badge-success">เปิด</span></h5>';
                    }
                    else{
                        result = '<h5><a id="record_status" style="color:black;" href="Http_request/change_alert_status.php?alert_id='+row[0]+'&status='+row[7]+'" ><span class="badge badge-danger">ปิด</span></h5>';
                    }
                    
                    return result;
                }
             }
            ]
        });
        $("#data_table tbody").on('click','#record_status',function(){
            return confirm('ต้องการเปลี่ยนสถานะการแจ้งเตือนหรือไม่');
        })

        $("#data_table tbody").on('click','#btn_show_ex',function(){
            window.open("user_export_file_alert/"+$(this).attr("file_name"), "_blank", "toolbar=yes,scrollbars=yes,resizable=yes");
        })
   })
</script>