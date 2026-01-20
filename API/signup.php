<?php

function do_signup($data)///
{
    $connect=$GLOBALS['connect'];
    
    $fullname=mysqli_real_escape_string($connect, $data['fullname']);
    $phone=mysqli_real_escape_string($connect, $data['phone']);

    //-----------------------------------------(new)
    if (strlen($fullname) < 3) 
    {
        $response=array("status"=>"failed","contentMsg"=>"الرجاء ادخال الاسم الحقيقي ");
        $json_encode=json_encode($response);
        print($json_encode);
        exit;
    }
    if (strlen($phone) != 10) 
    {
        $response=array("status"=>"failed","contentMsg"=>"خطأ في رقم الهاتف ، الرجاء التاكد من الرقم المدخل ");
        $json_encode=json_encode($response);
        print($json_encode);
        exit;
    }
    //-----------------------------------------
    
    $password = $data['password'];
    //-----------------------------------------(new)
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[\w!@#$%^&*]{8,}$/', $password)) 
    {
        $response=array("status"=>"failed","contentMsg"=>"كلمة المرور يجب ان تكون حروف وارقام اكثر من 8 خانات  ");
        $json_encode=json_encode($response);
        print($json_encode);
        exit;
    }
    //-----------------------------------------
    $password=hash('sha256', $password);

    $sql="INSERT INTO `users`(`phone`,`phone`,`password`, `user_role_account`) VALUES ('$fullname','$phone','$password',3)";
    $insert1 = mysqli_query($GLOBALS['connect'], $sql);
    
    $user_id = mysqli_insert_id($connect);##last row id

    
    
    file_put_contents("debug/do_signup.debug", $sql);
    if ($insert1) 
    {

        //----------------------
        $response=array("status"=>"succeed","contentMsg"=>"تم انشاء الحساب بنجاح","user_id"=>$user_id);
        $json_encode=json_encode($response);
        //----------------------
        
        print($json_encode);
        exit;
    }
    else
    {
        //----------------------
        $response=array("status"=>"failed","contentMsg"=>"خطأ في ادخال البيانات ");
        $json_encode=json_encode($response);
        //----------------------
        
        print($json_encode);
        exit;
    }
}


