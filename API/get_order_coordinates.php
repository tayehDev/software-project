<?php
function get_order_coordinates($data)
{
    $connect = $GLOBALS['connect'];

    $order_id = intval($data['order_id'] ?? 0);

    if ($order_id <= 0) {
        print json_encode([
            "status" => "failed",
            "msg" => "Invalid order_id"
        ]);
        exit;
    }

    $sql = "SELECT seller.latitude as seller_lat, seller.longitude as seller_lng, buyer.latitude as buyer_lat, buyer.longitude as buyer_lng
            FROM orders_list
            LEFT JOIN products ON orders_list.product_id = products.id
            LEFT JOIN users seller ON seller.id=products.by_user_id
            LEFT JOIN users buyer ON buyer.id=orders_list.by_user_id
            WHERE orders_list.id = $order_id
            LIMIT 1";

    //----------------------debug
    file_put_contents("debug/get_order_coordinates.sql.debug", $sql);
    //----------------------

    $query = mysqli_query($connect, $sql);

    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);

        $response = [
            "status" => "succeed",
            "data" => [
                [
                    "seller_lat" => $row['seller_lat'],
                    "seller_lng" => $row['seller_lng'],
                    "buyer_lat"  => $row['buyer_lat'],
                    "buyer_lng"  => $row['buyer_lng'],
                ]
            ]
        ];
    } else {
        $response = [
            "status" => "failed",
            "msg" => "Coordinates not found"
        ];
    }

    //----------------------debug
    file_put_contents("debug/get_order_coordinates_resp.sql.debug", json_encode($response));
    //----------------------

    print json_encode($response);
    exit;
}
?>

