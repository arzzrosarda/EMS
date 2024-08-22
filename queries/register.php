<?php
    global $conn;
    require "../db/conn.php";
    if (isset($_POST['email'])) {
        $result = '';
        $examno1 = $_POST['txt_examno1'];
        $examno2 = $_POST['txt_examno2'];
        $examno3 = $_POST['txt_examno2'];
        $uType = "examinee";
        $lnam = $_POST['last_name'];
        $fnam = $_POST['first_name'];
        $mnam = $_POST['middle_name'];
        $username = $_POST['username'];
        $MobNo = $_POST['txtno'];
        $email = $_POST['email'];
        $department = $_POST['depselect'];
        $gender = $_POST['gendersel'];
        $pass = $_POST['password'];
        $passHash = password_hash($pass, PASSWORD_DEFAULT);
        $haddress = $_POST['txt_address'];
        $brgy = $_POST['txt_brgy'];
        $citymun = $_POST['txt_citymun'];
        $prov = $_POST['txtprov'];
        $post = $_POST['txtpostal'];
        $isActive = '0';
        $examno = $examno1 . "-" . $examno2 . "-" . $examno3;
        $UNquery = $conn->query("SELECT * FROM user WHERE username = '$username'");
        $Equery = $conn->query("SELECT * FROM user WHERE email = '$email'");
        $Nquery = $conn->query("SELECT * FROM user WHERE exam_no = '$examno'");
        date_default_timezone_set("Asia/Manila");
        $date = date("Y-m-d");
        $time = date("h:i:s A");
        if ($Equery->rowCount() > 0) {
            $result = 'EMExists';
        }else if ($Nquery->rowCount() > 0) {
            $result = 'EExists';
        }else if ($UNquery->rowCount() > 0){
            $result = 'UExists';
        }else {
            $inquery = $conn->prepare("INSERT INTO user 
    (`exam_no`, `lname`, `fname`, `mname`, `department`, `gender`, `email`, `home_address`, `brgy`, `city`, `province`, `postal_code`, `contact_no`, `pass`, `usertype`, `isActive`, `username`, `AccDate`) 
VALUES 
    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $inquery->bindParam(1, $examno);
            $inquery->bindParam(2, $lnam);
            $inquery->bindParam(3, $fnam);
            $inquery->bindParam(4, $mnam);
            $inquery->bindParam(5, $department);
            $inquery->bindParam(6, $gender);
            $inquery->bindParam(7, $email);
            $inquery->bindParam(8, $haddress);
            $inquery->bindParam(9, $brgy);
            $inquery->bindParam(10, $citymun);
            $inquery->bindParam(11, $prov);
            $inquery->bindParam(12, $post);
            $inquery->bindParam(13, $MobNo);
            $inquery->bindParam(14, $passHash);
            $inquery->bindParam(15, $uType);
            $inquery->bindParam(16, $isActive);
            $inquery->bindParam(17, $username);
            $inquery->bindParam(18, $date);
            if ($inquery->execute()) {
                $Uquery = $conn->query("SELECT * FROM user WHERE email = '$email'");
                $Userfetch = $Uquery->fetch();
                $uID = $Userfetch['id'];
                $log = "User Registered:";
                $log = $conn->query("INSERT INTO ulog(`userID`, `logs`, `logDate`, `logTime`) VALUES ('$uID', '$log', '$date', '$time')");
                if ($log) {
                    $result = 'valid';
                }else {
                    $result = 'invalid';
                }
            }else {
                $result = 'invalid';
            }
        }
        echo $result;
    }