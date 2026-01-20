<?php
include "config.php"; 

$jsonBack = array();

if ($_SESSION['user_role_account'] != 1 && $_SESSION['user_role_account'] != 2) {
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

    $delete = mysqli_query($connect, "DELETE FROM `chat_conjunction` WHERE id='$row_id'");
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
    $question = mysqli_real_escape_string($connect, $_POST['question']);
    if (empty($question)) {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "Title is required";
        echo json_encode($jsonBack);
        exit();
    }
    $question = "'$question'";

    $answer = mysqli_real_escape_string($connect, $_POST['answer']);
    if (empty($answer)) {
        $answer = "NULL";
    } else {
        $answer = "'$answer'";
    }
}

// Add a new record
if ($_POST['action'] == 'add_row') {
    $sql = "
        INSERT INTO chat_conjunction (question, answer)
        VALUES ($question, $answer)
    ";
    if($_SESSION['user_role_account'] == 2)$sql = "
        INSERT INTO chat_conjunction (question, answer,auto_chat_seller_user_id)
        VALUES ($question, $answer,$_SESSION[user_id])
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
        UPDATE chat_conjunction SET 
            question = $question,
            answer = $answer
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

