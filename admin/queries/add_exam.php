<?php
global $conn;
session_start();
require "../../db/conn.php";
if (isset($_REQUEST['exam_title'])){
    $res = '';
    $user_email = $_SESSION['user'];
    $userQuery = $conn->query("SELECT * FROM user WHERE email = '$user_email' OR username = '$user_email'");
    $fetch_user = $userQuery->fetch();
    $user_id = $fetch_user['id'];
    $title = $_REQUEST['exam_title'];
    $division = $_REQUEST['exam_div'];
    $department = $_REQUEST['exam_dep'];
    $hour = $_REQUEST['hours'];
    $minutes = $_REQUEST['minute'];
    $seconds = $_REQUEST['second'];
    $test_number = $_REQUEST['test_number'];
    $time_limit = $hour.':'.$minutes.':'.$seconds;
    $Active = '0';
    date_default_timezone_set("Asia/Manila");
    $date = date("Y-m-d");
    $time = date("h:i:s A");
    $examQuery = $conn->query("SELECT * FROM exam_title WHERE title = '$title'");
    if ($examQuery->rowCount() == 0){
        $inExam = $conn->prepare("INSERT INTO exam_title (`title`, `department`, `division`, `num_test`, `time_limit`, `isActiveExam`) VALUES (?, ?, ?, ?, ?, ?)");
        $inExam->bindParam(1, $title);
        $inExam->bindParam(2, $department);
        $inExam->bindParam(3, $division);
        $inExam->bindParam(4, $test_number);
        $inExam->bindParam(5, $time_limit);
        $inExam->bindParam(6, $Active);
        if ($inExam->execute()){
            $log = "User Created Exam: ".$title;
            $log = $conn->query("INSERT INTO ulog(`userID`, `logs`, `logDate`, `logTime`) VALUES ('$user_id', '$log', '$date', '$time')");
            if ($log) {
                $res = 1;
            }
        }
    }else {
        $res = 2;
    }
    echo $res;
}
