<?php
    global $conn;
    session_start();
    require "../../../db/conn.php";

    if (isset($_POST['user_id'])){
        $res = '';
        $user_id = $_POST['user_id'];
        $pass = $_POST['password'];
        $password = password_hash($pass, PASSWORD_DEFAULT);
        $reset = $conn->query("UPDATE user SET pass = '$password' WHERE id = '$user_id'");
        if ($reset){
            $res = 1;
        }
        echo $res;
    }