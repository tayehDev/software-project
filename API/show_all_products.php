<?php
function show_all_products($data)
{
    $by_user_id = intval($data['by_user_id']);
    $search_text = mysqli_real_escape_string($GLOBALS['connect'], trim($data['search_text']));
    
    $product_info = array();
    
    // جلب دور المستخدم الحالي
    $query = mysqli_query($GLOBALS['connect'], "SELECT `user_role_account` FROM `users` WHERE id=$by_user_id");
    $user_role_account = mysqli_fetch_array($query)['user_role_account'];

    // اختيار SQL حسب دور المستخدم
    if($user_role_account == 1) {
        // إذا كان مدير أو صاحب حساب عام، عرض جميع المنتجات مع بيانات الناشر
        $sql = "
            SELECT p.*, u.fullname, u.business_name
            FROM `products` p
            LEFT JOIN `users` u ON p.by_user_id = u.id
            WHERE p.is_canceled_row = 0
            AND p.title LIKE '%$search_text%' 
        ";
    } else if($user_role_account == 2) {
        // إذا كان مستخدم عادي، عرض منتجاته فقط
        $sql = "
            SELECT p.*, u.fullname, u.business_name
            FROM `products` p
            LEFT JOIN `users` u ON p.by_user_id = u.id
            WHERE p.is_canceled_row = 0 AND p.by_user_id = $by_user_id
            AND p.title LIKE '%$search_text%' 
        ";
    }
    else if($user_role_account == 3) {
        // إذا كان مستخدم عادي، عرض منتجاته فقط
        $sql = "
            SELECT p.*, u.fullname, u.business_name
            FROM `products` p
            LEFT JOIN `users` u ON p.by_user_id = u.id
            WHERE p.is_canceled_row = 0 
            AND p.title LIKE '%$search_text%' 
        ";
    }

    //----------------------debug
    file_put_contents("debug/show_all_products.sql.debug", $sql);
    //----------------------
    
    $query_products = mysqli_query($GLOBALS['connect'], $sql);
    $num = mysqli_num_rows($query_products);
    
    if ($num > 0) {
        while ($post = mysqli_fetch_array($query_products)) {
            array_push($product_info, array(
                "product_id" => intval($post['id']),
                "product_title" => strval($post['title']),
                "img" => strval($post['img']),
                "price" => intval($post['price']),
                "category_id" => intval($post['category_id']),
                "brand_copy" => intval($post['brand_copy']),
                "publisher_fullname" => strval($post['fullname']),      // اسم الناشر الكامل
                "publisher_business" => strval($post['business_name'])  // اسم النشاط التجاري
            ));
        }
    }

    $response = array(
        "status" => "succeed",
        "dataView" => array(
            'product_info' => $product_info
        )
    );

    //----------------------debug
    file_put_contents("debug/show_all_products.debug", json_encode($response));
    //----------------------
    
    print(json_encode($response));
    exit;
}

