<?php
global$conn;
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
                    <li class="dropdown ">
                        <a href="admin.php" class="nav-link"><i class="fas fa-home"></i><span>Home</span></a>
                    </li>
                    <li class="menu-header">Exams</li>
                    <li class="dropdown active">
                        <a href="javascript:;" class="nav-link has-dropdown"><i class="fas fa-file-alt"></i> <span>Exams</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="add_exam.php" id="exam-link" >Create Exam</a></li>
                            <li class="active"><a class="nav-link" href="list_exam.php" id="exam-link" >View All</a></li>
                        </ul>
                    </li>
                    <li class="menu-header">Users</li>
                    <li class="dropdown ">
                        <a href="list_examinee.php" class="nav-link"><i class="fas fa-users"></i> <span>Examinees</span></a>
                    </li>
                    <li class="menu-header">Profile</li>
                    <li class="dropdown ">
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
                    <h1>Exam Monitoring</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item "> <a href="admin.php">Dashboard</a></div>
                        <div class="breadcrumb-item active">Exam Monitoring</div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-12 col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="section-title mt-0 d-inline">EXAM MONTORING</h4>
                                    <div class="card-header-action">
                                            <div id="active_button_container">
                                                <div class="btn-group">
                                                <a href="add_exam.php" type="button" class="btn btn-primary">
                                                    <i class='fas fa-plus-circle'></i>&nbsp;
                                                    Add
                                                </a>
                                                <button type='button' class='btn btn-sm btn-danger btnDelAllExam' disabled>
                                                    <i class='fas fa-trash-alt'></i>
                                                    Delete
                                                </button>
                                                </div>
                                            </div>
                                            <div id="trash_button_container">
                                                <div class="btn-group">
                                                <a href="add_exam.php" type="button" class="btn btn-primary">
                                                    <i class='fas fa-plus-circle'></i>&nbsp;
                                                    Add
                                                </a>
                                                <button type='button' class='btn btn-sm btn-info btnRestoreAllExam' disabled>
                                                    <i class='fas fa-undo'></i>
                                                    Restore
                                                </button>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="card-body" >
                                    <div class="row">
                                        <div class="col-12 ">
                                            <ul class="list-group">
                                                <div class="row">
                                                    <div class="col-lg-10">
                                                        <li class="list-group-item p-3 mb-3">
                                                            <h6 class="col text-md-left text-left"><strong>FILTER: </strong></h6>
                                                            <div class="col-md-12">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label class="col text-md-left text-left" for="diviselect">Division: </label>
                                                                        <select id="diviselect" class="form-control" autofocus>
                                                                            <option value="">Select Division</option>
                                                                            <?php
                                                                            $div_query = $conn->query("SELECT a.`division`, b.`department` FROM division a LEFT JOIN department b ON a.`department_id` = b.`id` WHERE b.`department` = '$department'");
                                                                            while ($div = $div_query->fetch()){ ?>

                                                                                <option value="<?php echo $div['division']; ?>"><?php echo $div['division']; ?></option>
                                                                            <?php }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="col text-md-left text-left" for="status_sel">Status: </label>
                                                                        <div class="col text-md-left">
                                                                            <select id="status_sel" class="form-control">
                                                                                <option value="">All Status</option>
                                                                                <option value="1">Active</option>
                                                                                <option value="0">InActive</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <li class="list-group-item p-3 mb-3">
                                                            <h6 class="col text-md-left text-left"><strong>FILTER: </strong></h6>
                                                            <div class="col text-md-left">
                                                                <label class="col text-md-left text-left" for="AccSel"> List: </label>
                                                                <select id="list_sel" class="form-control" required>
                                                                    <option value="0">Active list</option>
                                                                    <option value="1">Deleted</option>
                                                                </select>
                                                            </div>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="row" id="exam_active">
                                                    <div class="col-lg-12">
                                                        <li class="list-group-item p-3 mb-3">
                                                            <div id="exam_list">
                                                            </div>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="row" id="deleted_exam">
                                                    <div class="col-lg-12">
                                                        <li class="list-group-item p-3 mb-3">
                                                            <div id="exam_list_deleted">
                                                            </div>
                                                        </li>
                                                    </div>
                                                </div>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
        </div>
    </div>
</div>
<script src="../assets/js/page/list_exam.js"></script>
<?php include "modal/loadingModal.php"; ?>
</body>
</html>