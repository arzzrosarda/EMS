<?php
global $conn;
session_start();
    require "../../db/conn.php";
    if (isset($_REQUEST['quest'])){
        $res = '';
        $ci = '';
        $quest_no = $_REQUEST['quest'];
        $score = $_REQUEST['sc'];
        $exam_id = $_REQUEST['exam_id'];
        $user = $_REQUEST['euser'];
        if ($score >= 1){
            $ci = 'correct';
            $updateScoreQ = $conn->query("UPDATE exam_result SET correct_incorrect = '$ci', points = '$score' WHERE q_no = '$quest_no' AND exam_id = '$exam_id' AND examiner_id = '$user'");
            if ($updateScoreQ){
                $res = 1;
            }else {
                $res = 2;
            }
        }else if ($score == 0){
            $ci = 'incorrect';
            $updateScoreQ = $conn->query("UPDATE exam_result SET correct_incorrect = '$ci' WHERE q_no = '$quest_no' AND exam_name = '$exam_id' AND examiner_id = '$user'");
            if ($updateScoreQ){
                $res = 1;
            }else {
                $res = 2;
            }
        }
        echo $res;

    }