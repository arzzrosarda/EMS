<?php
 global $conn;
 session_start();
 require "../../../db/conn.php";
 if (isset($_POST['dept_id'])){
     $dept_id = $_POST['dept_id'];
     $delDivision = $conn->query("DELETE FROM division WHERE department_id = '$dept_id'");
 }