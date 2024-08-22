<?php
    global $conn;
    session_start();
    require "../../db/conn.php";
    if (isset($_POST['exam_id'])){
        $exam_id = $_POST['exam_id'];
        $exam_test_query = $conn->query("SELECT * FROM exam_test WHERE exam_id = '$exam_id'");
        $fetch = $exam_test_query->fetch();
        $TestI = $fetch['Test_I'];
        $TestII = $fetch['Test_II'];
        $TestIII = $fetch['Test_III'];
        $TestIV = $fetch['Test_IV'];
        $TestV = $fetch['Test_V'];
        $question_query = $conn->query("SELECT id, question_type FROM question WHERE q_id = '$exam_id'");
        while ($question = $question_query->fetch()){
            $question_id = $question['id'];
            $qtype = $question['question_type'];
            if ($qtype == $TestI || $qtype == $TestII || $qtype == $TestIII || $qtype == $TestIV || $qtype == $TestV){
                $quest = $conn->query("UPDATE question a LEFT JOIN options b ON a.`q_id` = b.`o_id` SET a.`active` = '1', b.`active` = '1' WHERE a.`id` = '$question_id' AND b.`id` = '$question_id'");
            }else {
                $quest = $conn->query("UPDATE question a LEFT JOIN options b ON a.`q_id` = b.`o_id` SET a.`active` = '0', b.`active` = '0' WHERE a.`id` = '$question_id' AND b.`id` = '$question_id'");
            }
        }
    }