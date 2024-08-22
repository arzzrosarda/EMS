<?php
global $conn;
session_start();
require "../../db/conn.php";
if (isset($_POST['user_id'])){
    $res = '';
    $user_id = $_POST['user_id'];
    $exam_id = $_POST['exam_id'];
    $final_exam_result = $conn->query("UPDATE exam_result SET isFinal = '1' WHERE exam_id = '$exam_id' AND examiner_id = '$user_id' AND isFinal = '0'");
    if ($final_exam_result ){
        $res = 1;
    }
    echo $res;
}