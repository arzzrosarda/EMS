
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
                    <a href="main_admin.php"><img id="img-logo" src="../../assets/img/logo/Cavite_Province.png"/></a>
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
            <div class="section">
                <?php if (isset($_REQUEST['exam_id'])){ ?>
                    <div class="section-header" id="header_exam_details">
                        <h1>Exam Details</h1>
                        <div class="section-header-breadcrumb">
                            <div class="breadcrumb-item "><a href="main_admin.php">Dashboard</a></div>
                            <div class="breadcrumb-item"><a href="list_exam.php">Exam Monitoring</a></div>
                            <div class="breadcrumb-item active">Exam Details</div>
                        </div>
                    </div>
                <?php }else { ?>
                    <div class="section-header" id="header_exam_details">
                        <h1>Create Exam</h1>
                        <div class="section-header-breadcrumb">
                            <div class="breadcrumb-item "> <a href="main_admin.php">Dashboard</a></div>
                            <div class="breadcrumb-item"><a href="list_exam.php">Exam Monitoring</a></div>
                            <div class="breadcrumb-item active">Create Exam</div>
                        </div>
                    </div>
                <?php } ?>
                <div class="section-body">
                    <?php
                    if (isset($_REQUEST['exam_id'])){
                        $exam_id = $_REQUEST['exam_id'];
                        $examQuery = $conn->query("SELECT a.`title`, a.`department`, a.`division`, a.`num_test`, a.`time_limit`, b.`Test_I`, b.`Test_II`, b.`Test_III`, b.`Test_IV`, b.`Test_V` FROM exam_title a LEFT JOIN exam_test b ON a.`id` = b.`exam_id` WHERE a.`id` = '$exam_id'");
                        $exam_fetch = $examQuery->fetch();
                        $department = $exam_fetch['department'];
                        $division = $exam_fetch['division'];
                        $test_I = $exam_fetch['Test_I'];
                        $test_II = $exam_fetch['Test_II'];
                        $test_III = $exam_fetch['Test_III'];
                        $test_IV = $exam_fetch['Test_IV'];
                        $test_V = $exam_fetch['Test_V'];
                        $txt_testI = '';
                        $txt_testII = '';
                        $txt_testIII = '';
                        $txt_testIV = '';
                        $txt_testV = '';

                        if ($test_I == 'Multiple Choice'){
                            $txt_testI = 'MC';
                        }else if ($test_I == 'Short Answer'){
                            $txt_testI = 'S';
                        }else if ($test_I == 'True/False'){
                            $txt_testI = 'TF';
                        }else if ($test_I == 'Essay'){
                            $txt_testI = 'E';
                        }else if ($test_I == 'Multiple Image'){
                            $txt_testI = 'MCI';
                        }

                        if ($test_II == 'Multiple Choice'){
                            $txt_testII = 'MC';
                        }else if ($test_II == 'Short Answer'){
                            $txt_testII = 'S';
                        }else if ($test_II == 'True/False'){
                            $txt_testII = 'TF';
                        }else if ($test_II == 'Essay'){
                            $txt_testII = 'E';
                        }else if ($test_II == 'Multiple Image'){
                            $txt_testII = 'MCI';
                        }

                        if ($test_III == 'Multiple Choice'){
                            $txt_testIII = 'MC';
                        }else if ($test_III == 'Short Answer'){
                            $txt_testIII = 'S';
                        }else if ($test_III == 'True/False'){
                            $txt_testIII = 'TF';
                        }else if ($test_III == 'Essay'){
                            $txt_testIII = 'E';
                        }else if ($test_III == 'Multiple Image'){
                            $txt_testIII = 'MCI';
                        }

                        if ($test_IV == 'Multiple Choice'){
                            $txt_testIV = 'MC';
                        }else if ($test_IV == 'Short Answer'){
                            $txt_testIV = 'S';
                        }else if ($test_IV == 'True/False'){
                            $txt_testIV = 'TF';
                        }else if ($test_IV == 'Essay'){
                            $txt_testIV = 'E';
                        }else if ($test_IV == 'Multiple Image'){
                            $txt_testIV = 'MCI';
                        }

                        if ($test_V == 'Multiple Choice'){
                            $txt_testV = 'MC';
                        }else if ($test_V == 'Short Answer'){
                            $txt_testV = 'S';
                        }else if ($test_V == 'True/False'){
                            $txt_testV = 'TF';
                        }else if ($test_V == 'Essay'){
                            $txt_testV = 'E';
                        }else if ($test_V == 'Multiple Image'){
                            $txt_testV = 'MCI';
                        } ?>
                        <script>
                            $(document).ready(function (){
                                $("#deptselect").val("<?php echo $department; ?>");

                                var time = $("#time").val();
                                var time1 = time.split(":");
                                $("#hoursel").val(String(time1[0]));
                                $("#minsel").val(String(time1[1]));
                                $("#secsel").val(String(time1[2]));
                                $("#testsel").val("<?php echo $exam_fetch['num_test']; ?>");
                                test = $("#testsel option:selected").val();
                                if (test == "1"){
                                    $("#testI").show();
                                    $("#testII").hide();
                                    $("#testIII").hide();
                                    $("#testIV").hide();
                                    $("#testV").hide();
                                    $("#txt_testI").val("<?php echo $txt_testI; ?>");
                                    $("#txt_testII").val("");
                                    $("#txt_testIII").val("");
                                    $("#txt_testIV").val("");
                                    $("#txt_testV").val("");
                                }
                                else if (test == "2"){
                                    $("#testI").show();
                                    $("#testII").show();
                                    $("#testIII").hide();
                                    $("#testIV").hide();
                                    $("#testV").hide();
                                    $("#txt_testI").val("<?php echo  $txt_testI; ?>");
                                    $("#txt_testII").val("<?php echo $txt_testII; ?>");
                                    $("#txt_testIII").val("");
                                    $("#txt_testIV").val("");
                                    $("#txt_testV").val("");
                                }
                                else if (test == "3"){
                                    $("#testI").show();
                                    $("#testII").show();
                                    $("#testIII").show();
                                    $("#testIV").hide();
                                    $("#testV").hide();
                                    $("#txt_testI").val("<?php echo  $txt_testI; ?>");
                                    $("#txt_testII").val("<?php echo $txt_testII; ?>");
                                    $("#txt_testIII").val("<?php echo $txt_testIII; ?>");
                                    $("#txt_testIV").val("");
                                    $("#txt_testV").val("");
                                }
                                else if (test == "4"){
                                    $("#testI").show();
                                    $("#testII").show();
                                    $("#testIII").show();
                                    $("#testIV").show();
                                    $("#testV").hide();
                                    $("#txt_testI").val("<?php echo $txt_testI; ?>");
                                    $("#txt_testII").val("<?php echo $txt_testII; ?>");
                                    $("#txt_testIII").val("<?php echo $txt_testIII; ?>");
                                    $("#txt_testIV").val("<?php echo $txt_testIV; ?>");
                                    $("#txt_testV").val("");
                                }
                                else if (test == "5"){
                                    $("#testI").show();
                                    $("#txt_testI").val("<?php echo $txt_testI; ?>");
                                    $("#testII").show();
                                    $("#txt_testII").val("<?php echo $txt_testII; ?>");
                                    $("#testIII").show();
                                    $("#txt_testIII").val("<?php echo $txt_testIII; ?>");
                                    $("#testIV").show();
                                    $("#txt_testIV").val("<?php echo $txt_testIV; ?>");
                                    $("#testV").show();
                                    $("#txt_testV").val("<?php echo $txt_testV; ?>");
                                }
                                else {
                                    $("#testI").hide();
                                    $("#testII").hide();
                                    $("#testIII").hide();
                                    $("#testIV").hide();
                                    $("#testV").hide();
                                    $("#txt_testI").val("");
                                    $("#txt_testII").val("");
                                    $("#txt_testIII").val("");
                                    $("#txt_testIV").val("");
                                    $("#txt_testV").val("");

                                }
                                $(".dblist option").css({"display":"block"});
                                $(".db-list").each(function() {
                                    var val = this.value;
                                    $(".db-list").not(this).find("option").filter(function() {
                                        return this.value === val;
                                    }).css({"display": "none"});
                                });
                                $("#btnquestiondetails").click( function (){
                                    Swal.fire({
                                        title: "QUESTION DETAILS",
                                        html: "you are about to go into question details, all of your progress won't be saved, are you sure?",
                                        icon: "info",
                                        showCancelButton: true,
                                        confirmButtonColor: "#1c3d77",
                                        confirmButtonText: "Yes, I am sure",
                                        cancelButtonText: "No, Cancel it",
                                        closeOnConfirm: false,
                                        closeOnCancel: true
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            $.redirect("add_question.php", {exam_id:<?php echo $exam_id; ?>});
                                        }
                                    });
                                });
                                $("#question_details").click( function (){
                                    Swal.fire({
                                        title: "QUESTION DETAILS",
                                        html: "you are about to go into question details, all of your progress won't be saved, are you sure?",
                                        icon: "info",
                                        showCancelButton: true,
                                        confirmButtonColor: "#1c3d77",
                                        confirmButtonText: "Yes, I am sure",
                                        cancelButtonText: "No, Cancel it",
                                        closeOnConfirm: false,
                                        closeOnCancel: true
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            $.redirect("add_question.php", {exam_id:<?php echo $exam_id; ?>});
                                        }
                                    });
                                });
                                var dep = $("#deptselect option:selected").val();
                                if (dep != ''){
                                    $.post("fragments/division_select.php", {department:dep}, function (div){
                                        $("#diviselect").html(div);
                                        $("#diviselect").val("<?php echo $division; ?>");
                                    });
                                }else {
                                    $.post("fragments/disabled_division.php", function (div){
                                        $("#diviselect").html(div);
                                    });
                                }
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
                                                    <div class="wizard-step wizard-step-active" id="exam_details">
                                                        <div class="wizard-step-icon">
                                                            <i class="far fa-file-alt"></i>
                                                        </div>
                                                        <div class="wizard-step-label">
                                                            Exam Details
                                                        </div>
                                                    </div>
                                                    <div class="wizard-step" id="question_details" style="cursor:pointer;">
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
                                        <div class="wizard-content mt-auto">
                                            <div class="wizard-pane">
                                                <div class="form-group row align-items-center">
                                                    <label class="col-md-3 text-md-right text-left">Title <label style="color:red;">*</label></label>
                                                    <div class="col-lg-7 col-md-6">
                                                        <input type="hidden" name="txt_exam_id" class="form-control" value="<?php echo $exam_id; ?>" required>
                                                        <input type="text" name="txt_title" class="form-control" value="<?php echo $exam_fetch['title']; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group row align-items-center">
                                                    <label class="col-md-3 text-md-right text-left" for="deptselect">Department <label style="color:red;">*</label></label>
                                                    <div class="col-lg-7 col-md-6">
                                                        <select id="deptselect" class="form-control" required>
                                                            <option value="">Select Department</option>
                                                            <?php
                                                            $department_query = $conn->query("SELECT * FROM department");
                                                            while ($department = $department_query->fetch()){?>
                                                                <option value="<?php echo $department['department']; ?>"><?php echo $department['department']; ?></option>
                                                            <?php }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row align-items-center">
                                                    <label class="col-md-3 text-md-right text-left" for="diviselect">Division <label style="color:red;">*</label></label>
                                                    <div class="col-lg-7 col-md-6">
                                                        <select id="diviselect" name="division" class="form-control">

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-3 text-md-right text-left mt-2">Time Limit <label style="color:red;">*</label></label>
                                                    <div class="col-lg-7 col-md-6">
                                                        <div class="input-group">
                                                            <input type="text" id="time" class="form-control" value="<?php echo $exam_fetch['time_limit']; ?>" style="display: none;">
                                                            <label class="col-md-1 text-md-right text-left mt-2">Hours:</label>
                                                            <div class="col-lg-2 col-md-6">
                                                                <select id="hoursel" name="hour" class="form-control" required>
                                                                    <option value="">hour</option>
                                                                    <option value="00">00</option>
                                                                    <option value="01">01</option>
                                                                    <option value="02">02</option>
                                                                    <option value="03">03</option>
                                                                    <option value="04">04</option>
                                                                    <option value="05">05</option>
                                                                    <option value="06">06</option>
                                                                    <option value="07">07</option>
                                                                    <option value="08">08</option>
                                                                    <option value="09">09</option>
                                                                    <option value="10">10</option>
                                                                    <option value="11">11</option>
                                                                    <option value="12">12</option>
                                                                    <option value="13">13</option>
                                                                    <option value="14">14</option>
                                                                    <option value="15">15</option>
                                                                    <option value="16">16</option>
                                                                    <option value="17">17</option>
                                                                    <option value="18">18</option>
                                                                    <option value="19">19</option>
                                                                    <option value="20">20</option>
                                                                    <option value="21">21</option>
                                                                    <option value="22">22</option>
                                                                    <option value="23">23</option>
                                                                    <option value="24">24</option>
                                                                </select >
                                                            </div>
                                                            <label class="col-md-2 text-md-right text-left mt-2">Minutes :</label>
                                                            <div class="col-lg-2 col-md-6">
                                                                <select id="minsel" name="minutes" class="form-control" required>
                                                                    <option value="">Minutes</option>
                                                                    <option value="00">00</option>
                                                                    <option value="01">01</option>
                                                                    <option value="02">02</option>
                                                                    <option value="03">03</option>
                                                                    <option value="04">04</option>
                                                                    <option value="05">05</option>
                                                                    <option value="06">06</option>
                                                                    <option value="07">07</option>
                                                                    <option value="08">08</option>
                                                                    <option value="09">09</option>
                                                                    <option value="10">10</option>
                                                                    <option value="11">11</option>
                                                                    <option value="12">12</option>
                                                                    <option value="13">13</option>
                                                                    <option value="14">14</option>
                                                                    <option value="15">15</option>
                                                                    <option value="16">16</option>
                                                                    <option value="17">17</option>
                                                                    <option value="18">18</option>
                                                                    <option value="19">19</option>
                                                                    <option value="20">20</option>
                                                                    <option value="21">21</option>
                                                                    <option value="22">22</option>
                                                                    <option value="23">23</option>
                                                                    <option value="24">24</option>
                                                                    <option value="25">25</option>
                                                                    <option value="26">26</option>
                                                                    <option value="27">27</option>
                                                                    <option value="28">28</option>
                                                                    <option value="29">29</option>
                                                                    <option value="30">30</option>
                                                                    <option value="31">31</option>
                                                                    <option value="32">32</option>
                                                                    <option value="33">33</option>
                                                                    <option value="34">34</option>
                                                                    <option value="35">35</option>
                                                                    <option value="36">36</option>
                                                                    <option value="37">37</option>
                                                                    <option value="38">38</option>
                                                                    <option value="39">39</option>
                                                                    <option value="40">40</option>
                                                                    <option value="41">41</option>
                                                                    <option value="42">42</option>
                                                                    <option value="43">43</option>
                                                                    <option value="44">44</option>
                                                                    <option value="45">45</option>
                                                                    <option value="46">46</option>
                                                                    <option value="47">47</option>
                                                                    <option value="48">48</option>
                                                                    <option value="49">49</option>
                                                                    <option value="50">50</option>
                                                                    <option value="51">51</option>
                                                                    <option value="52">52</option>
                                                                    <option value="53">53</option>
                                                                    <option value="54">54</option>
                                                                    <option value="55">55</option>
                                                                    <option value="56">56</option>
                                                                    <option value="57">57</option>
                                                                    <option value="58">58</option>
                                                                    <option value="59">59</option>
                                                                </select>
                                                            </div>
                                                            <label class="col-md-2 text-md-right text-left mt-2">Seconds :</label>
                                                            <div class="col-lg-2 col-md-6">
                                                                <select id="secsel" name="seconds" class="form-control" required>
                                                                    <option value="">Seconds</option>
                                                                    <option value="00">00</option>
                                                                    <option value="01">01</option>
                                                                    <option value="02">02</option>
                                                                    <option value="03">03</option>
                                                                    <option value="04">04</option>
                                                                    <option value="05">05</option>
                                                                    <option value="06">06</option>
                                                                    <option value="07">07</option>
                                                                    <option value="08">08</option>
                                                                    <option value="09">09</option>
                                                                    <option value="10">10</option>
                                                                    <option value="11">11</option>
                                                                    <option value="12">12</option>
                                                                    <option value="13">13</option>
                                                                    <option value="14">14</option>
                                                                    <option value="15">15</option>
                                                                    <option value="16">16</option>
                                                                    <option value="17">17</option>
                                                                    <option value="18">18</option>
                                                                    <option value="19">19</option>
                                                                    <option value="20">20</option>
                                                                    <option value="21">21</option>
                                                                    <option value="22">22</option>
                                                                    <option value="23">23</option>
                                                                    <option value="24">24</option>
                                                                    <option value="25">25</option>
                                                                    <option value="26">26</option>
                                                                    <option value="27">27</option>
                                                                    <option value="28">28</option>
                                                                    <option value="29">29</option>
                                                                    <option value="30">30</option>
                                                                    <option value="31">31</option>
                                                                    <option value="32">32</option>
                                                                    <option value="33">33</option>
                                                                    <option value="34">34</option>
                                                                    <option value="35">35</option>
                                                                    <option value="36">36</option>
                                                                    <option value="37">37</option>
                                                                    <option value="38">38</option>
                                                                    <option value="39">39</option>
                                                                    <option value="40">40</option>
                                                                    <option value="41">41</option>
                                                                    <option value="42">42</option>
                                                                    <option value="43">43</option>
                                                                    <option value="44">44</option>
                                                                    <option value="45">45</option>
                                                                    <option value="46">46</option>
                                                                    <option value="47">47</option>
                                                                    <option value="48">48</option>
                                                                    <option value="49">49</option>
                                                                    <option value="50">50</option>
                                                                    <option value="51">51</option>
                                                                    <option value="52">52</option>
                                                                    <option value="53">53</option>
                                                                    <option value="54">54</option>
                                                                    <option value="55">55</option>
                                                                    <option value="56">56</option>
                                                                    <option value="57">57</option>
                                                                    <option value="58">58</option>
                                                                    <option value="59">59</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-3 text-md-right text-left mt-2">Exam Test <label style="color:red;">*</label></label>
                                                    <div class="col-lg-7 col-md-6">
                                                        <select id="testsel" name="test_num" class="form-control" required>
                                                            <option value="">Select Here.....</option>
                                                            <option value="1">Test I Only</option>
                                                            <option value="2">Test I - Test II</option>
                                                            <option value="3">Test I - Test III</option>
                                                            <option value="4">Test I - Test IV</option>
                                                            <option value="5">Test I - Test V</option>
                                                        </select>
                                                        <span style="font-size: 10px;">Note: Limit to only Test V at most</span>
                                                    </div>
                                                </div>
                                                <div id="fields-list">
                                                    <div class="form-group row" id="testI" style="display: none;">
                                                        <label class="col-md-3 text-md-right text-left mt-2">Test I <label style="color:red;">*</label></label>
                                                        <div class="col-lg-7 col-md-6">
                                                            <select id="txt_testI" name="Test_I" class="form-control db-list">
                                                                <option value="">Select Here.....</option>
                                                                <option value="MC">Multiple Choice</option>
                                                                <option value="S">Short Answer</option>
                                                                <option value="TF">True or False</option>
                                                                <option value="MCI">Multiple Choice (Images)</option>
                                                                <option value="E">Essay</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row" id="testII" style="display: none;">
                                                        <label class="col-md-3 text-md-right text-left mt-2">Test II <label style="color:red;">*</label></label>
                                                        <div class="col-lg-7 col-md-6">
                                                            <select id="txt_testII" name="Test_II" class="form-control db-list">
                                                                <option value="">Select Here.....</option>
                                                                <option value="MC">Multiple Choice</option>
                                                                <option value="S">Short Answer</option>
                                                                <option value="TF">True or False</option>
                                                                <option value="MCI">Multiple Choice (Images)</option>
                                                                <option value="E">Essay</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row" id="testIII" style="display: none;">
                                                        <label class="col-md-3 text-md-right text-left mt-2">Test III <label style="color:red;">*</label></label>
                                                        <div class="col-lg-7 col-md-6">
                                                            <select id="txt_testIII" name="Test_III" class="form-control db-list">
                                                                <option value="">Select Here.....</option>
                                                                <option value="MC">Multiple Choice</option>
                                                                <option value="S">Short Answer</option>
                                                                <option value="TF">True or False</option>
                                                                <option value="MCI">Multiple Choice (Images)</option>
                                                                <option value="E">Essay</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row" id="testIV" style="display: none;">
                                                        <label class="col-md-3 text-md-right text-left mt-2">Test IV <label style="color:red;">*</label></label>
                                                        <div class="col-lg-7 col-md-6">
                                                            <select id="txt_testIV" name="Test_IV" class="form-control db-list">
                                                                <option value="">Select Here.....</option>
                                                                <option value="MC">Multiple Choice</option>
                                                                <option value="S">Short Answer</option>
                                                                <option value="TF">True or False</option>
                                                                <option value="MCI">Multiple Choice (Images)</option>
                                                                <option value="E">Essay</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row" id="testV" style="display: none;">
                                                        <label class="col-md-3 text-md-right text-left mt-2">Test V <label style="color:red;">*</label></label>
                                                        <div class="col-lg-7 col-md-6">
                                                            <select id="txt_testV" name="Test_V" class="form-control db-list">
                                                                <option value="">Select Here.....</option>
                                                                <option value="MC">Multiple Choice</option>
                                                                <option value="S">Short Answer</option>
                                                                <option value="TF">True or False</option>
                                                                <option value="MCI">Multiple Choice (Images)</option>
                                                                <option value="E">Essay</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-lg-7 col-md-6 text-right">
                                                        <button class="btn btn-icon icon-right btn-primary" type="button" id="btnupdateexamtitle">Save <i class="fas fa-save"></i></button>
                                                        <button class="btn btn-icon icon-right btn-primary" type="button" id="btnquestiondetails">Next <i class="fas fa-arrow-right"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                    else { ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4></h4>
                                        <div class="card-header-action">

                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mt-4">
                                            <div class="col-12 col-lg-8 offset-lg-2">
                                                <div class="wizard-steps">
                                                    <div class="wizard-step wizard-step-active" id="exam_details">
                                                        <div class="wizard-step-icon">
                                                            <i class="far fa-file-alt"></i>
                                                        </div>
                                                        <div class="wizard-step-label">
                                                            Create Exam
                                                        </div>
                                                    </div>
                                                    <div class="wizard-step" id="question_details">
                                                        <div class="wizard-step-icon">
                                                            <i class="fas fa-question-circle"></i>
                                                        </div>
                                                        <div class="wizard-step-label">
                                                            Create Questions
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wizard-content mt-auto" id="examdetailstab">
                                            <div class="wizard-pane">
                                                <div class="form-group row align-items-center">
                                                    <label class="col-md-3 text-md-right text-left">Title <label style="color:red;">*</label></label>
                                                    <div class="col-lg-7 col-md-6">
                                                        <input type="text" name="txt_title" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row align-items-center">
                                                    <label class="col-md-3 text-md-right text-left" for="depselect">Department <label style="color:red;">*</label></label>
                                                    <div class="col-lg-7 col-md-6">
                                                        <select id="depselect" class="form-control" required>
                                                            <option value="">Select Department</option>
                                                            <?php
                                                            $department_query = $conn->query("SELECT * FROM department");
                                                            while ($department = $department_query->fetch()){?>
                                                                <option value="<?php echo $department['department']; ?>"><?php echo $department['department']; ?></option>
                                                            <?php }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row align-items-center">
                                                    <label class="col-md-3 text-md-right text-left" for="divselect">Division <label style="color:red;">*</label></label>
                                                    <div class="col-lg-7 col-md-6" >
                                                        <select id="divselect" class="form-control">

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-3 text-md-right text-left mt-2">Time Limit <label style="color:red;">*</label></label>
                                                    <div class="col-lg-7 col-md-6">
                                                        <div class="input-group">
                                                            <label class="col-md-1 text-md-right text-left mt-2">Hours:</label>
                                                            <div class="col-lg-2 col-md-6">
                                                                <select id="hoursel" class="form-control" required>
                                                                    <option value="">hour</option>
                                                                    <option value="00">00</option>
                                                                    <option value="01">01</option>
                                                                    <option value="02">02</option>
                                                                    <option value="03">03</option>
                                                                    <option value="04">04</option>
                                                                    <option value="05">05</option>
                                                                    <option value="06">06</option>
                                                                    <option value="07">07</option>
                                                                    <option value="08">08</option>
                                                                    <option value="09">09</option>
                                                                    <option value="10">10</option>
                                                                    <option value="11">11</option>
                                                                    <option value="12">12</option>
                                                                    <option value="13">13</option>
                                                                    <option value="14">14</option>
                                                                    <option value="15">15</option>
                                                                    <option value="16">16</option>
                                                                    <option value="17">17</option>
                                                                    <option value="18">18</option>
                                                                    <option value="19">19</option>
                                                                    <option value="20">20</option>
                                                                    <option value="21">21</option>
                                                                    <option value="22">22</option>
                                                                    <option value="23">23</option>
                                                                    <option value="24">24</option>
                                                                </select >
                                                            </div>
                                                            <label class="col-md-2 text-md-right text-left mt-2">Minutes :</label>
                                                            <div class="col-lg-2 col-md-6">
                                                                <select id="minsel" class="form-control" required>
                                                                    <option value="">Minutes</option>
                                                                    <option value="00">00</option>
                                                                    <option value="01">01</option>
                                                                    <option value="02">02</option>
                                                                    <option value="03">03</option>
                                                                    <option value="04">04</option>
                                                                    <option value="05">05</option>
                                                                    <option value="06">06</option>
                                                                    <option value="07">07</option>
                                                                    <option value="08">08</option>
                                                                    <option value="09">09</option>
                                                                    <option value="10">10</option>
                                                                    <option value="11">11</option>
                                                                    <option value="12">12</option>
                                                                    <option value="13">13</option>
                                                                    <option value="14">14</option>
                                                                    <option value="15">15</option>
                                                                    <option value="16">16</option>
                                                                    <option value="17">17</option>
                                                                    <option value="18">18</option>
                                                                    <option value="19">19</option>
                                                                    <option value="20">20</option>
                                                                    <option value="21">21</option>
                                                                    <option value="22">22</option>
                                                                    <option value="23">23</option>
                                                                    <option value="24">24</option>
                                                                    <option value="25">25</option>
                                                                    <option value="26">26</option>
                                                                    <option value="27">27</option>
                                                                    <option value="28">28</option>
                                                                    <option value="29">29</option>
                                                                    <option value="30">30</option>
                                                                    <option value="31">31</option>
                                                                    <option value="32">32</option>
                                                                    <option value="33">33</option>
                                                                    <option value="34">34</option>
                                                                    <option value="35">35</option>
                                                                    <option value="36">36</option>
                                                                    <option value="37">37</option>
                                                                    <option value="38">38</option>
                                                                    <option value="39">39</option>
                                                                    <option value="40">40</option>
                                                                    <option value="41">41</option>
                                                                    <option value="42">42</option>
                                                                    <option value="43">43</option>
                                                                    <option value="44">44</option>
                                                                    <option value="45">45</option>
                                                                    <option value="46">46</option>
                                                                    <option value="47">47</option>
                                                                    <option value="48">48</option>
                                                                    <option value="49">49</option>
                                                                    <option value="50">50</option>
                                                                    <option value="51">51</option>
                                                                    <option value="52">52</option>
                                                                    <option value="53">53</option>
                                                                    <option value="54">54</option>
                                                                    <option value="55">55</option>
                                                                    <option value="56">56</option>
                                                                    <option value="57">57</option>
                                                                    <option value="58">58</option>
                                                                    <option value="59">59</option>
                                                                </select>
                                                            </div>
                                                            <label class="col-md-2 text-md-right text-left mt-2">Seconds :</label>
                                                            <div class="col-lg-2 col-md-6">
                                                                <select id="secsel" class="form-control" required>
                                                                    <option value="">Seconds</option>
                                                                    <option value="00">00</option>
                                                                    <option value="01">01</option>
                                                                    <option value="02">02</option>
                                                                    <option value="03">03</option>
                                                                    <option value="04">04</option>
                                                                    <option value="05">05</option>
                                                                    <option value="06">06</option>
                                                                    <option value="07">07</option>
                                                                    <option value="08">08</option>
                                                                    <option value="09">09</option>
                                                                    <option value="10">10</option>
                                                                    <option value="11">11</option>
                                                                    <option value="12">12</option>
                                                                    <option value="13">13</option>
                                                                    <option value="14">14</option>
                                                                    <option value="15">15</option>
                                                                    <option value="16">16</option>
                                                                    <option value="17">17</option>
                                                                    <option value="18">18</option>
                                                                    <option value="19">19</option>
                                                                    <option value="20">20</option>
                                                                    <option value="21">21</option>
                                                                    <option value="22">22</option>
                                                                    <option value="23">23</option>
                                                                    <option value="24">24</option>
                                                                    <option value="25">25</option>
                                                                    <option value="26">26</option>
                                                                    <option value="27">27</option>
                                                                    <option value="28">28</option>
                                                                    <option value="29">29</option>
                                                                    <option value="30">30</option>
                                                                    <option value="31">31</option>
                                                                    <option value="32">32</option>
                                                                    <option value="33">33</option>
                                                                    <option value="34">34</option>
                                                                    <option value="35">35</option>
                                                                    <option value="36">36</option>
                                                                    <option value="37">37</option>
                                                                    <option value="38">38</option>
                                                                    <option value="39">39</option>
                                                                    <option value="40">40</option>
                                                                    <option value="41">41</option>
                                                                    <option value="42">42</option>
                                                                    <option value="43">43</option>
                                                                    <option value="44">44</option>
                                                                    <option value="45">45</option>
                                                                    <option value="46">46</option>
                                                                    <option value="47">47</option>
                                                                    <option value="48">48</option>
                                                                    <option value="49">49</option>
                                                                    <option value="50">50</option>
                                                                    <option value="51">51</option>
                                                                    <option value="52">52</option>
                                                                    <option value="53">53</option>
                                                                    <option value="54">54</option>
                                                                    <option value="55">55</option>
                                                                    <option value="56">56</option>
                                                                    <option value="57">57</option>
                                                                    <option value="58">58</option>
                                                                    <option value="59">59</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-3 text-md-right text-left mt-2">Exam Test <label style="color:red;">*</label></label>
                                                    <div class="col-lg-7 col-md-6">
                                                        <select id="testsel" class="form-control" required>
                                                            <option value="">Select Here.....</option>
                                                            <option value="1">Test I Only</option>
                                                            <option value="2">Test I - Test II</option>
                                                            <option value="3">Test I - Test III</option>
                                                            <option value="4">Test I - Test IV</option>
                                                            <option value="5">Test I - Test V</option>
                                                        </select>
                                                        <span style="font-size: 10px;">Note: Limit to only Test V at most</span>
                                                    </div>
                                                </div>
                                                <div id="fields-list">
                                                    <div class="form-group row" id="testI" style="display: none;">
                                                        <label class="col-md-3 text-md-right text-left mt-2">Test I <label style="color:red;">*</label></label>
                                                        <div class="col-lg-7 col-md-6">
                                                            <select id="txt_testI" class="form-control db-list" >
                                                                <option value="">Select Here.....</option>
                                                                <option value="MC">Multiple Choice</option>
                                                                <option value="S">Short Answer</option>
                                                                <option value="TF">True or False</option>
                                                                <option value="MCI">Multiple Choice (Images)</option>
                                                                <option value="E">Essay</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row" id="testII" style="display: none;">
                                                        <label class="col-md-3 text-md-right text-left mt-2">Test II <label style="color:red;">*</label></label>
                                                        <div class="col-lg-7 col-md-6">
                                                            <select id="txt_testII" class="form-control db-list" >
                                                                <option value="">Select Here.....</option>
                                                                <option value="MC">Multiple Choice</option>
                                                                <option value="S">Short Answer</option>
                                                                <option value="TF">True or False</option>
                                                                <option value="MCI">Multiple Choice (Images)</option>
                                                                <option value="E">Essay</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row" id="testIII" style="display: none;">
                                                        <label class="col-md-3 text-md-right text-left mt-2">Test III <label style="color:red;">*</label></label>
                                                        <div class="col-lg-7 col-md-6">
                                                            <select id="txt_testIII" class="form-control db-list" >
                                                                <option value="">Select Here.....</option>
                                                                <option value="MC">Multiple Choice</option>
                                                                <option value="S">Short Answer</option>
                                                                <option value="TF">True or False</option>
                                                                <option value="MCI">Multiple Choice (Images)</option>
                                                                <option value="E">Essay</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row" id="testIV" style="display: none;">
                                                        <label class="col-md-3 text-md-right text-left mt-2">Test IV <label style="color:red;">*</label></label>
                                                        <div class="col-lg-7 col-md-6">
                                                            <select id="txt_testIV" class="form-control db-list" >
                                                                <option value="">Select Here.....</option>
                                                                <option value="MC">Multiple Choice</option>
                                                                <option value="S">Short Answer</option>
                                                                <option value="TF">True or False</option>
                                                                <option value="MCI">Multiple Choice (Images)</option>
                                                                <option value="E">Essay</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row" id="testV" style="display: none;">
                                                        <label class="col-md-3 text-md-right text-left mt-2">Test V <label style="color:red;">*</label></label>
                                                        <div class="col-lg-7 col-md-6">
                                                            <select id="txt_testV" class="form-control db-list" >
                                                                <option value="">Select Here.....</option>
                                                                <option value="MC">Multiple Choice</option>
                                                                <option value="S">Short Answer</option>
                                                                <option value="TF">True or False</option>
                                                                <option value="MCI">Multiple Choice (Images)</option>
                                                                <option value="E">Essay</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-lg-7 col-md-6 text-right">
                                                        <button class="btn btn-icon icon-right btn-primary" type="button" id="btnsbmtexamtitle">Save <i class="fas fa-arrow-right"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <script src="../../assets/js/page/main_add_exam.js"></script>
    <?php include "modal/loadingModal.php"; ?>
</body>
</html>