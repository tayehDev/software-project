<?php
include "config.php";

$jsonBack = array();

if ($_SESSION['user_role_account'] != 1) {
    $jsonBack["status"] = "error";
    $jsonBack["contentMsg"] = "You do not have permission to perform this action.";
    echo json_encode($jsonBack);
    exit();
}

//---------------------------------------------------------------
// Function for image upload
//---------------------------------------------------------------
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

//---------------------------------------------------------------
// Cancel (delete) user row
//---------------------------------------------------------------
if ($_REQUEST['action'] == 'cancel_row') {
    $row_id = intval($_REQUEST['row_id']);

    $delete = mysqli_query($connect, "UPDATE `users` SET is_canceled_row=1 WHERE id='$row_id'");
    if (!$delete) {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "An error occurred while deleting the record.";
        echo json_encode($jsonBack);
        exit();
    }

    $jsonBack["status"] = "ok";
    $jsonBack["contentMsg"] = "The record has been successfully deleted.";
    echo json_encode($jsonBack);
    exit();
}

//---------------------------------------------------------------
// Add or Edit user
//---------------------------------------------------------------
if ($_POST['action'] == 'add_row' || $_POST['action'] == 'edit_row') {

    $fullname = mysqli_real_escape_string($connect, $_POST['fullname']);
    if (!empty($fullname)) $fullname = "'$fullname'";
    else {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "First name is required.";
        echo json_encode($jsonBack);
        exit();
    }

    $nickname = mysqli_real_escape_string($connect, $_POST['nickname']);
    if (!empty($nickname)) $nickname = "'$nickname'";
    else {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "Last name is required.";
        echo json_encode($jsonBack);
        exit();
    }

    $address = mysqli_real_escape_string($connect, $_POST['address']);
    if (!empty($address)) $address = "'$address'";
    else $address = "NULL";

    $phone = mysqli_real_escape_string($connect, $_POST['phone']);
    if (!empty($phone)) $phone = "'$phone'";
    else {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "Phone number is required.";
        echo json_encode($jsonBack);
        exit();
    }

    $img_dst = "NULL";
    $img_update = "";
    if (!empty($_FILES['img']['name'])) {
        $datafile = array();
        $datafile['element_name'] = 'img';
        $datafile['upload_folder_location'] = "uploads/";
        $img_dst = image_upload($datafile);
        if ($img_dst != -1) {
            $img_dst = "'$img_dst'";
            $img_update = "img=$img_dst, ";
        } else {
            $jsonBack["status"] = "error";
            $jsonBack["contentMsg"] = "Error uploading image.";
            echo json_encode($jsonBack);
            exit();
        }
    }

    $area_id = intval($_POST['area_id']);
    $user_role_account = intval($_POST['user_role_account']);

    $password_update = "";
    $password = $_POST['password'];
    if (!empty($password)) {
        $password = hash("sha256", $password);
        $password_update = "password='$password', ";
    } else if ($_POST['action'] == 'add_row') {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "Password is required.";
        echo json_encode($jsonBack);
        exit();
    }
}

//---------------------------------------------------------------
// Add user
//---------------------------------------------------------------
if ($_POST['action'] == 'add_row') {

    $check = mysqli_query($connect, "SELECT * FROM users WHERE phone=$phone");
    $num = mysqli_num_rows($check);

    if ($num > 0) {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "This phone number is already registered to another user.";
        echo json_encode($jsonBack);
        exit();
    }

    $sql = "
    INSERT INTO users 
        (fullname, nickname, address, phone, img, area_id, user_role_account, password)
    VALUES
        ($fullname, $nickname, $address, $phone, $img_dst, $area_id, $user_role_account, '$password')";

    $insert = mysqli_query($connect, $sql);

    if (!$insert) {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "Error occurred while adding the user.";
        echo json_encode($jsonBack);
        exit();
    }

    $ok_msg = "User has been successfully added.";
}

//---------------------------------------------------------------
// Edit user
//---------------------------------------------------------------
if ($_POST['action'] == 'edit_row') {
    $row_id = intval($_POST['row_id']);

    $check = mysqli_query($connect, "SELECT * FROM users WHERE phone=$phone AND id!=$row_id");
    $num = mysqli_num_rows($check);

    if ($num > 0) {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "This phone number already exists for another user.";
        echo json_encode($jsonBack);
        exit();
    }

    $sql = "UPDATE users SET 
                fullname=$fullname,
                nickname=$nickname,
                address=$address,
                phone=$phone,
                $img_update
                $password_update
                area_id=$area_id,
                user_role_account=$user_role_account
            WHERE id=$row_id";

    $update = mysqli_query($connect, $sql);

    if (!$update) {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "An error occurred while saving changes.";
        echo json_encode($jsonBack);
        exit();
    }

    $ok_msg = "User information has been successfully updated.";
}

//---------------------------------------------------------------

$jsonBack["status"] = "ok";
$jsonBack["contentMsg"] = $ok_msg;
echo json_encode($jsonBack);
exit();

?>

