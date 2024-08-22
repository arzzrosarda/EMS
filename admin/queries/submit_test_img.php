<?php
global $conn;
session_start();
require "../../db/conn.php";
if (isset($_POST['exam_id'])){
    $examid = $_POST['exam_id'];
    $department = $_POST['department'];
    $q_type = $_POST['question_type'];
    $division = $_POST['test_div'];
    $res = '';
    if ($q_type == 'Multiple Choice'){
        $option_query = $conn->query("SELECT a.`id`, b.`question_type` FROM options a LEFT JOIN question b ON a.`id` = b.`id` WHERE a.`o_id` = '$examid' AND b.`q_id` = '$examid' AND b.`question_type` = 'Multiple Choice'");
        while ($option = $option_query->fetch()){
            $o_id = $option['id'];
                if ($_FILES['image1_'.$o_id]['name'] != ''){
                    $imgData = basename($_FILES['image1_'.$o_id]['name']);
                    if (!is_dir("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option1". "/")){
                        $directory_img = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option1". "/";
                        if (!mkdir($directory_img, 0777,true)){
                            die("failed to create folder");
                        }else {
                            $get_all_file = glob("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option1". "/*");
                            foreach ($get_all_file as $all_file){
                                if(is_file($all_file)) {
                                    unlink($all_file); // delete file
                                }
                            }
                            $img_data_upload = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option1". "/";
                        }
                    }else {
                        $get_all_file = glob("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option1". "/*");
                        foreach ($get_all_file as $all_file){
                            if(is_file($all_file)) {
                                unlink($all_file); // delete file
                            }
                        }
                        $img_data_upload = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option1". "/";
                    }

                    $target_file = $img_data_upload . basename($_FILES['image1_'.$o_id]['name']);
                    $insert_query = $conn->prepare("UPDATE options SET img_1 = :img WHERE id = '$o_id'");
                    $insert_query->bindValue(":img", $imgData);
                    if (file_exists($target_file)){
                        $res .= 'Exists';
                    }else {
                        $insert_query->execute();
                        move_uploaded_file($_FILES['image1_'.$o_id]['tmp_name'], $target_file);
                    }
                }
                if ($_FILES['image2_'.$o_id]['name'] != ''){
                    $imgData = basename($_FILES['image2_'.$o_id]['name']);
                    if (!is_dir("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option2". "/")){
                        $directory_img = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option2". "/";
                        if (!mkdir($directory_img, 0777,true)){
                            die("failed to create folder");
                        }else {
                            $get_all_file = glob("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option2". "/*");
                            foreach ($get_all_file as $all_file){
                                if(is_file($all_file)) {
                                    unlink($all_file); // delete file
                                }
                            }
                            $img_data_upload = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option2". "/";
                        }
                    }else {
                        $get_all_file = glob("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option2". "/*");
                        foreach ($get_all_file as $all_file){
                            if(is_file($all_file)) {
                                unlink($all_file); // delete file
                            }
                        }
                        $img_data_upload = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option2". "/";
                    }
                    $target_file = $img_data_upload . basename($_FILES['image2_'.$o_id]['name']);
                    $insert_query = $conn->prepare("UPDATE options SET img_2 = :img WHERE id = '$o_id'");
                    $insert_query->bindValue(":img", $imgData);
                    if (file_exists($target_file)){
                        $res .= 'Exists';
                    }else {
                        $insert_query->execute();
                        move_uploaded_file($_FILES['image2_'.$o_id]['tmp_name'], $target_file);

                    }
                }
                if ($_FILES['image3_'.$o_id]['name'] != ''){
                    $imgData = basename($_FILES['image3_'.$o_id]['name']);
                    if (!is_dir("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option3". "/")){
                        $directory_img = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option3". "/";
                        if (!mkdir($directory_img, 0777,true)){
                            die("failed to create folder");
                        }else {
                            $get_all_file = glob("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option3". "/*");
                            foreach ($get_all_file as $all_file){
                                if(is_file($all_file)) {
                                    unlink($all_file); // delete file
                                }
                            }
                            $img_data_upload = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option3". "/";
                        }
                    }else {
                        $get_all_file = glob("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option3". "/*");
                        foreach ($get_all_file as $all_file){
                            if(is_file($all_file)) {
                                unlink($all_file); // delete file
                            }
                        }
                        $img_data_upload = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option3". "/";
                    }
                    $target_file = $img_data_upload . basename($_FILES['image3_'.$o_id]['name']);
                    $insert_query = $conn->prepare("UPDATE options SET img_3 = :img WHERE id = '$o_id'");
                    $insert_query->bindValue(":img", $imgData);
                    if (file_exists($target_file)){
                        $res .= 'Exists';
                    }else {
                        $insert_query->execute();
                        move_uploaded_file($_FILES['image3_'.$o_id]['tmp_name'], $target_file);

                    }
                }
                if ($_FILES['image4_'.$o_id]['name'] != ''){
                    $imgData = basename($_FILES['image4_'.$o_id]['name']);
                    if (!is_dir("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option4". "/")){
                        $directory_img = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option4". "/";
                        if (!mkdir($directory_img, 0777,true)){
                            die("failed to create folder");
                        }else {
                            $get_all_file = glob("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option4". "/*");
                        foreach ($get_all_file as $all_file){
                            if(is_file($all_file)) {
                                unlink($all_file); // delete file
                            }
                        }
                            $img_data_upload = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option4". "/";
                        }
                    }else {
                        $get_all_file = glob("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option4". "/*");
                        foreach ($get_all_file as $all_file){
                            if(is_file($all_file)) {
                                unlink($all_file); // delete file
                            }
                        }
                        $img_data_upload = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option4". "/";
                    }
                    $target_file = $img_data_upload . basename($_FILES['image4_'.$o_id]['name']);
                    $insert_query = $conn->prepare("UPDATE options SET img_4 = :img WHERE id = '$o_id'");
                    $insert_query->bindValue(":img", $imgData);
                    if (file_exists($target_file)){
                        $res .= 'Exists';
                    }else {
                        $insert_query->execute();
                        move_uploaded_file($_FILES['image4_'.$o_id]['tmp_name'], $target_file);

                    }
                }
            $res .= 'valid';

        }
    }
    else if ($q_type == 'Short Answer'){
        $res .= "valid";
    }
    else if ($q_type == 'True/False'){
        $option_query = $conn->query("SELECT a.`id`, b.`question_type` FROM options a LEFT JOIN question b ON a.`id` = b.`id` WHERE a.`o_id` = '$examid' AND b.`q_id` = '$examid' AND b.`question_type` = 'True/False'");
        while ($option = $option_query->fetch()){
            $o_id = $option['id'];
                if ($_FILES['image1_'.$o_id]['name'] != ''){
                    $imgData = basename($_FILES['image1_'.$o_id]['name']);
                    if (!is_dir("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option1". "/")){
                        $directory_img = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option1". "/";
                        if (!mkdir($directory_img, 0777,true)){
                            die("failed to create folder");
                        }else {
                            $get_all_file = glob("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option1". "/*");
                        foreach ($get_all_file as $all_file){
                            if(is_file($all_file)) {
                                unlink($all_file); // delete file
                            }
                        }
                            $img_data_upload = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option1". "/";
                        }
                    }else {
                        $get_all_file = glob("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option1". "/*");
                        foreach ($get_all_file as $all_file){
                            if(is_file($all_file)) {
                                unlink($all_file); // delete file
                            }
                        }
                        $img_data_upload = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option1". "/";
                    }
                    $target_file = $img_data_upload . basename($_FILES['image1_'.$o_id]['name']);
                    $insert_query = $conn->prepare("UPDATE options SET img_1 = :img WHERE id = '$o_id'");
                    $insert_query->bindValue(":img", $imgData);
                    if (file_exists($target_file)){
                        $res .= 'Exists';
                    }else {
                        $insert_query->execute();
                        move_uploaded_file($_FILES['image1_'.$o_id]['tmp_name'], $target_file);

                    }
                }
                if ($_FILES['image2_'.$o_id]['name'] != ''){
                    $imgData = basename($_FILES['image2_'.$o_id]['name']);
                    if (!is_dir("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option2". "/")){
                        $directory_img = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option2". "/";
                        if (!mkdir($directory_img, 0777,true)){
                            die("failed to create folder");
                        }else {
                            $get_all_file = glob("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option2". "/*");
                        foreach ($get_all_file as $all_file){
                            if(is_file($all_file)) {
                                unlink($all_file); // delete file
                            }
                        }
                            $img_data_upload = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option2". "/";
                        }
                    }else {
                        $get_all_file = glob("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option2". "/*");
                        foreach ($get_all_file as $all_file){
                            if(is_file($all_file)) {
                                unlink($all_file); // delete file
                            }
                        }
                        $img_data_upload = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option2". "/";
                    }
                    $target_file = $img_data_upload . basename($_FILES['image2_'.$o_id]['name']);
                    $insert_query = $conn->prepare("UPDATE options SET img_2 = :img WHERE id = '$o_id'");
                    $insert_query->bindValue(":img", $imgData);
                    if (file_exists($target_file)){
                        $res .= 'Exists';
                    }else {
                        $insert_query->execute();
                        move_uploaded_file($_FILES['image2_'.$o_id]['tmp_name'], $target_file);

                    }
                }
                if ($_FILES['image3_'.$o_id]['name'] != ''){
                    $imgData = basename($_FILES['image3_'.$o_id]['name']);
                    if (!is_dir("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option3". "/")){
                        $directory_img = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option3". "/";
                        if (!mkdir($directory_img, 0777,true)){
                            die("failed to create folder");
                        }else {
                            $get_all_file = glob("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option3". "/*");
                        foreach ($get_all_file as $all_file){
                            if(is_file($all_file)) {
                                unlink($all_file); // delete file
                            }
                        }
                            $img_data_upload = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option3". "/";
                        }
                    }else {
                        $get_all_file = glob("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option3". "/*");
                        foreach ($get_all_file as $all_file){
                            if(is_file($all_file)) {
                                unlink($all_file); // delete file
                            }
                        }
                        $img_data_upload = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option3". "/";
                    }
                    $target_file = $img_data_upload . basename($_FILES['image3_'.$o_id]['name']);
                    $insert_query = $conn->prepare("UPDATE options SET img_3 = :img WHERE id = '$o_id'");
                    $insert_query->bindValue(":img", $imgData);
                    if (file_exists($target_file)){
                        $res .= 'Exists';
                    }else {
                        $insert_query->execute();
                        move_uploaded_file($_FILES['image3_'.$o_id]['tmp_name'], $target_file);

                    }
                }
                if ($_FILES['image4_'.$o_id]['name'] != ''){
                    $imgData = basename($_FILES['image4_'.$o_id]['name']);
                    if (!is_dir("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option4". "/")){
                        $directory_img = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option4". "/";
                        if (!mkdir($directory_img, 0777,true)){
                            die("failed to create folder");
                        }else {
                            $get_all_file = glob("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option4". "/*");
                        foreach ($get_all_file as $all_file){
                            if(is_file($all_file)) {
                                unlink($all_file); // delete file
                            }
                        }
                            $img_data_upload = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option4". "/";
                        }
                    }else {
                        $get_all_file = glob("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option4". "/*");
                        foreach ($get_all_file as $all_file){
                            if(is_file($all_file)) {
                                unlink($all_file); // delete file
                            }
                        }
                        $img_data_upload = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option4". "/";
                    }
                    $target_file = $img_data_upload . basename($_FILES['image4_'.$o_id]['name']);
                    $insert_query = $conn->prepare("UPDATE options SET img_4 = :img WHERE id = '$o_id'");
                    $insert_query->bindValue(":img", $imgData);
                    if (file_exists($target_file)){
                        $res .= 'Exists';
                    }else {
                        $insert_query->execute();
                        move_uploaded_file($_FILES['image4_'.$o_id]['tmp_name'], $target_file);

                    }
                }
            $res .= 'valid';
        }
    }
    else if ($q_type == 'Essay'){
        $res .= "valid";
    }
    else if ($q_type == 'Multiple Image'){
        $option_query = $conn->query("SELECT a.`id`, b.`question_type` FROM options a LEFT JOIN question b ON a.`id` = b.`id` WHERE a.`o_id` = '$examid' AND b.`q_id` = '$examid' AND b.`question_type` = 'Multiple Image'");
        while ($option = $option_query->fetch()){
            $o_id = $option['id'];
            if ($_FILES['image1_'.$o_id]['name'] != ''){
                $imgData = basename($_FILES['image1_'.$o_id]['name']);
                if (!is_dir("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option1". "/")){
                    $directory_img = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option1". "/";
                    if (!mkdir($directory_img, 0777,true)){
                        die("failed to create folder");
                    }else {
                        $get_all_file = glob("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option1". "/*");
                        foreach ($get_all_file as $all_file){
                            if(is_file($all_file)) {
                                unlink($all_file); // delete file
                            }
                        }
                        $img_data_upload = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option1". "/";
                    }
                }else {
                    $get_all_file = glob("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option1". "/*");
                        foreach ($get_all_file as $all_file){
                            if(is_file($all_file)) {
                                unlink($all_file); // delete file
                            }
                        }
                    $img_data_upload = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option1". "/";
                }
                $target_file = $img_data_upload . basename($_FILES['image1_'.$o_id]['name']);
                $insert_query = $conn->prepare("UPDATE options SET img_1 = :img WHERE id = '$o_id'");
                $insert_query->bindValue(":img", $imgData);
                if (file_exists($target_file)){
                    $res .= 'Exists';
                }else {
                    $insert_query->execute();
                    move_uploaded_file($_FILES['image1_'.$o_id]['tmp_name'], $target_file);

                }
            }
            if ($_FILES['image2_'.$o_id]['name'] != ''){
                $imgData = basename($_FILES['image2_'.$o_id]['name']);
                if (!is_dir("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option2". "/")){
                    $directory_img = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option2". "/";
                    if (!mkdir($directory_img, 0777,true)){
                        die("failed to create folder");
                    }else {
                        $get_all_file = glob("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option2". "/*");
                        foreach ($get_all_file as $all_file){
                            if(is_file($all_file)) {
                                unlink($all_file); // delete file
                            }
                        }
                        $img_data_upload = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option2". "/";
                    }
                }else {
                    $get_all_file = glob("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option2". "/*");
                        foreach ($get_all_file as $all_file){
                            if(is_file($all_file)) {
                                unlink($all_file); // delete file
                            }
                        }
                    $img_data_upload = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option2". "/";
                }
                $target_file = $img_data_upload . basename($_FILES['image2_'.$o_id]['name']);
                $insert_query = $conn->prepare("UPDATE options SET img_2 = :img WHERE id = '$o_id'");
                $insert_query->bindValue(":img", $imgData);
                if (file_exists($target_file)){
                    $res .= 'Exists';
                }else {
                    $insert_query->execute();
                    move_uploaded_file($_FILES['image2_'.$o_id]['tmp_name'], $target_file);

                }
            }
            if ($_FILES['image3_'.$o_id]['name'] != ''){
                $imgData = basename($_FILES['image3_'.$o_id]['name']);
                if (!is_dir("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option3". "/")){
                    $directory_img = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option3". "/";
                    if (!mkdir($directory_img, 0777,true)){
                        die("failed to create folder");
                    }else {
                        $get_all_file = glob("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option3". "/*");
                        foreach ($get_all_file as $all_file){
                            if(is_file($all_file)) {
                                unlink($all_file); // delete file
                            }
                        }
                        $img_data_upload = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option3". "/";
                    }
                }else {
                    $get_all_file = glob("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option3". "/*");
                        foreach ($get_all_file as $all_file){
                            if(is_file($all_file)) {
                                unlink($all_file); // delete file
                            }
                        }
                    $img_data_upload = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option3". "/";
                }
                $target_file = $img_data_upload . basename($_FILES['image3_'.$o_id]['name']);
                $insert_query = $conn->prepare("UPDATE options SET img_3 = :img WHERE id = '$o_id'");
                $insert_query->bindValue(":img", $imgData);
                if (file_exists($target_file)){
                    $res .= 'Exists';
                }else {
                    $insert_query->execute();
                    move_uploaded_file($_FILES['image3_'.$o_id]['tmp_name'], $target_file);

                }
            }
            if ($_FILES['image4_'.$o_id]['name'] != ''){
                $imgData = basename($_FILES['image4_'.$o_id]['name']);
                if (!is_dir("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option4". "/")){
                    $directory_img = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option4". "/";
                    if (!mkdir($directory_img, 0777,true)){
                        die("failed to create folder");
                    }else {
                        $get_all_file = glob("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option4". "/*");
                        foreach ($get_all_file as $all_file){
                            if(is_file($all_file)) {
                                unlink($all_file); // delete file
                            }
                        }
                        $img_data_upload = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option4". "/";
                    }
                }else {
                    $get_all_file = glob("../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option4". "/*");
                        foreach ($get_all_file as $all_file){
                            if(is_file($all_file)) {
                                unlink($all_file); // delete file
                            }
                        }
                    $img_data_upload = "../../assets/uploads/".$department."/".$division."/".$examid."/".$q_type."/".$o_id."/". "option4". "/";
                }
                $target_file = $img_data_upload . basename($_FILES['image4_'.$o_id]['name']);
                $insert_query = $conn->prepare("UPDATE options SET img_4 = :img WHERE id = '$o_id'");
                $insert_query->bindValue(":img", $imgData);
                if (file_exists($target_file)){
                    $res .= 'Exists';
                }else {
                    $insert_query->execute();
                    move_uploaded_file($_FILES['image4_'.$o_id]['tmp_name'], $target_file);

                }
            }
            $res .= 'valid';
        }
    }
    echo $res;

}