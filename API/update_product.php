<?php

function update_product($data)
{
    $connect = $GLOBALS['connect'];

    // 🔹 استخراج البيانات مع التحقق
    $product_id = intval($data['product_id'] ?? 0);
    $by_user_id = intval($data['by_user_id'] ?? 0);
    $title = mysqli_real_escape_string($connect, $data['title_product'] ?? '');
    $description = mysqli_real_escape_string($connect, $data['description'] ?? '');
    $price = floatval($data['price'] ?? 0);
    $category = intval($data['category'] ?? 0);
    $brand_copy = intval($data['brand_copy'] ?? 1);
    $base64Image = $data['image'] ?? null;

    if ($product_id <= 0 || $by_user_id <= 0) {
        echo json_encode([
            "status" => "failed",
            "contentMsg" => "Invalid product or user ID"
        ]);
        exit;
    }

    // 🔹 التحقق من أن المستخدم هو المالك أو إدمن
    $checkProduct = mysqli_query($connect, "
        SELECT p.img, u.user_role_account
        FROM products p
        JOIN users u ON p.by_user_id = u.id
        WHERE p.id = $product_id
        LIMIT 1
    ");

    if (!$checkProduct || mysqli_num_rows($checkProduct) == 0) {
        echo json_encode([
            "status" => "failed",
            "contentMsg" => "Product not found"
        ]);
        exit;
    }

    $oldProduct = mysqli_fetch_assoc($checkProduct);

    // السماح بالتعديل فقط إذا كان المستخدم مالك المنتج أو إدمن
    if ($oldProduct['user_role_account'] != 1 && $by_user_id != $data['by_user_id']) {
        echo json_encode([
            "status" => "failed",
            "contentMsg" => "Permission denied"
        ]);
        exit;
    }

    $image_name = $oldProduct['img'];

    // 🔹 معالجة الصورة الجديدة إذا تم رفعها
    if ($base64Image) {
        $imgData = base64_decode($base64Image);
        $image_name = uniqid("product_") . ".jpg";
        $upload_dir = "../uploads/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_path = $upload_dir . $image_name;
        file_put_contents($file_path, $imgData);

        // حذف الصورة القديمة إذا كانت موجودة
        if (!empty($oldProduct['img']) && file_exists($upload_dir . $oldProduct['img'])) {
            unlink($upload_dir . $oldProduct['img']);
        }
    }

    // 🔹 تحديث المنتج
    $sql = "UPDATE products SET 
                title = '$title',
                description = '$description',
                price = $price,
                category_id = $category,
                brand_copy = $brand_copy,
                img = " . ($image_name ? "'$image_name'" : "NULL") . "
            WHERE id = $product_id";

    $update = mysqli_query($connect, $sql);
    file_put_contents("debug/update_product.debug", $sql);

    if ($update) {
        $response = [
            "status" => "succeed",
            "contentMsg" => "Product updated successfully",
            "product_id" => $product_id
        ];
    } else {
        $response = [
            "status" => "failed",
            "contentMsg" => "Database error: " . mysqli_error($connect)
        ];
    }

    echo json_encode($response);
    exit;
}

