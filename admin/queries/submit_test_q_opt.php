<?php
global $conn;
session_start();
require "../../db/conn.php";
if (isset($_REQUEST['exam_id'])) {
    $examid = $_REQUEST['exam_id'];
    $department = $_SESSION['department'];
    $q_type = $_REQUEST['question_type'];
    $res = '';
    if ($q_type == 'Multiple Choice') {
        $option_query = $conn->query("SELECT a.`id`, b.`question_type` FROM options a LEFT JOIN question b ON a.`id` = b.`id` WHERE a.`o_id` = '$examid' AND b.`q_id` = '$examid' AND b.`question_type` = 'Multiple Choice'");
        while ($option = $option_query->fetch()) {
            $o_id = $option['id'];
            $question = $_REQUEST['question_' . $o_id];
            $option1 = $_REQUEST['option1_' . $o_id];
            $option2 = $_REQUEST['option2_' . $o_id];
            $option3 = $_REQUEST['option3_' . $o_id];
            $option4 = $_REQUEST['option4_' . $o_id];
            $answer = $_REQUEST['ans_' . $o_id];
            $points = $_REQUEST['points_' . $o_id];
            $question_query = $conn->query("UPDATE question a LEFT JOIN options b ON a.`id` = b.`id` SET a.`question` = '$question', a.`points` = '$points', b.`option_1` = '$option1', b.`option_2` = '$option2', b.`option_3` = '$option3', b.`option_4` = '$option4', b.`ans` = '$answer' WHERE a.`q_id` = '$examid' AND b.`o_id` = '$examid' AND a.`id` = '$o_id' AND b.`id` = '$o_id' ");
            if ($question_query) {
                $res .= "valid";
            } else {
                $res .= "invalid";
            }

        }
    } else if ($q_type == 'Short Answer') {
        $option_query = $conn->query("SELECT a.`id`, b.`question_type` FROM options a LEFT JOIN question b ON a.`id` = b.`id` WHERE a.`o_id` = '$examid' AND b.`q_id` = '$examid' AND b.`question_type` = 'Short Answer'");
        while ($option = $option_query->fetch()) {
            $o_id = $option['id'];
            $question = $_REQUEST['question_' . $o_id];
            $answer = $_REQUEST['ans_' . $o_id];
            $points = $_REQUEST['points_' . $o_id];
            $question_query = $conn->query("UPDATE question a LEFT JOIN options b ON a.`id` = b.`id` SET a.`question` = '$question', a.`points` = '$points', b.`ans` = '$answer' WHERE a.`q_id` = '$examid' AND b.`o_id` = '$examid' AND a.`id` = '$o_id' AND b.`id` = '$o_id' ");
            if ($question_query) {
                $res .= "valid";
            } else {
                $res .= "invalid";
            }
        }
    } else if ($q_type == 'True/False') {
        $option_query = $conn->query("SELECT a.`id`, b.`question_type` FROM options a LEFT JOIN question b ON a.`id` = b.`id` WHERE a.`o_id` = '$examid' AND b.`q_id` = '$examid' AND b.`question_type` = 'True/False'");
        while ($option = $option_query->fetch()) {
            $o_id = $option['id'];
            $question = $_REQUEST['question_' . $o_id];
            $answer = $_REQUEST['ans_' . $o_id];
            $with_without = $_REQUEST['with_without' . $o_id];
            $points = $_REQUEST['points_' . $o_id];
            $question_query = $conn->query("UPDATE question a LEFT JOIN options b ON a.`id` = b.`id` SET a.`question` = '$question', a.`points` = '$points', b.`ans` = '$answer' WHERE a.`q_id` = '$examid' AND b.`o_id` = '$examid' AND a.`id` = '$o_id' AND b.`id` = '$o_id' ");
            if ($question_query) {
                $res .= "valid";
            } else {
                $res .= "invalid";
            }
        }
    } else if ($q_type == 'Essay') {
        $option_query = $conn->query("SELECT a.`id`, b.`question_type` FROM options a LEFT JOIN question b ON a.`id` = b.`id` WHERE a.`o_id` = '$examid' AND b.`q_id` = '$examid' AND b.`question_type` = 'Essay'");
        while ($option = $option_query->fetch()) {
            $o_id = $option['id'];
            $question = $_REQUEST['question_' . $o_id];
            $answer = $_REQUEST['ans_' . $o_id];
            $points = $_REQUEST['points_' . $o_id];
            $question_query = $conn->query("UPDATE question a LEFT JOIN options b ON a.`id` = b.`id` SET a.`question` = '$question', a.`points` = '$points', b.`ans` = '$answer' WHERE a.`q_id` = '$examid' AND b.`o_id` = '$examid' AND a.`id` = '$o_id' AND b.`id` = '$o_id' ");
            if ($question_query) {
                $res .= "valid";
            } else {
                $res .= "invalid";
            }
        }
    } else if ($q_type == 'Multiple Image') {
        $option_query = $conn->query("SELECT a.`id`, b.`question_type` FROM options a LEFT JOIN question b ON a.`id` = b.`id` WHERE a.`o_id` = '$examid' AND b.`q_id` = '$examid' AND b.`question_type` = 'Multiple Image'");
        while ($option = $option_query->fetch()) {
            $o_id = $option['id'];
            $question = $_REQUEST['question_' . $o_id];
            $answer = $_REQUEST['ans_' . $o_id];
            $with_without = $_REQUEST['with_without' . $o_id];
            $points = $_REQUEST['points_' . $o_id];
            $question_query = $conn->query("UPDATE question a LEFT JOIN options b ON a.`id` = b.`id` SET a.`question` = '$question', a.`points` = '$points', b.`ans` = '$answer' WHERE a.`q_id` = '$examid' AND b.`o_id` = '$examid' AND a.`id` = '$o_id' AND b.`id` = '$o_id' ");
            if ($question_query) {
                $res .= "valid";
            } else {
                $res .= "invalid";
            }
        }
    }
    echo $res;
}