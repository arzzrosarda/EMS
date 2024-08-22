<?php
global $conn;
session_start();
    require "../../db/conn.php";
    if (isset($_REQUEST['user_id'])){
    $del = '';
    date_default_timezone_set("Asia/Manila");
    $date = date("Y-m-d");
    $time = date("h:i:s A");
    $user_id = $_REQUEST['user_id'];
    $all_exam_id = $_REQUEST['all_exam'];
    $length = $_REQUEST['length'];
    for ($i = 0; $i< $length; $i++){
        $exam_id = $all_exam_id[$i];
        $exams = $conn->query("SELECT * FROM exam_title WHERE isDeletedExam = '1' AND id = '$exam_id'");
        $examQuery = $conn->query("UPDATE exam_title SET isDeletedExam = '0', logDate = '$date', logTime = '$time' WHERE id = '$exam_id'");
        if ($examQuery){
            while ($exam = $exams->fetch()){
                $exam_title = $exam['title'];
                $log = "User Restored Exam :".$exam_title;
                $log = $conn->query("INSERT INTO ulog(`userID`, `logs`, `logDate`, `logTime`) VALUES ('$user_id', '$log', '$date', '$time')");
                if ($log) {
                    $del = 1;
                }
            }
        }else {
            $del = 2;
        }
    }
    echo $del;
}
