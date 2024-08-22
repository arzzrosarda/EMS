<?php
global $conn;
require "../db/conn.php";
if (isset($_POST['depselect'])){
    $department = $_POST['depselect'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $user_query = $conn->query("SELECT * FROM user WHERE username = '$username' OR email = '$email'");
    if ($user_query->rowCount() > 0){
        $fetch = $user_query->fetch();
        $user_id = $fetch['id'];
        $div_query = $conn->query("SELECT a.`department`, b.`id` AS div_id FROM department a LEFT JOIN division b ON a.`id` = b.`department_id` WHERE a.`department` = '$department' GROUP BY b.`id`");
        while($divi = $div_query->fetch()){
            $id = $divi['div_id'];
            $division = $_POST['division_'.$id];
            if ($division != ''){
                $insert_div = $conn->query("INSERT INTO user_division (`user_id`, `division`) VALUES ('$user_id', '$division')");
                $res = 'valid';
            }else {
                $res = 'valid';
            }
        }
    }else {
        $res = 'invalid';
    }
    echo $res;
}
