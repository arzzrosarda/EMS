<?php
    global $conn;
    session_start();
    require "../../db/conn.php";
    $result = '';
    if (isset($_REQUEST['userid'])) {
        $user_id = $_REQUEST['userid'];
        $username = $_REQUEST['user_name'];
        $MobNo = $_REQUEST['mphone'];
        $email = $_REQUEST['uemail'];
        $division = $_REQUEST['div'];
        $gender = $_REQUEST['gender'];
        $Uquery = $conn->query("SELECT * FROM user WHERE id = '$user_id'");
        $EOtherUser = $conn->query("SELECT * FROM user WHERE email = '$email' AND id != '$user_id'");
        $UNquery = $conn->query("SELECT * FROM user WHERE username = '$username' AND id != '$user_id'");
        if ($Uquery->rowCount() == 1){
            if ($EOtherUser->rowCount() == 0){
                if ($UNquery->rowCount() == 0){
                    $update_query = $conn->query("UPDATE user SET username = '$username', division = '$division', gender = '$gender', email = '$email', contact_no = '$MobNo' WHERE id = '$user_id'");
                    if ($update_query){
                        date_default_timezone_set("Asia/Manila");
                        $date = date("Y-m-d");
                        $time = date("h:i:s A");
                        $log = "User Profile Updated";
                        $log = $conn->query("INSERT INTO ulog(`userID`, `logs`, `logDate`, `logTime`) VALUES ('$user_id', '$log', '$date', '$time')");
                        if ($log) {
                            $result = 1;
                        }
                    }else {
                        $result = 2;
                    }
                }else {
                    $result = 5;
                }
            }else {
                $result = 4;
            }
        }else {
            $result = 3;
        }
    }else {
        $result = 6;
    }
    echo $result;