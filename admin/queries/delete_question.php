<?php
global $conn;
session_start();
require "../../db/conn.php";

if (isset($_POST['exam_id'])){
    $del = '';
    $exam_id = $_POST['exam_id'];
    $question_id = $_POST['quest_id'];
    $question_type = $_POST['question_type'];
    $department = $_POST['department'];
    $division = $_POST['division'];
    $delete_quest_query = $conn->query("DELETE question, options FROM question LEFT JOIN options ON question.`id` = options.`id` WHERE question.`id` = '$question_id' AND options.`id` = '$question_id' AND question.`q_id` = '$exam_id' AND options.`o_id` = '$exam_id'");
    $question_id_dir = "../../assets/uploads/".$department."/".$division."/".$exam_id."/".$question_type."/".$question_id."";
    if (is_dir($question_id_dir)){
        $get_directory1 = $question_id_dir."/option1";
        $get_all_file1 = glob($get_directory1."/*");
        foreach ($get_all_file1 as $all_file1){
            if(is_file($all_file1)) {
                unlink($all_file1); // delete file
                rmdir($get_directory1);
            }
        }
        $get_directory2 = $question_id_dir."/option2";
        $get_all_file2 = glob( $get_directory2."/*");
        foreach ($get_all_file2 as $all_file2){
            if(is_file($all_file2)) {
                unlink($all_file2); // delete file
                rmdir($get_directory2);
            }
        }
        $get_directory3 = $question_id_dir."/option3";
        $get_all_file3 = glob($get_directory3."/*");
        foreach ($get_all_file3 as $all_file3){
            if(is_file($all_file3)) {
                unlink($all_file3); // delete file
                rmdir($get_directory3);
            }
        }
        $get_directory4 = $question_id_dir."/option4";
        $get_all_file4 = glob($get_directory4."/*");
        foreach ($get_all_file4 as $all_file4){
            if(is_file($all_file4)) {
                unlink($all_file4); // delete file
                rmdir($get_directory4);
            }
        }
        rmdir($question_id_dir);
    }
    $del = 1;
    echo $del;
}