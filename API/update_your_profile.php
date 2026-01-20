<?php

function update_your_profile($data)
{
    $connect = $GLOBALS['connect'];
    $by_user_id = intval($data['by_user_id']);
    
    // Initialize variables
    $fullname = mysqli_real_escape_string($connect, $data['fullname'] ?? '');
    $business_name = mysqli_real_escape_string($connect, $data['business_name'] ?? '');
    $area_id = intval($data['area_id'] ?? 0);
    $address = mysqli_real_escape_string($connect, $data['address'] ?? '');
    $phone = mysqli_real_escape_string($connect, $data['phone'] ?? '');
    $password = $data['password'] ?? '';

    
    // Validate required fields
    if (empty($fullname) || empty($business_name)) {
        $response = array("status" => "failed", "contentMsg" => "يجب كتابة الاسم الكامل والتجاري");
        echo json_encode($response);
        exit;
    }
    
    // Validate phone number (if provided)
    if (!empty($phone)) {
        // Example Saudi phone number validation: starts with 05 and 10 digits
        if (!preg_match('/^05\d{8}$/', $phone)) {
            $response = array("status" => "failed", "contentMsg" => "رقم الهاتف غير صحيح. يجب أن يبدأ بـ 05 ويتكون من 10 أرقام");
            echo json_encode($response);
            exit;
        }
    }
    
    // Validate password (if provided)
    if (!empty($password)) {
        if (strlen($password) < 8) {
            $response = array("status" => "failed", "contentMsg" => "كلمة المرور يجب أن تكون 8 أحرف على الأقل");
            echo json_encode($response);
            exit;
        }
        $password = hash('sha256', $password);
        $set_password = "password='$password',";
    } else {
        $set_password = "";
    }
    
    
    // Update participant/profile table
    $sql = "UPDATE `users` SET 
            fullname='$fullname',
            business_name='$business_name',
            phone='$phone',
            address='$address',
            phone='$phone',
            $set_password
            area_id=$area_id
            WHERE id=$by_user_id";
    
    $update_participant = mysqli_query($connect, $sql);
    
    if ($update_participant) {
        $response = array("status" => "succeed", "contentMsg" => "تم تحديث البيانات بنجاح");
    } else {
        $response = array("status" => "failed", "contentMsg" => "خطأ في تحديث بيانات الملف الشخصي");
    }
    
    echo json_encode($response);
    exit;
}

