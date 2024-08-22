<?php
    global $conn;
    session_start();
    require "../../../db/conn.php";
    $res = '';
        if ($_FILES['dept_logo']['name'] != ''){
            $department = $_POST['txtDept'];
            $department_no = $_POST['txtDeptNo'];
            $dept_name = $_POST['txtDepartment'];
            $imgData = basename($_FILES['dept_logo']['name']);
            $dept_query = $conn->query("SELECT * FROM department WHERE department = '$department' AND department_active = '0'");
            if ($dept_query->rowCount() > 0){
                $res .= 'dept_exists';
            }else {
                if (!is_dir("../../../assets/uploads/".$department."/logo/")){
                    $directory_img = "../../../assets/uploads/".$department."/logo/";
                    if (!mkdir($directory_img, 0777,true)){
                        die("failed to create folder");
                    }else {
                        $get_all_file = glob("../../../assets/uploads/".$department."/logo/*");
                        foreach ($get_all_file as $all_file){
                            if(is_file($all_file)) {
                                unlink($all_file); // delete file
                            }
                        }
                        $img_data_upload = "../../../assets/uploads/".$department."/logo/";
                    }
                }
                else {
                    $get_all_file = glob("../../../assets/uploads/".$department."/logo/*");
                    foreach ($get_all_file as $all_file){
                        if(is_file($all_file)) {
                            unlink($all_file); // delete file
                        }
                    }
                    $img_data_upload = "../../../assets/uploads/".$department."/logo/";
                }
                $target_file = $img_data_upload . basename($_FILES['dept_logo']['name']);
                $insert_query = $conn->prepare("INSERT INTO department (`department`, `department_name`, `department_logo`, `department_no`) VALUES ( ?, ?, ?, ?)");
                $insert_query->bindParam(1, $department);
                $insert_query->bindParam(2, $dept_name);
                $insert_query->bindParam(3, $imgData);
                $insert_query->bindParam(4, $department_no);
                if (file_exists($target_file)){
                    $res .= 'Exists';
                }else {
                    $insert_query->execute();
                    move_uploaded_file($_FILES['dept_logo']['tmp_name'], $target_file);
                }
                $res .= 'valid';
            }
        }else {
            $res .= 'invalid';
        }
    echo $res;