<?php
   session_start();

   require_once('../config/configDB.php');
   require_once("../idm-service.php");
   $conn = $DBconnect;
   $service = new IDMService();
   $response = array();

   $username = $_POST["user_id"];

   $user_info_result = $service->getEmployeeInfoByUsername("93567815-dfbb-4727-b4da-ce42c046bfca",$username);

   if($user_info_result["GetEmployeeInfoByUsernameResult"]["ResponseMsg"] == "Success"){
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
   else{
      $response['error'] = true;
   }
   echo json_encode($response);
   // echo json_encode($_POST);
?>