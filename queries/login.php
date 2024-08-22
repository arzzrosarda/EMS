<?php
global $conn;
require "../db/conn.php";
    if (isset($_REQUEST['emailtxt'])) {
        $result = '';
        $Uemail = $_REQUEST['emailtxt'];
        $passwo = $_REQUEST['passtxt'];
        $query = $conn->query("SELECT * FROM user WHERE email = '$Uemail' OR username = '$Uemail' ");
        $fetch = $query->fetch();
        date_default_timezone_set("Asia/Manila");
        $date = date("Y-m-d");
        $time = date("h:i:s A");
        if($fetch['isDelActive'] == '0') {
            $verify = password_verify($passwo, $fetch["pass"]);
            if ($verify) {
                $activeQ = $conn->query("UPDATE user SET isActive = '1', logDate = '$date', logTime = '$time' WHERE email = '$Uemail' OR username = '$Uemail'");
                if ($activeQ) {
                    $uID = $fetch['id'];
                    $log = "User Logged in";
                    $log = $conn->query("INSERT INTO ulog(`userID`, `logs`, `logDate`, `logTime`) VALUES ('$uID', '$log', '$date', '$time')");
                    if ($log) {
                        $result = 1;
                    }
                }
            }else {
                $result = 2;
            }
        }else{
            $result = 3;
        }
        echo $result;
    }