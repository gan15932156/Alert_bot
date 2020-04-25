<?php
   session_start();

   require_once('../config/configDB.php');
   require_once("../idm-service.php");
   $conn = $DBconnect;
   $service = new IDMService();
   $response = array();
   $username = $_POST["user_id"];
   $user_info_result = $service->getEmployeeInfoByUsername("93567815-dfbb-4727-b4da-ce42c046bfca",$username);
   if($user_info_result["Status"] == "Fail"){
      $response['msg'] = "ไม่สามารถติดต่อ IDM service ได้";
      $response['error'] = true;
   }
   else{
      if($user_info_result["GetEmployeeInfoByUsernameResult"]["ResponseMsg"] == "Success"){
         $sql_check_user_id = 'SELECT * FROM `userpea` WHERE username = '.$username;
         $query = mysqli_query($conn,$sql_check_user_id);
         $countrow = mysqli_num_rows($query);
         if($countrow > 0){
            $response['msg'] = "รหัสพนักงานซ้ำในระบบ";
            $response['error'] = true;
         }
         else{
            $user_info = array(
               'username'=>$user_info_result["GetEmployeeInfoByUsernameResult"]["ResultObject"]["Username"],
               'FirstName'=>$user_info_result["GetEmployeeInfoByUsernameResult"]["ResultObject"]["FirstName"],
               'LastName'=>$user_info_result["GetEmployeeInfoByUsernameResult"]["ResultObject"]["LastName"],
               'PositionDescShort'=>$user_info_result["GetEmployeeInfoByUsernameResult"]["ResultObject"]["PositionDescShort"],
               'LevelDesc'=>$user_info_result["GetEmployeeInfoByUsernameResult"]["ResultObject"]["LevelDesc"],
               'DepartmentShort'=>$user_info_result["GetEmployeeInfoByUsernameResult"]["ResultObject"]["DepartmentShortName"]
            );
            $response['user_info'] = $user_info;
            $response['error'] = false;
         }
         
      }
      else{
         $response['msg'] = "ไม่พบรหัสพนักงานในระบบ";
         $response['error'] = true;
      } 
   }
  
   echo json_encode($response);
   // echo json_encode($_POST);
?>