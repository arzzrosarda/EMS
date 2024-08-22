<?php
    global $conn;
    session_start();
    require "../db/conn.php";
    if (isset($_REQUEST['userid'])) {
        $result = '';
        $user_id = $_REQUEST['userid'];
        $examno1 = $_REQUEST['exam1'];
        $examno2 = $_REQUEST['exam2'];
        $examno3 = $_REQUEST['exam3'];
        $username = $_REQUEST['username'];
        $lnam = $_REQUEST['lname'];
        $fnam = $_REQUEST['fname'];
        $mnam = $_REQUEST['mname'];
        $MobNo = $_REQUEST['mphone'];
        $email = $_REQUEST['uemail'];
        $division = $_REQUEST['div'];
        $gender = $_REQUEST['gender'];
        $haddress = $_REQUEST['homeaddress'];
        $brgy = $_REQUEST['barangay'];
        $citymun = $_REQUEST['city'];
        $prov = $_REQUEST['province'];
        $post = $_REQUEST['postcode'];
        $examno = $examno1 . "-" . $examno2 . "-" . $examno3;
        $Uquery = $conn->query("SELECT * FROM user WHERE id ='$user_id'");
        $OtherUser = $conn->query("SELECT * FROM user WHERE email = '$email' AND id !='$user_id'");
        $Nquery = $conn->query("SELECT * FROM user WHERE exam_no = '$examno' AND id != '$user_id'");
        $UNquery = $conn->query("SELECT * FROM user WHERE username = '$username' AND id != '$user_id'");
        if ($Uquery->rowCount() == 1){
            if ($OtherUser->rowCount() == 0){
                if ($Nquery->rowCount() == 0){
                    if ($UNquery->rowCount() == 0){
                        $inquery = $conn->query("UPDATE user SET exam_no = '$examno', username = '$username', lname = '$lnam', fname = '$fnam', mname = '$mnam', division = '$division', gender = '$gender', email = '$email', home_address = '$haddress',brgy = '$brgy', city = '$citymun', province = '$prov', postal_code = '$post', contact_no = '$MobNo' WHERE id = '$user_id'");
                        if ($inquery){
                            date_default_timezone_set("Asia/Manila");
                            $date = date("Y-m-d");
                            $time = date("h:i:s A");
                            $log = "User Profile Updated";
                            $log = $conn->query("INSERT INTO ulog(`userID`, `logs`, `logDate`, `logTime`) VALUES ('$user_id', '$log', '$date', '$time')");
                            if ($log) {
                                $result = 1;
                            }
                        }else {
                            $result = 2;
                        }
                    }else {
                        $result = 6;
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