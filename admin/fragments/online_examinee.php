<?php
global $conn;
session_start();
require "../../db/conn.php";
$res = 0;
if (isset($_SESSION['department'])) {
    $department = $_SESSION['department'];
    $active = $conn->query("SELECT * FROM user WHERE isActive = '1' AND usertype = 'examinee' AND department = '$department'");
    $countactive = $active->rowCount();
    $res = $countactive;
}
echo $res;