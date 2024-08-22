<?php
global $conn;
session_start();
require "../../db/conn.php";
$department = $_SESSION['department'];
$query = $conn->query("SELECT a.`username`, a.`email`, a.`contact_no`, b.`id`, b.`department`, b.`department_name`, b.`department_no`  FROM user a LEFT JOIN department b ON a.`department` = b.`department` WHERE a.`department` = '$department' AND a.`usertype` = 'admin'");
$row = $query->fetch();
$id = $row['id'];
?>
<div class="row">
    <div class="form-group col-12">
        <label>Division/s: </label>
        <ul class="list-group">
            <?php
                $division_query = $conn->query("SELECT * FROM division WHERE department_id = '$id'");
                while($div = $division_query->fetch()){ ?>
                    <li class="list-group-item"><?php echo $div['division']; ?></li>
                <?php }
            ?>
        </ul>
    </div>
    <div class="form-group col-12">
        <label>Department Contact No.: </label>
        <ul class="list-group">
            <li class="list-group-item"><?php echo $row['department_no']; ?></li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="form-group col-12">
        <h6 class="d-inline"><?php echo $department; ?> Account Information</h6>
    </div>
</div>
<div class="row ">
    <div class="form-group col-md-12 col-12">
        <label>Username: </label>
        <ul class="list-group">
            <li class="list-group-item"><?php echo $row['username']; ?></li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-7 col-12">
        <label>Email: </label>
        <ul class="list-group">
            <li class="list-group-item"><?php echo $row['email']; ?></li>
        </ul>
    </div>
    <div class="form-group col-md-5 col-12">
        <label><?php echo $department; ?> Contact No.: </label>
        <ul class="list-group">
            <li class="list-group-item"><?php echo $row['contact_no']; ?></li>
        </ul>

    </div>
</div>
