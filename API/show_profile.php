<?php


function show_profile($data)
{
    $by_user_id = intval($data['by_user_id']);

    $user_sql = "
        SELECT
            u.id,
            u.fullname,
            u.business_name,
            u.img,
            u.phone,
            u.area_id,
            u.address,
            c.name AS area_name
        FROM
            `users` AS u
        LEFT JOIN
            `area` AS c ON u.area_id = c.id
        WHERE
            u.id = $by_user_id
    ";

    $user_query = mysqli_query($GLOBALS['connect'], $user_sql);
    $user_row = mysqli_fetch_array($user_query, MYSQLI_ASSOC);

    // Check if user exists
    if (!$user_row) {
        $response = array("status" => "failed", "dataView" => "No user data available.");
        $json_encode = json_encode($response);
        file_put_contents("debug/show_profile.debug", $json_encode);
        print($json_encode);
        exit;
    }

    $area_array = array();

    // Fetch all cities
    $sql_cities = "SELECT id, name FROM `area` WHERE 1";
    $query_cities = mysqli_query($GLOBALS['connect'], $sql_cities);
    while ($area = mysqli_fetch_array($query_cities, MYSQLI_ASSOC)) {
        array_push($area_array, array("id" => intval($area['id']), "name" => strval($area['name'])));
    }

    // Prepare the response dataView
    $response_dataView = array(
        'id' => intval($user_row['id']),
        'fullname' => strval($user_row['fullname']),
        'business_name' => strval($user_row['business_name']),

        'phone' => strval($user_row['phone']),

        'img' => strval($user_row['img']),
        'area_id' => intval($user_row['area_id']),
        'address' => strval($user_row['address']),
        'cities' => $area_array,     
        'area_name' => strval($user_row['area_name']), 
    );

    $response = array(
        "status" => "succeed",
        "dataView" => $response_dataView
    );

    $json_encode = json_encode($response);

    // --- debug
    file_put_contents("debug/show_profile.debug", $json_encode);
    // ---

    print($json_encode);
    exit;
}

