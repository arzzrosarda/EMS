<?php
global $conn;
session_start();
require "../db/conn.php";
$res = '';
$user_id = $_SESSION['id'];
if (isset($_POST['insert'])){
    $exam_id = $_POST['exam_id'];
    $active_exam_take = $conn->query("SELECT * FROM active_take WHERE examinee_id = '$user_id' AND exam_id = '$exam_id'");
    if ($active_exam_take->rowCount() > 0){
        $active_exam_transaction = $conn->query("UPDATE active_take SET active = '1' WHERE examinee_id = '$user_id' AND exam_id = '$exam_id'");
        if ($active_exam_transaction){
            $res = 1;
        }
    }else {
        $active_exam_transaction = $conn->query("INSERT INTO active_take (`examinee_id`, `exam_id`, `active`) VALUES ('$user_id', '$exam_id', '1')");
        if ($active_exam_transaction) {
            $res = 1;
        }
    }
    echo $res;
}else if (isset($_POST['delete'])){
    $exam_id = $_POST['exam_id'];
    $active_exam_transaction = $conn->query("UPDATE active_take SET active = '0' WHERE examinee_id = '$user_id' AND exam_id = '$exam_id'");
}else if (isset($_POST['logout'])){
    $exam_id = $_POST['exam_id'];
    $userID = $_POST['user_id'];
    $active_exam_transaction = $conn->query("UPDATE active_take SET active = '0' WHERE examinee_id = '$userID' AND exam_id = '$exam_id'");
}


