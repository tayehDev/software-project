<?php

function new_user($data)
{
    $connect = $GLOBALS['connect'];

    // استلام البيانات
    $by_user_id = intval($data['by_user_id']);  // الأدمن الذي أنشأ الحساب
    $fullname  = mysqli_real_escape_string($connect, $data['fullname'] ?? '');
    $phone      = mysqli_real_escape_string($connect, $data['phone'] ?? '');
    $password   = mysqli_real_escape_string($connect, $data['password'] ?? '');
    $role       = intval($data['user_role_account'] ?? 3); // 1 Admin - 2 Seller - 3 Customer
    $area_id    = intval($data['area_id'] ?? 0);

    $latitude   = mysqli_real_escape_string($connect, $data['latitude'] ?? '');
    $longitude  = mysqli_real_escape_string($connect, $data['longitude'] ?? '');

    $base64Image = $data['image'] ?? null;
    $image_name = "";

    // رفع الصورة
    if ($base64Image) {
        $imgData = base64_decode($base64Image);
        $image_name = uniqid("user_") . ".jpg";

        $upload_dir = "../uploads/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        file_put_contents($upload_dir . $image_name, $imgData);
    }

    // SQL — إدخال مستخدم جديد
    $sql = "INSERT INTO `users` 
        (`fullname`, `phone`, `password`, `user_role_account`, `area_id`, `latitude`, `longitude`, `img`, `created_by`) 
        VALUES 
        ('$fullname', '$phone', '$password', $role, $area_id, '$latitude', '$longitude',
        " . ($image_name ? "'$image_name'" : "NULL") . ",
        $by_user_id)";

    $insert_q = mysqli_query($connect, $sql);
    $new_user_id = mysqli_insert_id($connect);

    file_put_contents("debug/new_user.debug", $sql);

    if ($insert_q) {
        $response = [
            "status" => "succeed",
            "contentMsg" => "تم إضافة المستخدم بنجاح",
            "user_id" => $new_user_id
        ];
    } else {
        $response = [
            "status" => "failed",
            "contentMsg" => "خطأ SQL: " . mysqli_error($connect)
        ];
    }

    echo json_encode($response);
    exit;
}

