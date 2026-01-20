<?php

function show_all_users($data)
{
    $connect = $GLOBALS['connect'];

    $by_user_id  = intval($data['by_user_id']);
    $search_text = mysqli_real_escape_string($connect, trim($data['search_text'] ?? ''));

    $users_list = [];

    // معرفة دور المستخدم الذي يطلب البيانات
    $q = mysqli_query($connect, "SELECT user_role_account FROM users WHERE id = $by_user_id");
    $user_role = mysqli_fetch_array($q)['user_role_account'];


    $sql = "SELECT * FROM users WHERE is_canceled_row = 0";

    // إضافة فلترة البحث (اختياري)
    if (!empty($search_text)) {
        $sql .= " AND (fullname LIKE '%$search_text%' OR phone LIKE '%$search_text%')";
    }

    file_put_contents("debug/show_all_users.sql.debug", $sql);

    $query = mysqli_query($connect, $sql);
    $num   = mysqli_num_rows($query);

    if ($num > 0) {
        while ($user = mysqli_fetch_array($query)) {
            $users_list[] = [
                "id"               => intval($user['id']),
                "fullname"            => strval($user['fullname']),
                "img"                   => strval($user['img']),
                "phone"                 => strval($user['phone']),
                "area_id"               => intval($user['area_id']),
                "user_role_account"     => intval($user['user_role_account']),
            ];
        }
    }

    $response = [
        "status" => "succeed",
        "dataView" => [
            "user_info" => $users_list
        ]
    ];

    $json = json_encode($response);

    file_put_contents("debug/show_all_users.debug", $json);

    echo $json;
    exit;
}

