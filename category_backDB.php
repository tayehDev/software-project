<?php
include "config.php";

if ($_SESSION['user_role_account'] != 1) {
    if (file_exists('../index.php')) echo "<script>window.top.location.href = '../index.php';</script>";
    else if (file_exists('../../index.php')) echo "<script>window.top.location.href = '../../index.php';</script>";
    exit;
}

// Save or Update Category
if (isset($_POST['_SAVE_UPDATE_BTN_'])) {

    $name = mysqli_real_escape_string($connect, $_POST['name']);
    $sort = intval($_POST['sort']);

    if ($_POST['_SAVE_UPDATE_BTN_'] == 'Save') { // Insert new category

        $sql = "INSERT INTO category (name, sort) VALUES ('$name', $sort)";
        $query = mysqli_query($connect, $sql);

        if ($query) {
            header('Location: category.php');
            exit;
        }

    } else if ($_POST['_SAVE_UPDATE_BTN_'] == 'Update') { // Update existing category

        $category_id = intval($_POST['category_id']);
        $sql = "UPDATE category SET name='$name', sort='$sort' WHERE id='$category_id'";
        $query = mysqli_query($connect, $sql);

        if ($query) {
            header('Location: category.php');
            exit;
        } else {
            echo 'Unable to update the record.';
        }
    }
}

// Delete Category (Soft Delete)
if (isset($_REQUEST['action']) && $_REQUEST['action'] == '_DELETE_DATA_') {

    if (isset($_SERVER['HTTP_REFERER'])) {

        $category_id = intval($_GET['category_id']);
        if ($category_id) {
            $del = mysqli_query($connect, "UPDATE category SET del=1 WHERE id='$category_id'");
            if ($del) {
                header('Location: category.php');
                exit;
            }
        }

    } else {
        die("Invalid request.");
    }
}

?>

