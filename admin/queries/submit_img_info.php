<?php
    global $conn;
    session_start();
    require "../../db/conn.php";
    $res = '';
    if (isset($_SESSION['department'])){
        $department = $_SESSION['department'];
        if ($_FILES['dept_logo']['name'] != ''){
            $imgData = basename($_FILES['dept_logo']['name']);
            if (!is_dir("../../assets/uploads/".$department."/logo/")){
                $directory_img = "../../assets/uploads/".$department."/logo/";
                if (!mkdir($directory_img, 0777,true)){
                    die("failed to create folder");
                }else {
                    $get_all_file = glob("../../assets/uploads/".$department."/logo/*");
                    foreach ($get_all_file as $all_file){
                        if(is_file($all_file)) {
                            unlink($all_file); // delete file
                        }
                    }
                    $img_data_upload = "../../assets/uploads/".$department."/logo/";
                }
            }else {
                $get_all_file = glob("../../assets/uploads/".$department."/logo/*");
                foreach ($get_all_file as $all_file){
                    if(is_file($all_file)) {
                        unlink($all_file); // delete file
                    }
                }
                $img_data_upload = "../../assets/uploads/".$department."/logo/";
            }
            $target_file = $img_data_upload . basename($_FILES['dept_logo']['name']);
            $insert_query = $conn->prepare("UPDATE department SET department_logo = :img WHERE department = '$department'");
            $insert_query->bindValue(":img", $imgData);
            if (file_exists($target_file)){
                $res .= 'Exists';
            }else {
                $insert_query->execute();
                move_uploaded_file($_FILES['dept_logo']['tmp_name'], $target_file);
            }
            $res .= 'valid';
        }else {
            $res .= 'valid';
        }

    }else{
        $res .= 'invalid';
    }
    echo $res;