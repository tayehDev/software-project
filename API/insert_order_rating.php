<?php
function insert_order_rating($data)
{
    $connect = $GLOBALS['connect'];

    $order_id = intval($data['order_id']);
    $by_user_id = intval($data['by_user_id']);
    $by_user_role = intval($data['by_user_role']);

    // التقييمات (قد تأتي أو لا)
    $evaluate_buyer_by_seller  = isset($data['evaluate_buyer_by_seller']) 
        ? intval($data['evaluate_buyer_by_seller']) : null;

    $evaluate_seller_by_buyer  = isset($data['evaluate_seller_by_buyer']) 
        ? intval($data['evaluate_seller_by_buyer']) : null;

    $evaluate_buyer_by_courier = isset($data['evaluate_buyer_by_courier']) 
        ? intval($data['evaluate_buyer_by_courier']) : null;

    $update_fields = [];

    /**
     * الأدوار (عدلها حسب نظامك):
     * 2 = Seller
     * 3 = Buyer
     * 4 = Courier
     */

    // البائع يقيم المشتري
    if ($by_user_role == 2 && $evaluate_buyer_by_seller !== null) {
        $update_fields[] = "evaluate_buyer_by_seller = $evaluate_buyer_by_seller";
    }

    // المشتري يقيم البائع
    if ($by_user_role == 3 && $evaluate_seller_by_buyer !== null) {
        $update_fields[] = "evaluate_seller_by_buyer = $evaluate_seller_by_buyer";
    }

    // المندوب يقيم المشتري
    if ($by_user_role == 4 && $evaluate_buyer_by_courier !== null) {
        $update_fields[] = "evaluate_buyer_by_courier = $evaluate_buyer_by_courier";
    }

    if (empty($update_fields)) {
        print json_encode([
            "status" => "failed",
            "msg" => "No rating data to update"
        ]);
        exit;
    }

    $sql = "
        UPDATE orders_list
        SET " . implode(", ", $update_fields) . "
        WHERE id = $order_id
        LIMIT 1
    ";

    //----------------------debug
    file_put_contents("debug/insert_order_rating.sql.debug", $sql);
    //----------------------

    $query = mysqli_query($connect, $sql);

    if ($query) {
        $response = [
            "status" => "succeed",
            "msg" => "Rating saved successfully"
        ];
    } else {
        $response = [
            "status" => "failed",
            "msg" => "Database error",
            "error" => mysqli_error($connect)
        ];
    }

    //----------------------debug
    file_put_contents("debug/insert_order_rating_resp.sql.debug", json_encode($response));
    //----------------------

    print json_encode($response);
    exit;
}
?>

