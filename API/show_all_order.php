<?php

function show_all_order($data)
{
    $response = array("status" => "success", "dataView" => array());
    $by_user_id=$data['by_user_id'];

    $checkUser = mysqli_query(
        $GLOBALS['connect'],
        "SELECT user_role_account FROM users WHERE id = $by_user_id LIMIT 1"
    );

    if (!$checkUser || mysqli_num_rows($checkUser) == 0) {
        echo json_encode([
            "status" => "failed",
            "contentMsg" => "Unauthorized"
        ]);
        exit;
    }

    $user = mysqli_fetch_assoc($checkUser);
    $user_role_account = (int)$user['user_role_account'];
    
    if($user_role_account==1)
    {
      $sql = "
          SELECT 
              pi.id as order_id,
              pi.sent_datetime,
              pi.product_id,
              i.title AS product_title,
              i.img,
              i.price,
              pi.client_response
          FROM 
              orders_list pi
          LEFT JOIN 
              products i ON i.id = pi.product_id
          WHERE 
              pi.is_canceled_row = 0
          ORDER BY pi.id DESC
      ";
    }
    else if($user_role_account==2)
    {
      $sql = "
          SELECT 
              pi.id as order_id,
              pi.sent_datetime,
              pi.product_id,
              i.title AS product_title,
              i.img,
              i.price,
              pi.client_response
          FROM 
              orders_list pi
          LEFT JOIN 
              products i ON i.id = pi.product_id
          WHERE 
              pi.is_canceled_row = 0
              AND i.by_user_id=$by_user_id
          ORDER BY pi.id DESC
      ";
    }
    else if($user_role_account==3)
    {
      $sql = "
          SELECT 
              pi.id as order_id,
              pi.sent_datetime,
              pi.product_id,
              i.title AS product_title,
              i.img,
              i.price,
              pi.client_response
          FROM 
              orders_list pi
          LEFT JOIN 
              products i ON i.id = pi.product_id
          WHERE 
              pi.is_canceled_row = 0
              AND pi.by_user_id=$by_user_id
          ORDER BY pi.id DESC
      ";
    }
    else if($user_role_account==4)
    {
      $sql = "
          SELECT 
              pi.id as order_id,
              pi.sent_datetime,
              pi.product_id,
              i.title AS product_title,
              i.img,
              i.price,
              pi.client_response
          FROM 
              orders_list pi
          LEFT JOIN 
              products i ON i.id = pi.product_id
          WHERE 
              pi.is_canceled_row = 0
              AND pi.courier_id=$by_user_id
              AND client_response=1
          ORDER BY pi.id DESC
      ";
    }

    // Log SQL for debugging
    file_put_contents("debug/show_received_order.sql.debug", $sql);

    $query = mysqli_query($GLOBALS['connect'], $sql);

    if ($query && mysqli_num_rows($query) > 0) {

        // English status names
        $status_text = array(
            0 => "Pending",
            1 => "Approved",
            2 => "Rejected"
        );

        while ($row = mysqli_fetch_assoc($query)) {
            $response['dataView'][] = array(
                'order_id'        => (int) $row['order_id'],
                'sent_datetime'   => (string) date("Y-m-d H:i", strtotime($row['sent_datetime'])),
                'product_title'   => (string) $row['product_title'],
                "amount"          => (int) $row['amount'],
                'img'             => (string) $row['img'],
                'client_response' => (string) $status_text[$row['client_response']],
                'price'           => (float) $row['price']
            );
        }
    }

    // Encode JSON
    $json_encoded = json_encode($response);

    // Log response JSON
    file_put_contents("debug/show_received_order.debug", $json_encoded);

    echo $json_encoded;
    exit;
}

