<?php
global $conn;
require "../../../db/conn.php";

if (isset($_REQUEST['department'])){
    $res = '';
    $department = $_REQUEST['department'];
    if ($department != ''){
        $res .= '<option value="">All Division</option>';
            $division_query = $conn->query("SELECT a.`id`, a.`department`, b.`department_id`, b.`division` FROM department a LEFT JOIN division b ON a.`id` = b.`department_id` WHERE a.`department` = '$department'");
            while ($division = $division_query->fetch()){
                $res .= '<option value="' . $division['division'] . '">'.  $division['division'] .'</option>';
            }
    }else {
        $res .= '<option value="">Select Division</option>';
    }
    echo $res;
 }