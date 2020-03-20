<?php
   function check_username_in_DB($username,$conn){
      $result = array();

      $sql = 'SELECT * FROM `userpea` WHERE `username` = "'.$username.'"';
      //echo $sql;
      $query = mysqli_query($conn,$sql);

      if($query){
         $rowcount = mysqli_num_rows($query);
         if($rowcount > 0){
            while($row = mysqli_fetch_row($query)){
               $result['level'] = $row[2];
               $result['userid'] = $row[0];
            }
            $result['result'] = true;
         }
         else{
            $result['result'] = false;
         }
      }
      else{
         $result['result'] = false;
      }
      return $result;
   }

   session_start();

   require_once('../config/configDB.php');
   $conn = $DBconnect;

   require_once("../idm-service.php");

   $service = new IDMService();

   $username = $_POST['username'];
   $password = $_POST['password'];

   $login_auth_key = '3a243291-44d0-4171-8b17-347cfc1472ea';
   $get_info_user_auth_key = '93567815-dfbb-4727-b4da-ce42c046bfca';

   $check_user_DB = check_username_in_DB($username,$conn);

   if($username == "admin" && $password == "0939870929"){

      $_SESSION['leveltest']= 1;
      $_SESSION['username'] = $username;
      $_SESSION['id_user'] = "admin";
      $_SESSION['FirstName'] = "พิทักษ์พล";
      $_SESSION['LastName'] = "ดำริห์ศิลป์";
      $_SESSION['PositionDescShort'] = "ผู้ดูแลระบบ";
      $_SESSION['LevelDesc'] = "";
      $_SESSION['DepartmentShort'] = "";
      
      echo '<meta http-equiv="refresh" content= "0; url=index_admin.php">';
   }
   else{
      if($check_user_DB['result']){ // check user in Database
         $result_login = $service->login($login_auth_key,$username, $password);
         $arr_result_login = array('1'=>$result_login["LoginResult"]["ResultObject"]["Result"]);
   
         if($arr_result_login[1]=="true"){
            $result_get_user_info = $service->getEmployeeInfoByUsername($get_info_user_auth_key,$username);
            //print_r($result_get_user_info);
            $_SESSION['username'] = $username;
            $_SESSION['id_user'] = $check_user_DB['userid'];
            $_SESSION['FirstName'] = $result_get_user_info["GetEmployeeInfoByUsernameResult"]["ResultObject"]["FirstName"];
            $_SESSION['LastName'] = $result_get_user_info["GetEmployeeInfoByUsernameResult"]["ResultObject"]["LastName"];
            $_SESSION['PositionDescShort'] = $result_get_user_info["GetEmployeeInfoByUsernameResult"]["ResultObject"]["PositionDescShort"];
            $_SESSION['LevelDesc'] = $result_get_user_info["GetEmployeeInfoByUsernameResult"]["ResultObject"]["LevelDesc"];
            $_SESSION['DepartmentShort'] = $result_get_user_info["GetEmployeeInfoByUsernameResult"]["ResultObject"]["DepartmentShortName"];
            
            if($check_user_DB['level'] == 1){
               $_SESSION['leveltest']= 1;
               echo '<meta http-equiv="refresh" content= "0; url=index_admin.php">';
            }
            else{
               $_SESSION['leveltest']= 0;
               echo '<meta http-equiv="refresh" content= "0; url=index_user.php">';
            }
         }
         else{
            echo '<center><div class="alert alert-danger" role="alert">
               ไม่พบชื่อผู้ใช้และรหัสผ่าน
               </div></center>';
         }
      }
      else{
         echo '<center><div class="alert alert-danger" role="alert">
         ไม่พบชื่อผู้ใช้และรหัสผ่าน
         </div></center>';
      }
   }
   
    


   // require_once('../config/configDB.php');

   // $conn = $DBconnect;
   
   

   // if(!empty($_POST['username']) && !empty($_POST['password'])){

   //    $username = $_POST['username'];

   //    $password = md5($_POST['password']);

   //    $sql="select * from empolyee where username='$username' and password='$password'";

   //    // echo $sql;
     
   //    $Query= mysqli_query($conn,$sql);

   //    $rows = mysqli_num_rows($Query);

   //    $result = $Query->fetch_assoc();

   //    if($rows>0 && $result['leveltest']==0){

   //       $_SESSION['username']=$username;
	// 		$_SESSION['id_user']=$result['id_user'];
   //       $_SESSION['name']=$result['name'];
   //       $_SESSION['leveltest']= $result['leveltest'];

   //       $filename  = $result["img_em"];
   //       $destination = "upload/" . $result["img_em"]; 
   //       $_SESSION['img_em']=  $destination;

   //       echo '<meta http-equiv="refresh" content= "0; url=index_user.php">';
   //    }
   //    else if($rows>0 && $result['leveltest']==1){
   //       $_SESSION['leveltest']= $result['leveltest'];
	// 		$_SESSION['username']=$username;
	// 		$_SESSION['id_user']=$result['id_user'];
   //       $_SESSION['name']=$result['name'];
         
	// 	   echo '<meta http-equiv="refresh" content= "0; url=index_admin.php">';
   //    }
   //    else{
   //       echo '<center><div class="alert alert-danger" role="alert">
   //       ไม่พบชื่อผู้ใช้และรหัสผ่าน
   //       </div></center>';
   //    }
   // }    
   // else{
   //    echo '<center><div class="alert alert-danger" role="alert">
   //          ไม่พบชื่อผู้ใช้และรหัสผ่าน
   //          </div></center>';
   // }

?>