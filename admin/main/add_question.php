
<?php
global $conn;
session_start();
require "../../db/conn.php";
if (!isset($_SESSION['user'])) {
    header("location: ../../auth/auth-login.php");
}else {
    $email = $_SESSION['user'];
    $query = $conn->query("SELECT * FROM user WHERE email = '$email' OR username = '$email' AND isActive = '1'");
    if ($query->rowCount() > 0){
        $row = $query->fetch();
        if ($row['usertype'] == "examinee") {
            header("location: ../../index.php");
        }else if($row['usertype'] == "admin"){
            header("location: ../admin.php");
        }else if ($row['isActive'] == '0'){
            session_destroy();
            header("location: ../../auth/auth-login.php");
        }
    }else {
        session_destroy();
        header("location: ../../auth/auth-login.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <link rel="shortcut icon" href="../../assets/img/logo/Cavite_Province.png">
    <title>PGC Exam Management System</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="../../assets/modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/modules/fontawesome/css/all.min.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/animate.min.css">
    <link rel="stylesheet" href="../../assets/css/components.css">
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="../../assets/modules/izitoast/css/iziToast.min.css">
    <!-- Sweet Alert JS -->
    <script src="../../assets/modules/sweetalert/sweetalert2.all.min.js"></script>

    <!-- General JS Scripts -->
    <script src="../../assets/modules/jquery.min.js"></script>
    <script src="../../assets/modules/popper.js"></script>
    <script src="../../assets/modules/tooltip.js"></script>
    <script src="../../assets/modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="../../assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
    <link rel="stylesheet" href="../../assets/modules/datatables/datatables.css">
    <script type="text/javascript" src="../../assets/modules/datatables/datatables.js"></script>
    <script src="../../assets/modules/chart.min.js"></script>
    <script src="../../assets/modules/chartjs-plugin-colorschemes.js"></script>

    <!-- JS Libraies -->
    <script src="../../assets/modules/izitoast/js/iziToast.min.js"></script>

    <!-- Template JS File -->
    <script src="../../assets/js/jquery.redirect.js"></script>
    <script src="../../assets/js/scripts.js"></script>
</head>

<body>
<?php include "modal/modal.php"; ?>
<div id="app">
    <div class="main-wrapper main-wrapper-1">
        <!-- Navigation bar -->
        <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar">
            <form class="form-inline mr-auto">
                <ul class="navbar-nav mr-3">
                    <li><a href="javascript:;" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
                </ul>
                <ul class="navbar-nav">
                    <li style="line-height: 1px; margin-top: -5px;"><h5 style=" color: #ffffff;">Provincial Government of Cavite</h5>
                        <br>
                        <span style="color: whitesmoke;">Exam Management System</span></li>
                </ul>
            </form>
            <ul class="navbar-nav navbar-right">
                <li class="dropdown"><a href="javascript:;" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                        <?php
                        if (isset($_SESSION['user'])) {
                            echo $row['lname'];
                        }
                        ?>
                        <div class="d-sm-none d-lg-inline-block">
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item has-icon" href="javascript:;" id="changepw1">
                            <i class="fas fa-key"></i> Change Password
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:;" id="logoutbtn" class="dropdown-item has-icon text-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <div class="main-sidebar sidebar-style-2">
            <aside id="sidebar-wrapper">
                <div class="sidebar-brand">
                    <a href="main_admin.php">
                        <img id="img-logo" src="../../assets/img/logo/Cavite_Province.png" style="height: 100px; width: 100px;">
                        <br>
                        EXAM MANAGEMENT <br>SYSTEM
                    </a>
                </div>

                <div class="sidebar-brand sidebar-brand-sm">
                    <a href="main_admin.php"><img id="img-logo" src="../../assets/img/logo/Cavite_Province.png" alt="PGC LOGO"/></a>
                </div>
                <ul class="sidebar-menu">
                    <li class="menu-header">Home</li>
                    <li class="dropdown ">
                        <a href="main_admin.php" class="nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a>
                    </li>
                    <li class="menu-header">Manage</li>
                    <li class="dropdown active">
                        <a href="list_exam.php" class="nav-link"><i class="fas fa-file-alt"></i> <span>Exams</span></a>
                    </li>
                    <li class="dropdown">
                        <a href="list_department.php" class="nav-link"><i class="fas fa-university"></i> <span>Departments</span></a>
                    </li>
                    <li class="dropdown ">
                        <a href="list_examinee.php" class="nav-link"><i class="fas fa-users"></i> <span>Examinees</span></a>
                    </li>

                    <li class="menu-header">Password</li>
                    <li class="dropdown">
                        <a class="nav-link" href="javascript:;" id="changepw"><i class="fas fa-key"></i> <span>Change Password</span></a>
                    </li>
                    <li class="menu-header">Logout</li>
                    <li class="dropdown">
                        <a href="javascript:;" class="nav-link" id="logoutbtn1"><i class="fas fa-power-off"></i> <span>Logout</span></a>
                    </li>
            </aside>
        </div>
        <!-- Navigation bar End -->

        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
                <?php if (isset($_POST['exam_id'])){ ?>
                    <div class="section-header" id="header_exam_details">
                        <h1>Exam Details</h1>
                        <div class="section-header-breadcrumb">
                            <div class="breadcrumb-item "><a href="main_admin.php">Dashboard</a></div>
                            <div class="breadcrumb-item"><a href="list_exam.php">Exam Monitoring</a></div>
                            <div class="breadcrumb-item active">Exam Details</div>
                        </div>
                    </div>
                    <div class="section-body">
                        <?php
                        $exam_id = $_POST['exam_id'];
                        $examQuery = $conn->query("SELECT a.`title`, a.`department`, a.`division`, a.`num_test`, a.`time_limit`, b.`Test_I`, b.`Test_II`, b.`Test_III`, b.`Test_IV`, b.`Test_V` FROM exam_title a LEFT JOIN exam_test b ON a.`id` = b.`exam_id` WHERE a.`id` = '$exam_id'");
                        $exam_fetch = $examQuery->fetch();
                        $department = $exam_fetch['department'];
                        $division = $exam_fetch['division'];
                        $test_I = $exam_fetch['Test_I'];
                        $test_II = $exam_fetch['Test_II'];
                        $test_III = $exam_fetch['Test_III'];
                        $test_IV = $exam_fetch['Test_IV'];
                        $test_V = $exam_fetch['Test_V']; ?>
                        <script>
                            $(document).ready(function (){

                                $("#exam_details").click( function (){
                                    Swal.fire({
                                        title: "EXAM DETAILS",
                                        html: "you are about to go into exam details, all of your progress won't be saved, are you sure?",
                                        icon: "info",
                                        showCancelButton: true,
                                        confirmButtonColor: "#1c3d77",
                                        confirmButtonText: "Yes, I am sure",
                                        cancelButtonText: "No, Cancel it",
                                        closeOnConfirm: false,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            $.redirect("add_exam.php", {"exam_id": <?php echo $exam_id; ?>});
                                        }
                                    });
                                });
                                $.post("modal/loader.php", function(load){
                                    $("#test1_form").html(load);
                                    $.post("fragments/test_I.php", {exam_id:<?php echo $exam_id; ?>}, function(test){
                                        $("#test1_form").html(test);
                                    });
                                });
                                $.post("modal/loader.php", function(load){
                                    $("#test2_form").html(load);
                                    $.post("fragments/test_II.php", {exam_id:<?php echo $exam_id; ?>}, function(test){
                                        $("#test2_form").html(test);
                                    });
                                });
                                $.post("modal/loader.php", function(load){
                                    $("#test3_form").html(load);
                                    $.post("fragments/test_III.php", {exam_id:<?php echo $exam_id; ?>}, function(test){
                                        $("#test3_form").html(test);
                                    });
                                });
                                $.post("modal/loader.php", function(load){
                                    $("#test4_form").html(load);
                                    $.post("fragments/test_IV.php", {exam_id:<?php echo $exam_id; ?>}, function(test){
                                        $("#test4_form").html(test);
                                    });
                                });
                                $.post("modal/loader.php", function(load){
                                    $("#test5_form").html(load);
                                    $.post("fragments/test_V.php", {exam_id:<?php echo $exam_id; ?>}, function(test){
                                        $("#test5_form").html(test);
                                    });
                                });

                                $("#submit_test1").click(function (){
                                    var submit = $("#submit_1");
                                    Swal.fire({
                                        title: "TEST I",
                                        html: "Press continue to save test I",
                                        icon: "info",
                                        showCancelButton: true,
                                        confirmButtonColor: "#1c3d77",
                                        confirmButtonText: "Continue",
                                        cancelButtonText: "Cancel",
                                        closeOnConfirm: false,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            submit.click();
                                        }
                                    });
                                });
                                $("#submit_test2").click(function (){
                                    var submit = $("#submit_2");
                                    Swal.fire({
                                        title: "TEST II",
                                        html: "Press continue to save test II",
                                        icon: "info",
                                        showCancelButton: true,
                                        confirmButtonColor: "#1c3d77",
                                        confirmButtonText: "Continue",
                                        cancelButtonText: "Cancel",
                                        closeOnConfirm: false,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            submit.click();
                                        }
                                    });
                                });
                                $("#submit_test3").click(function (){
                                    var submit = $("#submit_3");
                                    Swal.fire({
                                        title: "TEST III",
                                        html: "Press continue to save test III",
                                        icon: "info",
                                        showCancelButton: true,
                                        confirmButtonColor: "#1c3d77",
                                        confirmButtonText: "Continue",
                                        cancelButtonText: "Cancel",
                                        closeOnConfirm: false,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            submit.click();
                                        }
                                    });
                                });
                                $("#submit_test4").click(function (){
                                    var submit = $("#submit_4");
                                    Swal.fire({
                                        title: "TEST IV",
                                        html: "Press continue to save test IV",
                                        icon: "info",
                                        showCancelButton: true,
                                        confirmButtonColor: "#1c3d77",
                                        confirmButtonText: "Continue",
                                        cancelButtonText: "Cancel",
                                        closeOnConfirm: false,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            submit.click();
                                        }
                                    });
                                });
                                $("#submit_test5").click(function (){
                                    var submit = $("#submit_5");
                                    Swal.fire({
                                        title: "TEST V",
                                        html: "Press continue to save test V",
                                        icon: "info",
                                        showCancelButton: true,
                                        confirmButtonColor: "#1c3d77",
                                        confirmButtonText: "Continue",
                                        cancelButtonText: "Cancel",
                                        closeOnConfirm: false,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            submit.click();
                                        }
                                    });
                                });

                                var question_test = "<?php echo $exam_fetch['num_test']; ?>";
                                if(question_test == "1"){
                                    $("#question-testI").show();
                                    $("#question-testII").hide();
                                    $("#question-testIII").hide();
                                    $("#question-testIV").hide();
                                    $("#question-testV").hide();
                                }else if(question_test == "2"){
                                    $("#question-testI").show();
                                    $("#question-testII").show();
                                    $("#question-testIII").hide();
                                    $("#question-testIV").hide();
                                    $("#question-testV").hide();
                                }else if(question_test == "3"){
                                    $("#question-testI").show();
                                    $("#question-testII").show();
                                    $("#question-testIII").show();
                                    $("#question-testIV").hide();
                                    $("#question-testV").hide();
                                }else if(question_test == "4"){
                                    $("#question-testI").show();
                                    $("#question-testII").show();
                                    $("#question-testIII").show();
                                    $("#question-testIV").show();
                                    $("#question-testV").hide();
                                }else if(question_test == "5"){
                                    $("#question-testI").show();
                                    $("#question-testII").show();
                                    $("#question-testIII").show();
                                    $("#question-testIV").show();
                                    $("#question-testV").show();
                                }

                                $("form[name=test_form1]").on("submit", function(ev) {
                                    ev.preventDefault();
                                    var form= new FormData(this);
                                    $.ajax({
                                        url: "../queries/submit_test_img.php",
                                        type: "POST",
                                        data:  form,
                                        contentType: false,
                                        cache: false,
                                        processData: false,
                                        success: function(data) {
                                            if (data == "invalid") {
                                                alert("error");
                                            }
                                            else if (data == 'Exists'){
                                                Swal.fire("IMAGE", "Image Exists, make sure the image file is not already uploaded", "warning");
                                            }
                                        },
                                        error: function() {
                                            Swal.fire("ERROR", "Something Went Wrong!!", "error");
                                        }
                                    }),
                                        $.ajax({
                                            url: "../queries/submit_test_q_opt.php",
                                            type: "POST",
                                            data:  form,
                                            contentType: false,
                                            cache: false,
                                            processData: false,
                                            success: function(data) {
                                                if (data == "invalid") {
                                                    alert("error");
                                                }else {
                                                    $.post("modal/loader.php", function(load){
                                                        $("#test1_form").html(load);
                                                        $.post("fragments/test_I.php", {exam_id:<?php echo $exam_id; ?>}, function(test1){
                                                            $("#test1_form").html(test1);
                                                            Swal.fire("SAVED", "<strong>TEST I </strong> Successfully saved!!", "success");
                                                        });
                                                    });

                                                }
                                            },
                                            error: function() {
                                                Swal.fire("ERROR", "Something Went Wrong!!", "error");
                                            }
                                        });
                                });
                                $("form[name=test_form2]").on("submit", function(ev) {
                                    ev.preventDefault();
                                    var form= new FormData(this);
                                    $.ajax({
                                        url: "../queries/submit_test_img.php",
                                        type: "POST",
                                        data:  form,
                                        contentType: false,
                                        cache: false,
                                        processData: false,
                                        success: function(data) {
                                            if (data == "invalid") {
                                                alert("error");
                                            }
                                            else if (data == 'Exists'){
                                                Swal.fire("IMAGE", "Image Exists, make sure the image file is not already uploaded", "warning");
                                            }
                                        },
                                        error: function() {
                                            Swal.fire("ERROR", "Something Went Wrong!!", "error");
                                        }
                                    }),
                                        $.ajax({
                                            url: "../queries/submit_test_q_opt.php",
                                            type: "POST",
                                            data:  form,
                                            contentType: false,
                                            cache: false,
                                            processData: false,
                                            success: function(data) {
                                                if (data == "invalid") {
                                                    alert("error");
                                                }else {
                                                    $.post("modal/loader.php", function(load){
                                                        $("#test2_form").html(load);
                                                        $.post("fragments/test_II.php", {exam_id:<?php echo $exam_id; ?>}, function(test1){
                                                            $("#test2_form").html(test1);
                                                            Swal.fire("SAVED", "<strong>TEST II </strong> Successfully saved!!", "success");
                                                        });
                                                    });

                                                }
                                            },
                                            error: function() {
                                                Swal.fire("ERROR", "Something Went Wrong!!", "error");
                                            }
                                        });
                                });
                                $("form[name=test_form3]").on("submit", function(ev) {
                                    ev.preventDefault();
                                    var form= new FormData(this);
                                    $.ajax({
                                        url: "../queries/submit_test_img.php",
                                        type: "POST",
                                        data:  form,
                                        contentType: false,
                                        cache: false,
                                        processData: false,
                                        success: function(data) {
                                            if (data == "invalid") {
                                                alert("error");
                                            }
                                            else if (data == 'Exists'){
                                                Swal.fire("IMAGE", "Image Exists, make sure the image file is not already uploaded", "warning");
                                            }
                                        },
                                        error: function() {
                                            Swal.fire("ERROR", "Something Went Wrong!!", "error");
                                        }
                                    }),
                                        $.ajax({
                                            url: "../queries/submit_test_q_opt.php",
                                            type: "POST",
                                            data:  form,
                                            contentType: false,
                                            cache: false,
                                            processData: false,
                                            success: function(data) {
                                                if (data == "invalid") {
                                                    alert("error");
                                                }else {
                                                    $.post("modal/loader.php", function(load){
                                                        $("#test3_form").html(load);
                                                        $.post("fragments/test_III.php", {exam_id:<?php echo $exam_id; ?>}, function(test1){
                                                            $("#test3_form").html(test1);
                                                            Swal.fire("SAVED", "<strong>TEST III </strong> Successfully saved!!", "success");
                                                        });
                                                    });

                                                }
                                            },
                                            error: function() {
                                                Swal.fire("ERROR", "Something Went Wrong!!", "error");
                                            }
                                        });
                                });
                                $("form[name=test_form4]").on("submit", function(ev) {
                                    ev.preventDefault();
                                    var form= new FormData(this);
                                    $.ajax({
                                        url: "../queries/submit_test_img.php",
                                        type: "POST",
                                        data:  form,
                                        contentType: false,
                                        cache: false,
                                        processData: false,
                                        success: function(data) {
                                            if (data == "invalid") {
                                                alert("error");
                                            }
                                            else if (data == 'Exists'){
                                                Swal.fire("IMAGE", "Image Exists, make sure the image file is not already uploaded", "warning");
                                            }
                                        },
                                        error: function() {
                                            Swal.fire("ERROR", "Something Went Wrong!!", "error");
                                        }
                                    }),
                                        $.ajax({
                                            url: "../queries/submit_test_q_opt.php",
                                            type: "POST",
                                            data:  form,
                                            contentType: false,
                                            cache: false,
                                            processData: false,
                                            success: function(data) {
                                                if (data == "invalid") {
                                                    alert("error");
                                                }else {
                                                    $.post("modal/loader.php", function(load){
                                                        $("#test4_form").html(load);
                                                        $.post("fragments/test_IV.php", {exam_id:<?php echo $exam_id; ?>}, function(test1){
                                                            $("#test4_form").html(test1);
                                                            Swal.fire("SAVED", "<strong>TEST IV </strong> Successfully saved!!", "success");
                                                        });
                                                    });

                                                }
                                            },
                                            error: function() {
                                                Swal.fire("ERROR", "Something Went Wrong!!", "error");
                                            }
                                        });
                                });
                                $("form[name=test_form5]").on("submit", function(ev) {
                                    ev.preventDefault();
                                    var form= new FormData(this);
                                    $.ajax({
                                        url: "../queries/submit_test_img.php",
                                        type: "POST",
                                        data:  form,
                                        contentType: false,
                                        cache: false,
                                        processData: false,
                                        success: function(data) {
                                            if (data == "invalid") {
                                                alert("error");
                                            }
                                            else if (data == 'Exists'){
                                                Swal.fire("IMAGE", "Image Exists, make sure the image file is not already uploaded", "warning");
                                            }
                                        },
                                        error: function() {
                                            Swal.fire("ERROR", "Something Went Wrong!!", "error");
                                        }
                                    }),
                                        $.ajax({
                                            url: "../queries/submit_test_q_opt.php",
                                            type: "POST",
                                            data:  form,
                                            contentType: false,
                                            cache: false,
                                            processData: false,
                                            success: function(data) {
                                                if (data == "invalid") {
                                                    alert("error");
                                                }else {
                                                    $.post("modal/loader.php", function(load){
                                                        $("#test5_form").html(load);
                                                        $.post("fragments/test_V.php", {exam_id:<?php echo $exam_id; ?>}, function(test1){
                                                            $("#test5_form").html(test1);
                                                            Swal.fire("SAVED", "<strong>TEST V </strong> Successfully saved!!", "success");
                                                        });
                                                    });

                                                }
                                            },
                                            error: function() {
                                                Swal.fire("ERROR", "Something Went Wrong!!", "error");
                                            }
                                        });
                                });
                            });

                        </script>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4><?php echo $exam_fetch['title']; ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mt-4">
                                            <div class="col-12 col-lg-8 offset-lg-2">
                                                <div class="wizard-steps">
                                                    <div class="wizard-step " id="exam_details" style="cursor:pointer;">
                                                        <div class="wizard-step-icon">
                                                            <i class="far fa-file-alt"></i>
                                                        </div>
                                                        <div class="wizard-step-label">
                                                            Exam Details
                                                        </div>
                                                    </div>
                                                    <div class="wizard-step wizard-step-active" id="question_details">
                                                        <div class="wizard-step-icon">
                                                            <i class="fas fa-question-circle"></i>
                                                        </div>
                                                        <div class="wizard-step-label">
                                                            Question Details
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div class="wizard-content mt-auto" id="questiontab">
                                            <div class="wizard-pane">
                                                <div class="form-group row">
                                                    <div class="col-md-2 text-md-right text-center mt-2">
                                                    </div>
                                                    <div class="col-md-1 text-md-center text-center mt-2">
                                                        <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" id="question-testI" data-toggle="tab" href="#test1" role="tab" aria-controls="home" aria-selected="true" >
                                                                    Test I</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="question-testII" data-toggle="tab" href="#test2" role="tab" aria-controls="home" aria-selected="true" >
                                                                    Test II</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="question-testIII" data-toggle="tab" href="#test3" role="tab" aria-controls="home" aria-selected="true" >
                                                                    Test III</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="question-testIV" data-toggle="tab" href="#test4" role="tab" aria-controls="home" aria-selected="true" >
                                                                    Test IV</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="question-testV" data-toggle="tab" href="#test5" role="tab" aria-controls="home" aria-selected="true" >
                                                                    Test V</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-xl-7 col-sm-12 col-md-8">
                                                        <div class="tab-content no-padding" id="myTab2Content">
                                                            <div class="tab-pane fade show active" id="test1" role="tabpanel" >
                                                                <form name="test_form1" enctype="multipart/form-data">
                                                                    <input class="form-control" type="hidden" name="department" value="<?php echo $department;?>">
                                                                    <input class="form-control" type="hidden" name="test_div" value="<?php echo $division;?>">
                                                                    <div class="row" id="test1_form">

                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-xl-3"></div>
                                                                        <div class="col-xl-12 col-md-6 text-md-right text-right">
                                                                            <button class="btn btn-primary" type="submit" id="submit_1" name="submit_1" style="display: none;">Submit</button>
                                                                            <button class="btn btn-primary" type="button" id="submit_test1">Submit</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="tab-pane fade" id="test2" role="tabpanel" aria-labelledby="question-testII">
                                                                <form name="test_form2" enctype="multipart/form-data">
                                                                    <input class="form-control" type="hidden" name="department" value="<?php echo $department;?>">
                                                                    <input class="form-control" type="hidden" name="test_div" value="<?php echo $division;?>">
                                                                    <div class="row" id="test2_form">

                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-xl-3"></div>
                                                                        <div class="col-xl-12 col-md-6 text-md-right text-right">
                                                                            <button class="btn btn-primary" type="submit" id="submit_2" name="submit_2" style="display: none;">Submit</button>
                                                                            <button class="btn btn-primary" type="button" id="submit_test2">Submit</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="tab-pane fade" id="test3" role="tabpanel" aria-labelledby="question-testIII">
                                                                <form name="test_form3" enctype="multipart/form-data">
                                                                    <input class="form-control" type="hidden" name="department" value="<?php echo $department;?>">
                                                                    <input class="form-control" type="hidden" name="test_div" value="<?php echo $division;?>">
                                                                    <div class="row" id="test3_form">

                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-xl-3"></div>
                                                                        <div class="col-xl-12 col-md-6 text-md-right text-right">
                                                                            <button class="btn btn-primary" type="submit" id="submit_3" name="submit_3" style="display: none;">Submit</button>
                                                                            <button class="btn btn-primary" type="button" id="submit_test3">Submit</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="tab-pane fade" id="test4" role="tabpanel" aria-labelledby="question-testIV">
                                                                <form name="test_form4" enctype="multipart/form-data">
                                                                    <input class="form-control" type="hidden" name="department" value="<?php echo $department;?>">
                                                                    <input class="form-control" type="hidden" name="test_div" value="<?php echo $division;?>">
                                                                    <div class="row" id="test4_form">

                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-xl-3"></div>
                                                                        <div class="col-xl-12 col-md-6 text-md-right text-right">
                                                                            <button class="btn btn-primary" type="submit" id="submit_4" name="submit_4" style="display: none;">Submit</button>
                                                                            <button class="btn btn-primary" type="button" id="submit_test4">Submit</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="tab-pane fade" id="test5" role="tabpanel" aria-labelledby="question-testV">
                                                                <form name="test_form5" enctype="multipart/form-data">
                                                                    <input class="form-control" type="hidden" name="department" value="<?php echo $department;?>">
                                                                    <input class="form-control" type="hidden" name="test_div" value="<?php echo $division;?>">
                                                                    <div class="row" id="test5_form">

                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-xl-3"></div>
                                                                        <div class="col-xl-12 col-md-6 text-md-right text-right">
                                                                            <button class="btn btn-primary" type="submit" id="submit_5" name="submit_5" style="display: none;">Submit</button>
                                                                            <button class="btn btn-primary" type="button" id="submit_test5">Submit</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </section>
        </div>
    </div>
</div>
<script src="../../assets/js/page/main_add_question.js"></script>
<?php include "modal/loadingModal.php"; ?>
</body>
</html>