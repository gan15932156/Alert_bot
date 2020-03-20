<?php
    session_start();

?>	

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <!-- <script src="/phpexcel/lib/Jquery/jquery.js"></script> -->
    <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <title>Document</title>
</head>
<body>
<?php

require_once("idm-service.php");
$service = new IDMService();
// $userName = $_POST["idem"];
// $password =$_POST["password"];

$userName = "505972";
$password = "Su@48221899"; //Su@48221899

$authenKey = "3a243291-44d0-4171-8b17-347cfc1472ea";

$results1 = $service->login($authenKey,$userName, $password);

$arr= array('1'=>$results1["LoginResult"]["ResultObject"]["Result"]);
 
if($arr[1]=="true"){
    $results2 = $service->getEmployeeInfoByUsername("93567815-dfbb-4727-b4da-ce42c046bfca",$userName);
    $_SESSION['userName'] = $userName;
    $_SESSION['FirstName'] = $results2["GetEmployeeInfoByUsernameResult"]["ResultObject"]["FirstName"];
    $_SESSION['LastName'] = $results2["GetEmployeeInfoByUsernameResult"]["ResultObject"]["LastName"];
    $_SESSION['PositionDescShort'] = $results2["GetEmployeeInfoByUsernameResult"]["ResultObject"]["PositionDescShort"];
    $_SESSION['LevelDesc'] = $results2["GetEmployeeInfoByUsernameResult"]["ResultObject"]["LevelDesc"];
    $_SESSION['DepartmentShort'] = $results2["GetEmployeeInfoByUsernameResult"]["ResultObject"]["DepartmentShort"];
}
else{
    
}

echo $_SESSION['userName'].' '.$_SESSION['FirstName'].' '.$_SESSION['LastName'].' '.$_SESSION['PositionDescShort'];

?>