<?php

function delete_product($data)
{
    $connect = $GLOBALS['connect'];

    // =====================
    // 🔐 Validation
    // =====================
    $product_id = intval($data['product_id'] ?? 0);
    $by_user_id = intval($data['by_user_id'] ?? 0);

    if ($product_id <= 0 || $by_user_id <= 0) {
        echo json_encode([
            "status" => "failed",
            "contentMsg" => "Invalid parameters"
        ]);
        exit;
    }

    // =====================
    // 🛂 Check user role (admin or product owner)
    // =====================
    $checkUser = mysqli_query(
        $connect,
        "SELECT user_role_account FROM users WHERE id = $by_user_id LIMIT 1"
    );

    if (!$checkUser || mysqli_num_rows($checkUser) == 0) {
        echo json_encode([
            "status" => "failed",
            "contentMsg" => "Unauthorized"
        ]);
        exit;
    }

    $user = mysqli_fetch_assoc($checkUser);
    $isAdmin = ((int)$user['user_role_account'] === 1);

    // Check if user is admin or owner of the product
    $checkProduct = mysqli_query(
        $connect,
        "SELECT by_user_id, img FROM products WHERE id = $product_id LIMIT 1"
    );

    if (!$checkProduct || mysqli_num_rows($checkProduct) == 0) {
        echo json_encode([
            "status" => "failed",
            "contentMsg" => "Product not found"
        ]);
        exit;
    }

    $product = mysqli_fetch_assoc($checkProduct);

    if (!$isAdmin && $product['by_user_id'] != $by_user_id) {
        echo json_encode([
            "status" => "failed",
            "contentMsg" => "Permission denied"
        ]);
        exit;
    }

    $image = $product['img'];

    // =====================
    // ❌ Delete product (soft delete)
    // =====================
    $delete = mysqli_query(
        $connect,
        "UPDATE products SET is_canceled_row = 1 WHERE id = $product_id"
    );

    if ($delete) {
        // 🗑️ Delete image from server
        if (!empty($image)) {
            $imgPath = "../uploads/" . $image;
            if (file_exists($imgPath)) {
                unlink($imgPath);
            }
        }

        echo json_encode([
            "status" => "succeed",
            "contentMsg" => "Product deleted successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "failed",
            "contentMsg" => "SQL Error: " . mysqli_error($connect)
        ]);
    }

    exit;
}

