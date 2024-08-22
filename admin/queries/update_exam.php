<?php
global $conn;
session_start();
require "../../db/conn.php";
if (isset($_POST['exam_id'])){
    $res = '';
    $user_id = $_SESSION['id'];
    $exam_id = $_POST['exam_id'];
    $title = $_POST['exam_title'];
    $division = $_POST['exam_div'];
    $department = $_POST['exam_dep'];
    $hour = $_POST['hours'];
    $minutes = $_POST['minute'];
    $seconds = $_POST['second'];
    $test_number = $_POST['test_number'];
    $time_limit = $hour.':'.$minutes.':'.$seconds;
    $testI = $_POST['test_1'];
    $testII = $_POST['test_2'];
    $testIII = $_POST['test_3'];
    $testIV = $_POST['test_4'];
    $testV = $_POST['test_5'];
    $txt_testI = '';
    $txt_testII = '';
    $txt_testIII = '';
    $txt_testIV = '';
    $txt_testV = '';
    $Active = '0';

    if ($testI == 'MC'){
        $txt_testI = 'Multiple Choice';
    }else if ($testI == 'S'){
        $txt_testI = 'Short Answer';
    }else if ($testI == 'TF'){
        $txt_testI = 'True/False';
    }else if ($testI == 'E'){
        $txt_testI = 'Essay';
    }else if ($testI == 'MCI'){
        $txt_testI = 'Multiple Image';
    }

    if ($testII == 'MC'){
        $txt_testII = 'Multiple Choice';
    }else if ($testII == 'S'){
        $txt_testII = 'Short Answer';
    }else if ($testII == 'TF'){
        $txt_testII = 'True/False';
    }else if ($testII == 'E'){
        $txt_testII = 'Essay';
    }else if ($testII == 'MCI'){
        $txt_testII = 'Multiple Image';
    }

    if ($testIII == 'MC'){
        $txt_testIII = 'Multiple Choice';
    }else if ($testIII == 'S'){
        $txt_testIII = 'Short Answer';
    }else if ($testIII == 'TF'){
        $txt_testIII = 'True/False';
    }else if ($testIII == 'E'){
        $txt_testIII = 'Essay';
    }else if ($testIII == 'MCI'){
        $txt_testIII = 'Multiple Image';
    }

    if ($testIV == 'MC'){
        $txt_testIV = 'Multiple Choice';
    }else if ($testIV == 'S'){
        $txt_testIV = 'Short Answer';
    }else if ($testIV == 'TF'){
        $txt_testIV = 'True/False';
    }else if ($testIV == 'E'){
        $txt_testIV = 'Essay';
    }else if ($testIV == 'MCI'){
        $txt_testIV = 'Multiple Image';
    }

    if ($testV == 'MC'){
        $txt_testV = 'Multiple Choice';
    }else if ($testV == 'S'){
        $txt_testV = 'Short Answer';
    }else if ($testV == 'TF'){
        $txt_testV = 'True/False';
    }else if ($testV == 'E'){
        $txt_testV = 'Essay';
    }else if ($testV == 'MCI'){
        $txt_testV = 'Multiple Image';
    }
    date_default_timezone_set("Asia/Manila");
    $date = date("Y-m-d");
    $time = date("h:i:s A");
    $upExam = $conn->query("UPDATE exam_title a 
        LEFT JOIN exam_test b ON a.`id` = b.`exam_id` SET a.`title` = '$title', a.`department` = '$department', a.`division` = '$division', a.`num_test` = '$test_number', a.`time_limit` = '$time_limit',
        b.`Test_I` = '$txt_testI', b.`Test_II` = '$txt_testII', b.`Test_III` = '$txt_testIII', b.`Test_IV` = '$txt_testIV', b.`Test_V` = '$txt_testV'
        WHERE a.`id` = '$exam_id' AND b.`exam_id` = '$exam_id'");

    $log = "User Update Exam: ".$title;
    $log = $conn->query("INSERT INTO ulog(`userID`, `logs`, `logDate`, `logTime`) VALUES ('$user_id', '$log', '$date', '$time')");
    if ($log && $upExam) {
        $res = 1;
    }else {
        $res = 2;
    }
    echo $res;
}

