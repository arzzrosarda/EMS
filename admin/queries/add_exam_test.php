<?php
global $conn;
session_start();
require "../../db/conn.php";
if (isset($_POST['exam_title'])){
    $title = $_POST['exam_title'];
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

    $examQuery = $conn->query("SELECT * FROM exam_title WHERE title = '$title'");
    $exam = $examQuery->fetch();
    $exam_id = $exam['id'];
    $testQuery = $conn->prepare("INSERT INTO exam_test (`exam_id`, `Test_I`, `Test_II`, `Test_III`, `Test_IV`, `Test_V`) VALUES ( ?, ?, ?, ?, ?, ?)");
    $testQuery->bindParam(1, $exam_id);
    $testQuery->bindParam(2, $txt_testI);
    $testQuery->bindParam(3, $txt_testII);
    $testQuery->bindParam(4, $txt_testIII);
    $testQuery->bindParam(5, $txt_testIV);
    $testQuery->bindParam(6, $txt_testV);
    if ($testQuery->execute()){
        $res = 1;
    }else {
        $res = 2;
    }
    echo $res;

}
