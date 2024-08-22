<?php
global $conn;
session_start();
    require "../../db/conn.php";
    if (isset($_REQUEST['examid'])){
        $del = '';
        $exam_id = $_REQUEST['examid'];
        $user_id = $_REQUEST['user_id'];
        $exam_title = $_REQUEST['title'];
        date_default_timezone_set("Asia/Manila");
        $date = date("Y-m-d");
        $time = date("h:i:s A");
        $examQuery = $conn->query("UPDATE exam_title SET isDeletedExam = '1', logDate = '$date', logTime = '$time' WHERE id = '$exam_id'");
        if ($examQuery){
            $log = "User Deleted Exam :".$exam_title;
            $log = $conn->query("INSERT INTO ulog(`userID`, `logs`, `logDate`, `logTime`) VALUES ('$user_id', '$log', '$date', '$time')");
            if ($log) {
                $del = 1;
            }
        }else {
            $del = 2;
        }
        echo $del;
    }
