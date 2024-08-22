<?php
    global $conn;
    require "../db/conn.php";
    if (isset($_REQUEST['emailtxt'])) {
        $res = '';
        $txt = $_REQUEST['emailtxt'];
        $query = $conn->query("SELECT * FROM user WHERE email = '$txt' OR username = '$txt'");
        $row = $query->fetch();
        $department = $row['department'];
        $email = $row['email'];
        session_start();
        $_SESSION['user'] = $email;
        $_SESSION['id'] = $row['id'];
        $_SESSION['department'] = $department;
        if ($row['usertype'] == "admin") {
            $res = 1;
        }else if ($row['usertype'] == "examinee") {
            $res = 2;
        }else if ($row['usertype'] == "main"){
            $res = 3;
        }
        echo $res;
    }