 $(document).ready(function(){

   $(".loading_page").hide();

   var raw_data;
   var fullscreen_status = 0; // 0 = close, 1 = open

   $("#file_input").change(function() {
      // Check empty task
      if ($("#task_id").val() == "null") {

         Swal.fire({
            icon: 'warning',
            title: 'กรุณาเลือกงาน...',
            text: 'กรุณาเลือกงานก่อนเลือกไฟล์!',
         })
         $(this).val(null);
      } else {
         $("#upload_file_form").submit();
      }
   });

   // submit form upload
   $('#upload_file_form').on('submit', function(event) {

      event.preventDefault();
 
      $("#checkall").prop("checked", false);
      // $(".loading_page").show();

      // $(".root_div").css({
      //    "position": "absolute", 
      //    "top": "0px",  
      //    "left": "0px",  
      //    "width": "100%",   
      //    "height": "100%",   
      //    "overflow": "hidden"
      // });
      
      

      $.ajax({
         url: "Http_request/read_import_data.php",
         method: "POST",
         data: new FormData(this),
         contentType: false,
         dataType: 'JSON',
         processData: false,
         async: false,
         success: function(data) {
            $('.loading_page').hide();
            $('.root_div').removeAttr('style');
            // raw data
            raw_data = data.raw_data;
            // table html code
            $(".result_file_upload").html(data.result_table);
         }
      });
   })

   // on checkbox click (Table header)
   $(document).on("click", ".checkcol", function() {
      // check header is checked
      if( $(this).is(':checked')) {
            
         if($(this).val() == $('input[class="check_row_template"][value="'+$(this).val()+'"]').val()){
            $(this).parent().css("background-color", "#00FF66 ");
            $('td[class="'+$(this).val()+'"]').css("background-color", "#00FF66 ");
            // set check each field table (Task)
            $('input[value="'+$(this).val()+'"]').prop("checked", true);
         }
         else{
            $('input[value="'+$(this).val()+'"]').prop("checked", false);
            $(this).parent().css("background-color", "#FF3F3F");
            $('td[class="'+$(this).val()+'"]').css("background-color", "#FF3F3F");
         }
         
      }
      else {
         $(this).parent().css("background-color", "");
         $('td[class="'+$(this).val()+'"]').css("background-color", "");
         $('input[value="'+$(this).val()+'"]').prop("checked", false);
      }
   });

   // check all btn
   $("#checkall").click(function(){

      let null_fields = [];

      let missing_fields = '';

      if($(this).is(':checked')){

         $(".check_row_template").each(function(val){
            
            if($('.checkcol[value="'+$(this).val()+'"]').val() == $(this).val()){

               $('.checkcol[value="'+$(this).val()+'"]').parent().css("background-color", "#00FF66 ");

               $('td[class="'+$(this).val()+'"]').css("background-color", "#00FF66 ");

               $('input[value="'+$(this).val()+'"]').prop("checked", true);
            }
            else{
               null_fields.push($(this).val());
            }
           
         });
      }
      else{

         $(".check_row_template").each(function(val){

            $('.checkcol[value="'+$(this).val()+'"]').parent().css("background-color", "");

               $('td[class="'+$(this).val()+'"]').css("background-color", "");

               $('input[value="'+$(this).val()+'"]').prop("checked", false);

               null_fields.pop();
         });
      }
      
      Object.keys(null_fields).forEach(function (item) {

         missing_fields+=null_fields[item]+'\n';
          
      });

      if(missing_fields != ''){
          $("#checkall").prop("checked", false);
         
          Swal.fire({
              icon: 'error',
              title: 'ไม่พบหัวข้อ',
              text: missing_fields,

          })
      }
       
  });

   $("#task_id").change(function(){
      
      // clear checkbox div
      $(".show_template").empty();
      $(".result_file_upload").empty();
      $("#file_input").val(null);
      $("#checkall").prop("checked", false);
      // HTML code
      let html= '';
      if ($(this).val() != "null") {
         $.ajax({
            url: "Http_request/show_template_task.php",
            method: "POST",
            data: {
               task_id: $(this).val()
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
      }
      $(".show_template").html(html);
   });

   $("#btn_fullscreen").click(function(){
      let elent = document.getElementById('upload_result');
      //let elent = document.documentElement;
     // console.log(elent);
      if(fullscreen_status == 0){
         openFullscreen(elent);
         fullscreen_status = 1;
      }
      else{
         closeFullscreen(elent);
         fullscreen_status = 0;
      }
      
   })
});

function openFullscreen(element){
   if (element.requestFullscreen) {
      element.requestFullscreen();
    } else if (element.mozRequestFullScreen) { /* Firefox */
      element.mozRequestFullScreen();
    } else if (element.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
      element.webkitRequestFullscreen();
    } else if (element.msRequestFullscreen) { /* IE/Edge */
      element.msRequestFullscreen();
    }
}

function closeFullscreen(element){
   if (document.exitFullscreen) {
      document.exitFullscreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
      document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) {
      document.msExitFullscreen();
    }
}
function add_token_line(){
   if($("#task_id").val() == "null"){
      Swal.fire({
         icon: 'warning',
         title: 'กรุณาเลือกหัวข้องาน'
      })
   }
   else{
      $.ajax({
         url: "Http_request/get_add_token_line.php", 
         method: "POST",
         async: false,
         datatype:'json',
         data: $('#add_token_line').serialize(),
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
            title: 'สำเร็จ ต้องการเข้าสู่หน้าอัพโหลดไฟล์หรือไม่',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่',
            cancelButtonText: 'ไม่',
         }).then((result) => {
            if (result.value) {
               window.location.href="upload_file_page.php"
            }
         })
      });
   }  
}