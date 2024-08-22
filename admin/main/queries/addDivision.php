<?php
    global $conn;
    session_start();
    require "../../../db/conn.php";
    if (isset($_POST['dept_id'])){
        $res = '';
        $dept_id = $_POST['dept_id'];
        $div = '';
        $addDiv = $conn->query("INSERT INTO division (`department_id`, `division`) VALUES ('$dept_id', '$div')");
        if ($addDiv){
             $res = 1;
        }
        echo $res;
    }