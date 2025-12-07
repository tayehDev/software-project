<?php

ob_start();
session_start();
require "config.php";

$jsonBack = array();

//======================================= Image Upload Function
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

// Escape user input
$fullname = mysqli_real_escape_string($connect, $_POST['fullname']);
$nickname = mysqli_real_escape_string($connect, $_POST['nickname']);
$phone = mysqli_real_escape_string($connect, $_POST['phone']);
$address = mysqli_real_escape_string($connect, $_POST['address']);
$latitude = mysqli_real_escape_string($connect, $_POST['latitude']);
$longitude = mysqli_real_escape_string($connect, $_POST['longitude']);

// Hash password if provided
if (!empty($_POST['password'])) {
    $password = hash("sha256", $_POST['password']);
    $password_update = "password='$password', ";
}

$area_id = intval($_POST['area_id']);

// Check if phone number already exists for another user
$sql = mysqli_query($connect, "SELECT * FROM users WHERE phone='$phone' AND id!=$_SESSION[user_id]");
$num = mysqli_num_rows($sql);

if ($num > 0) {
    $jsonBack["status"] = "error";
    $jsonBack["contentMsg"] = "Phone number already exists for another user";
    echo json_encode($jsonBack);
    exit();
}

// Upload image if provided
if (!empty($_FILES['img']['name'])) {
    $datafile = array();
    $datafile['element_name'] = 'img';
    $datafile['upload_folder_location'] = "uploads/";
    $img_dst = image_upload($datafile);
    
    $img_dst_temp = $img_dst;
    $img_dst = "'$img_dst'";
    $img_update = "img=$img_dst, ";
}

// Update user information
$sql = "UPDATE users SET
        fullname='$fullname',
        nickname='$nickname',
        $password_update
        $img_update
        phone='$phone',
        area_id=$area_id,
        address='$address',
        latitude='$latitude',
        longitude='$longitude'
        WHERE id=$_SESSION[user_id]";

$query = mysqli_query($connect, $sql);

// Check for errors
if (!$query) {
    $jsonBack["status"] = "error";
    $jsonBack["contentMsg"] = "An error occurred while saving the data";
    echo json_encode($jsonBack);
    exit();
}

// Return success message
$jsonBack["status"] = "ok";
$jsonBack["contentMsg"] = "Saved successfully";
echo json_encode($jsonBack);
exit();

ob_end_flush();
?>

