<?php
    global $conn;
    require "../db/conn.php";
    session_start();
    if (isset($_SESSION['user'])) {
        $email = $_SESSION['user'];
        $query = $conn->query("UPDATE user SET isActive = '0' WHERE email = '$email' OR username = '$email'");
        $queryu = $conn->query("SELECT * FROM user WHERE email = '$email' OR username = '$email' AND isDelActive = '0' ");
        $fetch = $queryu->fetch();
        if ($query) {
            date_default_timezone_set("Asia/Manila");
            $date = date("Y-m-d");
            $time = date("h:i:s A");
            $uID = $fetch['id'];
            $log = "User Logged out";
            $log = $conn->query("INSERT INTO ulog(`userID`, `logs`, `logDate`, `logTime`) VALUES ('$uID', '$log', '$date', '$time')");
            if ($log) {
                session_destroy();
                header("location: ../auth/auth-login.php");
                exit();
            }
        }
    }
?>