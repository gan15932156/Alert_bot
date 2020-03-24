<?php
function reconstruct_HTTP_post_same_fields($fields,$opv,$value_type,$value){
   $array_key = array_keys(array_count_values($fields)); // get keys field (fields name)
   $result_obj = new stdClass();
   $result_obj2 = new stdClass();
   $result_obj_count_opv = new stdClass();
   $count_row = 0;
   foreach($array_key as $same_field){ // Declear object
      $result_obj->{$same_field} = array();
   }
   foreach($fields as $field){ //push row condition
      array_push($result_obj->{$field},array(
         'field_name' =>$field,
         'opv' =>$opv[$count_row],
         'value_type' => $value_type[$count_row],
         'value' => $value[$count_row]
      ));
      $count_row++;
   }
   foreach($result_obj as $key => $value){ // count same opv is "="
      $result_obj_count_opv->{$key} = 0;
      foreach($result_obj->{$key} as $arr){
         if($arr['opv'] == "="){
            $result_obj_count_opv->{$key}++;
         }
      }
   }
   foreach($result_obj as $key => $value){ // push row is same field and opv is equal "="
      $result_obj2->{$key} = array();
      foreach($result_obj->{$key} as $arr){
         if($result_obj_count_opv->{$key} > 1 && $arr['opv'] == "="){
            array_push($result_obj2->{$key},$arr);
         }
      }
   }
   foreach($result_obj2 as $key => $value){ // remove empty field
      if(empty($result_obj2->{$key})){
         unset($result_obj2->{$key});
      }
   }
   return $result_obj2;
   //return print_r($result_obj2);
}
function parse_condition_to_WHERE_IN($array_condition,$fields_json){
   
   $text = '(';
   $count_key = count(get_object_vars($array_condition)); 
   $outer_loop = 0;

   foreach($array_condition as $key => $value) { // parse row condition to WHERE IN SQL

      if($outer_loop == $count_key-1){
         if(count($array_condition->{$key}) > 1){
            $text.= '('; 
            $array_key = array_keys($fields_json, $key);
            $field_header = $array_key[0]+1;
            if($key == "รายการ"){

               $count_value = count($value);
               $inner_loop = 0;
               $text.='h'.$field_header.'_1 IN(';
               foreach($value as $arr){
                  if($inner_loop == $count_value-1){ // last
                     if($arr['value_type'] == 'con_value'){
                        if(is_numeric($arr['value'])){// ถ้าค่าเป็นตัวเลข
                           $text.= ' '.$arr['value'];
                        }
                        else{
                           $text.= ' '.'"'.$arr['value'].'"';
                        }
                     }
                     else{ //con_field
                        $array_key2 = array_keys($fields_json, $arr['value']);
                        $field_header2 = $array_key2[0]+1;
            
                        if($arr['value'] == "รายการ"){
                           $text.='h'.$field_header2.'_1';
                        }
                        else{
                           $text.='h'.$field_header2;
                        }
                     }   
                  }
                  else{
                     if($arr['value_type'] == 'con_value'){
                        if(is_numeric($arr['value'])){// ถ้าค่าเป็นตัวเลข
                           $text.= ' '.$arr['value'].',';
                        }
                        else{
                           $text.= ' '.'"'.$arr['value'].'",';
                        }
                     }
                     else{ //con_field
                        $array_key2 = array_keys($fields_json, $arr['value']);
                        $field_header2 = $array_key2[0]+1;
            
                        if($arr['value'] == "รายการ"){
                           $text.='h'.$field_header2.'_1,';
                        }
                        else{
                           $text.='h'.$field_header2.',';
                        }
                     }     
                  }
                  $inner_loop++;
               }
               $text.= ')'; 
            }
            else{
               $text.="h".$field_header." IN(";
               $count_value = count($value);
               $inner_loop = 0;

               foreach($value as $arr){
                  if($inner_loop == $count_value-1){ // last
                     if($arr['value_type'] == 'con_value'){
                        if(is_numeric($arr['value'])){// ถ้าค่าเป็นตัวเลข
                           $text.= ' '.$arr['value'];
                        }
                        else{
                           $text.= ' '.'"'.$arr['value'].'"';
                        }
                     }
                     else{
                        $array_key2 = array_keys($fields_json, $arr['value']);
                        $field_header2 = $array_key2[0]+1;
            
                        if($arr['value'] == "รายการ"){
                           $text.='h'.$field_header2.'_1';
                        }
                        else{
                           $text.='h'.$field_header2;
                        }
                     }   
                  }
                  else{
                     if($arr['value_type'] == 'con_value'){
                        if(is_numeric($arr['value'])){// ถ้าค่าเป็นตัวเลข
                           $text.= ' '.$arr['value'].',';
                        }
                        else{
                           $text.= ' '.'"'.$arr['value'].'",';
                        }
                     }
                     else{
                        $array_key2 = array_keys($fields_json, $arr['value']);
                        $field_header2 = $array_key2[0]+1;
            
                        if($arr['value'] == "รายการ"){
                           $text.='h'.$field_header2.'_1,';
                        }
                        else{
                           $text.='h'.$field_header2.',';
                        }
                     }   
                  }
                  $inner_loop++;
               }
               $text.= ')'; 
            }  
            $text.= ')';
         }
         else{
            $text.= ' (1=1)';
         }
      }
      else{
         if(count($array_condition->{$key}) > 1){
            $text.= '('; 
            $array_key = array_keys($fields_json, $key);
            $field_header = $array_key[0]+1;
            if($key == "รายการ"){
               $count_value = count($value);
               $inner_loop = 0;
               $text.='h'.$field_header.'_1 IN(';
              
               foreach($value as $arr){
                  if($inner_loop == $count_value-1){ // last
                     if($arr['value_type'] == 'con_value'){
                        if(is_numeric($arr['value'])){// ถ้าค่าเป็นตัวเลข
                           $text.= ' '.$arr['value'];
                        }
                        else{
                           $text.= ' '.'"'.$arr['value'].'"';
                        }
                     }
                     else{ //con_field
                        $array_key2 = array_keys($fields_json, $arr['value']);
                        $field_header2 = $array_key2[0]+1;
            
                        if($arr['value'] == "รายการ"){
                           $text.='h'.$field_header2.'_1';
                        }
                        else{
                           $text.='h'.$field_header2;
                        }
                     }   
                  }
                  else{
                     if($arr['value_type'] == 'con_value'){
                        if(is_numeric($arr['value'])){// ถ้าค่าเป็นตัวเลข
                           $text.= ' '.$arr['value'].',';
                        }
                        else{
                           $text.= ' '.'"'.$arr['value'].'",';
                        }
                     }
                     else{ //con_field
                        $array_key2 = array_keys($fields_json, $arr['value']);
                        $field_header2 = $array_key2[0]+1;
                        if($arr['value'] == "รายการ"){
                           $text.='h'.$field_header2.'_1,';
                        }
                        else{
                           $text.='h'.$field_header2.',';
                        }
                     }     
                  }
                  $inner_loop++;
               }
               $text.= ')'; 
            }
            else{
               $text.="h".$field_header." IN(";
               $count_value = count($value);
               $inner_loop = 0;
               foreach($value as $arr){
                  if($inner_loop == $count_value-1){ // last
                     if($arr['value_type'] == 'con_value'){
                        if(is_numeric($arr['value'])){// ถ้าค่าเป็นตัวเลข
                           $text.= ' '.$arr['value'];
                        }
                        else{
                           $text.= ' '.'"'.$arr['value'].'"';
                        }
                     }
                     else{
                        $array_key2 = array_keys($fields_json, $arr['value']);
                        $field_header2 = $array_key2[0]+1;
                        if($arr['value'] == "รายการ"){
                           $text.='h'.$field_header2.'_1';
                        }
                        else{
                           $text.='h'.$field_header2;
                        }
                     }   
                  }
                  else{
                     if($arr['value_type'] == 'con_value'){
                        if(is_numeric($arr['value'])){// ถ้าค่าเป็นตัวเลข
                           $text.= ' '.$arr['value'].',';
                        }
                        else{
                           $text.= ' '.'"'.$arr['value'].'",';
                        }
                     }
                     else{
                        $array_key2 = array_keys($fields_json, $arr['value']);
                        $field_header2 = $array_key2[0]+1;          
                        if($arr['value'] == "รายการ"){
                           $text.='h'.$field_header2.'_1,';
                        }
                        else{
                           $text.='h'.$field_header2.',';
                        }
                     }   
                  }
                  $inner_loop++;
               }
               $text.= ')'; 
            }
            $text.= ') AND ';
         }
         else{
            $text.= ' (1=1) AND ';
         }
      }
      $outer_loop++;
   }
   $text.=")";

   return $text;
   //return print_r($array_condition);
}
function parse_condition_to_WHERE_IN_sub($array_condition,$fields_json){
   
   $text = '';
   $count_key = count(get_object_vars($array_condition)); 
   $outer_loop = 0;

   foreach($array_condition as $key => $value) { // parse row condition to WHERE IN SQL
      if($outer_loop == $count_key-1){
         if(count($array_condition->{$key}) > 1){
            $text.= '('; 
            $array_key = array_keys($fields_json, $key);
            $field_header = $array_key[0]+1;
            if($key == "รายการ"){
               $count_value = count($value);
               $inner_loop = 0;
               $text.='h'.$field_header.'_1 IN(';
               foreach($value as $arr){
                  if($inner_loop == $count_value-1){ // last
                     if($arr['value_type'] == 'con_value'){
                        if(is_numeric($arr['value'])){// ถ้าค่าเป็นตัวเลข
                           $text.= ' '.$arr['value'];
                        }
                        else{
                           $text.= ' '.'"'.$arr['value'].'"';
                        }
                     }
                     else{ //con_field
                        $array_key2 = array_keys($fields_json, $arr['value']);
                        $field_header2 = $array_key2[0]+1;
            
                        if($arr['value'] == "รายการ"){
                           $text.='h'.$field_header2.'_1';
                        }
                        else{
                           $text.='h'.$field_header2;
                        }
                     }   
                  }
                  else{
                     if($arr['value_type'] == 'con_value'){
                        if(is_numeric($arr['value'])){// ถ้าค่าเป็นตัวเลข
                           $text.= ' '.$arr['value'].',';
                        }
                        else{
                           $text.= ' '.'"'.$arr['value'].'",';
                        }
                     }
                     else{ //con_field
                        $array_key2 = array_keys($fields_json, $arr['value']);
                        $field_header2 = $array_key2[0]+1;
            
                        if($arr['value'] == "รายการ"){
                           $text.='h'.$field_header2.'_1,';
                        }
                        else{
                           $text.='h'.$field_header2.',';
                        }
                     }     
                  }
                  $inner_loop++;
               }
               $text.= ')'; 
            }
            else{
               $text.="h".$field_header." IN(";
               $count_value = count($value);
               $inner_loop = 0;

               foreach($value as $arr){
                  if($inner_loop == $count_value-1){ // last
                     if($arr['value_type'] == 'con_value'){
                        if(is_numeric($arr['value'])){// ถ้าค่าเป็นตัวเลข
                           $text.= ' '.$arr['value'];
                        }
                        else{
                           $text.= ' '.'"'.$arr['value'].'"';
                        }
                     }
                     else{
                        $array_key2 = array_keys($fields_json, $arr['value']);
                        $field_header2 = $array_key2[0]+1;
            
                        if($arr['value'] == "รายการ"){
                           $text.='h'.$field_header2.'_1';
                        }
                        else{
                           $text.='h'.$field_header2;
                        }
                     }   
                  }
                  else{
                     if($arr['value_type'] == 'con_value'){
                        if(is_numeric($arr['value'])){// ถ้าค่าเป็นตัวเลข
                           $text.= ' '.$arr['value'].',';
                        }
                        else{
                           $text.= ' '.'"'.$arr['value'].'",';
                        }
                     }
                     else{
                        $array_key2 = array_keys($fields_json, $arr['value']);
                        $field_header2 = $array_key2[0]+1;
            
                        if($arr['value'] == "รายการ"){
                           $text.='h'.$field_header2.'_1,';
                        }
                        else{
                           $text.='h'.$field_header2.',';
                        }
                     }   
                  }
                  $inner_loop++;
               }
               $text.= ')'; 
            }  
            $text.= ')';
         }
         else{
            $text.= ' (1=1)';
         }
      }
      else{
         if(count($array_condition->{$key}) > 1){
            $text.= '('; 
            $array_key = array_keys($fields_json, $key);
            $field_header = $array_key[0]+1;
            if($key == "รายการ"){
               $count_value = count($value);
               $inner_loop = 0;
               $text.='h'.$field_header.'_1 IN(';
               foreach($value as $arr){
                  if($inner_loop == $count_value-1){ // last
                     if($arr['value_type'] == 'con_value'){
                        if(is_numeric($arr['value'])){// ถ้าค่าเป็นตัวเลข
                           $text.= ' '.$arr['value'];
                        }
                        else{
                           $text.= ' '.'"'.$arr['value'].'"';
                        }
                     }
                     else{ //con_field
                        $array_key2 = array_keys($fields_json, $arr['value']);
                        $field_header2 = $array_key2[0]+1;
            
                        if($arr['value'] == "รายการ"){
                           $text.='h'.$field_header2.'_1';
                        }
                        else{
                           $text.='h'.$field_header2;
                        }
                     }   
                  }
                  else{
                     if($arr['value_type'] == 'con_value'){
                        if(is_numeric($arr['value'])){// ถ้าค่าเป็นตัวเลข
                           $text.= ' '.$arr['value'].',';
                        }
                        else{
                           $text.= ' '.'"'.$arr['value'].'",';
                        }
                     }
                     else{ //con_field
                        $array_key2 = array_keys($fields_json, $arr['value']);
                        $field_header2 = $array_key2[0]+1;
                        if($arr['value'] == "รายการ"){
                           $text.='h'.$field_header2.'_1,';
                        }
                        else{
                           $text.='h'.$field_header2.',';
                        }
                     }     
                  }
                  $inner_loop++;
               }
               $text.= ')'; 
            }
            else{
               $text.="h".$field_header." IN(";
               $count_value = count($value);
               $inner_loop = 0;
               foreach($value as $arr){
                  if($inner_loop == $count_value-1){ // last
                     if($arr['value_type'] == 'con_value'){
                        if(is_numeric($arr['value'])){// ถ้าค่าเป็นตัวเลข
                           $text.= ' '.$arr['value'];
                        }
                        else{
                           $text.= ' '.'"'.$arr['value'].'"';
                        }
                     }
                     else{
                        $array_key2 = array_keys($fields_json, $arr['value']);
                        $field_header2 = $array_key2[0]+1;
                        if($arr['value'] == "รายการ"){
                           $text.='h'.$field_header2.'_1';
                        }
                        else{
                           $text.='h'.$field_header2;
                        }
                     }   
                  }
                  else{
                     if($arr['value_type'] == 'con_value'){
                        if(is_numeric($arr['value'])){// ถ้าค่าเป็นตัวเลข
                           $text.= ' '.$arr['value'].',';
                        }
                        else{
                           $text.= ' '.'"'.$arr['value'].'",';
                        }
                     }
                     else{
                        $array_key2 = array_keys($fields_json, $arr['value']);
                        $field_header2 = $array_key2[0]+1;          
                        if($arr['value'] == "รายการ"){
                           $text.='h'.$field_header2.'_1,';
                        }
                        else{
                           $text.='h'.$field_header2.',';
                        }
                     }   
                  }
                  $inner_loop++;
               }
               $text.= ')'; 
            }
            $text.= ') AND ';
         }
         else{
            $text.= ' (1=1) AND ';
         }
      }
      $outer_loop++;
   }
 

   return $text;
   //return print_r($array_condition);
}
function count_same_fields_for_WHERE_IN($fields,$opvlist){
   $count_same_fields = array_count_values($fields);
   $fields_arary_keys = array_keys($count_same_fields);
   $same_fields = array();
   foreach($fields_arary_keys as $field){
      if($count_same_fields[$field] > 1){
         array_push($same_fields,$field);
      }
   }
   return $same_fields;
}

function generate_main_sql_condition($conditions,$fields_json,$number_field){// return main sql condition

   $sql = '';

   if(empty($conditions['oplist']) || $number_field == 1){// ตรวจสอบเงื่อนไขแรก จะไม่มี AND และ OR
      $sql.= ' ';
   }
   else{
      $sql.= ' '.$conditions['oplist'];
   }

   if($conditions['fieldlist'] == 'รายการ'){
      $array_key = array_keys($fields_json, "รายการ");
      $field_header = $array_key[0]+1;
      if($conditions['value_type'] == 'con_value'){
         if(is_numeric($conditions['valuelist'])){
            $sql.= ' (h'.$field_header.'_1';
         }
         else{
            $sql.= ' (h'.$field_header.'_2';
         }
      }
      else{
         $sql.= ' (h'.$field_header.'_1';
      }         
   }
   else{
      $array_key = array_keys($fields_json, $conditions['fieldlist']);
      $field_header = $array_key[0]+1;
      $sql.= ' (h'.$field_header;
   }
   $sql.= ' '.$conditions['condition_opv'];
   
   if($conditions['value_type'] == 'con_value'){// ตรวจสอบ ชนิดของค่าเงื่อนไข ถ้าเป็น ตัวเลขหรือตัวอักษร
      if(is_numeric($conditions['valuelist'])){// ถ้าค่าเป็นตัวเลข
         $sql.= ' '.$conditions['valuelist'];
      }
      else{
         $sql.= ' '.'"'.$conditions['valuelist'].'"';
      }
   }
   else{
      if($conditions['valuelist'] == 'รายการ'){// ถ้าค่าของเงื่อนไขเป็นฟีลด์ และมีค่าเป็น h2
         $array_key = array_keys($fields_json, "รายการ");   
         $field_header = $array_key[0]+1;
         $sql.= ' h'.$field_header.'_1';
      }
      else{
         $array_key = array_keys($fields_json, $conditions['valuelist']);
         $field_header = $array_key[0]+1;
         $sql.= ' h'.$field_header;
      }
   }

   if($conditions['fieldlist'] == 'รายการ'){
      $array_key = array_keys($fields_json, "รายการ"); 
      $field_header = $array_key[0]+1;
      if($conditions['value_type'] == 'con_value'){
         if(is_numeric($conditions['valuelist'])){
            $sql.= ' AND h'.$field_header.'_1 != "")';
         }
         else{
            $sql.= ')';
         }
      }
      else{
         $sql.= ' AND h'.$field_header.'_1 != "")';
      }
   }
   else{
      $sql.= ')';
   }
   return $sql;
}

function generate_sub_sql_condition($conditions,$fields_json,$number_condition,$loop_non_same_fields_count){// return sub sql condition
   $sql = '';
   if(empty($conditions['conjection_condition']) || $loop_non_same_fields_count == 1){
      $sql.= ' (';
   }
   else{
      $sql .= ' '.$conditions['conjection_condition'];// ตัวเชื่อมระหว่างเงื่อนไขหลักกับเงื่อนไขรอง
      $sql .= ' (';
   }
  
   foreach($conditions['sub_con'] as $sub_con){// วนลูปตามจำนวนของเงื่อนไขย่อย
      if(empty($sub_con['sub_op_list']) || $number_condition == 1){
         $sql.= '';
      }
      else{
         $sql.= ' '.$sub_con['sub_op_list'];
      }  

      if($sub_con['sub_field_list'] == 'รายการ'){
         $array_key = array_keys($fields_json, "รายการ"); 
         $field_header = $array_key[0]+1;
         if($sub_con['sub_condition_value_type'] == 'con_value'){
            if(is_numeric($sub_con['sub_value_list'])){
               $sql.= ' ( h'.$field_header.'_1';
            }
            else{
               $sql.= ' ( h'.$field_header.'_2';
            }      
         }
         else{
            $sql.= ' ( h'.$field_header.'_1';
         }
      }
      else{
         $array_key = array_keys($fields_json, $sub_con['sub_field_list']);
         $field_header = $array_key[0]+1;
         $sql.= ' ( h'.$field_header;
      }
      $sql.= ' '.$sub_con['sub_condition_opv'];
 
      if($sub_con['sub_condition_value_type'] == 'con_value'){
         if(is_numeric($sub_con['sub_value_list'])){
            $sql.= ' '.$sub_con['sub_value_list'];
         }
         else{
            $sql.= ' '.'"'.$sub_con['sub_value_list'].'"';
         }
      }
      else{
         if($sub_con['sub_value_list'] == 'รายการ'){
            $array_key = array_keys($fields_json, "รายการ");
            $field_header = $array_key[0]+1;
            $sql.= ' h'.$field_header.'_1';
         }
         else{
            $array_key = array_keys($fields_json, $sub_con['sub_value_list']);
            $field_header = $array_key[0]+1;
            $sql.= ' h'.$field_header;
         }
      }

      if($sub_con['sub_field_list'] == 'รายการ'){
         $array_key = array_keys($fields_json, "รายการ");  
         $field_header = $array_key[0]+1;
         if($sub_con['sub_condition_value_type'] == 'con_value'){
            if(is_numeric($sub_con['sub_value_list'])){
               $sql.= ' AND h'.$field_header.'_1 != "" )';
            }
            else{
               $sql.= ' )';
            } 
         }
         else{
            $sql.= ' AND h'.$field_header.'_1 != "" )';
         }
      }
      else{
         $sql.= ' )';
      }
   }
   $sql.= ' )';
   return $sql;
}
function generate_sub_sql_condition2($sub_con,$fields_json,$number_condition){// return sub sql condition
   $sql = '';

   if(empty($sub_con['sub_op_list']) || $number_condition == 1){
      $sql.= '(';
   }
   else{
      $sql.= $sub_con['sub_op_list'].' (';
   }  
 
   if($sub_con['sub_field_list'] == 'รายการ'){
      $array_key = array_keys($fields_json, "รายการ");
      $field_header = $array_key[0]+1;
      if($sub_con['sub_condition_value_type'] == 'con_value'){
         if(is_numeric($sub_con['sub_value_list'])){
            $sql.= 'h'.$field_header.'_1';
         }
         else{
            $sql.= 'h'.$field_header.'_2';
         }
      }
      else{
         $sql.= 'h'.$field_header.'_1';
      }         
   }
   else{
      $array_key = array_keys($fields_json, $sub_con['sub_field_list']);
      $field_header = $array_key[0]+1;
      $sql.= 'h'.$field_header;
   }
   $sql.= ' '.$sub_con['sub_condition_opv'];
   
   if($sub_con['sub_condition_value_type'] == 'con_value'){// ตรวจสอบ ชนิดของค่าเงื่อนไข ถ้าเป็น ตัวเลขหรือตัวอักษร
      if(is_numeric($sub_con['sub_value_list'])){// ถ้าค่าเป็นตัวเลข
         $sql.= ' '.$sub_con['sub_value_list'];
      }
      else{
         $sql.= ' '.'"'.$sub_con['sub_value_list'].'"';
      }
   }
   else{
      if($sub_con['sub_value_list'] == 'รายการ'){// ถ้าค่าของเงื่อนไขเป็นฟีลด์ และมีค่าเป็น h2
         $array_key = array_keys($fields_json, "รายการ");   
         $field_header = $array_key[0]+1;
         $sql.= ' h'.$field_header.'_1';
      }
      else{
         $array_key = array_keys($fields_json, $sub_con['sub_value_list']);
         $field_header = $array_key[0]+1;
         $sql.= ' h'.$field_header;
      }
   }

   if($sub_con['sub_field_list'] == 'รายการ'){
      $array_key = array_keys($fields_json, "รายการ"); 
      $field_header = $array_key[0]+1;
      if($sub_con['sub_condition_value_type'] == 'con_value'){
         if(is_numeric($sub_con['sub_value_list'])){
            $sql.= ' AND h'.$field_header.'_1 != "") ';
         }
         else{
            $sql.= ') ';
         }
      }
      else{
         $sql.= ' AND h'.$field_header.'_1 != "") ';
      }
   }
   else{
      $sql.= ') ';
   }
   

   return $sql;

}
function generate_fields_WBS($fields_json){
   $sql = '';
   $array_key = array_keys($fields_json,"WBS");
   $field_header = $array_key[0]+1;
   $sql = 'count(*) as count,substr(h'.$field_header.',8,3) as WBS';
   return $sql;
}

function generate_fields($fields_json){
   $sql = ' table_name_id ,';
   $count_fields = count($fields_json);
   $i = 0;
   foreach($fields_json as $field){
      $field_header = '';
      if($count_fields-1 == $i){ // last field
         if($field == "รายการ"){
            $array_key = array_keys($fields_json,"รายการ");
            $field_header = $array_key[0]+1;
            $if_sql = ' h'.$field_header.'_1 ,';
            $if_sql .= 'h'.$field_header.'_2';
         }
         else{
            $array_key = array_keys($fields_json,$field);     
            $field_header = $array_key[0]+1;
            $if_sql = ' h'.$field_header;
         }
         $sql .= $if_sql;
      }
      else{
         if($field == "รายการ"){
            $array_key = array_keys($fields_json,"รายการ");      
            $field_header = $array_key[0]+1;
            $if_sql = ' h'.$field_header.'_1 ,';
            $if_sql .= ' h'.$field_header.'_2';
         }
         else{
            $array_key = array_keys($fields_json,$field);      
            $field_header = $array_key[0]+1;
            $if_sql = ' h'.$field_header;
         }
         $sql .= $if_sql.' ,';
      }   
      $i++;
   }   
   return $sql;
}
//$sql.= ' GROUP BY substr(h3,8,3) ORDER BY `h3`  ASC';

function generate_last_sql($fields_json){
   $sql = '';  
   foreach($fields_json as $field){
      $field_header = '';
      if($field == "WBS"){
         $array_key = array_keys($fields_json,"WBS");  
         $field_header = $array_key[0]+1;
         $sql = ' GROUP BY substr(h'.$field_header.',8,3) ORDER BY `h'.$field_header.'`  ASC';
      }
   }
   return $sql;
}

function generate_where_condition_WBS_fields($fields_json){
   $sql = '';
   $str = '';
   $WHERE_IN = new stdClass();
   $loop_non_same_fields_count = 1;
   $sub_row_data_count = json_decode($_POST['sub_row_data_count']);// รับข้อมูล JSON ข้อมูลจำนวนเงื่อนไขของเงื่อนไขย่อย

   if(!empty($_POST['main_fieldlist'])){

      $same_fields = count_same_fields_for_WHERE_IN($_POST['main_fieldlist'],$_POST['main_condition_opv']);
      if(!empty($same_fields)){
         $WHERE_IN = reconstruct_HTTP_post_same_fields($_POST['main_fieldlist'],$_POST['main_condition_opv'],$_POST['main_condition_value_type'],$_POST['main_condition_value_input'],$same_fields);
      }
   }
  
   $loop_count_main_con = 0; // ตัวแปรลูปของเงื่อนไขหลัก
   $loop_count_sub_con = 0; // ตัวแปรลูปของเงื่อนไขย่อย
   $loop_count_sub_con2 = 0;
   $loop_count_all_con = 0; // ตัวแปรลูปของเงื่อนไขทั้งหมด main/sub
   $loop_conjection_condition_count = 0 ; // ตัวแปรลูปของตัวเชื่อมเงื่อนไขย่อย
  

   $sql .= ' WHERE'; 
 
   $array_main_con = array(); // ประกาศตัวแปร array
   $array_sub_con = array(); // ประกาศตัวแปร array
   $array_sub_con_same_fields = array();
   $testvalue = '';
   foreach($_POST['condition_type_row'] as $condition_type){// ลูปเงื่อนไขทั้งหมด       
      if($condition_type == "main_con"){// ถ้าเป็นเงื่อนไขหลัก      
         if(!empty($WHERE_IN->{$_POST['main_fieldlist'][$loop_count_main_con]}) && $_POST['main_condition_opv'][$loop_count_main_con] == "="){
            $loop_count_main_con++;
            $loop_count_all_con++;           
         }
         else{
            $loop_array_main = array(// เก็บค่าเงื่อนไข ชนิด array
               'oplist' =>$_POST['main_oplist'][$loop_count_main_con], 
               'fieldlist' =>$_POST['main_fieldlist'][$loop_count_main_con],
               'condition_opv' =>$_POST['main_condition_opv'][$loop_count_main_con],
               'valuelist' => $_POST['main_condition_value_input'][$loop_count_main_con],
               'value_type' => $_POST['main_condition_value_type'][$loop_count_main_con]
            ); 
            
            $loop_count_main_con++;
            $loop_count_all_con++;           
            $sql .= generate_main_sql_condition($loop_array_main,$fields_json,$loop_non_same_fields_count);// ต่อข้อความด้วยเรียกใช้ฟังก์ชั่น  
            $testvalue.= generate_main_sql_condition($loop_array_main,$fields_json,$loop_non_same_fields_count);// ต่อข้อความด้วยเรียกใช้ฟังก์ชั่น  
            $loop_non_same_fields_count++;
         }   
      }
      else if($condition_type == "main_row_sub_con"){// ถ้าเป็นเงื่อนไขย่อย    

         if(empty($_POST['sub_con_optlist'][$loop_conjection_condition_count]) || $loop_non_same_fields_count == 1){
            $sql.= ' (';
            $str.= ' (';
         }
         else{
            $sql.= ' '.$_POST['sub_con_optlist'][$loop_conjection_condition_count].' (';
            $str.= ' '.$_POST['sub_con_optlist'][$loop_conjection_condition_count].' (';
         }
         
         $loop_sub_non_same_fields_count = 1;
         $loop_array_sub = array();
         $loop_array_sub2 = array();    
         //$loop_array_fields = array();    
         $loop_count_all_con++;     
         $number_of_sub_condition = $sub_row_data_count->{"sub_con".$loop_count_all_con};     
         $loop_array_sub['conjection_condition'] = $_POST['sub_con_optlist'][$loop_conjection_condition_count];      
         
         // Row sub con 
         $sub_fields_list = array();
         $sub_opv = array();
         $sub_value_type = array();
         $sub_value = array();

         $sub_WHERE_IN = new stdClass();
         
         for($i =1 ; $i <= $number_of_sub_condition ; $i++){
            array_push($sub_fields_list,$_POST['sub_fieldlist'][$loop_count_sub_con2]);
            array_push($sub_opv,$_POST['sub_condition_opv'][$loop_count_sub_con2]);
            array_push($sub_value_type,$_POST['sub_condition_value_type'][$loop_count_sub_con2]);
            array_push($sub_value,$_POST['sub_condition_value_input'][$loop_count_sub_con2]);
            $loop_count_sub_con2++;
         }

         if(!empty($sub_fields_list)){

            $sub_con_same_fields = count_same_fields_for_WHERE_IN($sub_fields_list,$sub_opv);
            if(!empty($sub_con_same_fields)){
               $sub_WHERE_IN = reconstruct_HTTP_post_same_fields($sub_fields_list,$sub_opv,$sub_value_type,$sub_value);
            }
         }

         // loop sub condition
         for($i =1 ; $i <= $number_of_sub_condition ; $i++){   
            if(!empty($sub_WHERE_IN->{$_POST['sub_fieldlist'][$loop_count_sub_con]}) && $_POST['sub_condition_opv'][$loop_count_sub_con] == "="){
               $loop_count_sub_con++;             
            }
            else{                 
               $sub_con = array(
                  'sub_op_list' =>$_POST['sub_oplist'][$loop_count_sub_con], 
                  'sub_field_list' =>$_POST['sub_fieldlist'][$loop_count_sub_con],
                  'sub_condition_opv' =>$_POST['sub_condition_opv'][$loop_count_sub_con],
                  'sub_value_list' => $_POST['sub_condition_value_input'][$loop_count_sub_con],
                  'sub_condition_value_type' => $_POST['sub_condition_value_type'][$loop_count_sub_con]
               ); 
               array_push($loop_array_sub2,$sub_con);
                               
               $loop_count_sub_con++;   
               $loop_array_sub['sub_con'] = $loop_array_sub2;   
               $sql.= generate_sub_sql_condition2($sub_con,$fields_json,$loop_sub_non_same_fields_count);
               $str.= generate_sub_sql_condition2($sub_con,$fields_json,$loop_sub_non_same_fields_count);
               $loop_sub_non_same_fields_count++;
            }   
         }

         if(!empty((array) $sub_WHERE_IN) && $loop_sub_non_same_fields_count == 1){

            $sql.= ' ';
            //$WHERE_IN
            $sql.= parse_condition_to_WHERE_IN_sub($sub_WHERE_IN,$fields_json);
            $str.= ' '.parse_condition_to_WHERE_IN_sub($sub_WHERE_IN,$fields_json);
         }
         else if(!empty((array) $sub_WHERE_IN) && $loop_sub_non_same_fields_count != 1){
            $sql.= ' AND ';
            //$WHERE_IN
            $sql.= parse_condition_to_WHERE_IN_sub($sub_WHERE_IN,$fields_json);
            $testvalue.= ' AND '.parse_condition_to_WHERE_IN_sub($sub_WHERE_IN,$fields_json);
         }
   
         $loop_conjection_condition_count++;  
         $sql.= ')';
         $str.= ')';
         $loop_non_same_fields_count++;
      }

   }

   if(!empty((array) $WHERE_IN) && $loop_non_same_fields_count == 1){

      $sql.= ' ';
      //$WHERE_IN
      $sql.= parse_condition_to_WHERE_IN($WHERE_IN,$fields_json);
      $testvalue.= ' '.parse_condition_to_WHERE_IN($WHERE_IN,$fields_json);
   }
   else if(!empty((array) $WHERE_IN) && $loop_non_same_fields_count != 1){
      $sql.= ' AND ';
      //$WHERE_IN
      $sql.= parse_condition_to_WHERE_IN($WHERE_IN,$fields_json);
      $testvalue.= ' AND '.parse_condition_to_WHERE_IN($WHERE_IN,$fields_json);
   }
   return $sql;
}
   session_start();

   require_once('../config/configDB.php');

   $conn = $DBconnect; // ตัวแปรเชื่อมต่อฐานข้อมูล
   $table = $_POST['table_nameeeeeeeee']; // ตัวแปรชื่อตาราง
   $response = array(); // ตัวแปร response ชนิด array
   $fields_json = json_decode($_POST['fields_count']);
   $sql = '';
   $sql_where = '';
   $str = '';
   $WHERE_IN = new stdClass();
   $loop_non_same_fields_count = 1;

   // if(in_array("WBS",$fields_json)){ // ตรวจถ้ามีฟีลด์ชื่อว่า WBS 
   //    $sql .= 'SELECT '.generate_fields_WBS($fields_json) . ' FROM '.'`'.$table.'`';
   //    if(!empty($_POST['condition_type_row'])){ // have condition
   //          $arr_select_sql = array();
   //          $sql_where = generate_where_condition_WBS_fields($fields_json);
   //          $sql_last_sql = generate_last_sql($fields_json);
   //          $sql_get_WBS = $sql.$sql_where.$sql_last_sql;
   //          $result = mysqli_query($conn,$sql_get_WBS);
   //          $array_key = array_keys($fields_json,"WBS");
   //          $field_header = $array_key[0]+1;
   //          if($result){
   //              $rowcount=mysqli_num_rows($result);
   //              if($rowcount > 0){
   //                  while($row = mysqli_fetch_row($result)){       
   //                      $sql_loop = 'SELECT '.generate_fields($fields_json). ' FROM '.'`'.$table.'`'.$sql_where.' AND substr(h'.$field_header.',8,3) = "'.$row[1].'"';   
   //                      array_push($arr_select_sql,$sql_loop);
   //                  }    
   //                  $response['WBS_select_sql'] = $arr_select_sql;             
   //              }
   //              else{
   //                  $response['WBS_select_sql'] = false;
   //              }
   //          }
   //          else{
   //              $response['WBS_select_sql'] = false;
   //          }
   //      }
   //      else{
   //          $sql_get_WBS = $sql.generate_last_sql($fields_json);
   //          $result = mysqli_query($conn,$sql_get_WBS);
   //          $array_key = array_keys($fields_json,"WBS");  
   //          $field_header = $array_key[0]+1;

   //          $arr_select_sql = array();
   //          if($result){
   //              $rowcount=mysqli_num_rows($result);
   //              if($rowcount > 0){
   //                  while($row = mysqli_fetch_row($result)){            
   //                      $sql_loop = 'SELECT '.generate_fields($fields_json). ' FROM '.'`'.$table.'` WHERE substr(h'.$field_header.',8,3) = "'.$row[1].'"';
   //                      array_push($arr_select_sql,$sql_loop);
   //                  }
   //                  $response['WBS_select_sql'] = $arr_select_sql;
   //              }
   //              else{
   //                  $response['WBS_select_sql'] = false;
   //              }
   //          }
   //          else{
   //              $response['WBS_select_sql'] = false;
   //          }     
   //      }
   //      $sql.= $sql_where;     
   //      $response['task_type'] = "WBS_task";
   // }
   // else{ 
   $sql .= 'SELECT '.generate_fields($fields_json) . ' FROM '.'`'.$table.'`'; //generate attributes
   if(!empty($_POST['condition_type_row'])){ // have conditions
      $sub_row_data_count = json_decode($_POST['sub_row_data_count']);// รับข้อมูล JSON ข้อมูลจำนวนเงื่อนไขของเงื่อนไขย่อย
      if(!empty($_POST['main_fieldlist'])){
         $same_fields = count_same_fields_for_WHERE_IN($_POST['main_fieldlist'],$_POST['main_condition_opv']);
         if(!empty($same_fields)){
            $WHERE_IN = reconstruct_HTTP_post_same_fields($_POST['main_fieldlist'],$_POST['main_condition_opv'],$_POST['main_condition_value_type'],$_POST['main_condition_value_input'],$same_fields);
         }
      }
      $loop_count_main_con = 0; // ตัวแปรลูปของเงื่อนไขหลัก
      $loop_count_sub_con = 0; // ตัวแปรลูปของเงื่อนไขย่อย
      $loop_count_sub_con2 = 0;
      $loop_count_all_con = 0; // ตัวแปรลูปของเงื่อนไขทั้งหมด main/sub
      $loop_conjection_condition_count = 0 ; // ตัวแปรลูปของตัวเชื่อมเงื่อนไขย่อย 
      $sql .= ' WHERE';   
      $array_main_con = array(); // ประกาศตัวแปร array
      $array_sub_con = array(); // ประกาศตัวแปร array
      $array_sub_con_same_fields = array();
      $testvalue = '';      
      foreach($_POST['condition_type_row'] as $condition_type){// ลูปเงื่อนไขทั้งหมด       
         if($condition_type == "main_con"){// ถ้าเป็นเงื่อนไขหลัก      
            if(!empty($WHERE_IN->{$_POST['main_fieldlist'][$loop_count_main_con]}) && $_POST['main_condition_opv'][$loop_count_main_con] == "="){
               $loop_count_main_con++;
               $loop_count_all_con++;           
            }
            else{
               $loop_array_main = array(// เก็บค่าเงื่อนไข ชนิด array
                   'oplist' =>$_POST['main_oplist'][$loop_count_main_con], 
                   'fieldlist' =>$_POST['main_fieldlist'][$loop_count_main_con],
                   'condition_opv' =>$_POST['main_condition_opv'][$loop_count_main_con],
                   'valuelist' => $_POST['main_condition_value_input'][$loop_count_main_con],
                   'value_type' => $_POST['main_condition_value_type'][$loop_count_main_con]
               );          
               $loop_count_main_con++;
               $loop_count_all_con++;           
               $sql .= generate_main_sql_condition($loop_array_main,$fields_json,$loop_non_same_fields_count);// ต่อข้อความด้วยเรียกใช้ฟังก์ชั่น  
               $testvalue.= generate_main_sql_condition($loop_array_main,$fields_json,$loop_non_same_fields_count);// ต่อข้อความด้วยเรียกใช้ฟังก์ชั่น  
               $loop_non_same_fields_count++;
            }   
         }
         else if($condition_type == "main_row_sub_con"){// ถ้าเป็นเงื่อนไขย่อย    
            if(empty($_POST['sub_con_optlist'][$loop_conjection_condition_count]) || $loop_non_same_fields_count == 1){
               $sql.= ' (';
               $str.= ' (';
            }
            else{
               $sql.= ' '.$_POST['sub_con_optlist'][$loop_conjection_condition_count].' (';
               $str.= ' '.$_POST['sub_con_optlist'][$loop_conjection_condition_count].' (';
            }   
            $loop_sub_non_same_fields_count = 1;
            $loop_array_sub = array();
            $loop_array_sub2 = array();    
            $loop_count_all_con++;     
            $number_of_sub_condition = $sub_row_data_count->{"sub_con".$loop_count_all_con};     
            $loop_array_sub['conjection_condition'] = $_POST['sub_con_optlist'][$loop_conjection_condition_count];      
            // Row sub con 
            $sub_fields_list = array();
            $sub_opv = array();
            $sub_value_type = array();
            $sub_value = array();
            $sub_WHERE_IN = new stdClass();        
            for($i =1 ; $i <= $number_of_sub_condition ; $i++){
               array_push($sub_fields_list,$_POST['sub_fieldlist'][$loop_count_sub_con2]);
               array_push($sub_opv,$_POST['sub_condition_opv'][$loop_count_sub_con2]);
               array_push($sub_value_type,$_POST['sub_condition_value_type'][$loop_count_sub_con2]);
               array_push($sub_value,$_POST['sub_condition_value_input'][$loop_count_sub_con2]);
               $loop_count_sub_con2++;
            }
            if(!empty($sub_fields_list)){

               $sub_con_same_fields = count_same_fields_for_WHERE_IN($sub_fields_list,$sub_opv);
               if(!empty($sub_con_same_fields)){
                  $sub_WHERE_IN = reconstruct_HTTP_post_same_fields($sub_fields_list,$sub_opv,$sub_value_type,$sub_value);
               }
            }
            // loop sub condition
            for($i =1 ; $i <= $number_of_sub_condition ; $i++){   
               if(!empty($sub_WHERE_IN->{$_POST['sub_fieldlist'][$loop_count_sub_con]}) && $_POST['sub_condition_opv'][$loop_count_sub_con] == "="){
                  $loop_count_sub_con++;             
               }
               else{                 
                  $sub_con = array(
                     'sub_op_list' =>$_POST['sub_oplist'][$loop_count_sub_con], 
                     'sub_field_list' =>$_POST['sub_fieldlist'][$loop_count_sub_con],
                     'sub_condition_opv' =>$_POST['sub_condition_opv'][$loop_count_sub_con],
                     'sub_value_list' => $_POST['sub_condition_value_input'][$loop_count_sub_con],
                     'sub_condition_value_type' => $_POST['sub_condition_value_type'][$loop_count_sub_con]
                  ); 
                  array_push($loop_array_sub2,$sub_con);                         
                  $loop_count_sub_con++;   
                  $loop_array_sub['sub_con'] = $loop_array_sub2;   
                  $sql.= generate_sub_sql_condition2($sub_con,$fields_json,$loop_sub_non_same_fields_count);
                  $str.= generate_sub_sql_condition2($sub_con,$fields_json,$loop_sub_non_same_fields_count);
                  $loop_sub_non_same_fields_count++;
               }   
            }
            if(!empty((array) $sub_WHERE_IN) && $loop_sub_non_same_fields_count == 1){

               $sql.= ' ';
               $sql.= parse_condition_to_WHERE_IN_sub($sub_WHERE_IN,$fields_json);
               $str.= ' '.parse_condition_to_WHERE_IN_sub($sub_WHERE_IN,$fields_json);
            }
            else if(!empty((array) $sub_WHERE_IN) && $loop_sub_non_same_fields_count != 1){
               $sql.= ' AND ';
               $sql.= parse_condition_to_WHERE_IN_sub($sub_WHERE_IN,$fields_json);
               $testvalue.= ' AND '.parse_condition_to_WHERE_IN_sub($sub_WHERE_IN,$fields_json);
            }
            $loop_conjection_condition_count++;  
            $sql.= ')';
            $str.= ')';
            $loop_non_same_fields_count++;
         }
      }
      if(!empty((array) $WHERE_IN) && $loop_non_same_fields_count == 1){
         $sql.= ' ';
         $sql.= parse_condition_to_WHERE_IN($WHERE_IN,$fields_json);
         $testvalue.= ' '.parse_condition_to_WHERE_IN($WHERE_IN,$fields_json);
      }
      else if(!empty((array) $WHERE_IN) && $loop_non_same_fields_count != 1){
         $sql.= ' AND ';
         $sql.= parse_condition_to_WHERE_IN($WHERE_IN,$fields_json);
         $testvalue.= ' AND '.parse_condition_to_WHERE_IN($WHERE_IN,$fields_json);
      }
      $response['WBS_select_sql'] = $sql;
   }
   else{
      $sql.= ' WHERE 1=1';
      $response['WBS_select_sql'] = $sql;
   }
       
        //$response['task_type'] = "other_task";
   //  }
   if(in_array("WBS",$fields_json)){
      $response['task_type'] = 'WBS_task';
   }
   else{
      $response['task_type'] = 'other_task';
   }
   if(!empty($_POST['row_id'])){
      $sql_row_selected = '';
      if($response['task_type'] == "WBS_task"){
         $record_count = count($_POST['row_id']);
         $i = 0 ;  
         $sql_row_selected.= $sql.' AND table_name_id IN (';
         foreach($_POST['row_id'] as $row_id){
            if ($i == $record_count - 1) {  // ตรวจสอบถ้าเป็นข้อมูลรายการสุดท้าย   ///  column แรก fixed เป็นลำดับที่  ///    
               $sql_row_selected .= ' '.$row_id;
            }
            else{
               $sql_row_selected .= ' '.$row_id.' ,'; 
            }
            $i++;
         }
         $sql_row_selected .=')';
         $response['WBS_select_sql'] = $sql_row_selected;       
      }
      else{
         $sql_row_selected .= ' AND table_name_id IN (';
         $record_count = count($_POST['row_id']);
         $i = 0 ;
         foreach($_POST['row_id'] as $row_id){
            if ($i == $record_count - 1) {  // ตรวจสอบถ้าเป็นข้อมูลรายการสุดท้าย   ///  column แรก fixed เป็นลำดับที่  ///
               $sql_row_selected .= ' '.$row_id;
            }
            else{
               $sql_row_selected .= ' '.$row_id.' ,'; 
            }
            $i++;
         }
         $sql_row_selected .=')';
         $sql.=$sql_row_selected;
         $response['WBS_select_sql'] = $sql;
      }  
   }  
  
    // $response['sql'] = $sql;
   $response['HTTP_post'] = $_POST;
   $response['json_count'] = $fields_json;
 
   echo json_encode($response);
?>