<?php
function get_categories($data)///
{
    $connect = $GLOBALS['connect'];

    $sql = "SELECT `id`, `name` FROM `category` WHERE `del` = 0 ORDER BY `sort` ASC";

    $result = mysqli_query($connect, $sql);

    if ($result) {
        $categories = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = [
                "id" => intval($row['id']),
                "name" => $row['name']
            ];
        }

        $response = [
            "status" => "succeed",
            "data" => $categories
        ];
    } else {
        $response = [
            "status" => "failed",
            "contentMsg" => "خطأ في جلب الفئات"
        ];
    }

    // Log SQL (اختياري)
    file_put_contents("debug/get_categories.debug", $sql);

    echo json_encode($response);
    exit;
}


