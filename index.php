
<?php
global $conn;
session_start();
require "db/conn.php";
if (!isset($_SESSION['user'])) {
    header("location: auth/auth-login.php");
}else {
    $email = $_SESSION['user'];
    $department = $_SESSION['department'];
    $query = $conn->query("SELECT * FROM user WHERE email = '$email' OR username = '$email' AND isActive = '1'");
    if ($query->rowCount() > 0){
        $row = $query->fetch();
        $user_id = $row['id'];
        if ($row['usertype'] == "main") {
            header("location: admin/main/main_admin.php");
        }else if($row['usertype'] == "admin"){
            header("location: admin/admin.php");
        }else if ($row['isActive'] == '0'){
            session_destroy();
            header("location: auth/auth-login.php");
        }
    }else {
        session_destroy();
        header("location: auth/auth-login.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <link rel="shortcut icon" href="assets/img/logo/Cavite_Province.png">
    <title><?php echo $department; ?> Exam System</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="assets/modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/modules/fontawesome/css/all.min.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/components.css">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="assets/modules/summernote/summernote-bs4.css">
    <link rel="stylesheet" href="assets/modules/izitoast/css/iziToast.min.css">
    <!-- Sweet Alert JS -->
    <script src="assets/modules/sweetalert/sweetalert2.all.min.js"></script>

    <!-- General JS Scripts -->
    <script src="assets/modules/jquery.min.js"></script>
    <script src="assets/modules/popper.js"></script>
    <script src="assets/modules/tooltip.js"></script>
    <script src="assets/modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
    <script src="assets/modules/moment.min.js"></script>
    <script src="assets/modules/summernote/summernote-bs4.js"></script>

    <!-- JS Libraies -->
    <script src="assets/modules/izitoast/js/iziToast.min.js"></script>

    <!-- Template JS File -->
    <script src="assets/js/jquery.redirect.js"></script>
    <script src="assets/js/scripts.js"></script>
</head>

<body>
<?php include "modal/modal.php"; ?>
<div id="app">
    <div class="main-wrapper main-wrapper-1">
        <!-- Navigation bar -->
        <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar" style="left: 10px;">
            <form class="form-inline mr-auto">
                <ul class="navbar-nav">
                    <div class="row p-0 m-0 align-items-center">
                        <img id="img-logo" src="assets/img/logo/Cavite_Province.png" style="width: 40px; height: 40px"> &nbsp;  &nbsp;
                        <li style="line-height: 1px; margin-top: -5px;">
                            <h6 style=" color: #ffffff;">Provincial Government of Cavite</h6><br>
                            <span style="color: whitesmoke;"><?php
                                $department_query = $conn->query("SELECT * FROM department a LEFT JOIN user b ON a.`department` = b.`department` WHERE a.`department` = '$department' AND b.`usertype` = 'admin'");
                                $fetch_dept = $department_query->fetch();
                                echo $fetch_dept['department_name'];
                                ?>
                                </span>
                        </li>
                    </div>
                </ul>
            </form>
            <ul class="navbar-nav">
                <li class="dropdown">
                    <a href="javascript:;" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                        <?php
                        $mname = mb_substr($row['mname'], 0, 1);
                        echo $row['lname']. ", " . $row['fname'] . " " . $mname . ". ";
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
        <!-- Navigation bar End -->


        <!-- Main Content -->
        <div class="main-content-exam">
            <section class="section">
                <div class="section-header pt-3 pb-2 align-items-center">
                    <h4 class="ml-4"> PGC EXAMINATION</h4>
                </div>
                <div class="container">
                    <div class="section-body">
                        <h2 class="section-title">Hi. <?php echo $row['lname']. ", " . $row['fname'] . " " . $row['mname']; ?></h2>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-12 col-sm-12">
                                <div class="card" style="height: 50vh;">
                                    <div class="card-header">
                                        <h4 class="d-inline">List of available exams</h4>
                                    </div>
                                    <div class="card-body" id="list_available_exam" style="overflow: auto;">

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Send us your Feedback?</h4>
                                    </div>
                                    <div class="card-body pb-0">
                                        <div class="form-group">
                                            <div class="selectgroup selectgroup-pills">
                                                <input type="hidden" class="form-control" id="input-rating">
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="icon-input" value="1" class="selectgroup-input">
                                                    <span class="selectgroup-button selectgroup-button-icon">&#128533;</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="icon-input" value="2" class="selectgroup-input">
                                                    <span class="selectgroup-button selectgroup-button-icon">&#128528;</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="icon-input" value="3" class="selectgroup-input">
                                                    <span class="selectgroup-button selectgroup-button-icon">&#128512;</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="icon-input" value="4" class="selectgroup-input">
                                                    <span class="selectgroup-button selectgroup-button-icon">&#128516;</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="icon-input" value="5" class="selectgroup-input">
                                                    <span class="selectgroup-button selectgroup-button-icon">&#128513;</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Got Suggestions? We'd love to hear them! <label style="color: lightgray">(Optional)</label></label>
                                            <textarea class="form-control" id="txt_feedback"></textarea>
                                        </div>
                                    </div>
                                    <div class="card-footer pt-0">
                                        <button class="btn btn-primary btnFeedback" type="button" data-user="<?php echo $user_id; ?>">Save Draft</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <footer class="main-footer-user">
                <div class="row col-lg-12 justify-content-center"><span>Copyright &copy; 2024</span></div>
                <div class="row col-lg-12 justify-content-center">
                    <h6><?php echo $fetch_dept['department_name']; ?></h6>
                </div>
                <div class="row col-lg-12 justify-content-center">
                    <span><strong>Contact No:</strong> <?php echo $fetch_dept['department_no']; ?></span> &nbsp;
                    <span><strong>Email Address:</strong> <?php echo $fetch_dept['email']; ?></span>
                </div>
            </footer>
        </div>
    </div>

    <!-- Page Specific JS File -->
    <script src="assets/js/page/index.js"></script>
</body>
</html>