<?php
function parse_condition_to_WHERE_IN($array_condition,$fields_json){
   // AND h1 IN ()

   // $array_key = array_keys($fields_json, "รายการ");
   // $field_header = $array_key[0]+1;
 
   // [field_name] => ID
   // [value_type] => con_value
   // [valuelist] => ฟหก

   $array_same_fields = array();
   $array_same_fields_count = array();

   $array_count_same_fields = array();

   $text = "";
   
   foreach($array_condition as $condition){

      // $array_key = array_keys($fields_json,$condition['field_name']);
      // $field_header = $array_key[0]+1;

      if($condition['field_name'] == "รายการ"){

      }
      else{
         $field_name = $condition['field_name'];
      }
     
      array_push($array_same_fields,$field_name);
   }

   $array_count_same_fields = array_count_values($array_same_fields);
   $array_count_same_fields_keys = array_keys($array_count_same_fields);

   $count_same_condition = 1;

   $obj_same_fields = new stdClass();

   foreach($array_count_same_fields_keys as $same_field){
      $obj_same_fields->{$same_field} = array();
      $i = 1;
      $count = $array_count_same_fields[$same_field];

      for($i ; $i <= $count ; $i++){   
         if($array_condition[$count_same_condition-1]['field_name'] == $same_field){
            $obj_array = array(
               'value_type'=>$array_condition[$count_same_condition-1]['value_type'],
               'value'=>$array_condition[$count_same_condition-1]['valuelist']
            );
            array_push($obj_same_fields->{$same_field},$obj_array);
         } 
         $count_same_condition++;
      }
   }
   
   foreach($obj_same_fields as $key => $value) {
      
   }
   return /*print_r($obj_same_fields)*/$text;

}
function count_same_fields_for_WHERE_IN($fields){

   $count_same_fields = array_count_values($fields);
   $fields_arary_keys = array_keys($count_same_fields);
   $same_fields = array();

   foreach($fields_arary_keys as $field){
      if($count_same_fields[$field] > 1){
         // array_push($same_fields,array(
         //    'field_name'=>$field,
         //    'fields_count'=>$count_same_fields[$field]
         // ));
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
   
   $sub_row_data_count = json_decode($_POST['sub_row_data_count']);// รับข้อมูล JSON ข้อมูลจำนวนเงื่อนไขของเงื่อนไขย่อย
   $loop_count_main_con = 0; // ตัวแปรลูปของเงื่อนไขหลัก
   $loop_count_sub_con = 0; // ตัวแปรลูปของเงื่อนไขย่อย
   $loop_count_all_con = 0; // ตัวแปรลูปของเงื่อนไขทั้งหมด main/sub
   $loop_conjection_condition_count = 0 ; // ตัวแปรลูปของตัวเชื่อมเงื่อนไขย่อย
   
   $sql .= ' WHERE'; 
   
   $array_main_con = array(); // ประกาศตัวแปร array
   $array_sub_con = array(); // ประกาศตัวแปร array

   foreach($_POST['condition_type_row'] as $condition_type){// ลูปเงื่อนไขทั้งหมด 
      if($condition_type == "main_con"){// ถ้าเป็นเงื่อนไขหลัก
         $loop_array_main = array(// เก็บค่าเงื่อนไข ชนิด array
            'oplist' =>$_POST['main_oplist'][$loop_count_main_con], 
            'fieldlist' =>$_POST['main_fieldlist'][$loop_count_main_con],
            'condition_opv' =>$_POST['main_condition_opv'][$loop_count_main_con],
            'valuelist' => $_POST['main_condition_value_input'][$loop_count_main_con],
            'value_type' => $_POST['main_condition_value_type'][$loop_count_main_con]
         );
         $loop_count_main_con++;
         $loop_count_all_con++;

         $sql .= generate_main_sql_condition($loop_array_main,$fields_json);// ต่อข้อความด้วยเรียกใช้ฟังก์ชั่น
      }
      else if($condition_type == "main_row_sub_con"){
         $loop_array_sub = array();
         $loop_array_sub2 = array();
         $loop_count_all_con++;
         $number_of_sub_condition = $sub_row_data_count->{"sub_con".$loop_count_all_con};
         $loop_array_sub['conjection_condition'] = $_POST['sub_con_optlist'][$loop_conjection_condition_count];
         for( $i =1 ; $i <= $number_of_sub_condition ; $i++){
            $sub_con = array(
               'sub_op_list' =>$_POST['sub_oplist'][$loop_count_sub_con], 
               'sub_field_list' =>$_POST['sub_fieldlist'][$loop_count_sub_con],
               'sub_condition_opv' =>$_POST['sub_condition_opv'][$loop_count_sub_con],
               'sub_value_list' => $_POST['sub_condition_value_input'][$loop_count_sub_con],
               'sub_condition_value_type' => $_POST['sub_condition_value_type'][$loop_count_sub_con]
            );
            $loop_count_sub_con++;
            array_push($loop_array_sub2,$sub_con);
         }
         $loop_conjection_condition_count++;
         $loop_array_sub['sub_con'] = $loop_array_sub2;
         $sql.= generate_sub_sql_condition($loop_array_sub,$fields_json);
      }
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
   
   if(in_array("WBS",$fields_json)){ // ตรวจถ้ามีฟีลด์ชื่อว่า WBS 
      $sql .= 'SELECT '.generate_fields_WBS($fields_json) . ' FROM '.'`'.$table.'`';
      if(!empty($_POST['condition_type_row'])){ 
         $sql_where = generate_where_condition_WBS_fields($fields_json);
         $sql_last_sql = generate_last_sql($fields_json);
         $sql_get_WBS = $sql.$sql_where.$sql_last_sql;
         $result = mysqli_query($conn,$sql_get_WBS);
         $query_data = new stdClass();
         $array_key = array_keys($fields_json,"WBS");
         $field_header = $array_key[0]+1;
         if($result){
            $rowcount=mysqli_num_rows($result);
            if($rowcount > 0){
               while($row = mysqli_fetch_row($result)){       
                  $sql_loop = 'SELECT '.generate_fields($fields_json). ' FROM '.'`'.$table.'`'.$sql_where.' AND substr(h'.$field_header.',8,3) = "'.$row[1].'"';   
                  $result2 = mysqli_query($conn,$sql_loop);                 
                  $query_data->{$row[1]} = array();   
                  while($row2 = mysqli_fetch_row($result2)){   
                     $i = 0 ;  
                     $row_data = new stdClass();
                     $row_data->{'primary_key'} = $row2[$i];
                     foreach($fields_json as $field){   
                        $i++;   
                        if($field == "รายการ"){  
                           $row_data->{$field} = $row2[$i].$row2[$i+1]; 
                           $i++;
                        }
                        else{
                           $row_data->{$field} = $row2[$i];
                        }     
                     }
                     array_push($query_data->{$row[1]},$row_data);   
                  }  
                  $response['inner_loop_sql'] = $sql_loop;
               }                 
               $response['query_data'] = $query_data;
            }
            else{
               $response['query_data'] = false;
            }
         }
         else{
            $response['query_data'] = false;
         }
      }
      else{
         $sql_get_WBS = $sql.generate_last_sql($fields_json);
         $result = mysqli_query($conn,$sql_get_WBS);
         $query_data = new stdClass();
         $array_key = array_keys($fields_json,"WBS");  
         $field_header = $array_key[0]+1;
         if($result){
            $rowcount=mysqli_num_rows($result);
            if($rowcount > 0){
               while($row = mysqli_fetch_row($result)){            
                  $sql_loop = 'SELECT '.generate_fields($fields_json). ' FROM '.'`'.$table.'` WHERE substr(h'.$field_header.',8,3) = "'.$row[1].'"';
                  $result2 = mysqli_query($conn,$sql_loop);                 
                  $query_data->{$row[1]} = array();   
                  while($row2 = mysqli_fetch_row($result2)){  
                     $i = 0 ;  
                     $row_data = new stdClass();     
                     $row_data->{'primary_key'} = $row2[$i];     
                     foreach($fields_json as $field){  
                        $i++;  
                        if($field == "รายการ"){  
                           $row_data->{$field} = $row2[$i].$row2[$i+1]; 
                           $i++;
                        }
                        else{
                           $row_data->{$field} = $row2[$i];
                        }                            
                     }
                     array_push($query_data->{$row[1]},$row_data);  
                  }
                  $response['inner_loop_sql'] = $sql_loop;
               }
               $response['query_data'] = $query_data;
            }
            else{
               $response['query_data'] = false;
            }
         }
         else{
            $response['query_data'] = false;
         }     
      }
      $sql.= $sql_where;     
      $response['task_type'] = "WBS_task";
   }
   else{ // have conditions
      $sql .= 'SELECT '.generate_fields($fields_json) . ' FROM '.'`'.$table.'`'; //generate attributes
      if(!empty($_POST['condition_type_row'])){ 
         $sub_row_data_count = json_decode($_POST['sub_row_data_count']);// รับข้อมูล JSON ข้อมูลจำนวนเงื่อนไขของเงื่อนไขย่อย

         if(!empty($_POST['main_fieldlist'])){
            $same_fields = count_same_fields_for_WHERE_IN($_POST['main_fieldlist']);
            $same_fields_WHERE_IN = array();
         }
        

         $loop_count_main_con = 0; // ตัวแปรลูปของเงื่อนไขหลัก
         $loop_count_sub_con = 0; // ตัวแปรลูปของเงื่อนไขย่อย
         $loop_count_sub_con2 = 0;
         $loop_count_all_con = 0; // ตัวแปรลูปของเงื่อนไขทั้งหมด main/sub
         $loop_conjection_condition_count = 0 ; // ตัวแปรลูปของตัวเชื่อมเงื่อนไขย่อย
         $loop_non_same_fields_count = 0;

         $sql .= ' WHERE'; 
       
         $array_main_con = array(); // ประกาศตัวแปร array
         $array_sub_con = array(); // ประกาศตัวแปร array
         $array_sub_con_same_fields = array();
         $testvalue = '';
         foreach($_POST['condition_type_row'] as $condition_type){// ลูปเงื่อนไขทั้งหมด       
            if($condition_type == "main_con"){// ถ้าเป็นเงื่อนไขหลัก      

               if(in_array($_POST['main_fieldlist'][$loop_count_main_con],$same_fields) && $_POST['main_condition_opv'][$loop_count_main_con] == "="){
                  
                  $array_same_fields_to_func = array(
                     'field_name' =>$_POST['main_fieldlist'][$loop_count_main_con],
                     'value_type' => $_POST['main_condition_value_type'][$loop_count_main_con],
                     'valuelist' => $_POST['main_condition_value_input'][$loop_count_main_con]
                  );

                  array_push($same_fields_WHERE_IN,$array_same_fields_to_func);

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
                  $loop_non_same_fields_count++;
                  $loop_count_main_con++;
                  $loop_count_all_con++;           
                  $sql .= generate_main_sql_condition($loop_array_main,$fields_json,$loop_non_same_fields_count);// ต่อข้อความด้วยเรียกใช้ฟังก์ชั่น  
                  $testvalue.= generate_main_sql_condition($loop_array_main,$fields_json,$loop_non_same_fields_count);// ต่อข้อความด้วยเรียกใช้ฟังก์ชั่น  
               }   
            }
            else if($condition_type == "main_row_sub_con"){// ถ้าเป็นเงื่อนไขย่อย    

               if(empty($_POST['sub_con_optlist'][$loop_conjection_condition_count]) || $loop_non_same_fields_count+1 == 1){
                  $sql.= ' (';
               }
               else{
                  $sql.= $_POST['sub_con_optlist'][$loop_conjection_condition_count].' (';
               }
               
               $loop_sub_non_same_fields_count = 0;
               $loop_array_sub = array();
               $loop_array_sub2 = array();    
               $loop_array_fields = array();    
               $loop_count_all_con++;     
               $number_of_sub_condition = $sub_row_data_count->{"sub_con".$loop_count_all_con};     
               $loop_array_sub['conjection_condition'] = $_POST['sub_con_optlist'][$loop_conjection_condition_count];      
               
               for($i =1 ; $i <= $number_of_sub_condition ; $i++){
                  array_push($loop_array_fields,$_POST['sub_fieldlist'][$loop_count_sub_con2]);
                  $loop_count_sub_con2++;
               }

               $sub_con_same_fields = count_same_fields_for_WHERE_IN($loop_array_fields);
               $sub_con_same_fields_WHERE_IN = array();

               for($i =1 ; $i <= $number_of_sub_condition ; $i++){

                  if(in_array($_POST['sub_fieldlist'][$loop_count_sub_con],$sub_con_same_fields) && $_POST['sub_condition_opv'][$loop_count_sub_con] == "="){
                  
                     $array_sub_con_same_fields_to_func = array(
                        'sub_field_name' =>$_POST['main_fieldlist'][$loop_count_main_con],
                        'sub_value_type' => $_POST['main_condition_value_type'][$loop_count_main_con],
                        'sub_valuelist' => $_POST['main_condition_value_input'][$loop_count_main_con]
                     );
   
                     array_push($sub_con_same_fields_WHERE_IN,$array_sub_con_same_fields_to_func);
                     
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
                    
                     $loop_sub_non_same_fields_count++;
                     $loop_non_same_fields_count++;
                     $loop_count_sub_con++;   
                     $loop_array_sub['sub_con'] = $loop_array_sub2;   
                     $sql.= generate_sub_sql_condition2($sub_con,$fields_json,$loop_sub_non_same_fields_count);
                     $testvalue.= generate_sub_sql_condition2($sub_con,$fields_json,$loop_sub_non_same_fields_count);
                  }   
               }
              
               // for( $i =1 ; $i <= $number_of_sub_condition ; $i++){
               //    $sub_con = array(
               //       'sub_op_list' =>$_POST['sub_oplist'][$loop_count_sub_con], 
               //       'sub_field_list' =>$_POST['sub_fieldlist'][$loop_count_sub_con],
               //       'sub_condition_opv' =>$_POST['sub_condition_opv'][$loop_count_sub_con],
               //       'sub_value_list' => $_POST['sub_condition_value_input'][$loop_count_sub_con],
               //       'sub_condition_value_type' => $_POST['sub_condition_value_type'][$loop_count_sub_con]
               //    ); 
               //    $loop_count_sub_con++;   
               //    array_push($loop_array_sub2,$sub_con);
               // }    
               $loop_conjection_condition_count++;  
               $sql.= ')';
            }
         }

         if(!empty($_POST['main_fieldlist'])){
            $sql.= ' AND ';

            $sql.= parse_condition_to_WHERE_IN($same_fields_WHERE_IN,$fields_json);
            $testvalue.= parse_condition_to_WHERE_IN($same_fields_WHERE_IN,$fields_json);
         }
         
         $result = mysqli_query($conn,$sql);
         $query_data = array(); 

         if($result){
            $rowcount=mysqli_num_rows($result);
            if($rowcount > 0){
               while($row = mysqli_fetch_row($result)){ 
                  $i = 0 ;   
                  $row_data = new stdClass();   
                  $row_data->{'primary_key'} = $row[$i];
                  foreach($fields_json as $field){
                     $i++;
                     $row_data->{$field} = $row[$i]; 
                  }     
                  array_push($query_data,$row_data);      
               }      
               $response['query_data'] = $query_data;
            }
            else{
               $response['query_data'] = false;
            }
         }
         else{
            $response['query_data'] = false;
         }
      }
      else{
         $result = mysqli_query($conn,$sql);
         $query_data = array();  
         if($result){
            $rowcount=mysqli_num_rows($result);
            if($rowcount > 0){
               while($row = mysqli_fetch_row($result)){
                  $i = 0 ;     
                  $row_data = new stdClass();     
                  $row_data->{'primary_key'} = $row[$i];   
                  foreach($fields_json as $field){
                     $i++;
                     $row_data->{$field} = $row[$i];
                  }  
                  array_push($query_data,$row_data);              
               }  
               $response['query_data'] = $query_data;
            }
            else{
               $response['query_data'] = false;
            }
         }
         else{  
            $response['query_data'] = false;   
         }
      }      
      $response['task_type'] = "other_task";
   }

   if(in_array("WBS",$fields_json)){
      $sql.= generate_last_sql($fields_json);
   }
   $response['sql'] = $sql;
   $response['HTTP_post'] = $_POST;
   $response['test_result'] = $testvalue;
   echo json_encode($response);
?>