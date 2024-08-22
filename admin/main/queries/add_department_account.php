<?php
    global $conn;
    session_start();
    require "../../../db/conn.php";

    if (isset($_POST['dept_id'])){
        $res = '';
        $dept_id = $_POST['dept_id'];
        $dept = $_POST['dept'];
        $username = $_POST['username'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $contact_no = $_POST['contact_no'];
        $pass = $_POST['password'];
        $usertype = "admin";
        $password = password_hash($pass, PASSWORD_DEFAULT);
        date_default_timezone_set("Asia/Manila");
        $date = date("Y-m-d");
        $time = date("h:i:s A");
        $dept_account = $conn->prepare("INSERT INTO user (`username`, `lname`, `department`, `email`, `contact_no`, `pass`, `usertype`, `AccDate`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)");
        $dept_account->bindParam(1, $username);
        $dept_account->bindParam(2, $name);
        $dept_account->bindParam(3, $dept);
        $dept_account->bindParam(4, $email);
        $dept_account->bindParam(5, $contact_no);
        $dept_account->bindParam(6, $password);
        $dept_account->bindParam(7, $usertype);
        $dept_account->bindParam(8, $date);
        if ($dept_account->execute()){
            $res = 1;
        }
        echo $res;
    }