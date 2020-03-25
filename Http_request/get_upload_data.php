<?php
   function validateDate($date, $format = 'd/m/Y'){// Check date format
      $d = DateTime::createFromFormat($format, $date);
      return $d && $d->format($format) === $date;
   }

   // convert price to numberic
   // REF. https://stackoverflow.com/questions/4982291/how-to-check-if-an-entered-value-is-currency
   
   function convertToValidPrice($price) {
      $price = str_replace(['-', ',', '$', ' '], '', $price);
      if(!is_numeric($price)) {$price = null;} 
      else {
         if(strpos($price, '.') !== false) {
            $dollarExplode = explode('.', $price);
            $dollar = $dollarExplode[0];
            $cents = $dollarExplode[1];
            if(strlen($cents) === 0) {$cents = '00';} 
            elseif(strlen($cents) === 1) {$cents = $cents.'0';} 
            elseif(strlen($cents) > 2) {$cents = substr($cents, 0, 2);}
            $price = $dollar.'.'.$cents;
         } 
         else {
            $cents = '';
            $price = $price.'.'.$cents;
         }
      }
      return $price;
   }
   
   function parse_data_type($data){// Check and parse data type
      $result_data ;// Declear result variable
      if($data != NULL || $data != ''){// Check null data
         if(validateDate($data)){ // Check data is Date format
            $datee = str_replace('/', '-', $data );// แทนที่เครื่องหมาย "/" ด้วย "-"
            $date2 = date("Y-m-d", strtotime($datee)); // แปลงรูปแบบของวันที่เป็น ปี-เดือน-วัน
            $result_data = '"'.$date2.'"';// Parse data to String 
         }
         else{
            $validate_price = convertToValidPrice($data);
            if($validate_price == null){
               $result_data = '"'.strval($data).'"';// Parse data to String
            }
            else{
               $result_data = $validate_price;
            }   
         }
      }
      else{
         if(strval($data) == '0'){ // Check data is 0 (String format)
            $result_data = '"'.'0'.'"';// Set result
         }
         else{
            $result_data = '"'.strval($data).'"'; // Parse data to String
         }
      }
      return $result_data;// Return value
   }

   function reConstruct_object_data($obj_data,$connectDB,$task_id){// Restructure object to match Template task 
      $new_obj = new stdClass(); // Declear blank class
      $template_task = array();// Declear array template task
      $sql = 'SELECT * FROM template_tb WHERE task_user_id = '.$task_id;// SQL 
      $query = mysqli_query($connectDB,$sql);// Query
      while($row = mysqli_fetch_row($query)){// Looping
         array_push($template_task,$row[2]);
      }
      foreach($template_task as $task){// Loop reconstruct object (Change fields)
         $new_obj->{$task} = $obj_data->{$task};// Set object is Template task
      }
      return $new_obj;
   }

   function uplaod_data($data,$task_name,$connectDB){// Upload data to database
      $result ;// Declear result variable
      $sql_task_fields = 'SELECT * FROM '.'`'.$task_name.'`';// SQL code
      $query_task_fields = mysqli_query($connectDB,$sql_task_fields);// Query
      $sql_insert = 'INSERT INTO '.'`'.$task_name.'` ('; // SQL code
      $fields_count = mysqli_num_fields($query_task_fields);// Count fields
      for($i =1 ; $i <= $fields_count-1; $i++){// // Loop fields 
         if($fields_count-1 == $i){// Check last field
            $sql_insert .= ' h'.$i.')';
         }
         else{
            $sql_insert .= ' h'.$i.',';
         }
      }
      $sql_insert.= ' VALUES (';// Append SQL code
      $parse_to_array = get_object_vars($data);// แปลง object เป็น array
      $key_obj = array_keys($parse_to_array);// เก็บ keys ของ Object
      $count_row = count($data->{$key_obj[0]});// นับจำนวนรายการ
      for($i = 0 ; $i <= $count_row-1 ; $i++){// Loop row (Record) 
         $sql_loop_insert = $sql_insert;// Set value SQL code
         for($j = 0 ; $j <= count($key_obj)-1 ; $j++){// Loop key object (Column)   
            if($key_obj[$j] == "ลำดับที่"){// Check column is ลำดับที่     
               if($j+1 == count($key_obj)){// Check last column
                  $sql_loop_insert .=' "'.strval($data->{$key_obj[$j]}[$i]).'"';
               }  
               else{
                  $sql_loop_insert .=' "'.strval($data->{$key_obj[$j]}[$i]).'" ,';
               }
            }
            else{ 
               if($j+1 == count($key_obj)){// Check last column    
                  $sql_loop_insert .=' '.parse_data_type($data->{$key_obj[$j]}[$i]);// Parse data
               }  
               else{
   
                  $sql_loop_insert .=' '.parse_data_type($data->{$key_obj[$j]}[$i]).' ,';
               }
            }
         }
         $sql_loop_insert .= ')';// Close SQL code
         mysqli_query($connectDB,$sql_loop_insert);// Query (Insert)   
         //echo $sql_loop_insert."\n";
      }
   }

   function upload_data_2($data,$task_name,$connectDB){
      $result_query;
      $result ;// Declear result variable 
      $sql_task_fields = 'SELECT * FROM '.'`'.$task_name.'`';// SQL code
      $query_task_fields = mysqli_query($connectDB,$sql_task_fields);// Query
      $sql_insert = 'INSERT INTO '.'`'.$task_name.'` (';
      $fields_count = mysqli_num_fields($query_task_fields);// Count fields
      $i = 1 ;
      while($fields = mysqli_fetch_field($query_task_fields)){
         if($fields->name == 'table_name_id'){
            $i++;
         }
         else{
            if($fields_count == $i){
               $sql_insert .= $fields->name.')';
            }
            else{
               $sql_insert .= $fields->name.',';
            }
            $i++;
         } 
      }
      $sql_insert.= ' VALUES (';// Append SQL code
      $parse_to_array = get_object_vars($data);// แปลง object เป็น array     
      $key_obj = array_keys($parse_to_array);// เก็บ keys ของ Object    
      $count_row = count($data->{$key_obj[0]});// นับจำนวนรายการ   
      for($i = 0 ; $i <= $count_row-1 ; $i++){// Loop row (Record)       
         $sql_loop_insert = '';// Set value SQL code
         $sql_loop_insert = $sql_insert;     
         for($j = 0 ; $j <= count($key_obj)-1 ; $j++){// Loop key object (Column)        
            if($key_obj[$j] == "ลำดับที่"){// Check column is ลำดับที่          
               if($j+1 == count($key_obj)){// Check last column
                  $sql_loop_insert .=' "'.strval($data->{$key_obj[$j]}[$i]).'"';
               }  
               else{
                  $sql_loop_insert .=' "'.strval($data->{$key_obj[$j]}[$i]).'" ,';
               }
            }
            else if($key_obj[$j] == "รายการ"){           
               if($j+1 == count($key_obj)){// Check last column
                  //$sql_loop_insert .=' "'.strval($data->{$key_obj[$j]}[$i]).'"';
                  $numberr = mb_substr($data->{$key_obj[$j]}[$i],0,2,'UTF-8');          
                  if(is_numeric($numberr)){
                     $strr = mb_substr($data->{$key_obj[$j]}[$i],2);
                     $data_push = $strr;    
                     $sql_loop_insert .=' "'.$numberr.'",';
                     $sql_loop_insert .=' "'.$strr.'"';
                  }
                  else{
                     $data_push = $data->{$key_obj[$j]}[$i];                 
                     $sql_loop_insert .=' "",';
                     $sql_loop_insert .=' "'.$data_push.'"';
                  }
               }  
               else{
                  $numberr = mb_substr($data->{$key_obj[$j]}[$i],0,2,'UTF-8');                      
                  if(is_numeric($numberr)){
                     $strr = mb_substr($data->{$key_obj[$j]}[$i],2);
                     $data_push = $strr;             
                     $sql_loop_insert .=' "'.$numberr.'",';
                     $sql_loop_insert .=' "'.$strr.'",';
                  }
                  else{
                     $data_push = $data->{$key_obj[$j]}[$i];                   
                     $sql_loop_insert .=' "",';
                     $sql_loop_insert .=' "'.$data_push.'",';
                  }
                  //$sql_loop_insert .=' "'.strval($data->{$key_obj[$j]}[$i]).'" ,';
               }
            }
            else{          
               if($j+1 == count($key_obj)){// Check last column             
                  $sql_loop_insert .=' '.parse_data_type($data->{$key_obj[$j]}[$i]);// Parse data
               }  
               else{
                  $sql_loop_insert .=' '.parse_data_type($data->{$key_obj[$j]}[$i]).' ,';
               }
            }
         }    
         $sql_loop_insert .= ')'; // Close SQL code
         //echo $sql_loop_insert."\n";
         // Query (Insert)
         $result_query = mysqli_query($connectDB,$sql_loop_insert);
      }
      if($result_query){
         return true;
      }
      else{
         return false;
      }
   }

   require_once('../config/configDB.php');// Call configDB.php 
   require_once("insert_log.php");

   $conn = $DBconnect; // Set variable (Connect database)
   $id_user = $_POST['id_user'];
   $parse_object_data = json_decode($_POST['data']);
   $task_id =  $_POST['task_id'];
   $task_name = $_POST['task_name'];
   $sql_task_user  = 'SELECT * FROM task_user WHERE task_user_id = '.$task_id;
   $query = mysqli_query($conn,$sql_task_user);
   $file_name = $_POST['file_name'];

   insert_log($conn,$id_user,'อัพโหลดไฟล์ '.$file_name.' งาน '.$task_name);

   while($row = mysqli_fetch_row($query)){
      $task_name = $row[2];
   }
   $new_obj = reConstruct_object_data($parse_object_data,$conn,$task_id);// Restructure object 
   if(upload_data_2($new_obj,$task_name,$conn)){// Upload data
      echo "true";
   }
   else{
      echo "false";
   }
   mysqli_close($conn);
