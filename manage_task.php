<?php   
    session_start(); 
    require_once('login_check.php'); 
    require_once("Http_request/insert_log.php");
    require_once('config/configDB.php');
    $conn = $DBconnect;
    insert_log($conn,$_SESSION['id_user'],'เรียกดูหน้าจัดการข้อมูลงาน');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าจัดการข้อมูลงาน</title>

    <?php require_once('config/include_lib.php'); ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
        <?php include_once('config/navbar.php'); ?>
            <div class="work_space">
                <div class="inner_work_space">
                    <div class="row text-center">
                    
                        <div class="col-md-12 "><h1><span class="badge badge-primary name_page"><b>หน้าจัดการข้อมูลงาน</b></span></h1></div>
                        <!-- Div กรองข้อมูล -->
                        <div class="col-md-12">
                            <div class="row condition_builder_div">
                                <div class="col-md-12">
                                    <table class="table table-striped table-hover table-sm" id="data_table" style="width:100%;">
                                        <thead class="table-bordered text-center text-light tb_head">
                                            <tr>
                                                <th width="10%" scope="col">รหัสงาน</th>
                                                <th width="90%" scope="col">ชื่องาน</th>
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

      <!-- Modal -->
      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title"><span id="exampleModalLabel" class="badge badge-primary name_page"><b>ข้อมูลงาน</b></span></h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modal_div_body">
        <div style="overflow:auto;height:100%;width:100%;" class="div_modal_table">
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
            ajax: { url:"Http_request/get_task_datatable.php"},
            'columns':[
            {
                data:'task_id',
                render: function (data,type,row){
                    return "<span id='task_table_id'>"+row[0]+"</span>";
                }
             },
             {
                data:'task_name',
                render: function (data,type,row){
                    return "<span id='task_table_name'>"+row[1]+"</span>";
                }
             }
            
            ]
        });
        $("#data_table tbody").on('click','tr',function(){
            let task_id_post = $(this).find("td span[id='task_table_id']").text();
            let task_name_post = $(this).find("td span[id='task_table_name']").text();
            $("#exampleModalLabel").text("ข้อมูลตารางงาน "+$(this).find("td span[id='task_table_name']").text());
            $("#div_modal_table").empty();
            // $("#modal_div_body").append("<p>"+task_id+"</p>");
            $.ajax({
                url: "Http_request/get_task_data_datatable.php",
                method: "POST",
                async: false,
                dataType: "JSON",
                data:{task_id : task_id_post, task_name: task_name_post}, 
                error: function(jqXHR, text, error) {
                    Swal.fire({
                    title: 'ไม่พบข้อมูล!',
                    icon: 'warning'
                    })
                }
            })
            .done(function(data) { // response
                if(data.result.data == false){
                    $("#modal_div_body").html("<p class='btn btn-danger btn-sm'>ไม่พบข้อมูล</p>");
                }
                else{
                    if(data.result.task_type == "WBS_task"){
                        let table = populate_table_WBS(data.result.data);
                       
                        $(".div_modal_table").html(table);
                        $('tr.header td span').click(function() {
                            $(this).parent().find('span').text(function(_, value) { return value == '-' ? '+' : '-' });
                            $(this).parent().parent().nextUntil('tr.header').slideToggle(50);
                        });//WBS_trs
                    }
                    else{
                        let table = populate_table(data.result.data);
                        $(".div_modal_table").html(table);
                    }
                }
            }); 
            $("#exampleModal").modal();
        })
   })

    function populate_table_WBS(data){
        let row = 1;// row count
        let html = '';
        // table thead
        html += '<table class="table table-sm table-bordered tb-result"><thead class="text-center text-light tb_head"><tr><th width="10%">#</th><th width="18%">ลำดับที่</th><th width="45%">รายการ</th><th width="27%">WBS(จำนวนรายการ)</th></tr></thead>';
        html += '<tbody class="text-center wbs_table_body">'; // tbody
        Object.keys(data).forEach(function(k) {
            if (k == "") { //<input class="check_wbs_row" type="checkbox" name="check_wbs_row[]" value="' + k + '">
                html += '<tr style="background-color:#d2b0ff;" class="header" data_wbs="null_value"><td><span class="btn btn-primary btn-sm normal_btn">-</span></td><td></td><td></td><td>' + k + '('+ data[k].length+')</td></tr>';
                let i = 1;
                Object.keys(data[k]).forEach(function(sub_loop) {
                    html += '<tr style="background-color:#e9d9ff;">';
                    html += '<td><b>' + i + '</b></td>';
                    html += '<td>' + data[k][sub_loop]["ลำดับที่"] + '</td>';
                    html += '<td class="text-left">' + data[k][sub_loop]["รายการ"] + '</td>';
                    html += '<td>' + data[k][sub_loop]["WBS"] + '</td>';
                    html += '</tr>';
                    i++;
                });
            } 
            else {
                html += '<tr style="background-color:#d2b0ff;" class="header" data_wbs="' + k + '"><td><span class="btn btn-primary btn-sm normal_btn">-</span></td><td></td><td></td><td>' + k + '('+ data[k].length+')</td></tr>';
                let i = 1;
                Object.keys(data[k]).forEach(function(sub_loop) {
                    html += '<tr style="background-color:#e9d9ff;">';
                    html += '<td><b>' + i + '</b></td>';
                    html += '<td>' + data[k][sub_loop]["ลำดับที่"] + '</td>';
                    html += '<td class="text-left">' + data[k][sub_loop]["รายการ"] + '</td>';
                    html += '<td>' + data[k][sub_loop]["WBS"] + '</td>';
                    html += '</tr>';
                    i++;
                });
            }
            row++;
        });
        html += '</tbody>';// close tag
        html += '</table>';
        return html;
    }
    function populate_table(data){
        let row = 1;// row count
        let html = '';
        // table thead
        html += '<table class="table table-sm table-bordered tb-result"><thead class="text-center text-light tb_head"><tr><th>#</th>'; 
        Object.keys(data[0]).forEach(function(k){
            if(k != "primary_key"){
                html += '<th>'+k+'</th>';
            }   
        })
        html += '</tr></thead>';
        html += '<tbody class="tb_body">';  
        Object.keys(data).forEach(function(k) {// loop query data
            html += '<tr>';
            html += '<td class="text-center"><b>' + row + '</b></td>';  
            Object.keys(data[k]).forEach(function(sub_loop){
                if(sub_loop != "primary_key"){
                    html += '<td class="text-left">' + data[k][sub_loop] + '</td>';
                }
            });
            html += '</tr>';
            row++;
        });
        html += '</tbody>';
        html += '</table>';
        return html;
    }
</script>