<?php
include 'config.php';  
$jsonBack = array();

// Check user session role
if ($_SESSION['user_role_account'] != 1 && $_SESSION['user_role_account'] != 2) {
    $jsonBack['status'] = "error";
    $jsonBack['contentMsg'] = "Session error";
    echo json_encode($jsonBack);
    exit();
}

$by_user_id = $_SESSION['user_id'];

// Image upload function
function image_upload($datafile)
{
    $element_name = $datafile['element_name'];
    $upload_folder_location = $datafile['upload_folder_location'];

    $targetFile = $upload_folder_location . basename($_FILES[$element_name]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $new_name = rand() . "_image_upload_" . time() . "." . $imageFileType;
    $targetFile = $upload_folder_location . $new_name;

    if (move_uploaded_file($_FILES[$element_name]["tmp_name"], $targetFile)) {
        return $new_name;
    } else {
        return -1;
    }
}

// Delete product
if ($_REQUEST['action'] == 'delete') {
    $row_id = intval($_REQUEST['row_id']);

    $delete = mysqli_query($connect, "UPDATE `products` SET is_canceled_row=1 WHERE id='$row_id'");
    if (!$delete) {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "Error while deleting";
        echo json_encode($jsonBack);
        exit();
    }

    $ok_msg = "Record deleted successfully";
    $jsonBack["status"] = "ok";
    $jsonBack["contentMsg"] = $ok_msg;
    echo json_encode($jsonBack);
    exit();
}

// Send order
if ($_REQUEST['action'] == 'send_order') {
    $product_id = intval($_REQUEST['product_id']);

    // Check if an order is already pending
    $sql = mysqli_query($connect, "SELECT * FROM orders_list WHERE by_user_id=$by_user_id AND product_id=$product_id AND is_canceled_row=0 AND client_response=0");
    $num = mysqli_num_rows($sql);
    if ($num > 0) {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "You cannot send a request for this product before the publisher responds or cancels the previous request";
        echo json_encode($jsonBack);
        exit();
    }

    $sent_datetime = date("Y-m-d H:i");
    $sql = "INSERT INTO `orders_list`(`sent_datetime`, `product_id`, `by_user_id`) VALUES ('$sent_datetime', $product_id, $by_user_id)";
    $query = mysqli_query($connect, $sql);
    if (!$query) {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "Error while sending request";
        echo json_encode($jsonBack);
        exit();
    }

    $ok_msg = "Order sent successfully";
    $jsonBack["status"] = "ok";
    $jsonBack["contentMsg"] = $ok_msg;
    echo json_encode($jsonBack);
    exit();
}

// Insert or update product
if ($_POST['action'] == 'insert_product' || $_POST['action'] == 'update_product') {
    $title = mysqli_real_escape_string($connect, $_POST['title']);
    if (!empty($title)) $title = "'$title'";
    else {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "Title is required";
        echo json_encode($jsonBack);
        exit();
    }

    $description = mysqli_real_escape_string($connect, $_POST['description']);
    if (!empty($description)) $description = "'$description'";
    else $description = "NULL";

    $img_dst = "NULL";
    if (!empty($_FILES['img']['name'])) {
        $datafile = array();
        $datafile['element_name'] = 'img';
        $datafile['upload_folder_location'] = "uploads/";
        $img_dst = image_upload($datafile);
        $img_dst_temp = $img_dst;
        $img_dst = "'$img_dst'";
    }

    if ($img_dst != "NULL") $img_update = "img=$img_dst,";

    $price = round(floatval($_POST['price']), 2);
    $brand_copy = intval($_POST['brand_copy']);
    $category_id = intval($_POST['category_id']);
}

// Insert product
if ($_POST['action'] == 'insert_product') {
    $sql = "INSERT INTO products (title, description, img, brand_copy, category_id, price, by_user_id) 
            VALUES ($title, $description, $img_dst, $brand_copy, $category_id, $price, $by_user_id)";

    $insert1 = mysqli_query($connect, $sql);
    if (!$insert1) {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "Error while inserting";
        echo json_encode($jsonBack);
        exit();
    }

    $ok_msg = "Record saved successfully";
}

// Update product
if ($_POST['action'] == 'update_product') {
    $row_id = intval($_POST['row_id']);
    $sql = "UPDATE products SET 
                title=$title,
                description=$description,
                $img_update
                brand_copy=$brand_copy,
                price=$price,
                category_id=$category_id
            WHERE id=$row_id";

    $update = mysqli_query($connect, $sql);
    if (!$update) {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "Error while saving changes";
        echo json_encode($jsonBack);
        exit();
    }

    $ok_msg = "Data updated successfully";
}

$jsonBack["status"] = "ok";
$jsonBack["contentMsg"] = $ok_msg;
echo json_encode($jsonBack);
exit();
?>

