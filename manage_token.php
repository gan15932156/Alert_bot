<?php   
    session_start(); 
    require_once('login_check.php'); 
    require_once("Http_request/insert_log.php");
    require_once('config/configDB.php');
    $conn = $DBconnect;
    insert_log($conn,$_SESSION['id_user'],'เรียกดูหน้าจัดการข้อมูลโทเคน');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าจัดการข้อมูลโทเคน</title>

    <?php require_once('config/include_lib.php'); ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
        <?php include_once('config/navbar.php'); ?>
            <div class="work_space">
                <div class="inner_work_space">
                    <div class="row text-center">
                    
                        <div class="col-md-12 "><h1><span class="badge badge-primary name_page"><b>หน้าจัดการข้อมูลโทเคน</b></span></h1></div>
                        <!-- Div กรองข้อมูล -->
                        <div class="col-md-12">
                            <div class="row condition_builder_div">
                                <div class="col-md-12">
                                    <table class="table table-striped table-hover table-sm" id="data_table" style="width:100%;">
                                        <thead class="table-bordered text-center text-light tb_head">
                                            <tr>
                                                <th width="15%" scope="col">ชื่องาน</th>
                                                <th width="15%" scope="col">กลุ่มไลน์</th>
                                                <th width="40%" scope="col">โทเคนไลน์</th>
                                                <th width="30%" scope="col">จัดการข้อมูล</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-bordered tb_body" style="font-size:16px;"></tbody>
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
    .toolbar {
    float: left;
}
</style>
<script>
    $(document).ready(function(){
        var table ;
        table = $('#data_table').DataTable({
            "dom": '<"toolbar">frtip',
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
            ajax: { url:"Http_request/get_task_datatable.php"},
            'columns':[
                {
               data:'task_name'
            },
            {
               data:'group_name'
            },
            {
               data:'token_line'
            },
            {
                data:'edit_delete',
                render: function (data,type,row){
                    let html = 'edit and delete btn';
                  
                    return html;
                }
            }
            
            ]
        });

        $("div.toolbar").html(' <a href="add_token_line.php" class="btn btn-success normal_btn">เพิ่มโทเคน</a>');

        // $("#data_table tbody").on('click','tr',function(){
        //     let task_id_post = $(this).find("td span[id='task_table_id']").text();
        //     let task_name_post = $(this).find("td span[id='task_table_name']").text();
        //     $("#exampleModalLabel").text("ข้อมูลตารางงาน "+$(this).find("td span[id='task_table_name']").text());
        //     $("#div_modal_table").empty();
        //     // $("#modal_div_body").append("<p>"+task_id+"</p>");
        //     $.ajax({
        //         url: "Http_request/get_task_data_datatable.php",
        //         method: "POST",
        //         async: false,
        //         dataType: "JSON",
        //         data:{task_id : task_id_post, task_name: task_name_post}, 
        //         error: function(jqXHR, text, error) {
        //             Swal.fire({
        //             title: 'ไม่พบข้อมูล!',
        //             icon: 'warning'
        //             })
        //         }
        //     })
        //     .done(function(data) { // response
        //         if(data.result.data == false){
        //             $("#modal_div_body").html("<p class='btn btn-danger btn-sm'>ไม่พบข้อมูล</p>");
        //         }
        //         else{
        //             if(data.result.task_type == "WBS_task"){
        //                 let table = populate_table_WBS(data.result.data);
                       
        //                 $(".div_modal_table").html(table);
        //                 $('tr.header td span').click(function() {
        //                     $(this).parent().find('span').text(function(_, value) { return value == '-' ? '+' : '-' });
        //                     $(this).parent().parent().nextUntil('tr.header').slideToggle(50);
        //                 });//WBS_trs
        //             }
        //             else{
        //                 let table = populate_table(data.result.data);
        //                 $(".div_modal_table").html(table);
        //             }
        //         }
        //     }); 
        //     $("#exampleModal").modal();
        // })
   })


</script>