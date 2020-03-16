$(document).ready(function(){

   // raw data
   var raw_data;

   // จำนวนแถวเงื่อนไข
   var i = 1;

   // webdatarocks pivot table
   var pivot;

   // HTML code select box
   var html_table_fields;

   // ประกาศ object ว่าง
   var sub_con_count = new Object();

   // query result object
   var query_result_object;


   $(".loading_page").hide();

   // task name selectbox onchange set field and set line token selectbox
   $("#task_id").change(function(){

      if($(this).val() == "null"){
         Swal.fire({
            title: 'กรุณาเลือกงาน',
            icon: 'warning'
         })
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
            }

            if(data.fields != "fail"){
               html_table_fields = populate_fields(data.fields);
            }
            else{
               Swal.fire({
                  title: 'ไม่พบข้อมูลงานในฐานข้อมูล!',
                  icon: 'error'
               })
            }

            console.log(html_table_fields);
            // // clear condition row
            // $("#append_condition").empty();
   
            // // clear result table
            // $(".result_table").empty();
   
            // html_table_fields = populate_fields2(data);
   
            // $('#fields_count').val(JSON.stringify(data));
         });
      }
      
   });








});

function populate_fields(data){

   let options = '';

   let i = 1;

   // loop JSON 
   Object.keys(data).forEach(function(key) {

       options += '<option value="' + data[key] + '">' + data[key] + '</option>';

       i++;
   })

   // return HTML code (Select box)
   return options;
}