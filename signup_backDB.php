<?php

ob_start();
session_start();
require "config.php";


$jsonBack = array();

//======================================= image_upload
function image_upload($datafile)
{
    $element_name = $datafile['element_name'];
    $upload_folder_location = $datafile['upload_folder_location'];

    $targetFile = $upload_folder_location . basename($_FILES[$element_name]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $new_name = strval(rand()) . "_image_upload_" . strval(time()) . "." . strval($imageFileType);
    $targetFile = $upload_folder_location . $new_name;

    if (move_uploaded_file($_FILES[$element_name]["tmp_name"], $targetFile)) {
        return $new_name;
    } else {
        return -1;
    }
}
//=======================================

$fullname   = mysqli_real_escape_string($connect, $_POST['fullname']);
$business_name   = mysqli_real_escape_string($connect, $_POST['business_name']);
$phone   = mysqli_real_escape_string($connect, $_POST['phone']);
$address = mysqli_real_escape_string($connect, $_POST['address']);
$password = hash("sha256", $_POST['password']);
$area_id = intval($_POST['area_id']);

// check if phone already exists
$sql = mysqli_query($connect, "SELECT * FROM users WHERE phone='$phone'");
$num = mysqli_num_rows($sql);

if ($num > 0) 
{
    $jsonBack["status"] = "error";
    $jsonBack["contentMsg"] = "This phone number is already registered with another user";
    echo json_encode($jsonBack);
    exit();
} 

$img_dst = "NULL";
if (!empty($_FILES['img']['name'])) 
{
    $datafile = array();
    $datafile['element_name'] = 'img';
    $datafile['upload_folder_location'] = "uploads/";
    $img_dst = image_upload($datafile);

    $img_dst_temp = $img_dst;
    $img_dst = "'$img_dst'";
}

$sql = "INSERT INTO users
(fullname,business_name,password,phone,area_id,address,img) 
VALUES
('$fullname','$business_name','$password','$phone',$area_id,'$address',$img_dst)";

$query = mysqli_query($connect, $sql);

$last_row_id = mysqli_insert_id($connect); // last inserted user id

if(!$query)
{
    $jsonBack["status"] = "error";
    $jsonBack["contentMsg"] = "An error occurred while saving data";
    echo json_encode($jsonBack);
    exit();
}

$jsonBack["status"] = "ok";
$jsonBack["contentMsg"] = "Account created successfully";
echo json_encode($jsonBack);
exit();

ob_end_flush();

?>

