<?php
global $conn;
session_start();
require "../../db/conn.php";
if (isset($_REQUEST['user_id'])){
    $del = '';
    date_default_timezone_set("Asia/Manila");
    $date = date("Y-m-d");
    $time = date("h:i:s A");
    $userid = $_REQUEST['user_id'];
    $all_user_id = $_REQUEST['all_user'];
    $length = $_REQUEST['length'];
    for ($i = 0; $i< $length; $i++){
        $user_id = $all_user_id[$i];
        $users = $conn->query("SELECT * FROM user WHERE isDelActive = '1' AND id = '$user_id'");
        $examQuery = $conn->query("UPDATE user SET isDelActive = '0' WHERE id = '$user_id'");
        if ($examQuery){
            while ($user = $users->fetch()){
                $username = $user['lname'].", ".$user['fname']." ".$user['mname'];
                $log = "User Restored: ".$username;
                $log = $conn->query("INSERT INTO ulog(`userID`, `logs`, `logDate`, `logTime`) VALUES ('$userid', '$log', '$date', '$time')");
                if ($log) {
                    $del = 1;
                }
            }
        }else {
            $del = 2;
        }
    }
    echo $del;
}
