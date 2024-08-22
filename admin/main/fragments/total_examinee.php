<?php
session_start();
global $conn;
require "../../../db/conn.php";
$examinee = $conn->query("SELECT * FROM user WHERE usertype = 'examinee' AND isDelActive = '0'");
$countexaminee = $examinee->rowCount();
echo $countexaminee;
?>