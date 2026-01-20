<?php
include 'config.php';
$jsonBack = array();

// Session check for allowed user roles
if($_SESSION['user_role_account'] != 1 && $_SESSION['user_role_account'] != 2 && $_SESSION['user_role_account'] != 3 && $_SESSION['user_role_account'] != 4)
{
    $jsonBack['status'] = "error";
    $jsonBack['contentMsg'] = "Session error";
    echo json_encode($jsonBack);
    exit();
}

$by_user_id = $_SESSION['user_id'];

// Send Order
if ($_REQUEST['action'] == 'send_order') 
{
   $product_id = intval($_REQUEST['product_id']);

   $sql = mysqli_query($connect, "SELECT by_user_id FROM products 
                                   WHERE 1
                                   AND id = $product_id 
                                   AND is_canceled_row = 0");
   $seller_id = mysqli_fetch_array($sql)['by_user_id'];
   
   $sql = mysqli_query($connect, "SELECT courier_id FROM seller_courier 
                                   WHERE 1
                                   AND seller_id = $seller_id");
                                   
    $courier_id = mysqli_fetch_array($sql)['courier_id'];
    
    //-----------------------------------------
    $sql = mysqli_query($connect, "SELECT * FROM orders_list 
                                   WHERE by_user_id = $by_user_id 
                                   AND product_id = $product_id 
                                   AND is_canceled_row = 0 
                                   AND client_response = 0");
    $num = mysqli_num_rows($sql);
    if ($num > 0) 
    {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "You cannot send an order for this product until the publisher responds or cancels the previous request.";
        echo json_encode($jsonBack);
        exit();
    } 
    //-----------------------------------------
    
    $sent_datetime = date("Y-m-d H:i");
    $sql = "INSERT INTO `orders_list`(`sent_datetime`, `product_id`, `by_user_id`,courier_id) 
            VALUES ('$sent_datetime', $product_id, $by_user_id,$courier_id)";
    $query = mysqli_query($connect, $sql);
    
    if (!$query) 
    {   
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "An error occurred while sending the order";
        echo json_encode($jsonBack);
        exit();
    }
    
    $jsonBack["status"] = "ok";
    $jsonBack["contentMsg"] = "Order sent successfully";
    echo json_encode($jsonBack);
    exit();
}

// Set Evaluation
if ($_REQUEST['action'] == 'set_evaluation' ) 
{
    $order_id = intval($_REQUEST['order_id']);
    $evaluation = intval($_REQUEST['evaluation']);

    // allowed columns
    $allowed_columns = ['evaluate_buyer_by_seller', 'evaluate_seller_by_buyer', 'evaluate_buyer_by_courier'];

    if (!in_array($_REQUEST['column'], $allowed_columns)) {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "Invalid evaluation field";
        echo json_encode($jsonBack);
        exit();
    }

    $column = $_REQUEST['column'];
    
    if($column=="evaluate_buyer_by_courier" && $_SESSION['user_role_account'] != 4)//not work any thing
    {
        $jsonBack["status"] = "ok";
        $jsonBack["contentMsg"] = "";
        echo json_encode($jsonBack);
        exit();
    }
    if($column=="evaluate_seller_by_buyer" && $_SESSION['user_role_account'] != 3)//not work any thing
    {
        $jsonBack["status"] = "ok";
        $jsonBack["contentMsg"] = "";
        echo json_encode($jsonBack);
        exit();
    }
    if($column=="evaluate_buyer_by_seller" && $_SESSION['user_role_account'] != 2)//not work any thing
    {
        $jsonBack["status"] = "ok";
        $jsonBack["contentMsg"] = "";
        echo json_encode($jsonBack);
        exit();
    }
    


    $sql = "UPDATE `orders_list` 
            SET `$column` = $evaluation 
            WHERE id = $order_id ";

    $query = mysqli_query($connect, $sql);

    if (!$query) 
    {   
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "Evaluation failed, maybe you are not the sender of this order";
        echo json_encode($jsonBack);
        exit();
    }
    
    $jsonBack["status"] = "ok";
    $jsonBack["contentMsg"] = "Evaluation submitted successfully";
    echo json_encode($jsonBack);
    exit();
}


// Delete Order
if ($_REQUEST['action'] == 'delete_order') 
{
    $order_id = intval($_REQUEST['order_id']);

    $sql = "UPDATE `orders_list` SET is_canceled_row = 1 WHERE id = $order_id";
    $query = mysqli_query($connect, $sql);
    
    if (!$query) 
    {   
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "Error deleting order";
        echo json_encode($jsonBack);
        exit();
    }
    
    $jsonBack["status"] = "ok";
    $jsonBack["contentMsg"] = "Order deleted successfully";
    echo json_encode($jsonBack);
    exit();
}

// Send Response
if ($_REQUEST['action'] == 'send_response') 
{
    $order_id = intval($_REQUEST['order_id']);
    $client_response = intval($_REQUEST['response']);

    $sql = "UPDATE `orders_list` SET client_response = $client_response WHERE id = $order_id";
    $query = mysqli_query($connect, $sql);
    
    if (!$query) 
    {   
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "Error sending response";
        echo json_encode($jsonBack);
        exit();
    }
    
    $jsonBack["status"] = "ok";
    $jsonBack["contentMsg"] = "Response sent successfully";
    echo json_encode($jsonBack);
    exit();
}

if ($_REQUEST['action'] == 'update_payment_method') 
{
    $order_id = intval($_REQUEST['order_id']);
    $payment_method = intval($_REQUEST['payment_method']);

    $sql = "UPDATE `orders_list` SET payment_method = $payment_method WHERE id = $order_id";// AND client_response=0";
    $query = mysqli_query($connect, $sql);
    
    if (!$query) 
    {   
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "Error payment method";
        echo json_encode($jsonBack);
        exit();
    }
    
    $jsonBack["status"] = "ok";
    $jsonBack["contentMsg"] = "update payment method successfully";
    echo json_encode($jsonBack);
    exit();
}

// -----------------------------
// Order Tracking
// -----------------------------
if ($_REQUEST['action'] == 'order_tracking' && $_SESSION['user_role_account']==4) 
{
    $order_id = intval($_REQUEST['order_id']);
    $stage = intval($_REQUEST['stage']);
    $by_user_id = $_SESSION['user_id'];
    if($stage==-1)
    {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "Can't stop tracking";
        echo json_encode($jsonBack);
        exit();
    }
    if ($order_id == 0) 
    {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "Order ID missing";
        echo json_encode($jsonBack);
        exit();
    }

    $sql = "INSERT INTO order_tracking 
            (at_datetime, order_id, by_user_id, stage) 
            VALUES (NOW(), $order_id, $by_user_id, $stage)";
    $query = mysqli_query($connect, $sql);

    if (!$query) 
    {   
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "Database error: ".mysqli_error($connect);
        echo json_encode($jsonBack);
        exit();
    }

    $jsonBack["status"] = "ok";
    $jsonBack["contentMsg"] = "Tracking updated successfully";
    echo json_encode($jsonBack);
    exit();
}


?>

