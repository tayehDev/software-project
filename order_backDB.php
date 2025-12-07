<?php
include 'config.php';
$jsonBack = array();

// Session check for allowed user roles
if($_SESSION['user_role_account'] != 1 && $_SESSION['user_role_account'] != 2 && $_SESSION['user_role_account'] != 3)
{
    $jsonBack['head'] = "error";
    $jsonBack['body'] = "Session error";
    echo json_encode($jsonBack);
    exit();
}

$by_user_id = $_SESSION['user_id'];

// Send Order
if ($_REQUEST['action'] == 'send_order') 
{
    $product_id = intval($_REQUEST['product_id']);

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
    $sql = "INSERT INTO `orders_list`(`sent_datetime`, `product_id`, `by_user_id`) 
            VALUES ('$sent_datetime', $product_id, $by_user_id)";
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
if ($_REQUEST['action'] == 'set_evaluation') 
{
    $order_id = intval($_REQUEST['order_id']);
    $evaluation = intval($_REQUEST['evaluation']);

    $sql = "UPDATE `orders_list` 
            SET sender_evaluation = $evaluation 
            WHERE id = $order_id AND by_user_id = $by_user_id";
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
?>

