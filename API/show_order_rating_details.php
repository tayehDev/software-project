<?php
function show_order_rating_details($data)
{
    $connect = $GLOBALS['connect'];

    $order_id = intval($data['order_id']);

    $sql = "
        SELECT evaluate_buyer_by_courier, evaluate_buyer_by_seller, evaluate_seller_by_buyer
        FROM orders_list
        WHERE id = $order_id
        LIMIT 1
    ";

    //----------------------debug
    file_put_contents("debug/show_order_rating_details.sql.debug", $sql);
    //----------------------

    $query = mysqli_query($connect, $sql);

    $rating_info = [];
    if ($row = mysqli_fetch_assoc($query)) {
        $rating_info[] = [
            "evaluate_buyer_by_courier" => intval($row['evaluate_buyer_by_courier']),
            "evaluate_buyer_by_seller"  => intval($row['evaluate_buyer_by_seller']),
            "evaluate_seller_by_buyer"  => intval($row['evaluate_seller_by_buyer'])
        ];
    }

    //----------------------debug
    file_put_contents("debug/show_order_rating_details_resp.sql.debug", json_encode($rating_info));
    //----------------------

    $response = [
        "status" => "succeed",
        "data" => $rating_info // مباشرة list لتسهيل Flutter
    ];

    print json_encode($response);
    exit;
}
?>

