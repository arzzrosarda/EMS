<?php
    global $conn;
    session_start();

$res = 0;
if (isset($_SESSION['department'])) {
    $department = $_SESSION['department'];
    require "../../db/conn.php";
    $exam = $conn->query("SELECT * FROM exam_title WHERE isActiveExam = '1' AND department = '$department'");
    $countexam = $exam->rowCount();
    $res = $countexam;
}
echo $res;
?>