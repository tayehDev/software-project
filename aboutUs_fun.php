<?php
include "config.php"; 

$jsonBack = array();

if ($_SESSION['user_role_account'] != 1) {
    $jsonBack["status"] = "error";
    $jsonBack["contentMsg"] = "You do not have permissions";
    echo json_encode($jsonBack);
    exit();
}

$jsonBack = array();

$user_id = $_SESSION['user_id'];

// Delete a record
if ($_REQUEST['action'] == 'cancel_row') {
    $row_id = intval($_REQUEST['row_id']);

    $delete = mysqli_query($connect, "DELETE FROM `about_us` WHERE id='$row_id'");
    if (!$delete) {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "An error occurred while deleting the record";
        echo json_encode($jsonBack);
        exit();
    }

    $ok_msg = "Record deleted successfully";
    $jsonBack["status"] = "ok";
    $jsonBack["contentMsg"] = $ok_msg;
    echo json_encode($jsonBack);
    exit();
}

// Add or edit a record
if ($_POST['action'] == 'add_row' || $_POST['action'] == 'edit_row') {
    $topic = mysqli_real_escape_string($connect, $_POST['topic']);
    if (empty($topic)) {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "Title is required";
        echo json_encode($jsonBack);
        exit();
    }
    $topic = "'$topic'";

    $description = mysqli_real_escape_string($connect, $_POST['description']);
    if (empty($description)) {
        $description = "NULL";
    } else {
        $description = "'$description'";
    }
}

// Add a new record
if ($_POST['action'] == 'add_row') {
    $sql = "
        INSERT INTO about_us (topic, description)
        VALUES ($topic, $description)
    ";

    $insert1 = mysqli_query($connect, $sql);

    if (!$insert1) {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "An error occurred while adding the record";
        echo json_encode($jsonBack);
        exit();
    }

    $ok_msg = "Record saved successfully";
}

// Edit an existing record
if ($_POST['action'] == 'edit_row') {
    $row_id = intval($_POST['row_id']);

    $sql = "
        UPDATE about_us SET 
            topic = $topic,
            description = $description
        WHERE id = $row_id
    ";

    $update = mysqli_query($connect, $sql);

    if (!$update) {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "An error occurred while saving the changes";
        echo json_encode($jsonBack);
        exit();
    }

    $ok_msg = "Record updated successfully";
}

$jsonBack["status"] = "ok";
$jsonBack["contentMsg"] = $ok_msg;
echo json_encode($jsonBack);
exit();
?>

