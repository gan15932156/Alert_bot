 $(document).ready(function(){

   $(".loading_page").hide();
   var raw_data;
   $("#file_input").change(function() {
      if ($("#task_id").val() == "null") {// Check empty task
         Swal.fire({
            icon: 'warning',
            title: 'กรุณาเลือกงาน...',
            text: 'กรุณาเลือกงานก่อนเลือกไฟล์!',
         })
         $(this).val(null);
      } 
      else {
         $("#upload_file_form").submit();
      }
   });

   
   $('#upload_file_form').on('submit', function(event) {// submit form upload
      event.preventDefault();
      $("#checkall").prop("checked", false);
      $(".loading_page").show();
      $(".root_div").css({
         "position": "absolute", 
         "top": "0px",  
         "left": "0px",  
         "width": "100%",   
         "height": "100%",   
         "overflow": "hidden"
      });
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
   $(document).on("click", ".checkcol", function() {// on checkbox click (Table header)
      if( $(this).is(':checked')) {// check header is checked       
         if($(this).val() == $('input[class="check_row_template"][value="'+$(this).val()+'"]').val()){
            $(this).parent().css("background-color", "#00FF66 ");
            $('td[class="'+$(this).val()+'"]').css("background-color", "#00FF66 ");
            $('input[value="'+$(this).val()+'"]').prop("checked", true);// set check each field table (Task)
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
   $("#checkall").click(function(){// check all btn
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
         missing_fields+='<li>'+null_fields[item]+'</li>';      
      });
      if(missing_fields != ''){
         $("#checkall").prop("checked", false);      
         Swal.fire({
            icon: 'error',
            title: 'ไม่พบหัวข้อ',
            html: '<ul style="height:35vh;overflow: auto;" class="text-left">'+missing_fields+'</ul>',
         })
      }  
   });
   $("#task_id").change(function(){ 
      $(".show_template").empty();// clear checkbox div
      $(".result_file_upload").empty();
      $("#file_input").val(null);
      $("#checkall").prop("checked", false);
      let html= '';// HTML code
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
   $("#btn_fullscreen").on('click', function() {
      if(IsFullScreenCurrently())
         GoOutFullscreen();
      else
         GoInFullscreen($(".result_file_upload").get(0));
   });
   $("#btn_submit").click(function() {// submit file (Confirm upload file)
      $(".loading_page").show();
      $(".root_div").css({
         "position": "absolute", 
         "top": "0px",  
         "left": "0px",  
         "width": "100%",   
         "height": "100%",   
         "overflow": "hidden"
      });
      var task_id_input = $("#task_id").val();
      let dadadsadad = new Object();// object data selected fields
      let id = [];// value in checkbox (Field name)
      $('.checkcol:checkbox:checked').each(function(i) {// get all value in checked (Field name)
         id[i] = $(this).val();
      });
      let col_template_count = $('.check_row_template').length; // count checked field template (From database)    
      let col_file_checked_count = $('.check_row_template:checkbox:checked').length;// count checked field Table (From user upload)
      if (col_template_count != col_file_checked_count) {// check if equal ถ้าผู้ใช้เลือกฟีลด์ตรงตามงาน
         $('.loading_page').hide();
         $('.root_div').removeAttr('style');
         Swal.fire({
            icon: 'warning',
            title: 'กรุณาเลือกคอลัมน์ให้ครบถ้วน...'
 
         })
      } 
      else {
         id.forEach(function(key) { // Loop user fields selected
            dadadsadad[key] = raw_data[key];// populate data each field (From user selected)
         })
         let fp = $("#file_input");
         let items = fp[0].files;
         let file_name_input = items[0].name;
         setTimeout(function(){ 
            $.ajax({
               url: "Http_request/get_upload_data.php",
               method: "POST",
               async: false,
               data: {
                  data: JSON.stringify(dadadsadad),
                  task_id: task_id_input,
                  task_name:$("#task_id option:selected").text(),
                  file_name:file_name_input,
                  id_user:$("#id_user").val()
               },
               success: function(data, textStatus, xhr) {  
                  if(data == "true" && xhr.status == 200){
                     $(".show_template").empty();
                     $(".result_file_upload").empty();
                     $("#file_input").val(null);
                     $("#checkall").prop("checked", false);
                     $("#task_id").val("null");
                     $('.loading_page').hide();
                     $('.root_div').removeAttr('style');
                     Swal.fire({
                        icon: 'success',
                        title: 'อัพโหลดเสร็จสิ้น...'
                     })
                  }
                  else{
                     $('.loading_page').hide();
                     $('.root_div').removeAttr('style');
                     Swal.fire({
                        icon: 'error',
                        title: 'ผิดพลาด'
                     })
                  }
               }
            })
         },1000);
      }
   })
});

function GoInFullscreen(element) {
	if(element.requestFullscreen)
		element.requestFullscreen();
	else if(element.mozRequestFullScreen)
		element.mozRequestFullScreen();
	else if(element.webkitRequestFullscreen)
		element.webkitRequestFullscreen();
	else if(element.msRequestFullscreen)
		element.msRequestFullscreen();
}
function GoOutFullscreen() {
	if(document.exitFullscreen)
		document.exitFullscreen();
	else if(document.mozCancelFullScreen)
		document.mozCancelFullScreen();
	else if(document.webkitExitFullscreen)
		document.webkitExitFullscreen();
	else if(document.msExitFullscreen)
		document.msExitFullscreen();
}
function IsFullScreenCurrently() {
	var full_screen_element = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement || null;
	if(full_screen_element === null)
		return false;
	else
		return true;
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