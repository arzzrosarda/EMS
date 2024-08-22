<?php
global $conn;
require "../../db/conn.php";
session_start();
if (isset($_REQUEST['exam_title'])){
    $title_id = '';
    $title = $_REQUEST['exam_title'];
    $exam_id_query = $conn->query("SELECT * FROM exam_title WHERE title = '$title'");
    $fetch_title_id = $exam_id_query->fetch();
    $exam_id = $fetch_title_id['id'];
    if ($exam_id_query->rowCount() > 0 ){
        $title_id = $exam_id;
    }else {
        $title_id = 1;
    }
    echo $title_id;
}