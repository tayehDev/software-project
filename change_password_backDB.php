<?php

require "config.php";

$jsonBack = array();

$phone=mysqli_real_escape_string($connect, $data['phone']);

$sql="SELECT * FROM `users` WHERE 1 AND phone='$phone'";
$query = mysqli_query($GLOBALS['connect'], $sql);
if($row= mysqli_fetch_array($query))
{
  $sql="INSERT INTO `change_password`(`user_id`,user_role_account) VALUES ('$row[id]','$row[user_role_account]')";
  $insert1 = mysqli_query($GLOBALS['connect'], $sql);
  file_put_contents("debug/change_password.debug", $sql);
  //----------------------
  $response=array("status"=>"ok","contentMsg"=>"سيتم التواصل معك لاستعادة كلمة المرور");
  $jsonBack=json_encode($response);
  //----------------------
  print($jsonBack);
  exit;
}
else
{
  //----------------------
  $response=array("status"=>"error","contentMsg"=>"اسم المستخدم غير موجود للمشتركين ");
  $jsonBack=json_encode($response);
  //----------------------
  
  print($jsonBack);
  exit;
}

echo json_encode($jsonBack);

?>

