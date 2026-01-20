<?php

function get_user_by_id($data)
{
    $connect = $GLOBALS['connect'];

    $user_id = intval($data['user_id']); // اسم صحيح يأتي من Flutter

    $sql = "SELECT * FROM `users` WHERE id = $user_id LIMIT 1";

    // Debug
    file_put_contents("debug/get_user_by_id.debug", $sql);

    $query = mysqli_query($connect, $sql);

    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);

        $response = array(
            "status" => "succeed",
            "data" => array(
                "id"                => strval($row['id']),
                "fullname"          => strval($row['fullname']),
                "phone"             => strval($row['phone']),
                "address"           => strval($row['address']),
                "latitude"          => strval($row['latitude']),
                "longitude"         => strval($row['longitude']),
                "area_id"           => strval($row['area_id']),
                "user_role_account" => strval($row['user_role_account']),
                "img"               => !empty($row['img']) ? strval($row['img']) : ""
            )
        );

    } else {
        $response = array(
            "status" => "failed",
            "message" => "User not found"
        );
    }

    $json = json_encode($response, JSON_UNESCAPED_UNICODE);
    file_put_contents("debug/get_user_by_id_response.debug", $json);

    echo $json;
    exit;
}

