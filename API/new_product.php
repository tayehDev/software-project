<?php


function new_product($data)///
{
    $connect = $GLOBALS['connect'];

    $by_user_id = intval($data['by_user_id']);
    $title = mysqli_real_escape_string($connect, $data['title_product'] ?? '');
    $description = mysqli_real_escape_string($connect, $data['description'] ?? '');
    $price = floatval($data['price'] ?? 0);
    $category = intval($data['category'] ?? 0);
    $brand_copy = intval($data['brand_copy'] ?? 1);
    $base64Image = $data['image'] ?? null;

    $image_path = null;
    $image_name="";

    if ($base64Image) {
        $imgData = base64_decode($base64Image);
        $image_name = uniqid("product_") . ".jpg";
        $upload_dir = "../uploads/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_path = $upload_dir . $image_name;
        file_put_contents($file_path, $imgData);
        $image_path = $file_path;
    }

    $sql = "INSERT INTO `products` (`by_user_id`, `title`, `description`, `price`, `category_id`, `brand_copy`, `img`)
            VALUES ($by_user_id, '$title', '$description', $price, $category, $brand_copy, " . ($image_name ? "'$image_name'" : "NULL") . ")";


    $insert1 = mysqli_query($connect, $sql);
    $new_product_id = mysqli_insert_id($connect); // آخر ID مُدخل

    file_put_contents("debug/new_product.debug", $sql);

    if ($insert1) {
        $response = [
            "status" => "succeed",
            "contentMsg" => "تم الاضافة",
            "product_id" => $new_product_id
        ];
    } else {
        $response = [
            "status" => "failed",
            "contentMsg" => "خطأ في ادخال البيانات: " . mysqli_error($connect)
        ];
    }

    echo json_encode($response);
    exit;
}


