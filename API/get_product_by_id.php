<?php



function get_product_by_id($data)//////
{
    $product_id = intval($data['product_id']);

    $sql = "SELECT * FROM `products` WHERE id = $product_id LIMIT 1";

    // Debug SQL query
    file_put_contents("debug/get_product_by_id.debug", $sql);

    $query = mysqli_query($GLOBALS['connect'], $sql);

    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);

        $response = array(
            "status" => "succeed",
            "data" => array(
                "id"           => strval($row['id']),
                "title_product"   => strval($row['title']),
                "description"  => strval($row['description']),
                "price"        => strval($row['price']),
                "category"     => strval($row['category_id']),
                "brand_copy"    => strval($row['brand_copy']),
                "img"          => !empty($row['img']) ? strval($row['img']) : "default.jpg"
            )
        );

    } else {
        $response = array(
            "status" => "failed",
            "message" => "Post not found"
        );
    }

    $json = json_encode($response, JSON_UNESCAPED_UNICODE);
    file_put_contents("debug/product_info.debug", $json);

    echo $json;
    exit;
}
