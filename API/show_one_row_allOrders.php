<?php

function show_one_row_allOrders($data)
{
    $response = array("status" => "success", "dataView" => array());

    $order_id = intval($data['order_id']); 

    $sql = "
        SELECT 
            pi.id as order_id,
            pi.sent_datetime,
            pi.product_id,
            i.title AS product_title,
            i.img,
            i.price,
            pi.client_response,
            users.fullname as buyer
        FROM 
            orders_list pi
        LEFT JOIN 
            products i ON i.id = pi.product_id
        LEFT JOIN users ON pi.by_user_id=users.id
        WHERE 
            pi.is_canceled_row = 0
            AND pi.id = $order_id
    ";

    // Log the executed SQL for debugging
    file_put_contents("debug/show_one_row_purchases.sql.debug", $sql);

    $query = mysqli_query($GLOBALS['connect'], $sql);

    if ($query && mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            // Map client_response to English
            $statusMap = array(
                0 => "Pending",
                1 => "Approved",
                2 => "Rejected"
            );

            $response['dataView'][] = array(
                'order_id'        => (int) $row['order_id'],
                'sent_datetime' => (string) date("Y-m-d H:i", strtotime($row['sent_datetime'])),
                'product_title' => (string) $row['product_title'],
                'img' => (string) $row['img'],
                'client_response' => (string) ($statusMap[$row['client_response']] ?? "Pending"),
                'price' => (float) $row['price'],
                'buyer' => (string) $row['buyer']
            );
        }
    }

    $json_encoded = json_encode($response);

    // Log the response JSON
    file_put_contents("debug/show_one_row_purchases.debug", $json_encoded);

    echo $json_encoded;
    exit;
}

