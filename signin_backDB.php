<?php

require "config.php";

$jsonBack = array();

$phone = mysqli_real_escape_string($connect, $_POST['phone']);
$password = hash("sha256", $_POST['password']);

if ($phone && $password) 
{
    $sql = "SELECT * FROM users WHERE phone='$phone' AND password='$password' AND is_canceled_row=0";
    $finder = mysqli_query($connect, $sql) or die("MySQL error");

    if (mysqli_num_rows($finder) != 0) 
    {
        while ($row = mysqli_fetch_object($finder)) 
        {
            $user_id      = $row->id;
            $phone        = $row->phone;
            $password     = $row->password;
            $user_role_account = $row->user_role_account;
        }

        unset($_SESSION['user_id']);
        unset($_SESSION['phone']);
        unset($_SESSION['password']);
        unset($_SESSION['user_role_account']);

        $_SESSION['user_id']      = $user_id;
        $_SESSION['phone']        = $phone;
        $_SESSION['password']     = $password;
        $_SESSION['user_role_account'] = $user_role_account;

        $jsonBack["contentMsg"] = "";
        $jsonBack["status"] = "ok";
    }
    else
    {
        $jsonBack["status"] = "error";
        $jsonBack["contentMsg"] = "Invalid username or password";
    }
} 
else 
{
    $jsonBack["status"] = "error";
    $jsonBack["contentMsg"] = "Please enter both username and password";
}

echo json_encode($jsonBack);

?>

