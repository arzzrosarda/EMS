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
    <link rel="stylesheet" href="../assets/modules/datatables/datatables.css">
    <script type="text/javascript" src="../assets/modules/datatables/datatables.js"></script>
    <script src="../assets/modules/chart.min.js"></script>
    <script src="../assets/modules/chartjs-plugin-colorschemes.js"></script>
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
                            $dept = $conn->query("SELECT * FROM department WHERE department = '$department'");
                            $fetch_dept = $dept->fetch();
                            echo $fetch_dept['department_name']; ?>
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
                    <li class="dropdown active">
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
                    <li class="dropdown">
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
                </ul>
            </aside>
        </div>
        <!-- Navigation bar End -->


        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>Dashboard</h1>
                </div>
                <div class="section-body" id="dashboard">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Examinees</h4>
                                    </div>
                                    <div class="card-body" id="total_examinee">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3  col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon" style="background: #0a568c;">
                                    <i class="fas fa-circle"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Online Examinees</h4>
                                    </div>
                                    <div class="card-body" id="online_examinee">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3  col-md-6 col-sm-6 col-12" >
                            <div class="card card-statistic-1">
                                <div class="card-icon " style="background: #0d6aad;">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Active Exams</h4>
                                    </div>
                                    <div class="card-body" id="total_active_exam">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3  col-md-6 col-sm-6 col-12" >
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-info">
                                    <i class="fas fa-file"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total InActive Exams</h4>
                                    </div>
                                    <div class="card-body" id="total_inactive_exam">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 col-md-12 col-12 col-sm-12">
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-12 col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <label> No. of user per division from <label id="start_year"></label> to <label id="curr_year"></label></label>
                                        </div>
                                        <div class="card-body" id="chart_user">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 col-12 col-sm-12">
                                    <div class="card p-2" style="height: 38vh;">
                                        <div class="card-header">
                                            <h4 class="d-inline"><?php echo $department; ?> EXAMINEES</h4>
                                            <div class="card-header-action">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-icon" id="filter-btn"><i class="fas fa-filter"></i> Filter</button>
                                                    <a href="list_examinee.php" class="btn btn-primary">View All</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body" style="overflow: auto;">
                                            <div id="filter_container">
                                                <div class="row pb-3 filter" id="filter"  style="display: none;">
                                                    <div class="col">
                                                        <select id="divselect" class="form-control" autofocus>
                                                            <option value="">Division</option>
                                                            <?php
                                                            $div_query = $conn->query("SELECT a.`division`, b.`department` FROM division a LEFT JOIN department b ON a.`department_id` = b.`id` WHERE b.`department` = '$department'");
                                                            while ($div = $div_query->fetch()){ ?>

                                                                <option value="<?php echo $div['division']; ?>"><?php echo $div['division']; ?></option>
                                                            <?php }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="examinees" >

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h4>USER LOG</h4>
                                </div>
                                <div class="card-body" id="user_log">

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-12 col-sm-12">
                            <div class="card p-2" style="height: 60vh;">
                                <div class="card-header">
                                    <h4><?php echo $department; ?> EXAMS</h4>
                                    <div class="card-header-action">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary btn-icon" id="filter-btn1"><i class="fas fa-filter"></i> Filter</button>
                                            <a href="list_exam.php" class="btn btn-primary">View All</a>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-body"  style="overflow: auto;">
                                    <div id="filter_container">
                                        <div class="row pb-3 filter" id="filter1"  style="display: none;">
                                            <div class="col">
                                                <select id="diviselect" class="form-control" autofocus>
                                                    <option value="">Division</option>
                                                    <?php
                                                    $div_query = $conn->query("SELECT a.`division`, b.`department` FROM division a LEFT JOIN department b ON a.`department_id` = b.`id` WHERE b.`department` = '$department'");
                                                    while ($div = $div_query->fetch()){ ?>

                                                        <option value="<?php echo $div['division']; ?>"><?php echo $div['division']; ?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <select id="status_sel" class="form-control">
                                                    <option value="">All Status</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">InActive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="list_exam_filter">

                                    </div>
                                </div>
                            </div>
                            <div class="card p-2" style="height: 50vh">
                                <div class="card-header">
                                    <h4><?php echo $department; ?> USER FEEDBACK</h4>
                                    <div class="card-header-action">
                                        <button type="button" class="btn btn-primary btn-icon" id="filter-btn2"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                                </div>
                                <div class="card-body" style="overflow: auto">
                                    <div id="filter_container">
                                        <div class="row pb-3 filter" id="filter2"  style="display: none;">
                                            <div class="col">
                                                <select id="divisionselect" class="form-control" autofocus>
                                                    <option value="">Division</option>
                                                    <?php
                                                    $div_query = $conn->query("SELECT a.`division`, b.`department` FROM division a LEFT JOIN department b ON a.`department_id` = b.`id` WHERE b.`department` = '$department'");
                                                    while ($div = $div_query->fetch()){ ?>

                                                        <option value="<?php echo $div['division']; ?>"><?php echo $div['division']; ?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="user_feedback">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
        </div>
    </div>
</div>
<script src="../assets/js/page/admin.js"></script>
<?php include "modal/loadingModal.php"; ?>
</body>
</html>