$(document).ready(function(){
   var raw_data;
   var i = 1;// จำนวนแถวเงื่อนไข
   var pivot;// webdatarocks pivot table
   var html_table_fields; // HTML code select box
   var sub_con_count = new Object();// ประกาศ object ว่าง
   var query_result_object;// query result object

   $(".loading_page").hide();

   $("#task_id").change(function(){// task name selectbox onchange set field and set line token selectbox
      $('#table_nameeeeeeeee').val($("#task_id option:selected").text()) // กำหนดค่าให้ element id = table_nameeeeeeeee
      if($(this).val() == "null"){
         Swal.fire({
            title: 'กรุณาเลือกงาน',
            icon: 'warning'
         })
         $("#token_line_id").empty();
         $("#append_condition").empty();// clear condition row
         $(".result_table").empty();// clear result table
      }
      else{
         $.ajax({
            url: "Http_request/get_select_line_token_&_fetch_fields.php",
            method: "POST",
            async: false,
            dataType: "JSON",
            data: { task_id: $(this).val() }, 
            error: function(jqXHR, text, error) {
               Swal.fire({
                  title: 'ไม่พบข้อมูล!',
                  icon: 'warning'
               })
            }
         })
         .done(function(data) { // response
            //console.log(data)
            i = 1;
            if(data.token_line != "fail"){ 
               $("#token_line_id").html(data.token_line); 
            }
            else{          
               Swal.fire({
                  title: 'ไม่พบกลุ่มไลน์!',
                  icon: 'warning'
               })
               $("#append_condition").empty();// clear condition row
               $(".result_table").empty();// clear result table
            }
            if(data.fields != "fail"){
               html_table_fields = populate_fields(data.fields);
               $('#fields_count').val(JSON.stringify(data.fields));
               $("#append_condition").empty();// clear condition row
               $(".result_table").empty();// clear result table
            }
            else{
               Swal.fire({
                  title: 'ไม่พบข้อมูลงานในฐานข้อมูล!',
                  icon: 'error'
               })
            }
            // html_table_fields = populate_fields2(data);          
         });
      }     
   });

   $("#alert_time_type").change(function(){
      let html = '';
      $('.alert_time_type_input').empty();
      if($(this).val() == "period"){
         html+= '<div class="row"><label>เลือกหน่วยเวลา</label> &nbsp;&nbsp;<select class="form-control col-md-3" name="alert_time_type_time_type" id="alert_time_type_time_type"><option value="null">เลือกหน่วยเวลา</option><option value="s">วินาที</option><option value="m">นาที</option><option value="h">ชั่วโมง</option><!-- <option value="d">วัน</option> --></select> &nbsp; &nbsp;<label>กรอกจำนวน</label> &nbsp;&nbsp;<input type="number" min="0" name="alert_time_type_value" id="alert_time_type_value" class="form-control col-md-2"></div>';
      }
      else{
         html += '<div class="row"><label>กรอกเวลา</label> &nbsp;&nbsp;<input type="datetime-local" class="form-control col-md-5" name="alert_time_type_value" id="alert_time_type_value"></div>';        
      }
      $('.alert_time_type_input').append(html);
   });

   $("#send_query").click(function(){
      //let func_result = form_is_null();
      $.ajax({
         url: "Http_request/get_condition_builder_form.php",
         method: "POST",
         async: false,
         dataType: "JSON",
         data:$("#condition_builder_form").serialize(), 
         error: function(jqXHR, text, error) {
            Swal.fire({
               title: 'ไม่พบข้อมูล!',
               icon: 'warning'
            })
         }
      })
      .done(function(data) { // response
         console.log(data)

         if (!data.query_data) {
               Swal.fire({
                  title: 'ไม่พบข้อมูล!',
                  icon: 'error'
               })
         } 
         else {
            $(".result_table").empty();

            console.log(data.query_data)

            // if (data.task_type == "WBS_task") {
            //    $(".result_table").html(generate_table_WBS_task(data.query_data));

            //    $('tr.header td span').click(function() {
            //       $(this).parent().find('span').text(function(_, value) { return value == '-' ? '+' : '-' });
            //       $(this).parent().parent().nextUntil('tr.header').slideToggle(50);
            //    });

            //    $('.checkall').click(function() {
            //       if ($(this).is(':checked')) {
            //          $('.check_wbs_row').each(function() {
            //             $(this).attr('checked', true)
            //          });
            //       } 
            //       else {
            //          $('.check_wbs_row').each(function() {
            //             $(this).attr('checked', false)
            //          });
            //       }
            //    });
            // } 
            // else {
            //    $(".result_table").html(generate_table_result(data.query_data));

            //    $("#other_task_check_all").click(function(){
                  
            //       if($(this).is(':checked')){

            //          $('.check_row').each(function(i) {
                         
            //             $('input[value="'+$(this).val()+'"]').prop("checked", true);
            //          });

                    
            //       }
            //       else{
            //          $(this).prop("checked", false);
            //          $('.check_row').each(function(i) {
            //             $('input[value="'+$(this).val()+'"]').prop("checked", false);
            //          });
            //       }
            //    })
            // }
         }
      }); 
   });

  
   $('#add_condition').click(function() { // on click button add main condition
      let html = '';// HTML code input condition 
      if($("#task_id").val() == "null"){
         Swal.fire({
            title: 'กรุณาเลือกหัวข้องานก่อน',
            icon: 'warning'
         })
      }
      else{
         if (i == 1) {// check first row
            html += '<tr name="row' + i + '" id="row' + i + '">';
            html += '<td><input type="hidden" name="condition_type_row[]" class="condition_type_row" value="main_con"><button data_row_id ="row' + i + '"  type="button" name="remove" class="btn btn-danger btn-sm remove">X</button></td>';
            html += '<td><input class="form-control main_oplist" type="text" name="main_oplist[]" readonly value=""></td>';
            html += '<td><select class="form-control main_fieldlist" name="main_fieldlist[]" data-live-search="true">' + html_table_fields + '</select></td>';
            html += '<td><select class="form-control main_condition_opv" name="main_condition_opv[]" ><option value="=">เท่ากับ</option><option value=">">มากกว่า</option><option value="<">น้อยกว่า</option><option value=">=">มากกว่าหรือเท่ากับ</option><option value="<=">น้อยกว่าหรือเท่ากับ</option></select></td>';
            html += '<td id="selector_field4"><select class="form-control main_valuelist" name="main_valuelist[]" ><option value="null_value">กรุณาเลือก</option><option value="con_value">ค่า</option><option value="con_fields">ฟีลด์</option></select></td>';
            html += '</tr>';
         } 
         else {
            html += '<tr name="row' + i + '" id="row' + i + '">';
            html += '<td><input type="hidden" name="condition_type_row[]" class="condition_type_row" value="main_con"><button data_row_id ="row' + i + '"  type="button" name="remove" class="btn btn-danger btn-sm remove">X</button></td>';
            html += '<td><select class="form-control main_oplist" name="main_oplist[]" > <option value="AND">AND</option> <option value="OR">OR</option></select></td>';
            html += '<td><select class="form-control main_fieldlist" name="main_fieldlist[]"  data-live-search="true">' + html_table_fields + '</select></td>';
            html += '<td><select class="form-control main_condition_opv" name="main_condition_opv[]" ><option value="=">เท่ากับ</option><option value=">">มากกว่า</option><option value="<">น้อยกว่า</option><option value=">=">มากกว่าหรือเท่ากับ</option><option value="<=">น้อยกว่าหรือเท่ากับ</option></select></td>';
            html += '<td id="selector_field4"><select class="form-control main_valuelist" name="main_valuelist[]" ><option value="null_value">กรุณาเลือก</option><option value="con_value">ค่า</option><option value="con_fields">ฟีลด์</option></select></td>';
            html += '</tr>';
         }        
         $('#append_condition').append(html);// append HTML code        
         i++;// increase row index
      }
   })

   $("#add_sub_condition").click(function() {// on click button add sub condition row
      let html = '';// HTML code sub condition 
      if (sub_con_count["sub_con" + i] = undefined) {} else { sub_con_count["sub_con" + i] = 0; } // check object property is undefined
      if (i == 1) {// check first row
         html += '<tr name="row' + i + '" id="row' + i + '" sub_con_row="' + i + '">';
         html += '<td><input type="hidden" name="condition_type_row[]" class="condition_type_row" value="main_row_sub_con"><button data_row_id ="row' + i + '"  type="button" name="remove" class="btn btn-danger btn-sm remove">X</button>     <button data_row_id ="row' + i + '" row_id = "' + i + '"  type="button" name="add_sub_con" class="btn btn-primary btn-sm add_sub_con">+</button></td>';
         html += '<td colspan="1"></td>';
         html += '<td colspan="1"><label>เลือกตัวเชื่อมเงื่อนไข</label></td>';
         html += '<td colspan="1"> <input class="form-control sub_con_optlist" type="text" name="sub_con_optlist[]" readonly value=""></input></td>';
         html += '<td colspan="1"></td>';
         html += '</tr>';
      } 
      else {
         html += '<tr name="row' + i + '" id="row' + i + '" sub_con_row="' + i + '">';
         html += '<td><input type="hidden" name="condition_type_row[]" class="condition_type_row" value="main_row_sub_con"><button data_row_id ="row' + i + '"  type="button" name="remove" class="btn btn-danger btn-sm remove">X</button>     <button data_row_id ="row' + i + '" row_id = "' + i + '"  type="button" name="add_sub_con" class="btn btn-primary btn-sm add_sub_con">+</button></td>';
         html += '<td colspan="1"></td>';
         html += '<td colspan="1"><label>เลือกตัวเชื่อมเงื่อนไข</label></td>';
         html += '<td colspan="1"><select class="form-control sub_con_optlist" name="sub_con_optlist[]" > <option value="AND">AND</option> <option value="OR">OR</option></select></td>';
         html += '<td colspan="1"></td>';
         html += '</tr>';
      }
      $('#append_condition').append(html);// append HTML code
      i++;// increase row index
   });
  
   $(document).on('click', '.add_sub_con', function() {// on click button add sub condition
      let btn_obj = $(this).attr("row_id");// get row id
      let html = '';// HTML code
      sub_con_count["sub_con" + btn_obj]++;// increase row id count condition
      $("#sub_row_data_count").val(JSON.stringify(sub_con_count));// แปลง Object เป็น String
      if (sub_con_count["sub_con" + btn_obj] == 1) {// check sub condition first row
         html += '<tr name="sub_con_row' + i + '_' + sub_con_count["sub_con" + btn_obj] + '" id="sub_con_row' + i + '_' + sub_con_count["sub_con" + btn_obj] + '">';
         html += '<td><input type="hidden" name="condition_type_row[]" class="condition_type_row" value="sub_con"><button data_row_id ="sub_con_row' + i + '_' + sub_con_count["sub_con" + btn_obj] + '"  type="button" name="remove_sub_condition" data_sub_condition="' + btn_obj + '" class="btn btn-warning btn-sm remove_sub_condition">X</button></td>';
         html += '<td><input class="form-control sub_oplist" type="text" name="sub_oplist[]" readonly value=""></td>';
         html += '<td><select class="form-control sub_fieldlist" name="sub_fieldlist[]" data-live-search="true">' + html_table_fields + '</select></td>';
         html += '<td><select class="form-control sub_condition_opv" name="sub_condition_opv[]" ><option value="=">เท่ากับ</option><option value=">">มากกว่า</option><option value="<">น้อยกว่า</option><option value=">=">มากกว่าหรือเท่ากับ</option><option value="<=">น้อยกว่าหรือเท่ากับ</option></select></td>';
         html += '<td id="sub_con_selector_field4"><select class="form-control sub_valuelist" name="sub_valuelist[]" ><option value="null_value">กรุณาเลือก</option><option value="con_value">ค่า</option><option value="con_fields">ฟีลด์</option></select></td>';
         html += '</tr>';
      } 
      else {
         html += '<tr name="sub_con_row' + i + '_' + sub_con_count["sub_con" + btn_obj] + '" id="sub_con_row' + i + '_' + sub_con_count["sub_con" + btn_obj] + '">';
         html += '<td><input type="hidden" name="condition_type_row[]" class="condition_type_row" value="sub_con"><button data_row_id ="sub_con_row' + i + '_' + sub_con_count["sub_con" + btn_obj] + '"  type="button" name="remove_sub_condition" data_sub_condition="' + btn_obj + '" class="btn btn-warning btn-sm remove_sub_condition">X</button></td>';
         html += '<td><select class="form-control sub_oplist" name="sub_oplist[]" > <option value="AND">AND</option> <option value="OR">OR</option></select></td>';
         html += '<td><select class="form-control sub_fieldlist" name="sub_fieldlist[]"  data-live-search="true">' + html_table_fields + '</select></td>';
         html += '<td><select class="form-control sub_condition_opv" name="sub_condition_opv[]" ><option value="=">เท่ากับ</option><option value=">">มากกว่า</option><option value="<">น้อยกว่า</option><option value=">=">มากกว่าหรือเท่ากับ</option><option value="<=">น้อยกว่าหรือเท่ากับ</option></select></td>';
         html += '<td id="sub_con_selector_field4"><select class="form-control sub_valuelist" name="sub_valuelist[]" ><option value="null_value">กรุณาเลือก</option><option value="con_value">ค่า</option><option value="con_fields">ฟีลด์</option></select></td>';
         html += '</tr>';
      }
      $('#append_condition').append(html);// append HTML code
   });

   $(document).on('click', '.remove', function() { // เมื่อคลิกปุ่ม remove
      $(this).closest('tr').remove(); // ลบรายการที่เลือก (tr)
      i--;
   });

   $(document).on('click', '.remove_sub_condition', function() { // เมื่อคลิกปุ่ม remove
      let btn_obj = $(this);
      let sub_row_id = btn_obj.attr("data_sub_condition");
      sub_con_count["sub_con" + sub_row_id]--;
      $("#sub_row_data_count").val(JSON.stringify(sub_con_count));
      $(this).closest('tr').remove(); // ลบรายการที่เลือก (tr)
   });

   $(document).on('change', '.main_valuelist', function() {// on change fields select box in main condition
      let td_tag_obj = $(this).closest("tr").find("tr td[data_row_id]");// get tr object
      let row_id = td_tag_obj.prevObject[0].id;// get row id 
      let selected_value = $(this).val();// get selected value
      if (selected_value == 'null_value') {// check null value
         Swal.fire({
            title: 'กรุณาเลือกเงื่อนไข!',
            icon: 'warning'
         })
      } 
      else if (selected_value == 'con_value') {
         let html = '';// HTML code
         html += "<input class='form-control main_condition_value_input'  type='text' name='main_condition_value_input[]'   placeholder='กรอกค่า' required/>";
         html += "<input class='form-control main_condition_value_type' type='hidden' name='main_condition_value_type[]'  value='con_value'/>";
         $("tr[id='" + row_id + "'] td[id='selector_field4']").empty();// clear cell in td
         $("tr[id='" + row_id + "'] td[id='selector_field4']").html(html);// show HTML code
      } 
      else if (selected_value == 'con_fields') {
         let html = '';// HTML code
         html += '<select class="form-control main_condition_value_input"  name="main_condition_value_input[]" >' + html_table_fields + '</select>';
         html += "<input class='form-control main_condition_value_type' type='hidden' name='main_condition_value_type[]'  value='con_fields'/>";
         $("tr[id='" + row_id + "'] td[id='selector_field4']").empty();// clear cell in td
         $("tr[id='" + row_id + "'] td[id='selector_field4']").html(html);// show HTML code
      }
   });

   $(document).on('change', '.sub_valuelist', function() {// on change fields select box in sub condition   
      let td_tag_obj = $(this).closest("tr").find("tr td[data_row_id]");// get tr object
      let row_id = td_tag_obj.prevObject[0].id;// get row id
      let selected_value = $(this).val();// get selected value
      if (selected_value == 'null_value') {// check null value
         alert("กรุณาเลือกเงื่อนไข")
      } 
      else if (selected_value == 'con_value') {
         let html = '';
         html += "<input class='form-control sub_condition_value_input'  type='text' name='sub_condition_value_input[]'   placeholder='กรอกค่า'/>";
         html += "<input class='form-control sub_condition_value_type' type='hidden' name='sub_condition_value_type[]'  value='con_value'/>";
         $("tr[id='" + row_id + "'] td[id='sub_con_selector_field4']").empty();
         $("tr[id='" + row_id + "'] td[id='sub_con_selector_field4']").html(html);
      } 
      else if (selected_value == 'con_fields') {
         let html = '';
         html += '<select class="form-control sub_condition_value_input"  name="sub_condition_value_input[]" >' + html_table_fields + '</select>';
         html += "<input class='form-control sub_condition_value_type' type='hidden' name='sub_condition_value_type[]'  value='con_fields'/>";
         $("tr[id='" + row_id + "'] td[id='sub_con_selector_field4']").empty();
         $("tr[id='" + row_id + "'] td[id='sub_con_selector_field4']").html(html);
      }
   });
 
   $("#reset_condition").click(function() {// reset table condition button  
      console.clear;
      i = 1;
      Object.keys(sub_con_count).forEach(function(key) {// clear object
         delete sub_con_count[key];
      })
      $("#append_condition").empty();// clear condition row
      $("#sub_row_data_count").val('');
   });
 
   $("#reset_all").click(function() {//reser all table and condition
      console.clear;
      i = 1;
      Object.keys(sub_con_count).forEach(function(key) {// clear object
         delete sub_con_count[key];
      })
      $("#append_condition").empty();// clear condition row   
      $(".result_table").empty();// clear result table
      $("#sub_row_data_count").val('');
   });

  




});

function form_is_null(){
   let result = new Object();
   if($("#task_id").val() == "null" && $("#token_line_id").val() == null){
      result["error"] = true;
      result['msg'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
   }
   else{
      if($("#alert_time_type").val() == "null_time_type"){
         result["error"] = true;
         result['msg'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
      }
      else{
         if($("#alert_time_type").val() == "period" ){
            if( $("#alert_time_type_time_type").val() == "null"){
               result["error"] = true;
               result['msg'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
            }
            else{
               if($("#alert_time_type_value").val() == "" || $("#alert_time_type_value").val() == "0"){
                  result["error"] = true;
                  result['msg'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
               }
               else{
                  result["error"] = false;
               }              
            }
         }
         else if($("#alert_time_type").val() == "fix" ){
            if($("#alert_time_type_value").val() == ""){
               result["error"] = true;
               result['msg'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
            }
            else{
               result["error"] = false;
            }
         }
      }
   }
   return result;
}

function populate_fields(data){
   let options = '';
   let i = 1;
   Object.keys(data).forEach(function(key) {// loop JSON 
       options += '<option value="' + data[key] + '">' + data[key] + '</option>';
       i++;
   })
   return options;// return HTML code (Select box)
}

function generate_table_WBS_task(data) {
   let row = 1;// row count
   let html = '';
   // table thead
   html += '<table class="table table-sm table-bordered table_wbs"><thead class="text-center bg-primary"><tr><th width="5%">#</th><th width="10%">#</th><th width="13%">ลำดับที่</th><th width="45%">รายการ</th><th width="27%">WBS(จำนวนรายการ)</th></tr></thead>';
   html += '<tbody class="text-center wbs_table_body">'; // tbody
   Object.keys(data).forEach(function(k) {
      if (k == "") {
         html += '<tr style="background-color:lightblue;" class="header" data_wbs="null_value"><td><input class="check_wbs_row" type="checkbox" name="check_wbs_row[]" value="' + k + '"></td><td><span class="btn btn-primary btn-sm">-</span></td><td></td><td></td><td>' + k + '('+ data[k].length+')</td></tr>';
         let i = 1;
         Object.keys(data[k]).forEach(function(sub_loop) {
            html += '<tr>';
            html += '<td><input class="check_wbs_row" type="checkbox" name="check_wbs_row[]" value="' + data[k][sub_loop]["primary_key"]+ '"></td>';
            html += '<td><b>' + i + '</b></td>';
            html += '<td>' + data[k][sub_loop]["ลำดับที่"] + '</td>';
            html += '<td class="text-left">' + data[k][sub_loop]["รายการ"] + '</td>';
            html += '<td>' + data[k][sub_loop]["WBS"] + '</td>';
            html += '</tr>';
            i++;
         });
      } 
      else {
         html += '<tr style="background-color:lightblue;" class="header" data_wbs="' + k + '"><td><input class="check_wbs_row" type="checkbox" name="check_wbs_row[]" value="' + k + '"></td><td><span class="btn btn-primary btn-sm">-</span></td><td></td><td></td><td>' + k + '('+ data[k].length+')</td></tr>';
         let i = 1;
         Object.keys(data[k]).forEach(function(sub_loop) {
            html += '<tr>';
            html += '<td><input class="check_wbs_row" type="checkbox" name="check_wbs_row[]" value="' + data[k][sub_loop]["primary_key"] + '"></td>';
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


function generate_table_result(data) {// generate table result
   // ERROR ไม่สามารถแสดงข้อมูลแบบ Dynamic ได้เนื่องจากกำหนด คอลัมน์แบบตายตัว (Fix) fix ชื่อคอลัมน์ไว้เลยแสดงงานอื่นไม่ได้ 

   let row = 1;// row count
   let html = '';
   // table thead
   html += '<div style="overflow: auto;width:100%;height:70vh;"><table class="table table-sm table-bordered tb-result"><thead class="text-center bg-primary"><tr><th><input type="checkbox" id="other_task_check_all"></th><th>#</th>'; 
   
   Object.keys(data[0]).forEach(function(k){
      if(k != "primary_key"){
         html += '<th>'+k+'</th>';
      }
      
   })


   html += '</tr></thead>';
   
   html += '<tbody>';  
   Object.keys(data).forEach(function(k) {// loop query data
      html += '<tr style="background-color:lightblue;">';
      html += '<td class="text-center"><input type="checkbox" name="check_row[]" class="check_row" value="' + data[k]['primary_key'] + '" /></td>';
      html += '<td class="text-center"><b>' + row + '</b></td>';  
      Object.keys(data[k]).forEach(function(sub_loop){
         if(sub_loop != "primary_key"){
            html += '<td class="text-left">' + data[k][sub_loop] + '</td>';
         }
      });
      html += '</tr>';
      row++;
   });
   html += '</tbody>';// close tag
   html += '</table></div>';
   return html;// return HTML code
}