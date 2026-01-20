<?php
function show_order_tracking($data)
{
    $connect = $GLOBALS['connect'];

    $order_id   = intval($data['order_id']);

    $stages = [
        0 => "Processing",
        1 => "To Sorting Center",
        2 => "At Sorting Center",
        3 => "To Buyer",
        4 => "Delivered"
    ];

    $track_info = [];

    $sql = "
        SELECT stage, at_datetime
        FROM order_tracking
        WHERE order_id = $order_id
        ORDER BY id ASC
    ";
    //----------------------debug
    file_put_contents("debug/show_order_tracking.sql.debug", $sql);
    //----------------------
    $query = mysqli_query($connect, $sql);

    while ($row = mysqli_fetch_assoc($query)) {
        $stage_id = intval($row['stage']);

        $track_info[] = [
            "stage_id"   => $stage_id,
            "stage_name" => $stages[$stage_id] ?? "Unknown",
            "at_datetime" => date("Y-m-d H:i", strtotime($row['at_datetime']))
        ];
    }
    //----------------------debug
    file_put_contents("debug/show_order_tracking_resp.sql.debug", json_encode($track_info));
    //----------------------
    $response = [
        "status" => "succeed",
        "dataView" => $track_info // مباشرة list لتسهيل Flutter
    ];

    print json_encode($response);
    exit;
}
?>

