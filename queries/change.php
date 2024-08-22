<?php
global $conn;
session_start();
    require "../db/conn.php";
    if (isset($_REQUEST['userid'])) {
        $changeres = '';
        $Uid = $_REQUEST['userid'];
        $oldpw = $_REQUEST['password'];
        $newpw = $_REQUEST['newpass'];
        $newpwhash = password_hash($newpw, PASSWORD_DEFAULT);
        $oldpwQuery = $conn->query("SELECT * FROM user WHERE id = '$Uid'");
        $fetchpw = $oldpwQuery->fetch();
        $verifypw = password_verify($oldpw, $fetchpw['pass']);
        if ($verifypw) {
            $passQuery = $conn->query("UPDATE user SET pass = '$newpwhash' WHERE id = '$Uid'");
            if ($passQuery) {
                date_default_timezone_set("Asia/Manila");
                $date = date("Y-m-d");
                $time = date("h:i:s A");
                $log = "User Change Password";
                $log = $conn->query("INSERT INTO ulog(`userID`, `logs`, `logDate`, `logTime`) VALUES ('$Uid', '$log', '$date', '$time')");
                if ($log) {
                    $changeres = 1;
                }
            }
        }else {
            $changeres = 2;
        }
        echo $changeres;
    }