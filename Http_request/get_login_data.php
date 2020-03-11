<?php

   session_start();
   
   require_once('../config/configDB.php');

   $conn = $DBconnect;
   
   

   if(!empty($_POST['username']) && !empty($_POST['password'])){

      $username = $_POST['username'];

      $password = md5($_POST['password']);

      $sql="select * from empolyee where username='$username' and password='$password'";

      // echo $sql;
     
      $Query= mysqli_query($conn,$sql);

      $rows = mysqli_num_rows($Query);

      $result = $Query->fetch_assoc();

      if($rows>0 && $result['leveltest']==0){

         $_SESSION['username']=$username;
			$_SESSION['id_user']=$result['id_user'];
         $_SESSION['name']=$result['name'];
         $_SESSION['leveltest']= $result['leveltest'];

         $filename  = $result["img_em"];
         $destination = "upload/" . $result["img_em"]; 
         $_SESSION['img_em']=  $destination;

         echo '<meta http-equiv="refresh" content= "0; url=index_user.php">';
      }
      else if($rows>0 && $result['leveltest']==1){
         $_SESSION['leveltest']= $result['leveltest'];
			$_SESSION['username']=$username;
			$_SESSION['id_user']=$result['id_user'];
         $_SESSION['name']=$result['name'];
         
		   echo '<meta http-equiv="refresh" content= "0; url=index_admin.php">';
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

?>