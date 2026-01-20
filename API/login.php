<?php
function do_login($data)///
{
    $username=$data["username"];
    $password=hash('sha256', $data['password']);

    $sql="SELECT * FROM `users` WHERE 1 AND phone='$username' AND password='$password' ";
    $query = mysqli_query($GLOBALS['connect'], $sql);
    $num = mysqli_num_rows($query);
    
    if ($num > 0) 
    {
        $row= mysqli_fetch_array($query);
        
        //----------------------debug
        file_put_contents("debug/login.debug", $sql);
        //----------------------
        $response=array("status"=>"succeed","by_user_id"=>intval($row['id']), "user_role_account"=>intval($row['user_role_account']));
        $json_encode=json_encode($response);
        //----------------------debug
        file_put_contents("debug/login_status.debug", $json_encode);
        //----------------------
        print($json_encode);
        exit;
    }
    else
    {
        
        //----------------------debug
        file_put_contents("debug/login.debug", $sql);
        //----------------------
        $response=array("status"=>"failed","contentMsg"=>"خطأ في بيانات الدخول");
        $json_encode=json_encode($response);
        //----------------------debug
        file_put_contents("debug/login_status.debug", $json_encode);
        //----------------------
        print($json_encode);
        exit;
    }
}
