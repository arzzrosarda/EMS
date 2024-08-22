<?php
    global $conn;
    session_start();
    require "../../../db/conn.php";
    if (isset($_POST['dept_id'])){
        $res = '';
        $div_id = $_POST['div_id'];
        $dept_id = $_POST['dept_id'];
        $division_query = $conn->query("SELECT id FROM division WHERE department_id = '$dept_id'");
        while ($fetchID = $division_query->fetch()){
            $division_id = $fetchID['id'];
            $division = $_POST['div_input'.$division_id];
            $addDiv = $conn->query("UPDATE division SET division = '$division' WHERE id = '$division_id' AND department_id = '$dept_id'");
            if ($addDiv){
                $res = 'valid';
            }else {
                $res = 'invalid';
            }
        }
        echo $res;
    }