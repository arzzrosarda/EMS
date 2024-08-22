<?php
session_start();
global $conn;
require "../../../db/conn.php";
$exam = $conn->query("SELECT * FROM exam_title");
$countexam = $exam->rowCount();
echo $countexam;