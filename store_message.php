<?php
session_start();
require "config.php";

$sender = $_SESSION["user_id"];
$receiver = $_POST["receiver_id"];
$user_msg = $_POST["user_msg"];
$bot_msg = $_POST["bot_msg"];
$mode = $_POST["human_ai"];

// store user's message
$connect->query("INSERT INTO messenger (client1_user_id, client2_user_id, msg_content, human_ai)
              VALUES ('$sender', '$receiver', '$user_msg', '$mode')");

// store AI reply only if mode is AI
if($mode == "2"){
    $connect->query("INSERT INTO messenger (client1_user_id, client2_user_id, msg_content, human_ai)
                  VALUES ('$receiver', '$sender', '$bot_msg', '2')");
}

echo "success";
?>

