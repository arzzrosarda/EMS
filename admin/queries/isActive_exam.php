<?php
session_start();
global $conn;
require "../../db/conn.php";
if (isset($_REQUEST['exam_id'])){
    $del = '';
    $exam_id = $_REQUEST['exam_id'];
    $user_id = $_REQUEST['user_id'];
    $exam_title = $_REQUEST['exam_title'];
    $isActive = $_REQUEST['isActive'];
    date_default_timezone_set("Asia/Manila");
    $date = date("Y-m-d");
    $time = date("h:i:s A");
    $isActive_query = $conn->query("UPDATE exam_title SET isActiveExam = '$isActive' WHERE id = '$exam_id'");
    if ($isActive_query){
        $log = "User Deactivate Exam : ". $exam_title;
        $log = $conn->query("INSERT INTO ulog(`userID`, `logs`, `logDate`, `logTime`) VALUES ('$user_id', '$log', '$date', '$time')");
        if ($log) {
            $del = 1;
        }
    }
    echo $del;
}