<?php
global $conn;
session_start();
require "../../../db/conn.php";
if (isset($_POST['txt_user_id'])) {
    $result = '';
    $email = $_POST['txt_email'];
    $user_id = $_POST['txt_user_id'];
    $username = $_POST['txt_username'];
    $lnam = $_POST['txt_lname'];
    $fnam = $_POST['txt_fname'];
    $mnam = $_POST['txt_mname'];
    $MobNo = $_POST['txt_phone'];
    $gender = $_POST['gendersel'];
    $haddress = $_POST['txt_home'];
    $brgy = $_POST['txt_brgy'];
    $citymun = $_POST['txt_city'];
    $prov = $_POST['txt_province'];
    $post = $_POST['txt_postal_code'];
    $examno = $_POST['txt_exam_no'];
    $department = $_POST['department'];
    $Uquery = $conn->query("SELECT * FROM user WHERE email = '$email' AND id !='$user_id'");
    $Nquery = $conn->query("SELECT * FROM user WHERE exam_no = '$examno' AND id != '$user_id'");
    $Username_query = $conn->query("SELECT * FROM user WHERE username = '$username' AND id != '$user_id'");
    if ($Uquery->rowCount() == 0){
        if ($Nquery->rowCount() == 0){
            if ($Username_query->rowCount() == 0){
                $inquery = $conn->query("UPDATE user SET exam_no = '$examno', username = '$username', lname = '$lnam', fname = '$fnam', mname = '$mnam', department = '$department', gender = '$gender', email = '$email', home_address = '$haddress', brgy = '$brgy', city = '$citymun', province = '$prov', postal_code = '$post', contact_no = '$MobNo' WHERE id = '$user_id'");
                $result = 1;
                if ($inquery){
                    date_default_timezone_set("Asia/Manila");
                    $date = date("Y-m-d");
                    $time = date("h:i:s A");
                    $log = "User Profile Updated";
                    $log = $conn->query("INSERT INTO ulog(`userID`, `logs`, `logDate`, `logTime`) VALUES ('$user_id', '$log', '$date', '$time')");
                    if ($log) {
                        $del_division = $conn->query("DELETE FROM user_division WHERE user_id = '$user_id'");
                        $div_query = $conn->query("SELECT a.`department`, b.`id` AS div_id FROM department a LEFT JOIN division b ON a.`id` = b.`department_id` WHERE a.`department` = '$department' GROUP BY b.`id`");
                        while ($divi = $div_query->fetch()){
                            $id = $divi['div_id'];
                            if (isset($_POST['division_'.$id])){
                                $division = $_POST['division_'.$id];
                                $insert_div = $conn->query("INSERT INTO user_division (`user_id`, `division`) VALUES ('$user_id', '$division')");
                                $result = 1;
                            }
                        }
                    }
                }else {
                    $result = 2;
                }

            }else {
                $result = 5;
            }
        }else {
            $result = 4;
        }

    }else {
        $result = 3;
    }

    echo $result;
}