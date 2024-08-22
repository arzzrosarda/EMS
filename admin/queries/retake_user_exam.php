<?php
global $conn;
session_start();
require "../../db/conn.php";
if (isset($_POST['user_id'])){
    $res = '';
    $user_id = $_POST['user_id'];
    $exam_id = $_POST['exam_id'];
    $del_exam_result = $conn->query("DELETE FROM exam_result WHERE exam_id = '$exam_id' AND examiner_id = '$user_id' AND isFinal = '0'");
    $retake_exam = $conn->query("UPDATE active_take SET active = '0' WHERE examinee_id = '$user_id' AND exam_id = '$exam_id'");
    if ($del_exam_result && $retake_exam){
        $res = 1;
    }
    echo $res;
}