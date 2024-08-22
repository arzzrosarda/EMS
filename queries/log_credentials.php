<?php
    global $conn;
    require "../db/conn.php";
    session_start();
    $res = '';
    if (!isset($_SESSION['id'])){
        $res = 3;
    }else {
        $user_id = $_SESSION['id'];
        $login_credentials = $conn->query("SELECT * FROM user WHERE id = '$user_id' AND isActive = '0'");
        if ($login_credentials->rowCount() > 0){
            $res = 1;
        }else {
            $res = 2;
        }
    }


    echo $res;