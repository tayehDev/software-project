
<?php
/*
header("Content-Type: application/json");
header('access-control-allow-origin: *');
header('Access-Control-Allow-Headers: *');
*/
?>


<?php

include "../config.php";
include "signup.php";
include "login.php";
include "change_password.php";
include "show_profile.php";
include "update_your_profile.php";
include "get_categories.php";
include "new_product.php";
include "update_product.php";

include "show_all_products.php";
include "delete_product.php";


include "new_user.php";
include "show_all_users.php";
include "get_user_by_id.php";
include "delete_user.php";

include "new_purchases.php";

//include "update_purchases_product_row.php";
include "get_product_by_id.php";
include "show_order_tracking.php";
include "insert_order_tracking.php";
include "show_order_rating_details.php";
include "insert_order_rating.php";

include "show_one_row_allOrders.php";
//include "show_one_row_sentOrders.php";
//include "show_one_row_receivedOrders.php";

//include "show_received_order.php";
include "show_all_order.php";
include "get_order_coordinates.php";
//include "show_sent_order.php";


// Takes raw data from the request
$json = file_get_contents('php://input');
$data = json_decode($json,true);

//----------------------debug
file_put_contents("debug/receive.debug", $json);
//----------------------


if($data['action']=="do_signup")do_signup($data);
if($data['action']=="do_login")do_login($data);
if($data['action']=="change_password")change_password($data);
if($data['action']=="show_profile")show_profile($data);
if($data['action']=="update_your_profile")update_your_profile($data);
if($data['action']=="get_categories")get_categories($data);
if($data['action']=="new_product")new_product($data);
if($data['action']=="update_product")update_product($data);

if($data['action']=="show_all_products")show_all_products($data);
if($data['action']=="delete_product")delete_product($data);


if($data['action']=="new_purchases")new_purchases($data);

//if($data['action']=="update_purchases_product_row")update_purchases_product_row($data);
if($data['action']=="get_product_by_id")get_product_by_id($data);



if($data['action']=="show_all_order")show_all_order($data);
if($data['action']=="get_order_coordinates")get_order_coordinates($data);
//if($data['action']=="show_sent_order")show_sent_order($data);
//if($data['action']=="show_received_order")show_received_order($data);
if($data['action']=="show_one_row_allOrders")show_one_row_allOrders($data);
//if($data['action']=="show_one_row_sentOrders")show_one_row_sentOrders($data);
//if($data['action']=="show_one_row_receivedOrders")show_one_row_receivedOrders($data);
if($data['action']=="show_order_tracking")show_order_tracking($data);
if($data['action']=="insert_order_tracking")insert_order_tracking($data);
if($data['action']=="show_order_rating_details")show_order_rating_details($data);
if($data['action']=="insert_order_rating")insert_order_rating($data);

if($data['action']=="new_user")new_user($data);
if($data['action']=="show_all_users")show_all_users($data);
if($data['action']=="get_user_by_id")get_user_by_id($data);
if($data['action']=="delete_user")delete_user($data);






