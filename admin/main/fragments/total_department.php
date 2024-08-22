<?php
session_start();
global $conn;
require "../../../db/conn.php";
$department = $conn->query("SELECT * FROM department");
$countDept = $department->rowCount();
echo $countDept;