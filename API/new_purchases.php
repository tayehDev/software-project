<?php



function new_purchases($data)////
{
    $connect=$GLOBALS['connect'];
    
    $by_user_id=intval($data['by_user_id']);
    $product_id=intval($data['product_id']);


   $sql = mysqli_query($connect, "SELECT by_user_id FROM products 
                                   WHERE 1
                                   AND id = $product_id 
                                   AND is_canceled_row = 0");
   $seller_id = mysqli_fetch_array($sql)['by_user_id'];
   //file_put_contents("debug/new_purchases.debug",  "111");
   $sql = mysqli_query($connect, "SELECT courier_id FROM seller_courier 
                                   WHERE 1
                                   AND seller_id = $seller_id");
    //file_put_contents("debug/new_purchases.debug",  "111");
    $courier_id = mysqli_fetch_array($sql)['courier_id'];
    if(empty($courier_id)){
        //----------------------
        $response=array("status"=>"failed","contentMsg"=>"البائع غير مكتمل البيانات حيث لم يسجل مع شركة توصيل");
        $json_encode=json_encode($response);
        //----------------------
        
        print($json_encode);
        exit;
    }
    
    $amount=intval($data['amount']);

    $sql="INSERT INTO `orders_list`(by_user_id,`amount`, `product_id`,courier_id) VALUES ($by_user_id,$amount,$product_id,$courier_id)";
    
    $insert1 = mysqli_query($GLOBALS['connect'], $sql);
    
    $order_id = mysqli_insert_id($connect);##last row id
    
    file_put_contents("debug/new_purchases.debug", $sql);
    if ($insert1) 
    {

        //----------------------
        $response=array("status"=>"succeed","contentMsg"=>"تم الاضافة","order_id"=>$order_id);
        $json_encode=json_encode($response);
        //----------------------
        
        print($json_encode);
        exit;
    }
    else
    {
        //----------------------
        $response=array("status"=>"failed","contentMsg"=>"خطأ في ادخال البيانات ");
        $json_encode=json_encode($response);
        //----------------------
        
        print($json_encode);
        exit;
    }
}

