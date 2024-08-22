<?php
global $conn;
session_start();
require "../../db/conn.php";

$res = '';
    if (isset($_REQUEST['exam_id']) && isset($_REQUEST['input_num'])){
        $exam_id = $_REQUEST['exam_id'];
        $user_in = $_REQUEST['input_num'];
        $question_type = $_REQUEST['qtype'];
        $with = '0';
        $active = "1";
        for ($i = 0; $i<$user_in; $i++){
            $question_query = $conn->prepare("INSERT INTO question (q_id, question_type, active) VALUES (?, ?, ?)");
            $question_query->bindParam(1, $exam_id);
            $question_query->bindParam(2, $question_type);
            $question_query->bindParam(3, $active);
            $option_query = $conn->prepare("INSERT INTO options (o_id, with_without, active) VALUES (?, ?, ?)");
            $option_query->bindParam(1, $exam_id);
            $option_query->bindParam(2, $with);
            $option_query->bindParam(3, $active);
            if ($question_query->execute() && $option_query->execute()){
                $res = 1;
            }else {
                $res = 2;
            }
        }
    }else {
        $res = 3;
    }
    echo $res;
