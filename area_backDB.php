<?php
include "config.php";

if ($_SESSION['user_role_account'] != 1) {
    if (file_exists('../index.php')) echo "<script>window.top.location.href = '../index.php';</script>";
    else if (file_exists('../../index.php')) echo "<script>window.top.location.href = '../../index.php';</script>";
    exit;
}

if (isset($_POST['_SAVE_UPDATE_BTN_'])) {

    $name = mysqli_real_escape_string($connect, $_POST['name']);

    // SAVE NEW CITY
    if ($_POST['_SAVE_UPDATE_BTN_'] == 'Save') {
        $sql = "INSERT INTO area (name) VALUES ('$name')";
        $query = mysqli_query($connect, $sql);

        if ($query) {
            header('Location: area.php');
            exit;
        } else {
            echo "Error while saving data.";
        }
    }

    // UPDATE EXISTING CITY
    else if ($_POST['_SAVE_UPDATE_BTN_'] == 'Update') {
        $area_id = intval($_POST['area_id']);
        $sql = "UPDATE area SET name='$name' WHERE id='$area_id'";
        $query = mysqli_query($connect, $sql);

        if ($query) {
            header('Location: area.php');
            exit;
        } else {
            echo "Error while updating data.";
        }
    }
}

// DELETE CITY (SOFT DELETE)
if (isset($_REQUEST['action']) && $_REQUEST['action'] == '_DELETE_DATA_') {
    if (isset($_SERVER['HTTP_REFERER'])) {
        $area_id = intval($_GET['area_id']);
        if ($area_id) {
            $sql = "UPDATE area SET is_canceled_row=1 WHERE id='$area_id'";
            $del = mysqli_query($connect, $sql);
            if ($del) {
                header('Location: area.php');
                exit;
            } else {
                echo "Error while deleting record.";
            }
        }
    } else {
        die("Invalid request");
    }
}
?>

