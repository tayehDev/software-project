<?php
function insert_order_tracking($data)
{
    $connect = $GLOBALS['connect'];

    $order_id   = intval($data['order_id']);
    $by_user_id = intval($data['by_user_id']);
    $current_user_role_account = intval($data['by_user_role']); // من Flutter
    $stages     = $data['stages']; // array

    // 🔒 السماح فقط للمستخدمين ذوي الدور 4
    if ($current_user_role_account != 4) {
        print json_encode(["status" => "forbidden", "message" => "You are not allowed"]);
        exit;
    }
    
    //----------------------debug
    file_put_contents("debug/insert_order_tracking.stages.debug", json_encode($stages));
    //----------------------
    

    // ✅ إدخال كل مرحلة
    foreach ($stages as $stage) {
        $stage = intval($stage);

        // منع التكرار لنفس المرحلة لنفس الطلب
        $check = mysqli_query($connect, "SELECT id FROM order_tracking WHERE order_id=$order_id AND stage=$stage");
        if (mysqli_num_rows($check) == 0) {
            mysqli_query($connect, "
                INSERT INTO order_tracking (order_id, stage, at_datetime)
                VALUES ($order_id, $stage, NOW())
            ");
        }
    }

    print json_encode(["status" => "succeed"]);
    exit;
}
?>

