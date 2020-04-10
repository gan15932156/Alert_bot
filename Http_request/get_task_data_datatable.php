<?php
    require_once('../config/configDB.php');
    $conn = $DBconnect;
    $task_id = $_POST['task_id'];
    $task_name = $_POST['task_name'];
    $sql_select_templates = 'SELECT * FROM `template_tb` WHERE task_user_id = '.$task_id;
    $query = mysqli_query($conn,$sql_select_templates);
    $fields = array();
    $response = array();
    while($row = mysqli_fetch_array($query)){
        array_push($fields,$row['colum_name']);
    }

    if(in_array("WBS",$fields)){
        $response['result'] = fetch_HTML_code_WBS_task($task_id,$task_name,$fields,$conn);
    }
    else{
        $response['result'] = fetch_HTML_code_other_task($task_id,$task_name,$fields,$conn);
    }

    echo json_encode($response);

    function fetch_HTML_code_WBS_task($task_id,$task_name,$fields,$conn){
        //SELECT count(*) as count,substr(h3,8,3) as WBS FROM `งาน0912354` GROUP BY substr(h3,8,3) ORDER BY `h3`  ASC
        $array_key = array_keys($fields,"WBS");  
        $field_header = $array_key[0]+1;
        $sql='SELECT substr(h'.$field_header.',8,3) as WBS';
        $sql.=' FROM `'.$task_name.'`';
        $sql.='GROUP BY substr(h'.$field_header.',8,3) ORDER BY `h'.$field_header.'`  ASC';
        $query = mysqli_query($conn,$sql);
        $return_data = array();
        if($query){
            $rowcount=mysqli_num_rows($query);
            if($rowcount > 0){
                $WBS = array();
                $result = new stdClass();
                while($row = mysqli_fetch_array($query)){
                    array_push($WBS,$row['WBS']);
                }
                $sql_select_data = 'SELECT '.generate_fields($fields).' FROM `'.$task_name.'` WHERE substr(h'.$field_header.',8,3) = ';
                foreach($WBS as $wbs){
                    $sql2 = $sql_select_data.'"'.$wbs.'"';
                    $query = mysqli_query($conn,$sql2);
                    $result->{$wbs} = array();   
                    while($row = mysqli_fetch_row($query)){
                        $i = 0 ; 
                        $row_data = new stdClass();     
                        $row_data->{'primary_key'} = $row[$i];     
                        foreach($fields as $field){  
                           $i++;  
                           if($field == "รายการ"){  
                              $row_data->{$field} = $row[$i].$row[$i+1]; 
                              $i++;
                           }
                           else{
                              $row_data->{$field} = $row[$i];
                           }                            
                        }
                        array_push($result->{$wbs},$row_data);  
                    }
                }
                $return_data['task_type'] = "WBS_task";
                $return_data['data'] = $result;
            }
            else{
                $return_data['data'] = false;
            }
        }
        else{
            $return_data['data'] = false;
        }
        return $return_data;    
    }
    function fetch_HTML_code_other_task($task_id,$task_name,$fields,$conn){
        $sql = 'SELECT '.generate_fields($fields).' FROM `'.$task_name.'`';
        $return_data = array();
        $query = mysqli_query($conn,$sql);
        if($query){
            $rowcount=mysqli_num_rows($query);
            if($rowcount > 0){
                $result = array();
                while($row = mysqli_fetch_row($query)){
                    $i = 0 ; 
                    $row_data = new stdClass();     
                    $row_data->{'primary_key'} = $row[$i];     
                    foreach($fields as $field){  
                       $i++;  
                       if($field == "รายการ"){  
                          $row_data->{$field} = $row[$i].$row[$i+1]; 
                          $i++;
                       }
                       else{
                          $row_data->{$field} = $row[$i];
                       }                            
                    }
                    array_push($result,$row_data);  
                }
                $return_data['task_type'] = "other_task";
                $return_data['data'] = $result;
            }
            else{
                $return_data['data'] = false; 
            }
        }
        else{
            $return_data['data'] = false;
        }
        return $return_data;
    }
    function generate_fields($fields_json){
        $sql = " table_name_id ,";
        $count_fields = count($fields_json);
        $i = 0;
        foreach($fields_json as $field){
           $field_header = '';
           if($count_fields-1 == $i){ // last field
              if($field == "รายการ"){
                 $array_key = array_keys($fields_json,"รายการ");
                 $field_header = $array_key[0]+1;
                 $if_sql = " h".$field_header."_1 ,";
                 $if_sql .= "h".$field_header."_2";
              }
              else{
                 $array_key = array_keys($fields_json,$field);     
                 $field_header = $array_key[0]+1;
                 $if_sql = " h".$field_header;
              }
              $sql .= $if_sql;
           }
           else{
              if($field == "รายการ"){
                 $array_key = array_keys($fields_json,"รายการ");      
                 $field_header = $array_key[0]+1;
                 $if_sql = " h".$field_header."_1 ,";
                 $if_sql .= " h".$field_header."_2";
              }
              else{
                 $array_key = array_keys($fields_json,$field);      
                 $field_header = $array_key[0]+1;
                 $if_sql = " h".$field_header;
              }
              $sql .= $if_sql." ,";
           }   
           $i++;
        }   
        return $sql;
     }
?>