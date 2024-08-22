<?php
global $conn;
session_start();
require "../db/conn.php";
if (!isset($_SESSION['user'])) {
    header("location: ../auth/auth-login.php");
}else {
    $email = $_SESSION['user'];
    $query = $conn->query("SELECT * FROM user WHERE email = '$email' OR username = '$email' AND isActive = '1'");
    if ($query->rowCount() > 0){
        $row = $query->fetch();
        $department = $_SESSION['department'];
        $dept_1 = mb_substr($department, 0, 1);
        if ($row['usertype'] == "examinee") {
            header("location: ../index.php");
        }else if($row['usertype'] == "main"){
            header("location: main/main_admin.php");
        }else if ($row['isActive'] == '0'){
            session_destroy();
            header("location: ../auth/auth-login.php");
        }
    }else {
        session_destroy();
        header("location: ../auth/auth-login.php");
    }
    $dept_query = $conn->query("SELECT * FROM department WHERE department = '$department'");
    $dept = $dept_query->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <link rel="shortcut icon" href="../assets/img/logo/Cavite_Province.png">
    <title>PGC - <?php echo $department; ?> Exam Management System</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="../assets/modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/modules/fontawesome/css/all.min.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/animate.min.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="../assets/modules/izitoast/css/iziToast.min.css">
    <!-- Sweet Alert JS -->
    <script src="../assets/modules/sweetalert/sweetalert2.all.min.js"></script>

    <!-- General JS Scripts -->
    <script src="../assets/modules/jquery.min.js"></script>
    <script src="../assets/modules/popper.js"></script>
    <script src="../assets/modules/tooltip.js"></script>
    <script src="../assets/modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="../assets/modules/nicescroll/jquery.nicescroll.min.js"></script>

    <!-- JS Libraies -->
    <script src="../assets/modules/izitoast/js/iziToast.min.js"></script>

    <!-- Template JS File -->
    <script src="../assets/js/jquery.redirect.js"></script>
    <script src="../assets/js/scripts.js"></script>
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
                        <span style="color: whitesmoke;"><?php
                            echo $dept['department_name']; ?>
                             - Exam Management System</span></li>
                </ul>
            </form>
            <ul class="navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="javascript:;" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                        <?php
                        if (isset($_SESSION['user'])) {
                            echo $row['lname'];
                        }
                        ?>
                        <div class="d-sm-none d-lg-inline-block">

                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="admin_profile.php" class="dropdown-item has-icon">
                            <i class="fas fa-user"></i> View Profile
                        </a>
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
                    <a href="admin.php">
                        <img id="img-logo" src="../assets/img/logo/Cavite_Province.png" style="height: 100px; width: 100px;">
                        <br>
                        EXAM MANAGEMENT
                        <br>
                        SYSTEM
                    </a>
                </div>
                <div class="sidebar-brand sidebar-brand-sm">
                    <a href="admin.php"><img id="img-logo" src="../assets/img/logo/Cavite_Province.png"/></a>
                </div>
                <ul class="sidebar-menu">
                    <li class="menu-header">Home</li>
                    <li class="dropdown ">
                        <a href="admin.php" class="nav-link"><i class="fas fa-home"></i><span>Home</span></a>
                    </li>
                    <li class="menu-header">Exams</li>
                    <li class="dropdown ">
                        <a href="javascript:;" class="nav-link has-dropdown"><i class="fas fa-file-alt"></i> <span>Exams</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="add_exam.php" id="exam-link" >Create Exam</a></li>
                            <li><a class="nav-link" href="list_exam.php" id="exam-link" >View All</a></li>
                        </ul>
                    </li>
                    <li class="menu-header">Users</li>
                    <li class="dropdown ">
                        <a href="list_examinee.php" class="nav-link"><i class="fas fa-users"></i> <span>Examinees</span></a>
                    </li>
                    <li class="menu-header">Profile</li>
                    <li class="dropdown active">
                        <a href="admin_profile.php" class="nav-link"><i class="fas fa-university"></i> <span><?php echo $department; ?> Profile</span></a>
                    </li>
                    <li class="menu-header">Password</li>
                    <li class="dropdown">
                        <a class="nav-link " href="javascript:;" id="changepw"><i class="fas fa-key"></i> <span>Change Password</span></a>
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
                <div class="section-header">
                    <h1><?php echo $department; ?> Profile</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item "> <a href="admin.php">Dashboard</a></div>
                        <div class="breadcrumb-item active"><?php echo $department; ?> Profile</div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="container">
                            <div class="col-md-10">
                                <div class="card profile-widget">
                                    <div class="profile-widget-header ">
                                        <form enctype="multipart/form-data" action="admin_profile.php" name="department" method="get">
                                            <div class="profile-widget-picture" style="box-shadow: none;">
                                                <div class="wrap-custom-file-logo">
                                                    <input type="file" name="dept_logo" id="dept_logo" accept="image/png"/>
                                                    <label  for="dept_logo" class="file-ok" style="background-image: url('../assets/uploads/<?php echo $department; ?>/Logo/<?php echo $dept['department_logo']; ?>')">
                                                    </label>
                                                </div>
                                                <div class="text-center">
                                                    <button class="btn btn-round btn-success btn-sm" id="btnSaveProfile" type="submit" style="display: none;">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="profile-widget-items">
                                            <div class="profile-widget-item">
                                                <div class="profile-widget-item-label text-lg-left pl-5"><?php echo $department; ?></div>
                                                <div class="profile-widget-item-value text-lg-left pl-5"><?php echo $dept['department_name']; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="profile-widget-description pt-0 pb-0 pl-5 pr-5" id="department_information">

                                    </div>
                                    <div class="card-footer text-right">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
        </div>
    </div>
</div>
<!-- Page Specific JS File -->
<script src="../assets/js/page/admin_profile.js"></script>
<?php require "modal/loadingModal.php"; ?>
</body>
</html>