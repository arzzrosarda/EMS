<?php
    global $conn;
    require "../db/conn.php";
    if (isset($_POST['user_id'])){
        $user = '';
        $user_id = $_POST['user_id'];
        $message = $_POST['message'];
        $rate = $_POST['rate'];
        date_default_timezone_set("Asia/Manila");
        $date = date("Y-m-d");
        $time = date("h:i:s A");
        $feedback = $conn->query("INSERT INTO feedback(user_id, message, feedback_date, feedback_time, rate) VALUES ('$user_id', '$message', '$date', '$time', '$rate')");
        if ($feedback){
            $user = 1;
        }else {
            $user = 2;
        }
        echo $user;
    }