<?php
include "config.php"; 

$jsonBack = array();

// Check user permissions
if ($_SESSION['user_role_account'] != 1) {
    $jsonBack["status"] = "error";
    $jsonBack["contentMsg"] = "You do not have permission";
    echo json_encode($jsonBack);
    exit();
}

// Function to upload an image
function image_upload($datafile) {
    $element_name = $datafile['element_name'];
    $upload_folder_location = $datafile['upload_folder_location'];

    $targetFile = $upload_folder_location . basename($_FILES[$element_name]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Generate a unique file name
    $new_name = rand() . "_image_upload_" . time() . "." . $imageFileType;
    $targetFile = $upload_folder_location . $new_name;

    if (move_uploaded_file($_FILES[$element_name]["tmp_name"], $targetFile)) {
        return $new_name;
    } else {
        return -1;
    }
}

$jsonBack = array();
$user_id = $_SESSION['user_id'];

// Delete record
if ($_REQUEST['action'] == 'cancel_row') {
    $row_id = intval($_REQUEST['row_id']);

    $delete = mysqli_query($connect, "DELETE FROM `head_img` WHERE id='$row_id'");
    if (!$delete) {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "Error deleting the record";
        echo json_encode($jsonBack);
        exit();
    }

    $ok_msg = "Record deleted successfully";
    $jsonBack["status"] = "ok";
    $jsonBack["contentMsg"] = $ok_msg;
    echo json_encode($jsonBack);
    exit();
}

// Add or edit record
if ($_POST['action'] == 'add_row' || $_POST['action'] == 'edit_row') {
    $topic = mysqli_real_escape_string($connect, $_POST['topic']);
    if (!empty($topic)) {
        $topic = "'$topic'";
    } else {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "Title is required";
        echo json_encode($jsonBack);
        exit();
    }

    $img_dst = "NULL";
    if (!empty($_FILES['img']['name'])) {
        $datafile = array(
            'element_name' => 'img',
            'upload_folder_location' => "uploads/"
        );
        $img_dst = image_upload($datafile);
        $img_dst_temp = $img_dst;
        $img_dst = "'$img_dst'";
    }

    if ($img_dst != "NULL") $img_update = "img=$img_dst,";
}

// Add new record
if ($_POST['action'] == 'add_row') {
    $sql = "
        INSERT INTO head_img (topic, img)
        VALUES ($topic, $img_dst)
    ";

    $insert1 = mysqli_query($connect, $sql);
    if (!$insert1) {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "Error adding the record";
        echo json_encode($jsonBack);
        exit();
    }

    $ok_msg = "Record added successfully";
}

// Edit record
if ($_POST['action'] == 'edit_row') {
    $row_id = intval($_POST['row_id']);

    $sql = "
        UPDATE head_img SET
            $img_update
            topic=$topic
        WHERE id=$row_id
    ";

    $update = mysqli_query($connect, $sql);
    if (!$update) {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "Error saving changes";
        echo json_encode($jsonBack);
        exit();
    }

    $ok_msg = "Record updated successfully";
}

// Return success response
$jsonBack["status"] = "ok";
$jsonBack["contentMsg"] = $ok_msg;
echo json_encode($jsonBack);
exit();
?>

