<?php
global $conn;
session_start();
    require "../../../db/conn.php";
    if (isset($_POST['user_id'])){
        $restore = '';
        date_default_timezone_set("Asia/Manila");
        $date = date("Y-m-d");
        $time = date("h:i:s A");
        $user_id = $_POST['user_id'];
        $all_department_id = $_POST['all_department'];
        $length = $_POST['length'];
        for ($i = 0; $i< $length; $i++){
            $dept_id = $all_department_id[$i];
            $departments = $conn->query("SELECT * FROM department WHERE department_active = '1' AND id = '$dept_id'");
            $departmentQuery = $conn->query("UPDATE department SET department_active = '0' WHERE id = '$dept_id'");
            if ($departmentQuery){
                while ($department = $departments->fetch()){
                    $department_title = $department['department'];
                    $user = $conn->query("UPDATE user SET isDelActive = '0' WHERE department = '$department_title'");
                    $exam = $conn->query("UPDATE exam_title SET isDeletedExam = '0' WHERE department = '$department_title'");
                    $log = "User Deleted Department :".$department_title;
                    $log = $conn->query("INSERT INTO ulog(`userID`, `logs`, `logDate`, `logTime`) VALUES ('$user_id', '$log', '$date', '$time')");
                    if ($log) {
                        $restore = 1;
                    }
                }
            }else {
                $restore = 2;
            }
        }
        echo $restore;
    }
