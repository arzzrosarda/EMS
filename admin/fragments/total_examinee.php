<?php
session_start();
global $conn;
require "../../db/conn.php";
$res = 0;
if (isset($_SESSION['department'])){
    $department = $_SESSION['department'];
    $examinee = $conn->query("SELECT * FROM user WHERE usertype = 'examinee' AND isDelActive = '0' AND department = '$department'");
    $countexaminee = $examinee->rowCount();
    $res = $countexaminee;
}
echo $res;
?>