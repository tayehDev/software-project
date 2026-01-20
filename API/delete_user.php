<?php

function delete_user($data)
{
    $connect = $GLOBALS['connect'];

    // =====================
    // 🔐 Validation
    // =====================
    $user_id    = intval($data['user_id'] ?? 0);
    $by_user_id = intval($data['by_user_id'] ?? 0);

    if ($user_id <= 0 || $by_user_id <= 0) {
        echo json_encode([
            "status" => "failed",
            "contentMsg" => "Invalid parameters"
        ]);
        exit;
    }

    // =====================
    // 🛂 Check admin role
    // =====================
    $checkAdmin = mysqli_query(
        $connect,
        "SELECT user_role_account FROM users WHERE id = $by_user_id LIMIT 1"
    );

    if (!$checkAdmin || mysqli_num_rows($checkAdmin) == 0) {
        echo json_encode([
            "status" => "failed",
            "contentMsg" => "Unauthorized"
        ]);
        exit;
    }

    $admin = mysqli_fetch_assoc($checkAdmin);
    if ((int)$admin['user_role_account'] !== 1) {
        echo json_encode([
            "status" => "failed",
            "contentMsg" => "Permission denied"
        ]);
        exit;
    }

    // =====================
    // 🖼️ Get user image
    // =====================
    $getUser = mysqli_query(
        $connect,
        "SELECT img FROM users WHERE id = $user_id LIMIT 1"
    );

    if (!$getUser || mysqli_num_rows($getUser) == 0) {
        echo json_encode([
            "status" => "failed",
            "contentMsg" => "User not found"
        ]);
        exit;
    }

    $user = mysqli_fetch_assoc($getUser);
    $image = $user['img'];

    // =====================
    // ❌ Delete user
    // =====================
    $delete = mysqli_query(
        $connect,
        "UPDATE users SET is_canceled_row=1 WHERE id = $user_id"
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
            "contentMsg" => "User deleted successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "failed",
            "contentMsg" => "SQL Error: " . mysqli_error($connect)
        ]);
    }

    exit;
}

