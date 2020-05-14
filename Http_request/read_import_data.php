<?php
   function get_data($file_data,$hrow,$hcol){
      $row_data_obj = new stdClass();// row_data object         
      $header_data = array();// header table    
      for($i = 1 ; $i <= $hrow ; $i++){// Loop data      
         if($i == 1){// First row (header)
            for($j = 0 ; $j <= $hcol ; $j++){       
               $data = $file_data->getCellByColumnAndRow($j, $i)->getValue();// Get data          
               $row_data_obj ->{$data} = array();// Declear object is Array           
               array_push($header_data,$data);// Push data
            }
         }
         else{  // Second row
            for($j = 0 ; $j <= $hcol ; $j++){             
               $data = $file_data->getCellByColumnAndRow($j, $i)->getValue();// get value in cell             
               $data_obj = $file_data->getCellByColumnAndRow($j, $i);// get object in cell
               if($data != NULL || $data != ''){ //ตรวจสอบเมื่อค่าในตัวแปร data ไม่ว่าง
                  if($data == 0){
                     array_push($row_data_obj ->{$header_data[$j]},strval($data));
                  }
                  else{
                     if(PHPExcel_Shared_Date::isDateTime($data_obj)){                                             
                        $date_data = date($format = "d/m/Y", PHPExcel_Shared_Date::ExcelToPHP($data));// parse to Date                 
                       array_push($row_data_obj ->{$header_data[$j]},$date_data);// push data
                     }
                     else{
                        if(is_numeric($data)){
                           $cell_data = floatval($data);
                           array_push($row_data_obj ->{$header_data[$j]},$cell_data);
                        }
                        else{
                           $cell_data = strval($data);
                           array_push($row_data_obj ->{$header_data[$j]},$cell_data);
                        }
                     }
                  }  
               }  
               else{                  
                  if(strval($data) == '0'){// check null value is equal 0
                     array_push($row_data_obj ->{$header_data[$j]},'0');
                  }
                  else{
                     array_push($row_data_obj ->{$header_data[$j]},strval($data));
                  }  
               }
            }
         }
      }
      return $row_data_obj;
   }
   function show_result($data){
      $html = ''; // HTML code     
      $array_parse = get_object_vars($data);// Parse object To Array      
      $key_obj = array_keys($array_parse);// get Array keys     
      $row_count = count($data->{$key_obj[0]});// Count row
      // HTML code
      $html.= '<table class="table table-sm table-bordered" id="tb_result"><thead class="text-center tb_head">';
      $html.='<tr>';  
      foreach($key_obj as $header){ // Populate Header
         $html.='<th><input class="checkcol cb-element" type="checkbox" id="checkcol" name="checkcol[]" value="'.$header.'">   '.$header.'</th>';
      }
      $html.='</tr></thead><tbody class="table-bordered tb_body">';     
      for($i = 0 ; $i <= $row_count-1 ; $i++){// Populate data
         $html.='<tr>';
         for($j = 0 ; $j <= count($key_obj)-1 ; $j++){
            $html.='<td class="'.$key_obj[$j].'" id="dataexcelpop">'.$data->{$key_obj[$j]}[$i].'</td>';
         }
         $html.='</tr>';
      }
      $html.= '<tbody></table>';
      return $html;
   }

   require_once $_SERVER['DOCUMENT_ROOT']."/Alert_bot/lib/PHPExcel-1.8/Classes/PHPExcel.php"; //เรียกใช้ไลบรารี่ PHPExcel
   $response  = array();
   if(!empty($_FILES['file_input'])){
      $file_array = explode(".", $_FILES["file_input"]["name"]);
      if($file_array[1] == "xls" || $file_array[1] == "xlsx" || $file_array[1] == "XLSX" || $file_array[1] == "xlsx"){
         $tmpfname = $_FILES["file_input"]["tmp_name"]; 
         // ตั้งค่าไลบรารี่ PHPExcel
         $inputFileType = PHPExcel_IOFactory::identify($tmpfname);  
         $objReader = PHPExcel_IOFactory::createReader($inputFileType);  
         $objReader->setReadDataOnly(false);  
         $objPHPExcel = $objReader->load($tmpfname);  // โหลดไฟล์
         $objWorksheet = $objPHPExcel->setActiveSheetIndex(0); // เรียกใช้เฉพาะ sheet แรก
        
         //---------------------------------------------------------//
         set_time_limit(600); // เพิ่มเวลาให้สามรถประมวลผลได้นานขึ้น จากปกติ 30 วินาที
         //---------------------------------------------------------//
         
         $highestRow = $objWorksheet->getHighestRow(); // เก็บค่าจำนวนรายการ (Row)
         $highestColumn = $objWorksheet->getHighestColumn(); // เก็บค่าชื่อคอลัมภ์ เช่น 'F'
         $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn)-1;  
         $result_data = get_data($objWorksheet, $highestRow,$highestColumnIndex); // get data from User upload (Raw data)    
         $response['result_table'] = show_result($result_data); // get HTML code result+data (Table)
         $response['raw_data'] = $result_data;
         $response['error'] = false;
      }
      else{
         $response['error'] = true;
      }
   }
   else{
      $response['error'] = true;
   }
   echo json_encode($response);
?>
