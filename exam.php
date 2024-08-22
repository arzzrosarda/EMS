<?php
global $conn;
session_start();
require "db/conn.php";
if (!isset($_SESSION['user'])) {
    header("location: auth/auth-login.php");
}else {
    if (!isset($_POST['examid'])){
        header("location: index.php");
    }else {
        $email = $_SESSION['user'];
        $exam_id = $_POST['examid'];
        $department = $_SESSION['department'];
        $query = $conn->query("SELECT * FROM user WHERE email = '$email' OR username = '$email' AND isActive = '1'");
        if ($query->rowCount() > 0){
            $row = $query->fetch();
            $userID = $row['id'];
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

    <!-- Sweet Alert JS -->
    <script src="assets/modules/sweetalert/sweetalert2.all.min.js"></script>

    <!-- General JS Scripts -->
    <script src="assets/modules/jquery.min.js"></script>
    <script src="assets/modules/popper.js"></script>
    <script src="assets/modules/tooltip.js"></script>
    <script src="assets/modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
    <script src="assets/js/project.js"></script>

    <!-- Template JS File -->
    <script src="assets/js/jquery.redirect.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script>
        $(document).ready(function (){
            window.addEventListener("pagehide", (event) => {
                $.post("queries/active_transaction.php", {delete:"delete", exam_id:<?php echo $exam_id;?>});
            });

        });
    </script>
</head>
<body>
<?php include "modal/modal.php"; ?>
<div id="app">
    <div class="main-wrapper main-wrapper-1">
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

        <!-- Main Content -->
        <div class="main-content-exam pb-5">
            <?php
            if (isset($_POST['examid'])) {
            $id = $_POST['examid'];
            $titleQuery = $conn->query("SELECT * FROM exam_title WHERE id = '$id' AND isActiveExam = '1' AND department = '$department'");
            $title = $titleQuery->fetch(); ?>
            <section class='section'>
                <div class='section-header'>
                    <h1 style='margin-left: 20px;'><?php echo $department." - ".$title['title']; ?></h1>
                </div>
            </section>
            <form action='' method='post' autocomplete='off'>
                <fieldset>
                    <?php
                    $QuestionQuery = $conn->query("SELECT * FROM question WHERE q_id = '$id' AND active = '1'");
                    $optionQuery = $conn->query("SELECT * FROM options WHERE o_id = '$id' AND active = '1'");
                    // Submit Query //
                    if (isset($_POST['submit'])) {
                        ?><script>
                                $(document).ready(function (){
                                    var credentials = setInterval(function () {
                                        $.post("queries/log_credentials.php", function (res){
                                            if (res == 1){
                                                clearInterval(credentials);
                                                Swal.fire({
                                                    title: "YOU HAVE BEEN LOGGED OUT",
                                                    html: "Press continue to proceed",
                                                    icon: "warning",
                                                    allowOutsideClick: false,
                                                    confirmButtonColor: "#1c3d77",
                                                    confirmButtonText: "Continue",
                                                    closeOnConfirm: false,
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location.href="queries/logout.php";
                                                    }
                                                });
                                            }
                                            else if (res == 3){
                                                clearInterval(credentials);
                                                Swal.fire({
                                                    title: "YOU HAVE BEEN LOGGED OUT",
                                                    html: "Press continue to proceed",
                                                    icon: "warning",
                                                    confirmButtonColor: "#1c3d77",
                                                    allowOutsideClick: false,
                                                    confirmButtonText: "Continue",
                                                    closeOnConfirm: false,
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        $.redirect("auth/auth-login.php");
                                                    }
                                                });
                                            }
                                        });
                                    }, 2000);
                                    //logout btn//
                                    $("#logoutbtn").click( function(){
                                        Swal.fire({
                                            title: "LOGOUT",
                                            html: "Are you sure you want to logout?",
                                            icon: "warning",
                                            showCancelButton: true,
                                            confirmButtonColor: "#1c3d77",
                                            confirmButtonText: "Yes, I am sure",
                                            cancelButtonText: "No, Stay logged in",
                                            closeOnConfirm: false,
                                            closeOnCancel: true
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.onbeforeunload = function() {
                                                    return "Data will be lost if you leave the page, are you sure?";
                                                };
                                                let timerInterval;
                                                Swal.fire({
                                                    title: "LOGGING OUT....",
                                                    html: "",
                                                    timer: 1000,
                                                    icon: "warning",
                                                    showConfirmButton: false,
                                                    timerProgressBar: true,
                                                    allowOutsideClick: false,
                                                    didOpen: () => {
                                                        Swal.showLoading();
                                                        const timer = Swal.getPopup().querySelector("b");
                                                        timerInterval = setInterval(() => {
                                                            timer.textContent = `${Swal.getTimerLeft()}`;
                                                        }, 100);
                                                    },
                                                    willClose: () => {
                                                        clearInterval(timerInterval);
                                                    }
                                                }).then((result) => {
                                                    if (result.dismiss === Swal.DismissReason.timer) {
                                                        window.location.href="queries/logout.php";
                                                    }
                                                });

                                            }
                                        });
                                    });
                                });
                            </script>
                        <div class="row">
                            <div class="container">
                                <div class="card card-secondary">
                                    <div class="card-header" style="justify-content: center;">
                                        <h3>Thank you for participating on our exam!</h3>
                                    </div>
                                    <div class="card-body" style="text-align: center; justify-content: center;">
                                        <h3>SUBMITTED</h3>
                                        <img src="assets/img/firework.png" style="width: 400px; height: 400px; padding: 50px;">
                                    </div>
                                </div>
                            </div>
                        </div>
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
                    <?php $Ntitle = $title['title'];
                    $examQuery = $conn->query("SELECT * FROM exam_title WHERE title = '$Ntitle' AND isActiveExam = '1' AND department = '$department'");
                    $examtitle = $examQuery->fetch();
                    $examresultQ = $conn->query("SELECT * FROM exam_result WHERE examiner_id = '$userID' and exam_id = '$id'");
                    if ($examresultQ->rowCount() > 0) { ?>
                        <script>
                            Swal.fire({
                                title: "<?php echo $Ntitle; ?>",
                                html: 'You have already taken this exam!',
                                icon: 'error',
                                allowOutsideClick: false,
                                confirmButtonText: 'Ok',
                                closeOnConfirm: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href='index.php';
                                }
                            });
                        </script>
                    <?php } else {
                    $active_exam_transaction = $conn->query("UPDATE active_take SET active = '2' WHERE examinee_id = '$userID' AND exam_id = '$exam_id'");
                    date_default_timezone_set("Asia/Manila");
                    $date = date("Y-m-d");
                    $time = date("h:i:s A");
                    $log = "User Submitted Exam: " . $Ntitle;
                    $log = $conn->query("INSERT INTO ulog(`userID`, `logs`, `logDate`, `logTime`) VALUES ('$userID', '$log', '$date', '$time')");
                    if ($log) {
                    while ($row = $QuestionQuery->fetch()) {
                    $option = $optionQuery->fetch();
                    $qid = $row['id'];
                    $qName = $row['question'];
                    $qPoints = $row['points'];
                    $Ename = $title['title'];
                    if (!isset($_POST[$qid])) {
                    $Ans = '';
                    $queryoption = $conn->query("SELECT * FROM options WHERE id = '$qid'");
                    while ($opres = $queryoption->fetch()) {
                    if ($opres['ans'] == $Ans) {
                    $correct = 'correct';
                    $sql = $conn->prepare("INSERT INTO exam_result (exam_id, q_no, examiner_id, ans, correct_incorrect, points, ans_date, ans_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $sql->bindParam(1,  $id,PDO::PARAM_STR);
                    $sql->bindParam(2, $qid, PDO::PARAM_STR);
                    $sql->bindParam(3, $userID, PDO::PARAM_STR);
                    $sql->bindParam(4, $Ans, PDO::PARAM_STR);
                    $sql->bindParam(5, $correct, PDO::PARAM_STR);
                    $sql->bindParam(6, $qPoints, PDO::PARAM_INT);
                    $sql->bindParam(7, $date, PDO::PARAM_STR);
                    $sql->bindParam(8, $time, PDO::PARAM_STR);
                    if ($sql->execute()) { ?>
                        <script>
                            Swal.fire({
                                title: "<?php echo $Ntitle; ?>",
                                html: 'Submitted Successfully',
                                icon: 'success',
                                allowOutsideClick: false,
                                confirmButtonText: 'Ok',
                                closeOnConfirm: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href='index.php';
                                }
                            });
                        </script>
                    <?php }else { ?>
                        <script>
                            Swal.fire('EXAM!', 'Insert Unsuccessful', 'error');
                        </script>
                    <?php } }else if ($opres['ans'] == 'forreview'){
                    $forreview = 'forreview';
                    $sql = $conn->prepare("INSERT INTO exam_result (exam_id, q_no, examiner_id, ans, correct_incorrect, points, ans_date, ans_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $sql->bindParam(1,  $id,PDO::PARAM_STR);
                    $sql->bindParam(2, $qid, PDO::PARAM_STR);
                    $sql->bindParam(3, $userID, PDO::PARAM_STR);
                    $sql->bindParam(4, $Ans, PDO::PARAM_STR);
                    $sql->bindParam(5, $forreview, PDO::PARAM_STR);
                    $sql->bindParam(6, $qPoints, PDO::PARAM_INT);
                    $sql->bindParam(7, $date, PDO::PARAM_STR);
                    $sql->bindParam(8, $time, PDO::PARAM_STR);
                    if ($sql->execute()) { ?>
                        <script>
                            Swal.fire({
                                title: "<?php echo $Ntitle; ?>",
                                html: 'Submitted Successfully',
                                icon: 'success',
                                allowOutsideClick: false,
                                confirmButtonText: 'Ok',
                                closeOnConfirm: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href='index.php';
                                }
                            });
                        </script>
                    <?php }else { ?>
                        <script>
                            Swal.fire('EXAM!', 'Insert Unsuccessful', 'error');
                        </script>";
                    <?php   } }else {
                    $incorrect = 'incorrect';
                    $sql = $conn->prepare("INSERT INTO exam_result (exam_id, q_no, examiner_id, ans, correct_incorrect, points, ans_date, ans_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $sql->bindParam(1,  $id,PDO::PARAM_STR);
                    $sql->bindParam(2, $qid, PDO::PARAM_STR);
                    $sql->bindParam(3, $userID, PDO::PARAM_STR);
                    $sql->bindParam(4, $Ans, PDO::PARAM_STR);
                    $sql->bindParam(5, $incorrect, PDO::PARAM_STR);
                    $sql->bindParam(6, $qPoints, PDO::PARAM_INT);
                    $sql->bindParam(7, $date, PDO::PARAM_STR);
                    $sql->bindParam(8, $time, PDO::PARAM_STR);
                    if ($sql->execute()) { ?>
                        <script>
                            Swal.fire({
                                title: "<?php echo $Ntitle; ?>",
                                html: 'Submitted Successfully',
                                icon: 'success',
                                allowOutsideClick: false,
                                confirmButtonText: 'Ok',
                                closeOnConfirm: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href='index.php';
                                }
                            });
                        </script>
                    <?php }else { ?>
                        <script>
                            Swal.fire('EXAM!', 'Insert Unsuccessful', 'error');
                        </script>
                    <?php } } } } else {
                    $Ans = $_POST[$qid];
                    $queryoption = $conn->query("SELECT * FROM options WHERE id = '$qid'");
                    while ($opres = $queryoption->fetch()) {
                    if ($opres['ans'] == $Ans) {
                    $correct = 'correct';
                    $sql = $conn->prepare("INSERT INTO exam_result (exam_id, q_no, examiner_id, ans, correct_incorrect, points, ans_date, ans_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $sql->bindParam(1,  $id,PDO::PARAM_STR);
                    $sql->bindParam(2, $qid, PDO::PARAM_STR);
                    $sql->bindParam(3, $userID, PDO::PARAM_STR);
                    $sql->bindParam(4, $Ans, PDO::PARAM_STR);
                    $sql->bindParam(5, $correct, PDO::PARAM_STR);
                    $sql->bindParam(6, $qPoints, PDO::PARAM_INT);
                    $sql->bindParam(7, $date, PDO::PARAM_STR);
                    $sql->bindParam(8, $time, PDO::PARAM_STR);
                    if ($sql->execute()) { ?>
                        <script>
                            Swal.fire({
                                title: "<?php echo $Ntitle; ?>",
                                html: 'Submitted Successfully',
                                icon: 'success',
                                allowOutsideClick: false,
                                confirmButtonText: 'Ok',
                                closeOnConfirm: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href='index.php';
                                }
                            });
                        </script>
                    <?php }else { ?>
                        <script>
                            Swal.fire('EXAM!', 'Insert Unsuccessful', 'error');
                        </script>
                    <?php }
                    }
                    else if ($opres['ans'] == 'forreview'){
                    $forreview = 'forreview';
                    $sql = $conn->prepare("INSERT INTO exam_result (exam_id, q_no, examiner_id, ans, correct_incorrect, points, ans_date, ans_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $sql->bindParam(1,  $id,PDO::PARAM_STR);
                    $sql->bindParam(2, $qid, PDO::PARAM_STR);
                    $sql->bindParam(3, $userID, PDO::PARAM_STR);
                    $sql->bindParam(4, $Ans, PDO::PARAM_STR);
                    $sql->bindParam(5, $forreview, PDO::PARAM_STR);
                    $sql->bindParam(6, $qPoints, PDO::PARAM_INT);
                    $sql->bindParam(7, $date, PDO::PARAM_STR);
                    $sql->bindParam(8, $time, PDO::PARAM_STR);
                    if ($sql->execute()) { ?>
                        <script>
                            Swal.fire({
                                title: "<?php echo $Ntitle; ?>",
                                html: 'Submitted Successfully',
                                icon: 'success',
                                allowOutsideClick: false,
                                confirmButtonText: 'Ok',
                                closeOnConfirm: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href='index.php';
                                }
                            });
                        </script>
                    <?php }
                    else { ?>
                        <script>
                            Swal.fire('EXAM!', 'Insert Unsuccessful', 'error');
                        </script>
                    <?php }
                    }
                    else {
                    $incorrect = 'incorrect';
                    $sql = $conn->prepare("INSERT INTO exam_result (exam_id, q_no, examiner_id, ans, correct_incorrect, points, ans_date, ans_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $sql->bindParam(1,  $id,PDO::PARAM_STR);
                    $sql->bindParam(2, $qid, PDO::PARAM_STR);
                    $sql->bindParam(3, $userID, PDO::PARAM_STR);
                    $sql->bindParam(4, $Ans, PDO::PARAM_STR);
                    $sql->bindParam(5, $incorrect, PDO::PARAM_STR);
                    $sql->bindParam(6, $qPoints, PDO::PARAM_INT);
                    $sql->bindParam(7, $date, PDO::PARAM_STR);
                    $sql->bindParam(8, $time, PDO::PARAM_STR);
                    if ($sql->execute()) { ?>
                        <script>
                            Swal.fire({
                                title: "<?php echo $Ntitle; ?>",
                                html: 'Submitted Successfully',
                                icon: 'success',
                                allowOutsideClick: false,
                                confirmButtonText: 'Ok',
                                closeOnConfirm: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href='index.php';
                                }
                            });
                        </script>
                    <?php }
                    else { ?>
                        <script>
                            Swal.fire('EXAM!', 'Insert Unsuccessful', 'error');
                        </script>
                    <?php }
                    }
                    }
                    }
                    }
                    }
                    }
                    }
                    else {
                    $Ntitle = $title['title'];
                    $examQuery = $conn->query("SELECT * FROM exam_title WHERE title = '$Ntitle' AND isActiveExam = '1' AND department = '$department'");
                    $examtitle = $examQuery->fetch();
                    $division = $examtitle['division'];
                    $userfullname = $row['lname'] . ', ' . $row['fname'] . " " . $row['mname'];
                    $examresultQ = $conn->query("SELECT * FROM exam_result WHERE examiner_id = '$userID' and exam_id = '$id'");
                    if ($examresultQ->rowCount() > 0) { ?>
                        <script>
                            Swal.fire({
                                title: "<?php echo $Ntitle; ?>",
                                html: 'You have already taken this exam!',
                                icon: 'warning',
                                allowOutsideClick: false,
                                confirmButtonText: 'Ok',
                                closeOnConfirm: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href='index.php';
                                }
                            });
                        </script>
                    <?php }
                    else {
                        $index = 1;
                        $testQuery = $conn->query("SELECT * FROM exam_test WHERE exam_id = '$id'");
                        $testRes = $testQuery->fetch();
                        if ($testRes != null) {
                            echo '<div class="card" id="countdown-col">
                                        <div class="card-body">
                                        <input class="form-control" type="hidden" value="'. $id . '" name="examid">
                                        <input type="text" id="time-value" value="'. $title['time_limit']. '" style="display: none;">
                                          <div class="countdown">
                                          </div>
                                        </div>
                                      </div>';
                        // Test I
                    $testI = $testRes['Test_I'];
                    // Test I Query
                    $QuestionOptionI = $conn->query("SELECT a.`id`AS question_no, a.`question`, a.`question_type`, a.`points`, b.`option_1`, b.`option_2`,b.`option_3`,b.`option_4`,b.`img_1`,b.`img_2`,b.`img_3`,b.`img_4`, b.`ans`
                                                                                FROM `question` a 
                                                                                LEFT JOIN `options` b ON a.`id` = b.`id`
                                                                                WHERE a.`q_id` = '$id' AND b.`o_id` = '$id' AND a.`question_type` = '$testI'");
                                                if ($testI == "Multiple Choice") {
                    ?>
                    <div class='row' id='test1'>
                        <div class='container'>
                            <div class='card card-secondary' >
                                <div class='card-header' style='display: block;'>
                                    <div class='row'>
                                        <div class='col'>
                                            <h4> Test I: Multiple Choice </h4>
                                            <span>Directions: Choose the BEST answer for the following questions.</span>
                                        </div>
                                        <div class='col' style='text-align: right;'>
                                            <span class='align-text-top'>Page 1 of <?php echo $title['num_test'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if ($QuestionOptionI->rowCount() > 0) {
                            while ($rowQuestionOption = $QuestionOptionI->fetch()) { ?>
                            <div class='card' style='padding: 25px 15px 25px 15px;'>
                                <div class='form-wrapper' >
                                    <div class='row' style='display: flex;'>
                                        <section><span class='question-no'><?php echo $index++." ." ?></span></section>
                                        <div class='col'>
                                            <section><span class='question'><?php echo  $rowQuestionOption['question']; ?><br><span style="color: red;">( <?php echo $rowQuestionOption['points']; ?> Point/s )</span>. </span></section>
                                            <div class='radio-group'>
                                                <?php
                                                if ($rowQuestionOption['img_1'] != null
                                                    && $rowQuestionOption['img_2'] != null
                                                    && $rowQuestionOption['img_3'] != null
                                                    && $rowQuestionOption['img_4'] != null) {
                                                    echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option1/' . $rowQuestionOption['img_1'] . '" />
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option2/' . $rowQuestionOption['img_2'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option3/' . $rowQuestionOption['img_3'] . '"/>
                                                        <h4>III</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option4/' . $rowQuestionOption['img_4'] . '"/>
                                                        <h4>IV</h4>
                                                        </div>
                                                        </div>';
                                                }
                                                else if ($rowQuestionOption['img_1'] != null
                                                    && $rowQuestionOption['img_2'] != null
                                                    && $rowQuestionOption['img_3'] != null
                                                    && $rowQuestionOption['img_4'] == null) {
                                                    echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option1/' . $rowQuestionOption['img_1'] . '" />
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option2/' . $rowQuestionOption['img_2'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option3/' . $rowQuestionOption['img_3'] . '"/>
                                                        <h4>III</h4>
                                                        </div>
                                                        </div>';
                                                }
                                                else if ($rowQuestionOption['img_2'] != null
                                                    && $rowQuestionOption['img_3'] != null
                                                    && $rowQuestionOption['img_4'] != null
                                                    && $rowQuestionOption['img_1'] == null) {
                                                    echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option2/' . $rowQuestionOption['img_2'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option3/' . $rowQuestionOption['img_3'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option4/' . $rowQuestionOption['img_4'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                }
                                                else if ($rowQuestionOption['img_1'] != null
                                                    && $rowQuestionOption['img_3'] != null
                                                    && $rowQuestionOption['img_4'] != null
                                                    && $rowQuestionOption['img_2'] == null) {
                                                    echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option1/' . $rowQuestionOption['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option3/' . $rowQuestionOption['img_3'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option4/' . $rowQuestionOption['img_4'] . '"/>
                                                        <h4>III</h4>
                                                        </div>
                                                        </div>';
                                                }
                                                else if ($rowQuestionOption['img_1'] != null
                                                    && $rowQuestionOption['img_2'] != null
                                                    && $rowQuestionOption['img_4'] != null
                                                    && $rowQuestionOption['img_3'] == null) {
                                                    echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option1/' . $rowQuestionOption['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option2/' . $rowQuestionOption['img_1'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option4/' . $rowQuestionOption['img_4'] . '"/>
                                                        <h4>III</h4>
                                                        </div>
                                                        </div>';
                                                }
                                                //1-2 OR 2-1
                                                else if ($rowQuestionOption['img_1'] != null
                                                    && $rowQuestionOption['img_2'] != null
                                                    && $rowQuestionOption['img_3'] == null
                                                    && $rowQuestionOption['img_4'] == null) {
                                                    echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option1/' . $rowQuestionOption['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option2/' . $rowQuestionOption['img_2'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                }

                                                //1-3 OR 3-1
                                                else if ($rowQuestionOption['img_1'] != null
                                                    && $rowQuestionOption['img_2'] == null
                                                    && $rowQuestionOption['img_3'] != null
                                                    && $rowQuestionOption['img_4'] == null) {
                                                    echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option1/' . $rowQuestionOption['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option3/' . $rowQuestionOption['img_3'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                }
                                                //1-4 OR 4-1
                                                else if ($rowQuestionOption['img_1'] != null
                                                    && $rowQuestionOption['img_2'] == null
                                                    && $rowQuestionOption['img_3'] == null
                                                    && $rowQuestionOption['img_4'] != null) {
                                                    echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option1/' . $rowQuestionOption['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option4/' . $rowQuestionOption['img_4'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                }
                                                //2-3 OR 3-2
                                                else if ($rowQuestionOption['img_1'] == null
                                                    && $rowQuestionOption['img_2'] != null
                                                    && $rowQuestionOption['img_3'] != null
                                                    && $rowQuestionOption['img_4'] == null) {
                                                    echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option2/' . $rowQuestionOption['img_2'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option3/' . $rowQuestionOption['img_3'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                }
                                                //2-4 OR 4-2
                                                else if ($rowQuestionOption['img_1'] == null
                                                    && $rowQuestionOption['img_2'] != null
                                                    && $rowQuestionOption['img_3'] == null
                                                    && $rowQuestionOption['img_4'] != null) {
                                                    echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option2/' . $rowQuestionOption['img_2'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option4/' . $rowQuestionOption['img_4'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                }
                                                //3-4 OR 4-3
                                                else if ($rowQuestionOption['img_1'] == null
                                                    && $rowQuestionOption['img_2'] == null
                                                    && $rowQuestionOption['img_3'] != null
                                                    && $rowQuestionOption['img_4'] != null) {
                                                    echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option3/' . $rowQuestionOption['img_3'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option4/' . $rowQuestionOption['img_4'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                }

                                                else if ($rowQuestionOption['img_1'] != null
                                                    && $rowQuestionOption['img_2'] == null
                                                    && $rowQuestionOption['img_3'] == null
                                                    && $rowQuestionOption['img_4'] == null) {
                                                    echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option1/' . $rowQuestionOption['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        </div>';
                                                }
                                                else if ($rowQuestionOption['img_2'] != null
                                                    && $rowQuestionOption['img_3'] == null
                                                    && $rowQuestionOption['img_4'] == null
                                                    && $rowQuestionOption['img_1'] == null) {
                                                    echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option2/' . $rowQuestionOption['img_2'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        </div>';
                                                }
                                                else if ($rowQuestionOption['img_3'] != null
                                                    && $rowQuestionOption['img_4'] == null
                                                    && $rowQuestionOption['img_1'] == null
                                                    && $rowQuestionOption['img_2'] == null) {
                                                    echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option3/' . $rowQuestionOption['img_3'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        </div>';
                                                }
                                                else if ($rowQuestionOption['img_4'] != null
                                                    && $rowQuestionOption['img_1'] == null
                                                    && $rowQuestionOption['img_2'] == null
                                                    && $rowQuestionOption['img_3'] == null) {
                                                    echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option4/' . $rowQuestionOption['img_4'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        </div>';
                                                }

                                                echo "<label id='optionlbl'>"
                                                    . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOption['question_no'] . "'" . "value=" . "'" . $rowQuestionOption['option_1'] . "'>"
                                                    . "<span id='spantxt'>A. " .  $rowQuestionOption['option_1'] . "</span>"
                                                    . "</label>";
                                                echo "<label id='optionlbl'>"
                                                    . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOption['question_no'] . "'" . "value=" . "'" . $rowQuestionOption['option_2'] . "'>"
                                                    . "<span id='spantxt'>B. " . $rowQuestionOption['option_2']  . "</span>"
                                                    . "</label>";
                                                echo "<label id='optionlbl'>"
                                                    . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOption['question_no'] . "'" . "value=" . "'" . $rowQuestionOption['option_3'] . "'>"
                                                    . "<span id='spantxt'>C. " . $rowQuestionOption['option_3'] . "</span>"
                                                    . "</label>";
                                                echo "<label id='optionlbl'>"
                                                    . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOption['question_no'] . "'" . "value=" . "'" . $rowQuestionOption['option_4'] . "'>"
                                                    . '<span id="spantxt">D. ' . $rowQuestionOption['option_4'] . "</span>"
                                                    . "</label>"
                                                    . "</div>"
                                                    . "</div>"
                                                    . "</div>"
                                                    . "</div>"
                                                    . "</div>";
                                                }
                                                echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>";
                                                $testII = $testRes['Test_II'];
                                                if ($testII == null || $testII == 0 || $testII == "") {
                                                    echo "<nav aria-label='Page navigation example'>
                                            <ul class='pagination'>
                                              <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                              <li class='page-item active'><button class='page-link' type='button'>1</button></li>&nbsp&nbsp
                                              <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                            </ul>
                                          </nav>";
                                                }else{
                                                    if ($title['num_test'] == '2') {
                                                        echo "<nav aria-label='Page navigation example'>
                                            <ul class='pagination'>
                                              <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                              <li class='page-item active'><button class='page-link' type='button' id='back11'>1</button></li>
                                              <li class='page-item'><button class='page-link' type='button' id='back12'>2</button></li>
                                              <li class='page-item'><button class='page-link' type='button' id='nextbtn2'>Next</button></li>
                                            </ul>
                                          </nav>";
                                                    }else if ($title['num_test'] == '3') {
                                                        echo "<nav aria-label='Page navigation example'>
                                            <ul class='pagination '>
                                              <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                              <li class='page-item active'><button class='page-link' type='button' id='back11'>1</button></li>
                                              <li class='page-item'><button class='page-link' type='button' id='back12'>2</button></li>
                                              <li class='page-item'><button class='page-link' type='button' id='back13'>3</button></li>
                                              <li class='page-item'><button class='page-link' type='button' id='nextbtn2'>Next</button></li>
                                            </ul>
                                          </nav>";
                                                    }else if ($title['num_test'] == '4') {
                                                        echo "<nav aria-label='Page navigation example'>
                                            <ul class='pagination'>
                                              <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                              <li class='page-item active'><button class='page-link' type='button' id='back11'>1</button></li>
                                              <li class='page-item'><button class='page-link' type='button' id='back12'>2</button></li>
                                              <li class='page-item'><button class='page-link' type='button' id='back13'>3</button></li>
                                              <li class='page-item'><button class='page-link' type='button' id='back14'>4</button></li>
                                              <li class='page-item'><button class='page-link' type='button' id='nextbtn2'>Next</button></li>
                                            </ul>
                                          </nav>";
                                                    }else if ($title['num_test'] == '5') {
                                                        echo "<nav aria-label='Page navigation example'>
                                            <ul class='pagination'>
                                              <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                              <li class='page-item active'><button class='page-link' type='button' id='back11'>1</button></li>
                                              <li class='page-item'><button class='page-link' type='button' id='back12'>2</button></li>
                                              <li class='page-item'><button class='page-link' type='button' id='back13'>3</button></li>
                                              <li class='page-item'><button class='page-link' type='button' id='back14'>4</button></li>
                                              <li class='page-item'><button class='page-link' type='button' id='back15'>5</button></li>
                                              <li class='page-item'><button class='page-link' type='button' id='nextbtn2'>Next</button></li>
                                            </ul>
                                          </nav>";
                                                    }
                                                }
                                                echo "</div>
                          </div>
                          </div>";

                                                }
                                                }
                                                else if ($testI == "Short Answer") {
                                                    echo "<div class='row' id='test1'>
                        <div class='container'>
                                <div class='card card-secondary' >
                                  <div class='card-header' style='display: block;'> 
                                  <div class='row'>
                                    <div class='col'>
                                      <h4> Test I: Short Answer </h4>
                                      <span>Directions: Using your own words, answer each question in the space provided</span>
                                    </div>
                                    <div class='col' style='text-align: right;'>
                                      <span class='align-text-top'>Page 1 of " . $title['num_test'] . "</span>
                                    </div>
                                    </div>
                                    </div>
                                    </div>";
                                                    if ($QuestionOptionI->rowCount() > 0) {
                                                        while ($rowQuestionOption = $QuestionOptionI->fetch()) {
                                                            echo "<div class='card' style='padding: 25px 15px 25px 15px;'>
                                     <div class='form-wrapper' >
                                          <div class='row' style='display: flex;'>
                                          <section><span class='question-no'>" . $index++ . " ."."</span></section>
                                          <div class='col'>
                                          <section><span class='question'>".$rowQuestionOption['question']. '<br><span style="color: red;">' . '( '. $rowQuestionOption['points'] . ' Point/s )' . '</span>'. "</span></section>
                                          <input class='form-control form-control-sm' id='shorttxt' oninput='this.value = this.value.toUpperCase()' type='text' name='" . $rowQuestionOption['question_no'] . "' style='border-bottom: 1px solid #000;'>
                                          </div>
                                          </div>
                                        </div>
                                        </div>";
                                                        }
                                                    }
                                                    echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>";
                                                    $testII = $testRes['Test_II'];
                                                    if ($testII == null || $testII == 0 || $testII == "") {
                                                        echo "<nav aria-label='Page navigation example'>
                                                    <ul class='pagination'>
                                                      <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                                      <li class='page-item active'><button class='page-link' type='button'>1</button></li>&nbsp&nbsp
                                                      <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                                    </ul>
                                                  </nav>";
                                                    }else{
                                                        if ($title['num_test'] == '2') {
                                                            echo "<nav aria-label='Page navigation example'>
                                                    <ul class='pagination'>
                                                      <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                                      <li class='page-item active'><button class='page-link' type='button' id='back11'>1</button></li>
                                                      <li class='page-item '><button class='page-link' type='button' id='back12'>2</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='nextbtn2'>Next</button></li>
                                                    </ul>
                                                  </nav>";
                                                        }else if ($title['num_test'] == '3') {
                                                            echo "<nav aria-label='Page navigation example'>
                                                    <ul class='pagination '>
                                                      <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                                      <li class='page-item active'><button class='page-link' type='button' id='back11'>1</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back12'>2</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back13'>3</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='nextbtn2'>Next</button></li>
                                                    </ul>
                                                  </nav>";
                                                        }else if ($title['num_test'] == '4') {
                                                            echo "<nav aria-label='Page navigation example'>
                                                    <ul class='pagination'>
                                                      <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                                      <li class='page-item active'><button class='page-link' type='button' id='back11'>1</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back12'>2</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back13'>3</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back14'>4</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='nextbtn2'>Next</button></li>
                                                    </ul>
                                                  </nav>";
                                                        }else if ($title['num_test'] == '5') {
                                                            echo "<nav aria-label='Page navigation example'>
                                                    <ul class='pagination'>
                                                      <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                                      <li class='page-item active'><button class='page-link' type='button' id='back11'>1</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back12'>2</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back13'>3</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back14'>4</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back15'>5</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='nextbtn2'>Next</button></li>
                                                    </ul>
                                                  </nav>";
                                                        }
                                                    }
                                                    echo "</div>
                                </div>
                              </div>";

                                                }
                                                else if ($testI == "True/False") {
                                                    echo "<div class='row' id='test1''>
                          <div class='container'>
                                  <div class='card card-secondary' >
                                    <div class='card-header' style='display: block;'>
                                        <div class='row'>
                                          <div class='col'>
                                          <h4> Test I: True or False </h4>
                                          <span>Directions: Read the statements carefully and tell whether the statement is TRUE or FALSE. Choose TRUE if the statement is correct and FALSE if otherwise.</span>
                                          </div>
                                          <div class='col' style='text-align: right;'>
                                            <span class='align-text-top'>Page 1 of " . $title['num_test'] . "</span>
                                          </div>
                                          </div>
                                      </div>
                                </div>";
                                                    if ($QuestionOptionI->rowCount() > 0) {
                                                        while ($rowQuestionOption = $QuestionOptionI->fetch()) {
                                                            echo "<div class='card' style='padding: 25px 15px 25px 15px;'>
                                      <div class='form-wrapper' >
                                            <div class='row' style='display: flex;'>
                                            <section><span class='question-no'>" . $index++ . " ."."</span></section>
                                            <div class='col'>
                                            <section><span class='question'>".$rowQuestionOption['question']. '<br><span style="color: red;">' . '( '. $rowQuestionOption['points'] . ' Point/s )' . '</span>'. "</span></section>"
                                                                . "<div class='radio-group'>";
                                                            if ($rowQuestionOption['img_1'] != null
                                                                && $rowQuestionOption['img_2'] != null
                                                                && $rowQuestionOption['img_3'] != null
                                                                && $rowQuestionOption['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option1/' . $rowQuestionOption['img_1'] . '" />
                                                      
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option2/' . $rowQuestionOption['img_2'] . '"/>
                                                      
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option3/' . $rowQuestionOption['img_3'] . '"/>
                                                      
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option4/' . $rowQuestionOption['img_4'] . '"/>
                                                      
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOption['img_1'] != null
                                                                && $rowQuestionOption['img_2'] != null
                                                                && $rowQuestionOption['img_3'] != null
                                                                && $rowQuestionOption['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option1/' . $rowQuestionOption['img_1'] . '" />
                                                      
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option2/' . $rowQuestionOption['img_2'] . '"/>
                                                      
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option3/' . $rowQuestionOption['img_3'] . '"/>
                                                      
                                                        </div>
                                                        </div>';
                                                            }else if ($rowQuestionOption['img_2'] != null
                                                                && $rowQuestionOption['img_3'] != null
                                                                && $rowQuestionOption['img_4'] != null
                                                                && $rowQuestionOption['img_1'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option2/' . $rowQuestionOption['img_2'] . '"/>
                                                      
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option3/' . $rowQuestionOption['img_3'] . '"/>
                                                      
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option4/' . $rowQuestionOption['img_4'] . '"/>
                                                      
                                                        </div>
                                                        </div>';
                                                            }else if ($rowQuestionOption['img_1'] != null
                                                                && $rowQuestionOption['img_3'] != null
                                                                && $rowQuestionOption['img_4'] != null
                                                                && $rowQuestionOption['img_2'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option1/' . $rowQuestionOption['img_1'] . '"/>
                                                      
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option3/' . $rowQuestionOption['img_3'] . '"/>
                                                      
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option4/' . $rowQuestionOption['img_4'] . '"/>
                                                      
                                                        </div>
                                                        </div>';
                                                            }else if ($rowQuestionOption['img_1'] != null
                                                                && $rowQuestionOption['img_2'] != null
                                                                && $rowQuestionOption['img_4'] != null
                                                                && $rowQuestionOption['img_3'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option1/' . $rowQuestionOption['img_1'] . '"/>
                                                      
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option2/' . $rowQuestionOption['img_1'] . '"/>
                                                      
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option4/' . $rowQuestionOption['img_4'] . '"/>
                                                      
                                                        </div>
                                                        </div>';
                                                            }
                                                            //1-2 OR 2-1
                                                            else if ($rowQuestionOption['img_1'] != null
                                                                && $rowQuestionOption['img_2'] != null
                                                                && $rowQuestionOption['img_3'] == null
                                                                && $rowQuestionOption['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option1/' . $rowQuestionOption['img_1'] . '"/>
                                                      
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option2/' . $rowQuestionOption['img_2'] . '"/>
                                                      
                                                        </div>
                                                        </div>';
                                                            }

                                                            //1-3 OR 3-1
                                                            else if ($rowQuestionOption['img_1'] != null
                                                                && $rowQuestionOption['img_2'] == null
                                                                && $rowQuestionOption['img_3'] != null
                                                                && $rowQuestionOption['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option1/' . $rowQuestionOption['img_1'] . '"/>
                                                      
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option3/' . $rowQuestionOption['img_3'] . '"/>
                                                      
                                                        </div>
                                                        </div>';
                                                            }
                                                            //1-4 OR 4-1
                                                            else if ($rowQuestionOption['img_1'] != null
                                                                && $rowQuestionOption['img_2'] == null
                                                                && $rowQuestionOption['img_3'] == null
                                                                && $rowQuestionOption['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option1/' . $rowQuestionOption['img_1'] . '"/>
                                                      
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option4/' . $rowQuestionOption['img_4'] . '"/>
                                                      
                                                        </div>
                                                        </div>';
                                                            }
                                                            //2-3 OR 3-2
                                                            else if ($rowQuestionOption['img_1'] == null
                                                                && $rowQuestionOption['img_2'] != null
                                                                && $rowQuestionOption['img_3'] != null
                                                                && $rowQuestionOption['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option2/' . $rowQuestionOption['img_2'] . '"/>
                                                      
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option3/' . $rowQuestionOption['img_3'] . '"/>
                                                      
                                                        </div>
                                                        </div>';
                                                            }
                                                            //2-4 OR 4-2
                                                            else if ($rowQuestionOption['img_1'] == null
                                                                && $rowQuestionOption['img_2'] != null
                                                                && $rowQuestionOption['img_3'] == null
                                                                && $rowQuestionOption['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option2/' . $rowQuestionOption['img_2'] . '"/>
                                                      
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option4/' . $rowQuestionOption['img_4'] . '"/>
                                                      
                                                        </div>
                                                        </div>';
                                                            }
                                                            //3-4 OR 4-3
                                                            else if ($rowQuestionOption['img_1'] == null
                                                                && $rowQuestionOption['img_2'] == null
                                                                && $rowQuestionOption['img_3'] != null
                                                                && $rowQuestionOption['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option3/' . $rowQuestionOption['img_3'] . '"/>
                                                      
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option4/' . $rowQuestionOption['img_4'] . '"/>
                                                      
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOption['img_1'] != null
                                                                && $rowQuestionOption['img_2'] == null
                                                                && $rowQuestionOption['img_3'] == null
                                                                && $rowQuestionOption['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option1/' . $rowQuestionOption['img_1'] . '"/>
                                                      
                                                        </div>
                                                        </div>';
                                                            }else if ($rowQuestionOption['img_2'] != null
                                                                && $rowQuestionOption['img_3'] == null
                                                                && $rowQuestionOption['img_4'] == null
                                                                && $rowQuestionOption['img_1'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option2/' . $rowQuestionOption['img_2'] . '"/>
                                                      
                                                        </div>
                                                        </div>';
                                                            }else if ($rowQuestionOption['img_3'] != null
                                                                && $rowQuestionOption['img_4'] == null
                                                                && $rowQuestionOption['img_1'] == null
                                                                && $rowQuestionOption['img_2'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option3/' . $rowQuestionOption['img_3'] . '"/>
                                                      
                                                        </div>
                                                        </div>';
                                                            }else if ($rowQuestionOption['img_4'] != null
                                                                && $rowQuestionOption['img_1'] == null
                                                                && $rowQuestionOption['img_2'] == null
                                                                && $rowQuestionOption['img_3'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOption['question_type'] . '/' . $rowQuestionOption['question_no'] . '/option4/' . $rowQuestionOption['img_4'] . '"/>
                                                      
                                                        </div>
                                                        </div>';
                                                            }
                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOption['question_no'] . "'" . " value='True'>"
                                                                . "<span id='spantxt'>TRUE</span>"
                                                                . "</label>";
                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOption['question_no'] . "'" . " value='False'>"
                                                                . "<span id='spantxt'>FALSE</span>"
                                                                . "</label>"
                                                                . "</div>"
                                                                . "</div>"
                                                                . "</div>"
                                                                . "</div>
</div>";
                                                        }
                                                        echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>";
                                                        $testII = $testRes['Test_II'];
                                                        if ($testII == null || $testII == 0 || $testII == "") {
                                                            echo "<nav aria-label='Page navigation example'>
                                          <ul class='pagination'>
                                            <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                            <li class='page-item active'><button class='page-link' type='button'>1</button></li>&nbsp&nbsp
                                            <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                          </ul>
                                        </nav>";
                                                        }else{
                                                            if ($title['num_test'] == '2') {
                                                                echo "<nav aria-label='Page navigation example'>
                                          <ul class='pagination'>
                                            <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                            <li class='page-item active'><button class='page-link' type='button' id='back11'>1</button></li>
                                            <li class='page-item '><button class='page-link' type='button' id='back12'>2</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='nextbtn2'>Next</button></li>
                                          </ul>
                                        </nav>";
                                                            }else if ($title['num_test'] == '3') {
                                                                echo "<nav aria-label='Page navigation example'>
                                          <ul class='pagination '>
                                            <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                            <li class='page-item active'><button class='page-link' type='button' id='back11'>1</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='back12'>2</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='back13'>3</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='nextbtn2'>Next</button></li>
                                          </ul>
                                        </nav>";
                                                            }else if ($title['num_test'] == '4') {
                                                                echo "<nav aria-label='Page navigation example'>
                                          <ul class='pagination'>
                                            <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                            <li class='page-item active'><button class='page-link' type='button' id='back11'>1</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='back12'>2</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='back13'>3</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='back14'>4</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='nextbtn2'>Next</button></li>
                                          </ul>
                                        </nav>";
                                                            }else if ($title['num_test'] == '5') {
                                                                echo "<nav aria-label='Page navigation example'>
                                          <ul class='pagination'>
                                            <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                            <li class='page-item active'><button class='page-link' type='button' id='back11'>1</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='back12'>2</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='back13'>3</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='back14'>4</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='back15'>5</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='nextbtn2'>Next</button></li>
                                          </ul>
                                        </nav>";
                                                            }
                                                        }
                                                        echo "</div>
                                </div>
                                </div>";

                                                    }
                                                }
                                                else if ($testI == "Essay") {
                                                    echo "<div class='row' id='test1'>
                          <div class='container'>
                                  <div class='card card-secondary' >
                                    <div class='card-header' style='display: block;'> 
                                    <div class='row'>
                                      <div class='col'>
                                      <h4> Test I: Essay </h4>
                                      <span>Directions: Answer the question to the best of your knowledge. Write your answer in the space provided</span>
                                      </div>
                                      <div class='col' style='text-align: right;'>
                                        <span class='align-text-top'>Page 1 of " . $title['num_test'] . "</span>
                                      </div>
                                      </div>
                                      </div>
                                      </div>";
                                                    if ($QuestionOptionI->rowCount() > 0) {
                                                        while ($rowQuestionOption = $QuestionOptionI->fetch()) {
                                                            echo "<div class='card' style='padding: 25px 15px 25px 15px;'>
                                       <div class='form-wrapper' >
                                            <div class='row' style='display: flex;'>
                                            <section><span class='question-no'>" . $index++ . " ."."</span></section>
                                            <div class='col'>
                                            <section><span class='question'>".$rowQuestionOption['question']. '<br><span style="color: red;">' . '( '. $rowQuestionOption['points'] . ' Point/s )' . '</span>'. "</span></section>
                                            <textarea class='form-control' style='margin-top: 20px;' rows='3' name='" . $rowQuestionOption['question_no'] . "' autofocus></textarea>
                                            </div>
                                            </div>
                                          </div>
                                          </div>";
                                                        }
                                                    }
                                                    echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>";
                                                    $testII = $testRes['Test_II'];
                                                    if ($testII == null || $testII == 0 || $testII == "") {
                                                        echo "<nav aria-label='Page navigation example'>
                                          <ul class='pagination'>
                                            <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                            <li class='page-item active'><button class='page-link' type='button'>1</button></li>&nbsp&nbsp
                                            <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                          </ul>
                                        </nav>";
                                                    }else{
                                                        if ($title['num_test'] == '2') {
                                                            echo "<nav aria-label='Page navigation example'>
                                          <ul class='pagination'>
                                            <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                            <li class='page-item active'><button class='page-link' type='button' id='back11'>1</button></li>
                                            <li class='page-item '><button class='page-link' type='button' id='back12'>2</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='nextbtn2'>Next</button></li>
                                          </ul>
                                        </nav>";
                                                        }else if ($title['num_test'] == '3') {
                                                            echo "<nav aria-label='Page navigation example'>
                                          <ul class='pagination '>
                                            <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                            <li class='page-item active'><button class='page-link' type='button' id='back11'>1</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='back12'>2</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='back13'>3</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='nextbtn2'>Next</button></li>
                                          </ul>
                                        </nav>";
                                                        }else if ($title['num_test'] == '4') {
                                                            echo "<nav aria-label='Page navigation example'>
                                          <ul class='pagination'>
                                            <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                            <li class='page-item active'><button class='page-link' type='button' id='back11'>1</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='back12'>2</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='back13'>3</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='back14'>4</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='nextbtn2'>Next</button></li>
                                          </ul>
                                        </nav>";
                                                        }else if ($title['num_test'] == '5') {
                                                            echo "<nav aria-label='Page navigation example'>
                                          <ul class='pagination'>
                                            <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                            <li class='page-item active'><button class='page-link' type='button' id='back11'>1</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='back12'>2</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='back13'>3</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='back14'>4</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='back15'>5</button></li>
                                            <li class='page-item'><button class='page-link' type='button' id='nextbtn2'>Next</button></li>
                                          </ul>
                                        </nav>";
                                                        }
                                                    }
                                                    echo "</div>
                                </div>
                                </div>";

                                                }
                                                else if ($testI == "Multiple Image") {
                                                    echo "<div class='row' id='test1'>
                            <div class='container'>
                                    <div class='card card-secondary' >
                                      <div class='card-header' style='display: block;'>
                                      <div class='row'>
                                        <div class='col'>
                                          <h4> Test I: Multiple Choice (Images) </h4>
                                          <span>Directions: Choose the BEST IMAGE answer for the following questions. </span>
                                        </div>
                                        <div class='col' style='text-align: right;'>
                                          <span class='align-text-top'>Page 1 of " . $title['num_test'] . "</span>
                                        </div>
                                        </div>
                                        </div>
                                        </div>";
                                                    if ($QuestionOptionI->rowCount() > 0) {
                                                        while ($rowQuestionOption = $QuestionOptionI->fetch()) {
                                                            echo "<div class='card' style='padding: 25px 15px 25px 15px;'>
                                      <div class='form-wrapper' >
                                              <div class='row' style='display: flex;'>
                                              <section><span class='question-no'>" . $index++ . " ."."</span></section>
                                              <div class='col'>
                                              <section><span class='question'>".$rowQuestionOption['question']. '<br><span style="color: red;">' . '( '. $rowQuestionOption['points'] . ' Point/s )' . '</span>'. "</span></section>"
                                                                ."<div class='radio-group'>";
                                                            echo '<div class="row" style="justify-content: space-evenly;">
                                                            <div clas="col-4" style="text-align: center;">
                                                            <label>
                                                                <input class="btn radio-btn" type="radio" name="' . $rowQuestionOption['question_no'] . '" value="' . $rowQuestionOption['option_1'] . '">
                                                                <img class="img-option"src="assets/uploads/'.$department.'/'.$division.'/'.$id.'/'.$rowQuestionOption['question_type'].'/'.$rowQuestionOption['question_no'].'/'.'/option1/'.$rowQuestionOption['img_1'].'"/>
                                                            </label>
                                                            </div>
                                                            <div clas="col-4" style="text-align: center;">
                                                            <label>
                                                                <input class="btn radio-btn" type="radio" name="' . $rowQuestionOption['question_no'] . '" value="' . $rowQuestionOption['option_2'] . '">
                                                                <img class="img-option"src="assets/uploads/'.$department.'/'.$division.'/'.$id.'/'.$rowQuestionOption['question_type'].'/'.$rowQuestionOption['question_no'].'/'.'/option2/'.$rowQuestionOption['img_2'].'"/>
                                                            </label>
                                                            </div>
                                                            <div clas="col-4" style="text-align: center;">
                                                            <label>
                                                                <input class="btn radio-btn" type="radio" name="' . $rowQuestionOption['question_no'] . '" value="' . $rowQuestionOption['option_3'] . '">
                                                                <img class="img-option"src="assets/uploads/'.$department.'/'.$division.'/'.$id.'/'.$rowQuestionOption['question_type'].'/'.$rowQuestionOption['question_no'].'/'.'/option3/'.$rowQuestionOption['img_3'].'"/>
                                                            </label>
                                                            </div>
                                                            <div clas="col-4" style="text-align: center;">
                                                            <label>
                                                                <input class="btn radio-btn" type="radio" name="' . $rowQuestionOption['question_no'] . '" value="' . $rowQuestionOption['option_4'] . '">
                                                                <img class="img-option"src="assets/uploads/'.$department.'/'.$division.'/'.$id.'/'.$rowQuestionOption['question_type'].'/'.$rowQuestionOption['question_no'].'/'.'/option4/'.$rowQuestionOption['img_4'].'"/>
                                                            </label>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            </div>';
                                                        }
                                                        echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>";
                                                        $testII = $testRes['Test_II'];
                                                        if ($testII == null || $testII == 0 || $testII == "") {
                                                            echo "<nav aria-label='Page navigation example'>
                                                  <ul class='pagination'>
                                                    <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                                    <li class='page-item active'><button class='page-link' type='button'>1</button></li>&nbsp&nbsp
                                                    <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                                  </ul>
                                                </nav>";
                                                        }else{
                                                            if ($title['num_test'] == '2') {
                                                                echo "<nav aria-label='Page navigation example'>
                                                  <ul class='pagination'>
                                                    <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                                    <li class='page-item active'><button class='page-link' type='button' id='back11'>1</button></li>
                                                    <li class='page-item '><button class='page-link' type='button' id='back12'>2</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='nextbtn2'>Next</button></li>
                                                  </ul>
                                                </nav>";
                                                            }else if ($title['num_test'] == '3') {
                                                                echo "<nav aria-label='Page navigation example'>
                                                  <ul class='pagination '>
                                                    <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                                    <li class='page-item active'><button class='page-link' type='button' id='back11'>1</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back12'>2</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back13'>3</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='nextbtn2'>Next</button></li>
                                                  </ul>
                                                </nav>";
                                                            }else if ($title['num_test'] == '4') {
                                                                echo "<nav aria-label='Page navigation example'>
                                                  <ul class='pagination'>
                                                    <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                                    <li class='page-item active'><button class='page-link' type='button' id='back11'>1</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back12'>2</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back13'>3</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back14'>4</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='nextbtn2'>Next</button></li>
                                                  </ul>
                                                </nav>";
                                                            }else if ($title['num_test'] == '5') {
                                                                echo "<nav aria-label='Page navigation example'>
                                                  <ul class='pagination'>
                                                    <li class='page-item disabled'><button class='page-link' type='button'>Previous</button></li>
                                                    <li class='page-item active'><button class='page-link' type='button' id='back11'>1</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back12'>2</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back13'>3</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back14'>4</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back15'>5</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='nextbtn2'>Next</button></li>
                                                  </ul>
                                                </nav>";
                                                            }
                                                        }
                                                        echo "</div>
                                        </div>
                                        </div>";

                                                    }
                                                }
                                                // Test I End


                                                // Test II Start
                                                $testII = $testRes['Test_II'];
                                                // Test II Query
                                                $QuestionOptionII = $conn->query("SELECT a.`id`AS question_no, a.`question`, a.`question_type`, a.`points`, b.`option_1`, b.`option_2`,b.`option_3`,b.`option_4`,b.`img_1`,b.`img_2`,b.`img_3`,b.`img_4`, b.`ans`
                                                                                FROM `question` a 
                                                                                LEFT JOIN `options` b ON a.`id` = b.`id`
                                                                                WHERE a.`q_id` = '$id' AND b.`o_id` = '$id' AND a.`question_type` = '$testII'");
                                                if ($testII == "Multiple Choice") {
                                                    echo "<div class='row' id='test2' style='display: none;'>
                        <div class='container'>
                                <div class='card card-secondary' >
                                  <div class='card-header' style='display: block;'>
                                  <div class='row'>
                                    <div class='col'>
                                    <h4> Test II: Multiple Choice </h4>
                                    <span>Directions: Choose the BEST answer for the following questions.</span>
                                    </div>
                                    <div class='col' style='text-align: right;'>
                                      <span class='align-text-top'>Page 2 of " . $title['num_test'] . "</span>
                                    </div>
                                    </div>
                                    </div>
                                    </div>";
                                                    if ($QuestionOptionII->rowCount() > 0) {
                                                        while ($rowQuestionOptionII = $QuestionOptionII->fetch()) {
                                                            echo "<div class='card' style='padding: 25px 15px 25px 15px;'>
                                  <div class='form-wrapper' >
                                          <div class='row' style='display: flex;'>
                                          <section><span class='question-no'>" . $index++ . " ."."</span></section>
                                          <div class='col'>
                                          <section><span class='question'>".$rowQuestionOptionII['question'].'<br><span style="color: red;">' . '( '. $rowQuestionOptionII['points'] . ' Point/s )' . '</span>'. '</span>'."</span></section>"
                                                                ."<div class='radio-group'>";
                                                            if ($rowQuestionOptionII['img_1'] != null
                                                                && $rowQuestionOptionII['img_2'] != null
                                                                && $rowQuestionOptionII['img_3'] != null
                                                                && $rowQuestionOptionII['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option1/' . $rowQuestionOptionII['img_1'] . '" />
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option2/' . $rowQuestionOptionII['img_2'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option3/' . $rowQuestionOptionII['img_3'] . '"/>
                                                        <h4>III</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option4/' . $rowQuestionOptionII['img_4'] . '"/>
                                                        <h4>IV</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionII['img_1'] != null
                                                                && $rowQuestionOptionII['img_2'] != null
                                                                && $rowQuestionOptionII['img_3'] != null
                                                                && $rowQuestionOptionII['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option1/' . $rowQuestionOptionII['img_1'] . '" />
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option2/' . $rowQuestionOptionII['img_2'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option3/' . $rowQuestionOptionII['img_3'] . '"/>
                                                        <h4>III</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionII['img_2'] != null
                                                                && $rowQuestionOptionII['img_3'] != null
                                                                && $rowQuestionOptionII['img_4'] != null
                                                                && $rowQuestionOptionII['img_1'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option2/' . $rowQuestionOptionII['img_2'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option3/' . $rowQuestionOptionII['img_3'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option4/' . $rowQuestionOptionII['img_4'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionII['img_1'] != null
                                                                && $rowQuestionOptionII['img_3'] != null
                                                                && $rowQuestionOptionII['img_4'] != null
                                                                && $rowQuestionOptionII['img_2'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option1/' . $rowQuestionOptionII['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option3/' . $rowQuestionOptionII['img_3'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option4/' . $rowQuestionOptionII['img_4'] . '"/>
                                                        <h4>III</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionII['img_1'] != null
                                                                && $rowQuestionOptionII['img_2'] != null
                                                                && $rowQuestionOptionII['img_4'] != null
                                                                && $rowQuestionOptionII['img_3'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option1/' . $rowQuestionOptionII['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option2/' . $rowQuestionOptionII['img_1'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option4/' . $rowQuestionOptionII['img_4'] . '"/>
                                                        <h4>III</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            //1-2 OR 2-1
                                                            else if ($rowQuestionOptionII['img_1'] != null
                                                                && $rowQuestionOptionII['img_2'] != null
                                                                && $rowQuestionOptionII['img_3'] == null
                                                                && $rowQuestionOptionII['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option1/' . $rowQuestionOptionII['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option2/' . $rowQuestionOptionII['img_2'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }

                                                            //1-3 OR 3-1
                                                            else if ($rowQuestionOptionII['img_1'] != null
                                                                && $rowQuestionOptionII['img_2'] == null
                                                                && $rowQuestionOptionII['img_3'] != null
                                                                && $rowQuestionOptionII['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option1/' . $rowQuestionOptionII['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option3/' . $rowQuestionOptionII['img_3'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            //1-4 OR 4-1
                                                            else if ($rowQuestionOptionII['img_1'] != null
                                                                && $rowQuestionOptionII['img_2'] == null
                                                                && $rowQuestionOptionII['img_3'] == null
                                                                && $rowQuestionOptionII['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option1/' . $rowQuestionOptionII['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option4/' . $rowQuestionOptionII['img_4'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            //2-3 OR 3-2
                                                            else if ($rowQuestionOptionII['img_1'] == null
                                                                && $rowQuestionOptionII['img_2'] != null
                                                                && $rowQuestionOptionII['img_3'] != null
                                                                && $rowQuestionOptionII['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option2/' . $rowQuestionOptionII['img_2'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option3/' . $rowQuestionOptionII['img_3'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            //2-4 OR 4-2
                                                            else if ($rowQuestionOptionII['img_1'] == null
                                                                && $rowQuestionOptionII['img_2'] != null
                                                                && $rowQuestionOptionII['img_3'] == null
                                                                && $rowQuestionOptionII['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option2/' . $rowQuestionOptionII['img_2'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option4/' . $rowQuestionOptionII['img_4'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            //3-4 OR 4-3
                                                            else if ($rowQuestionOptionII['img_1'] == null
                                                                && $rowQuestionOptionII['img_2'] == null
                                                                && $rowQuestionOptionII['img_3'] != null
                                                                && $rowQuestionOptionII['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option3/' . $rowQuestionOptionII['img_3'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option4/' . $rowQuestionOptionII['img_4'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }

                                                            else if ($rowQuestionOptionII['img_1'] != null
                                                                && $rowQuestionOptionII['img_2'] == null
                                                                && $rowQuestionOptionII['img_3'] == null
                                                                && $rowQuestionOptionII['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option1/' . $rowQuestionOptionII['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionII['img_2'] != null
                                                                && $rowQuestionOptionII['img_3'] == null
                                                                && $rowQuestionOptionII['img_4'] == null
                                                                && $rowQuestionOptionII['img_1'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option2/' . $rowQuestionOptionII['img_2'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionII['img_3'] != null
                                                                && $rowQuestionOptionII['img_4'] == null
                                                                && $rowQuestionOptionII['img_1'] == null
                                                                && $rowQuestionOptionII['img_2'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option3/' . $rowQuestionOptionII['img_3'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionII['img_4'] != null
                                                                && $rowQuestionOptionII['img_1'] == null
                                                                && $rowQuestionOptionII['img_2'] == null
                                                                && $rowQuestionOptionII['img_3'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option4/' . $rowQuestionOptionII['img_4'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        </div>';
                                                            }

                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOptionII['question_no'] . "'" . "value=" . "'" . $rowQuestionOptionII['option_1'] . "'>"
                                                                . '<span id="spantxt">A. ' .  $rowQuestionOptionII['option_1'] . "</span>"
                                                                . "</label>";
                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOptionII['question_no'] . "'" . "value=" . "'" . $rowQuestionOptionII['option_2'] . "'>"
                                                                . '<span id="spantxt">B. ' . $rowQuestionOptionII['option_2']  . "</span>"
                                                                . "</label>";
                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOptionII['question_no'] . "'" . "value=" . "'" . $rowQuestionOptionII['option_3'] . "'>"
                                                                . '<span id="spantxt">C. ' . $rowQuestionOptionII['option_3'] . "</span>"
                                                                . "</label>"
                                                            ;
                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOptionII['question_no'] . "'" . "value=" . "'" . $rowQuestionOptionII['option_4'] . "'>"
                                                                . '<span id="spantxt">D. ' . $rowQuestionOptionII['option_4'] . "</span>"
                                                                . "</label>"
                                                                . "</div>"
                                                                . "</div>"
                                                                . "</div>"
                                                                . "</div></div>";
                                                        }
                                                        echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>";
                                                        $testIII = $testRes['Test_III'];
                                                        if ($testIII == null || $testIII == 0 || $testIII == "") {
                                                            echo "<nav aria-label='Page navigation example'>
                                                    <ul class='pagination'>
                                                      <li class='page-item'><button class='page-link' type='button' id='backbtn1'>Previous</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back21'>1</button></li>
                                                      <li class='page-item active'><button class='page-link' type='button' id='back22'>2</button></li>&nbsp&nbsp
                                                       <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                                    </ul>
                                                  </nav>
                                          ";
                                                        }else{
                                                            if ($title['num_test'] == '3') {
                                                                echo "<nav aria-label='Page navigation example'>
                                                    <ul class='pagination'>
                                                      <li class='page-item'><button class='page-link' type='button' id='backbtn1'>Previous</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back21'>1</button></li>
                                                      <li class='page-item active'><button class='page-link' type='button' id='back22'>2</button></li>
                                                      <li class='page-item '><button class='page-link' type='button' id='back23'>3</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='nextbtn3'>Next</button></li>
                                                    </ul>
                                                  </nav>";
                                                            }else if ($title['num_test'] == '4') {
                                                                echo "<nav aria-label='Page navigation example'>
                                                    <ul class='pagination'>
                                                      <li class='page-item'><button class='page-link' type='button' id='backbtn1'>Previous</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back21'>1</button></li>
                                                      <li class='page-item active'><button class='page-link' type='button' id='back22'>2</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back23'>3</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back24'>4</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='nextbtn3'>Next</button></li>
                                                    </ul>
                                                  </nav>";
                                                            }else if ($title['num_test'] == '5') {
                                                                echo "<nav aria-label='Page navigation example'>
                                                    <ul class='pagination'>
                                                      <li class='page-item'><button class='page-link' type='button' id='backbtn1'>Previous</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back21'>1</button></li>
                                                      <li class='page-item active'><button class='page-link' type='button' id='back22'>2</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back23'>3</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back24'>4</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back25'>5</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='nextbtn3'>Next</button></li>
                                                    </ul>
                                                  </nav>";
                                                            }
                                                        }
                                                        echo "</div>
                              </div>
                              </div>";

                                                    }
                                                }
                                                else if ($testII == "Short Answer") {
                                                    echo "<div class='row' id='test2' style='display: none;'>
                        <div class='container'>
                                <div class='card card-secondary' >
                                  <div class='card-header' style='display: block;'> 
                                  <div class='row'>
                                    <div class='col'>
                                      <h4> Test II: Short Answer </h4>
                                      <span>Directions: Using your own words, answer each question in the space provided</span>
                                    </div>
                                    <div class='col' style='text-align: right;'>
                                      <span class='align-text-top'>Page 2 of " . $title['num_test'] . "</span>
                                    </div>
                                    </div>
                                    </div>
                                    </div>";
                                                    if ($QuestionOptionII->rowCount() > 0) {
                                                        while ($rowQuestionOptionII = $QuestionOptionII->fetch()) {
                                                            echo "<div class='card' style='padding: 25px 15px 25px 15px;'>
                                     <div class='form-wrapper' >
                                          <div class='row' style='display: flex;'>
                                          <section><span class='question-no'>" . $index++ . " ."."</span></section>
                                          <div class='col'>
                                          <section><span class='question'>".$rowQuestionOptionII['question'].'<br><span style="color: red;">' . '( '. $rowQuestionOptionII['points'] . ' Point/s )' . '</span>'. "</span></section>
                                          <input class='form-control form-control-sm' id='shorttxt' oninput='this.value = this.value.toUpperCase()' type='text' name='" . $rowQuestionOptionII['question_no'] . "' style='border-bottom: 1px solid #000;'>
                                          </div>
                                          </div>
                                        </div>
                                        </div>";
                                                        }
                                                    }
                                                    echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>";
                                                    $testIII = $testRes['Test_III'];
                                                    if ($testIII == null || $testIII == 0 || $testIII == "") {
                                                        echo "<nav aria-label='Page navigation example'>
                                                <ul class='pagination'>
                                                  <li class='page-item'><button class='page-link' type='button' id='backbtn1'>Previous</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back21'>1</button></li>
                                                  <li class='page-item active'><button class='page-link' type='button' id='back22'>2</button></li>&nbsp&nbsp
                                                  <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                                </ul>
                                              </nav>
                                      ";
                                                    }else{
                                                        if ($title['num_test'] == '3') {
                                                            echo "<nav aria-label='Page navigation example'>
                                                <ul class='pagination'>
                                                  <li class='page-item'><button class='page-link' type='button' id='backbtn1'>Previous</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back21'>1</button></li>
                                                  <li class='page-item active'><button class='page-link' type='button' id='back22'>2</button></li>
                                                  <li class='page-item '><button class='page-link' type='button' id='back23'>3</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='nextbtn3'>Next</button></li>
                                                </ul>
                                              </nav>";
                                                        }else if ($title['num_test'] == '4') {
                                                            echo "<nav aria-label='Page navigation example'>
                                                <ul class='pagination'>
                                                  <li class='page-item'><button class='page-link' type='button' id='backbtn1'>Previous</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back21'>1</button></li>
                                                  <li class='page-item active'><button class='page-link' type='button' id='back22'>2</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back23'>3</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back24'>4</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='nextbtn3'>Next</button></li>
                                                </ul>
                                              </nav>";
                                                        }else if ($title['num_test'] == '5') {
                                                            echo "<nav aria-label='Page navigation example'>
                                                <ul class='pagination'>
                                                  <li class='page-item'><button class='page-link' type='button' id='backbtn1'>Previous</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back21'>1</button></li>
                                                  <li class='page-item active'><button class='page-link' type='button' id='back22'>2</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back23'>3</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back24'>4</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back25'>5</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='nextbtn3'>Next</button></li>
                                                </ul>
                                              </nav>";
                                                        }
                                                    }
                                                    echo "</div>
                          </div>
                          </div>";

                                                }
                                                else if ($testII == "True/False") {
                                                    echo "<div class='row' id='test2' style='display:none;'>
                        <div class='container'>
                                <div class='card card-secondary' >
                                  <div class='card-header' style='display: block;'>
                                  <div class='row'>
                                    <div class='col'>
                                    <h4> Test II: True or False </h4>
                                    <span>Directions: Read the statements carefully and tell whether the statement is TRUE or FALSE. Choose TRUE if the statement is correct and FALSE if otherwise.</span>
                                    </div>
                                    <div class='col' style='text-align: right;'>
                                      <span class='align-text-top'>Page 2 of " . $title['num_test'] . "</span>
                                    </div>
                                    </div>
                                    </div>
                                    </div>";
                                                    if ($QuestionOptionII->rowCount() > 0) {
                                                        while ($rowQuestionOptionII = $QuestionOptionII->fetch()) {
                                                            echo "<div class='card' style='padding: 25px 15px 25px 15px;'>
                                    <div class='form-wrapper' >
                                          <div class='row' style='display: flex;'>
                                          <section><span class='question-no'>" . $index++ . " ."."</span></section>
                                          <div class='col'>
                                          <section><span class='question'>".$rowQuestionOptionII['question']. '<br><span style="color: red;">' . '( '. $rowQuestionOptionII['points'] . ' Point/s )' . '</span>'. "</span></section>"
                                                                . "<div class='radio-group'>";
                                                            if ($rowQuestionOptionII['img_1'] != null
                                                                && $rowQuestionOptionII['img_2'] != null
                                                                && $rowQuestionOptionII['img_3'] != null
                                                                && $rowQuestionOptionII['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option1/' . $rowQuestionOptionII['img_1'] . '" />
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option2/' . $rowQuestionOptionII['img_2'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option3/' . $rowQuestionOptionII['img_3'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option4/' . $rowQuestionOptionII['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionII['img_1'] != null
                                                                && $rowQuestionOptionII['img_2'] != null
                                                                && $rowQuestionOptionII['img_3'] != null
                                                                && $rowQuestionOptionII['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option1/' . $rowQuestionOptionII['img_1'] . '" />
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option2/' . $rowQuestionOptionII['img_2'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option3/' . $rowQuestionOptionII['img_3'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionII['img_2'] != null
                                                                && $rowQuestionOptionII['img_3'] != null
                                                                && $rowQuestionOptionII['img_4'] != null
                                                                && $rowQuestionOptionII['img_1'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option2/' . $rowQuestionOptionII['img_2'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option3/' . $rowQuestionOptionII['img_3'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option4/' . $rowQuestionOptionII['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionII['img_1'] != null
                                                                && $rowQuestionOptionII['img_3'] != null
                                                                && $rowQuestionOptionII['img_4'] != null
                                                                && $rowQuestionOptionII['img_2'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option1/' . $rowQuestionOptionII['img_1'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option3/' . $rowQuestionOptionII['img_3'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option4/' . $rowQuestionOptionII['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionII['img_1'] != null
                                                                && $rowQuestionOptionII['img_2'] != null
                                                                && $rowQuestionOptionII['img_4'] != null
                                                                && $rowQuestionOptionII['img_3'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option1/' . $rowQuestionOptionII['img_1'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option2/' . $rowQuestionOptionII['img_1'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option4/' . $rowQuestionOptionII['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            //1-2 OR 2-1
                                                            else if ($rowQuestionOptionII['img_1'] != null
                                                                && $rowQuestionOptionII['img_2'] != null
                                                                && $rowQuestionOptionII['img_3'] == null
                                                                && $rowQuestionOptionII['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option1/' . $rowQuestionOptionII['img_1'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option2/' . $rowQuestionOptionII['img_2'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }

                                                            //1-3 OR 3-1
                                                            else if ($rowQuestionOptionII['img_1'] != null
                                                                && $rowQuestionOptionII['img_2'] == null
                                                                && $rowQuestionOptionII['img_3'] != null
                                                                && $rowQuestionOptionII['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option1/' . $rowQuestionOptionII['img_1'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option3/' . $rowQuestionOptionII['img_3'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            //1-4 OR 4-1
                                                            else if ($rowQuestionOptionII['img_1'] != null
                                                                && $rowQuestionOptionII['img_2'] == null
                                                                && $rowQuestionOptionII['img_3'] == null
                                                                && $rowQuestionOptionII['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option1/' . $rowQuestionOptionII['img_1'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option4/' . $rowQuestionOptionII['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            //2-3 OR 3-2
                                                            else if ($rowQuestionOptionII['img_1'] == null
                                                                && $rowQuestionOptionII['img_2'] != null
                                                                && $rowQuestionOptionII['img_3'] != null
                                                                && $rowQuestionOptionII['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option2/' . $rowQuestionOptionII['img_2'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option3/' . $rowQuestionOptionII['img_3'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            //2-4 OR 4-2
                                                            else if ($rowQuestionOptionII['img_1'] == null
                                                                && $rowQuestionOptionII['img_2'] != null
                                                                && $rowQuestionOptionII['img_3'] == null
                                                                && $rowQuestionOptionII['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option2/' . $rowQuestionOptionII['img_2'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option4/' . $rowQuestionOptionII['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            //3-4 OR 4-3
                                                            else if ($rowQuestionOptionII['img_1'] == null
                                                                && $rowQuestionOptionII['img_2'] == null
                                                                && $rowQuestionOptionII['img_3'] != null
                                                                && $rowQuestionOptionII['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option3/' . $rowQuestionOptionII['img_3'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option4/' . $rowQuestionOptionII['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionII['img_1'] != null
                                                                && $rowQuestionOptionII['img_2'] == null
                                                                && $rowQuestionOptionII['img_3'] == null
                                                                && $rowQuestionOptionII['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option1/' . $rowQuestionOptionII['img_1'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionII['img_2'] != null
                                                                && $rowQuestionOptionII['img_3'] == null
                                                                && $rowQuestionOptionII['img_4'] == null
                                                                && $rowQuestionOptionII['img_1'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option2/' . $rowQuestionOptionII['img_2'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionII['img_3'] != null
                                                                && $rowQuestionOptionII['img_4'] == null
                                                                && $rowQuestionOptionII['img_1'] == null
                                                                && $rowQuestionOptionII['img_2'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option3/' . $rowQuestionOptionII['img_3'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionII['img_4'] != null
                                                                && $rowQuestionOptionII['img_1'] == null
                                                                && $rowQuestionOptionII['img_2'] == null
                                                                && $rowQuestionOptionII['img_3'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionII['question_type'] . '/' . $rowQuestionOptionII['question_no'] . '/option4/' . $rowQuestionOptionII['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }

                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOptionII['question_no'] . "'" . " value='True'>"
                                                                . "<span id='spantxt'>TRUE</span>"
                                                                . "</label>";
                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOptionII['question_no'] . "'" . " value='False'>"
                                                                . "<span id='spantxt'>FALSE</span>"
                                                                . "</label>"
                                                                . "</div>"
                                                                . "</div>"
                                                                . "</div>"
                                                                . "</div></div>";
                                                        }
                                                        echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>";
                                                        $testIII = $testRes['Test_III'];
                                                        if ($testIII == null || $testIII == 0 || $testIII == "") {
                                                            echo "<nav aria-label='Page navigation example'>
                                              <ul class='pagination'>
                                                <li class='page-item'><button class='page-link' type='button' id='backbtn1'>Previous</button></li>
                                                <li class='page-item'><button class='page-link' type='button' id='back21'>1</button></li>
                                                <li class='page-item active'><button class='page-link' type='button' id='back22'>2</button></li>&nbsp&nbsp
                                                <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                              </ul>
                                            </nav>
                                    ";
                                                        }else{
                                                            if ($title['num_test'] == '3') {
                                                                echo "<nav aria-label='Page navigation example'>
                                              <ul class='pagination'>
                                                <li class='page-item'><button class='page-link' type='button' id='backbtn1'>Previous</button></li>
                                                <li class='page-item'><button class='page-link' type='button' id='back21'>1</button></li>
                                                <li class='page-item active'><button class='page-link' type='button' id='back22'>2</button></li>
                                                <li class='page-item '><button class='page-link' type='button' id='back23'>3</button></li>
                                                <li class='page-item'><button class='page-link' type='button' id='nextbtn3'>Next</button></li>
                                              </ul>
                                            </nav>";
                                                            }else if ($title['num_test'] == '4') {
                                                                echo "<nav aria-label='Page navigation example'>
                                              <ul class='pagination'>
                                                <li class='page-item'><button class='page-link' type='button' id='backbtn1'>Previous</button></li>
                                                <li class='page-item'><button class='page-link' type='button' id='back21'>1</button></li>
                                                <li class='page-item active'><button class='page-link' type='button' id='back22'>2</button></li>
                                                <li class='page-item'><button class='page-link' type='button' id='back23'>3</button></li>
                                                <li class='page-item'><button class='page-link' type='button' id='back24'>4</button></li>
                                                <li class='page-item'><button class='page-link' type='button' id='nextbtn3'>Next</button></li>
                                              </ul>
                                            </nav>";
                                                            }else if ($title['num_test'] == '5') {
                                                                echo "<nav aria-label='Page navigation example'>
                                              <ul class='pagination'>
                                                <li class='page-item'><button class='page-link' type='button' id='backbtn1'>Previous</button></li>
                                                <li class='page-item'><button class='page-link' type='button' id='back21'>1</button></li>
                                                <li class='page-item active'><button class='page-link' type='button' id='back22'>2</button></li>
                                                <li class='page-item'><button class='page-link' type='button' id='back23'>3</button></li>
                                                <li class='page-item'><button class='page-link' type='button' id='back24'>4</button></li>
                                                <li class='page-item'><button class='page-link' type='button' id='back25'>5</button></li>
                                                <li class='page-item'><button class='page-link' type='button' id='nextbtn3'>Next</button></li>
                                              </ul>
                                            </nav>";
                                                            }
                                                        }
                                                        echo "</div>
                        </div>
                        </div>";

                                                    }
                                                }
                                                else if ($testII == "Essay") {
                                                    echo "<div class='row' id='test2' style='display: none;'>
                        <div class='container'>
                                <div class='card card-secondary' >
                                  <div class='card-header' style='display: block;'> 
                                  <div class='row'>
                                    <div class='col'>
                                    <h4> Test II: Essay </h4>
                                    <span>Directions: Answer the question to the best of your knowledge. Write your answer in the space provided</span>
                                    </div>
                                    <div class='col' style='text-align: right;'>
                                      <span class='align-text-top'>Page 2 of " . $title['num_test'] . "</span>
                                    </div>
                                    </div>
                                    </div>
                                    </div>";
                                                    if ($QuestionOptionII->rowCount() > 0) {
                                                        while ($rowQuestionOptionII = $QuestionOptionII->fetch()) {
                                                            echo "<div class='card' style='padding: 25px 15px 25px 15px;'>
                                                         <div class='form-wrapper' >
                                                              <div class='row' style='display: flex;'>
                                                              <section><span class='question-no'>" . $index++ . " ."."</span></section>
                                                              <div class='col'>
                                                              <section><span class='question'>".$rowQuestionOptionII['question']. '<br><span style="color: red;">' . '( '. $rowQuestionOptionII['points'] . ' Point/s )' . '</span>'. "</span></section>
                                                              <textarea class='form-control' style='margin-top: 20px;' rows='3' name='" . $rowQuestionOptionII['question_no'] . "' ></textarea>
                                                              </div>
                                                              </div>
                                                            </div>
                                                            </div>";
                                                        }
                                                    }
                                                    echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>";
                                                    $testIII = $testRes['Test_III'];
                                                    if ($testIII == null || $testIII == 0 || $testIII == "") {
                                                        echo "<nav aria-label='Page navigation example'>
                                                <ul class='pagination'>
                                                  <li class='page-item'><button class='page-link' type='button' id='backbtn1'>Previous</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back21'>1</button></li>
                                                  <li class='page-item active'><button class='page-link' type='button' id='back22'>2</button></li>&nbsp&nbsp
                                                  <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                                </ul>
                                              </nav>
                                      ";
                                                    }else{
                                                        if ($title['num_test'] == '3') {
                                                            echo "<nav aria-label='Page navigation example'>
                                                <ul class='pagination'>
                                                  <li class='page-item'><button class='page-link' type='button' id='backbtn1'>Previous</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back21'>1</button></li>
                                                  <li class='page-item active'><button class='page-link' type='button' id='back22'>2</button></li>
                                                  <li class='page-item '><button class='page-link' type='button' id='back23'>3</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='nextbtn3'>Next</button></li>
                                                </ul>
                                              </nav>";
                                                        }else if ($title['num_test'] == '4') {
                                                            echo "<nav aria-label='Page navigation example'>
                                                <ul class='pagination'>
                                                  <li class='page-item'><button class='page-link' type='button' id='backbtn1'>Previous</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back21'>1</button></li>
                                                  <li class='page-item active'><button class='page-link' type='button' id='back22'>2</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back23'>3</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back24'>4</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='nextbtn3'>Next</button></li>
                                                </ul>
                                              </nav>";
                                                        }else if ($title['num_test'] == '5') {
                                                            echo "<nav aria-label='Page navigation example'>
                                                <ul class='pagination'>
                                                  <li class='page-item'><button class='page-link' type='button' id='backbtn1'>Previous</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back21'>1</button></li>
                                                  <li class='page-item active'><button class='page-link' type='button' id='back22'>2</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back23'>3</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back24'>4</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back25'>5</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='nextbtn3'>Next</button></li>
                                                </ul>
                                              </nav>";
                                                        }
                                                    }
                                                    echo "</div>
                                      </div>
                                      </div>";

                                                }
                                                else if ($testII == "Multiple Image") {
                                                    echo "<div class='row' id='test2' style='display: none;'>
                                                <div class='container'>
                                                        <div class='card card-secondary' >
                                                          <div class='card-header' style='display: block;'>
                                                          <div class='row'>
                                                            <div class='col'>
                                                              <h4> Test II: Multiple Choice (Images) </h4>
                                                              <span>Directions: Choose the BEST IMAGE answer for the following questions. </span>
                                                            </div>
                                                            <div class='col' style='text-align: right;'>
                                                              <span class='align-text-top'>Page 2 of " . $title['num_test'] . "</span>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            </div>";

                                                    if ($QuestionOptionII->rowCount() > 0) {
                                                        while ($rowQuestionOptionII = $QuestionOptionII->fetch()) {
                                                            echo "<div class='card' style='padding: 25px 15px 25px 15px;'>
                                      <div class='form-wrapper' >
                                              <div class='row' style='display: flex;'>
                                              <section><span class='question-no'>" . $index++ . " ."."</span></section>
                                              <div class='col'>
                                              <section><span class='question'>".$rowQuestionOptionII['question']. '<br><span style="color: red;">' . '( '. $rowQuestionOptionII['points'] . ' Point/s )' . '</span>'. "</span></section>"
                                                                ."<div class='radio-group'>";
                                                            echo '<div class="row" style="justify-content: space-evenly;">
                                                            <div clas="col-4" style="text-align: center;">
                                                            <label>
                                                                <input class="btn radio-btn" type="radio" name="' . $rowQuestionOptionII['question_no'] . '" value="' . $rowQuestionOptionII['option_1'] . '">
                                                                <img class="img-option"src="assets/uploads/'.$department.'/'.$division.'/'.$id.'/'.$rowQuestionOptionII['question_type'].'/'.$rowQuestionOptionII['question_no'].'/'.'/option1/'.$rowQuestionOptionII['img_1'].'"/>
                                                            </label>
                                                            </div>
                                                            <div clas="col-4" style="text-align: center;">
                                                            <label>
                                                                <input class="btn radio-btn" type="radio" name="' . $rowQuestionOptionII['question_no'] . '" value="' . $rowQuestionOptionII['option_2'] . '">
                                                                <img class="img-option"src="assets/uploads/'.$department.'/'.$division.'/'.$id.'/'.$rowQuestionOptionII['question_type'].'/'.$rowQuestionOptionII['question_no'].'/'.'/option2/'.$rowQuestionOptionII['img_2'].'"/>
                                                            </label>
                                                            </div>
                                                            <div clas="col-4" style="text-align: center;">
                                                            <label>
                                                                <input class="btn radio-btn" type="radio" name="' . $rowQuestionOptionII['question_no'] . '" value="' . $rowQuestionOptionII['option_3'] . '">
                                                                <img class="img-option"src="assets/uploads/'.$department.'/'.$division.'/'.$id.'/'.$rowQuestionOptionII['question_type'].'/'.$rowQuestionOptionII['question_no'].'/'.'/option3/'.$rowQuestionOptionII['img_3'].'"/>
                                                            </label>
                                                            </div>
                                                            <div clas="col-4" style="text-align: center;">
                                                            <label>
                                                                <input class="btn radio-btn" type="radio" name="' . $rowQuestionOptionII['question_no'] . '" value="' . $rowQuestionOptionII['option_4'] . '">
                                                                <img class="img-option"src="assets/uploads/'.$department.'/'.$division.'/'.$id.'/'.$rowQuestionOptionII['question_type'].'/'.$rowQuestionOptionII['question_no'].'/'.'/option4/'.$rowQuestionOptionII['img_4'].'"/>
                                                            </label>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            </div>';
                                                        }
                                                        echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>";
                                                        $testIII = $testRes['Test_III'];
                                                        if ($testIII == null || $testIII == 0 || $testIII == "") {
                                                            echo "<nav aria-label='Page navigation example'>
                                                <ul class='pagination'>
                                                  <li class='page-item'><button class='page-link' type='button' id='backbtn1'>Previous</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back21'>1</button></li>
                                                  <li class='page-item active'><button class='page-link' type='button' id='back22'>2</button></li>&nbsp&nbsp
                                                  <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                                </ul>
                                              </nav>
                                      ";
                                                        }else{
                                                            if ($title['num_test'] == '3') {
                                                                echo "<nav aria-label='Page navigation example'>
                                                <ul class='pagination'>
                                                  <li class='page-item'><button class='page-link' type='button' id='backbtn1'>Previous</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back21'>1</button></li>
                                                  <li class='page-item active'><button class='page-link' type='button' id='back22'>2</button></li>
                                                  <li class='page-item '><button class='page-link' type='button' id='back23'>3</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='nextbtn3'>Next</button></li>
                                                </ul>
                                              </nav>";
                                                            }else if ($title['num_test'] == '4') {
                                                                echo "<nav aria-label='Page navigation example'>
                                                <ul class='pagination'>
                                                  <li class='page-item'><button class='page-link' type='button' id='backbtn1'>Previous</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back21'>1</button></li>
                                                  <li class='page-item active'><button class='page-link' type='button' id='back22'>2</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back23'>3</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back24'>4</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='nextbtn3'>Next</button></li>
                                                </ul>
                                              </nav>";
                                                            }else if ($title['num_test'] == '5') {
                                                                echo "<nav aria-label='Page navigation example'>
                                                <ul class='pagination'>
                                                  <li class='page-item'><button class='page-link' type='button' id='backbtn1'>Previous</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back21'>1</button></li>
                                                  <li class='page-item active'><button class='page-link' type='button' id='back22'>2</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back23'>3</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back24'>4</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='back25'>5</button></li>
                                                  <li class='page-item'><button class='page-link' type='button' id='nextbtn3'>Next</button></li>
                                                </ul>
                                              </nav>";
                                                            }
                                                        }
                                                        echo "</div>
                                      </div>
                                      </div>";

                                                    }
                                                }
                                                // Test II End

                                                // Test III Start
                                                $testIII = $testRes['Test_III'];
                                                // Test III Query
                                                $QuestionOptionIII = $conn->query("SELECT a.`id`AS question_no, a.`question`, a.`question_type`, a.`points`, b.`option_1`, b.`option_2`,b.`option_3`,b.`option_4`,b.`img_1`,b.`img_2`,b.`img_3`,b.`img_4`, b.`ans`
                                                                                FROM `question` a 
                                                                                LEFT JOIN `options` b ON a.`id` = b.`id`
                                                                                WHERE a.`q_id` = '$id' AND b.`o_id` = '$id' AND a.`question_type` = '$testIII'");
                                                if ($testIII == "True/False") {
                                                    echo "<div class='row' id='test3' style='display: none;'>
                        <div class='container'>
                                <div class='card card-secondary' >
                                  <div class='card-header' style='display: block;'>
                                  <div class='row'>
                                    <div class='col'>
                                    <h4> Test III: True or False </h4>
                                    <span>Directions: Read the statements carefully and tell whether the statement is TRUE or FALSE. Choose TRUE if the statement is correct and FALSE if otherwise.</span>
                                    </div>
                                    <div class='col' style='text-align: right;'>
                                      <span class='align-text-top'>Page 3 of " . $title['num_test'] . "</span>
                                    </div>
                                    </div>
                                    </div>
                                    </div>";
                                                    if ($QuestionOptionIII->rowCount() > 0) {
                                                        while ($rowQuestionOptionIII = $QuestionOptionIII->fetch()) {
                                                            echo "<div class='card' style='padding: 25px 15px 25px 15px;'>
                                    <div class='form-wrapper' >
                                          <div class='row' style='display: flex;'>
                                          <section><span class='question-no'>" . $index++ . " ."."</span></section>
                                          <div class='col'>
                                          <section><span class='question'>".$rowQuestionOptionIII['question']. '<br><span style="color: red;">' . '( '. $rowQuestionOptionIII['points'] . ' Point/s )' . '</span>'. "</span></section>"
                                                                . "<div class='radio-group'>";

                                                            if ($rowQuestionOptionIII['img_1'] != null
                                                                && $rowQuestionOptionIII['img_2'] != null
                                                                && $rowQuestionOptionIII['img_3'] != null
                                                                && $rowQuestionOptionIII['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option1/' . $rowQuestionOptionIII['img_1'] . '" />
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option2/' . $rowQuestionOptionIII['img_2'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option3/' . $rowQuestionOptionIII['img_3'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option4/' . $rowQuestionOptionIII['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIII['img_1'] != null
                                                                && $rowQuestionOptionIII['img_2'] != null
                                                                && $rowQuestionOptionIII['img_3'] != null
                                                                && $rowQuestionOptionIII['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option1/' . $rowQuestionOptionIII['img_1'] . '" />
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option2/' . $rowQuestionOptionIII['img_2'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option3/' . $rowQuestionOptionIII['img_3'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIII['img_2'] != null
                                                                && $rowQuestionOptionIII['img_3'] != null
                                                                && $rowQuestionOptionIII['img_4'] != null
                                                                && $rowQuestionOptionIII['img_1'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option2/' . $rowQuestionOptionIII['img_2'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option3/' . $rowQuestionOptionIII['img_3'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option4/' . $rowQuestionOptionIII['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIII['img_1'] != null
                                                                && $rowQuestionOptionIII['img_3'] != null
                                                                && $rowQuestionOptionIII['img_4'] != null
                                                                && $rowQuestionOptionIII['img_2'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option1/' . $rowQuestionOptionIII['img_1'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option3/' . $rowQuestionOptionIII['img_3'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option4/' . $rowQuestionOptionIII['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIII['img_1'] != null
                                                                && $rowQuestionOptionIII['img_2'] != null
                                                                && $rowQuestionOptionIII['img_4'] != null
                                                                && $rowQuestionOptionIII['img_3'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option1/' . $rowQuestionOptionIII['img_1'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option2/' . $rowQuestionOptionIII['img_1'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option4/' . $rowQuestionOptionIII['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            //1-2 OR 2-1
                                                            else if ($rowQuestionOptionIII['img_1'] != null
                                                                && $rowQuestionOptionIII['img_2'] != null
                                                                && $rowQuestionOptionIII['img_3'] == null
                                                                && $rowQuestionOptionIII['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option1/' . $rowQuestionOptionIII['img_1'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option2/' . $rowQuestionOptionIII['img_2'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }

                                                            //1-3 OR 3-1
                                                            else if ($rowQuestionOptionIII['img_1'] != null
                                                                && $rowQuestionOptionIII['img_2'] == null
                                                                && $rowQuestionOptionIII['img_3'] != null
                                                                && $rowQuestionOptionIII['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option1/' . $rowQuestionOptionIII['img_1'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option3/' . $rowQuestionOptionIII['img_3'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            //1-4 OR 4-1
                                                            else if ($rowQuestionOptionIII['img_1'] != null
                                                                && $rowQuestionOptionIII['img_2'] == null
                                                                && $rowQuestionOptionIII['img_3'] == null
                                                                && $rowQuestionOptionIII['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option1/' . $rowQuestionOptionIII['img_1'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option4/' . $rowQuestionOptionIII['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            //2-3 OR 3-2
                                                            else if ($rowQuestionOptionIII['img_1'] == null
                                                                && $rowQuestionOptionIII['img_2'] != null
                                                                && $rowQuestionOptionIII['img_3'] != null
                                                                && $rowQuestionOptionIII['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option2/' . $rowQuestionOptionIII['img_2'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option3/' . $rowQuestionOptionIII['img_3'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            //2-4 OR 4-2
                                                            else if ($rowQuestionOptionIII['img_1'] == null
                                                                && $rowQuestionOptionIII['img_2'] != null
                                                                && $rowQuestionOptionIII['img_3'] == null
                                                                && $rowQuestionOptionIII['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option2/' . $rowQuestionOptionIII['img_2'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option4/' . $rowQuestionOptionIII['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            //3-4 OR 4-3
                                                            else if ($rowQuestionOptionIII['img_1'] == null
                                                                && $rowQuestionOptionIII['img_2'] == null
                                                                && $rowQuestionOptionIII['img_3'] != null
                                                                && $rowQuestionOptionIII['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option3/' . $rowQuestionOptionIII['img_3'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option4/' . $rowQuestionOptionIII['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIII['img_1'] != null
                                                                && $rowQuestionOptionIII['img_2'] == null
                                                                && $rowQuestionOptionIII['img_3'] == null
                                                                && $rowQuestionOptionIII['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option1/' . $rowQuestionOptionIII['img_1'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIII['img_2'] != null
                                                                && $rowQuestionOptionIII['img_3'] == null
                                                                && $rowQuestionOptionIII['img_4'] == null
                                                                && $rowQuestionOptionIII['img_1'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option2/' . $rowQuestionOptionIII['img_2'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIII['img_3'] != null
                                                                && $rowQuestionOptionIII['img_4'] == null
                                                                && $rowQuestionOptionIII['img_1'] == null
                                                                && $rowQuestionOptionIII['img_2'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option3/' . $rowQuestionOptionIII['img_3'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIII['img_4'] != null
                                                                && $rowQuestionOptionIII['img_1'] == null
                                                                && $rowQuestionOptionIII['img_2'] == null
                                                                && $rowQuestionOptionIII['img_3'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option4/' . $rowQuestionOptionIII['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }

                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOptionIII['question_no'] . "'" . " value='True'>"
                                                                . "<span id='spantxt'>TRUE</span>"
                                                                . "</label>";
                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOptionIII['question_no'] . "'" . " value='False'>"
                                                                . "<span id='spantxt'>FALSE</span>"
                                                                . "</label>"
                                                                . "</div>"
                                                                . "</div>"
                                                                . "</div>"
                                                                . "</div></div>";
                                                        }
                                                        echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>";
                                                        $testIV = $testRes['Test_IV'];
                                                        if ($testIV == null || $testIV == 0 || $testIV == "") {
                                                            echo "<nav aria-label='Page navigation example'>
                                                  <ul class='pagination'>
                                                    <li class='page-item'><button class='page-link' type='button' id='backbtn2'>Previous</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back31'>1</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back32'>2</button></li>
                                                    <li class='page-item active'><button class='page-link' type='button' id='back33'>3</button></li>&nbsp&nbsp
                                                    <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                                  </ul>
                                                </nav>
                                        ";
                                                        }else{
                                                            if ($title['num_test'] == '4') {
                                                                echo "<nav aria-label='Page navigation example'>
                                                  <ul class='pagination'>
                                                    <li class='page-item'><button class='page-link' type='button' id='backbtn2'>Previous</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back31'>1</button></li>
                                                    <li class='page-item '><button class='page-link' type='button' id='back32'>2</button></li>
                                                    <li class='page-item active'><button class='page-link' type='button' id='back33'>3</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back34'>4</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='nextbtn4'>Next</button></li>
                                                  </ul>
                                                </nav>";
                                                            }else if ($title['num_test'] == '5') {
                                                                echo "<nav aria-label='Page navigation example'>
                                                  <ul class='pagination'>
                                                    <li class='page-item'><button class='page-link' type='button' id='backbtn2'>Previous</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back31'>1</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back32'>2</button></li>
                                                    <li class='page-item active'><button class='page-link' type='button' id='back33'>3</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back34'>4</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back35'>5</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='nextbtn4'>Next</button></li>
                                                  </ul>
                                                </nav>";
                                                            }
                                                        }
                                                        echo "</div>
                              </div>
                              </div>";

                                                    }
                                                }
                                                else if ($testIII == "Essay") {
                                                    echo "<div class='row' id='test3' style='display: none;'>
                                            <div class='container'>
                                                    <div class='card card-secondary' >
                                                      <div class='card-header' style='display: block;'> 
                                                      <div class='row'>
                                                        <div class='col'>
                                                        <h4> Test III: Essay </h4>
                                                        <span>Directions: Answer the question to the best of your knowledge. Write your answer in the space provided</span>
                                                        </div>
                                                        <div class='col' style='text-align: right;'>
                                                          <span class='align-text-top'>Page 3 of " . $title['num_test'] . "</span>
                                                        </div>
                                                        </div>
                                                        </div>
                                                        </div>";
                                                    if ($QuestionOptionIII->rowCount() > 0) {
                                                        while ($rowQuestionOptionIII = $QuestionOptionIII->fetch()) {
                                                            echo "<div class='card' style='padding: 25px 15px 25px 15px;'>
                                     <div class='form-wrapper' >
                                          <div class='row' style='display: flex;'>
                                          <section><span class='question-no'>" . $index++ . " ."."</span></section>
                                          <div class='col'>
                                          <section><span class='question'>".$rowQuestionOptionIII['question']. '<br><span style="color: red;">' . '( '. $rowQuestionOptionIII['points'] . ' Point/s )' . '</span>'. "</span></section>
                                          <textarea class='form-control' style='margin-top: 20px;' rows='3' name='" . $rowQuestionOptionIII['question_no'] . "' ></textarea>
                                          </div>
                                          </div>
                                        </div>
                                        </div>";
                                                        }
                                                    }
                                                    echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>";
                                                    $testIV = $testRes['Test_IV'];
                                                    if ($testIV == null || $testIV == 0 || $testIV == "") {
                                                        echo "<nav aria-label='Page navigation example'>
                                                  <ul class='pagination'>
                                                    <li class='page-item'><button class='page-link' type='button' id='backbtn2'>Previous</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back31'>1</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back32'>2</button></li>
                                                    <li class='page-item active'><button class='page-link' type='button' id='back33'>3</button></li>&nbsp&nbsp
                                                    <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                                  </ul>
                                                </nav>
                                        ";
                                                    }else{
                                                        if ($title['num_test'] == '4') {
                                                            echo "<nav aria-label='Page navigation example'>
                                                  <ul class='pagination'>
                                                    <li class='page-item'><button class='page-link' type='button' id='backbtn2'>Previous</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back31'>1</button></li>
                                                    <li class='page-item '><button class='page-link' type='button' id='back32'>2</button></li>
                                                    <li class='page-item active'><button class='page-link' type='button' id='back33'>3</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back34'>4</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='nextbtn4'>Next</button></li>
                                                  </ul>
                                                </nav>";
                                                        }else if ($title['num_test'] == '5') {
                                                            echo "<nav aria-label='Page navigation example'>
                                                  <ul class='pagination'>
                                                    <li class='page-item'><button class='page-link' type='button' id='backbtn2'>Previous</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back31'>1</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back32'>2</button></li>
                                                    <li class='page-item active'><button class='page-link' type='button' id='back33'>3</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back34'>4</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back35'>5</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='nextbtn4'>Next</button></li>
                                                  </ul>
                                                </nav>";
                                                        }
                                                    }
                                                    echo "</div>
                              </div>
                              </div>";

                                                }
                                                else if ($testIII == "Multiple Image") {
                                                    echo "<div class='row' id='test3' style='display: none;'>
                            <div class='container'>
                                    <div class='card card-secondary' >
                                      <div class='card-header' style='display: block;'>
                                      <div class='row'>
                                        <div class='col'>
                                          <h4> Test III: Multiple Choice (Images) </h4>
                                          <span>Directions: Choose the BEST IMAGE answer for the following questions. </span>
                                        </div>
                                        <div class='col' style='text-align: right;'>
                                          <span class='align-text-top'>Page 3 of " . $title['num_test'] . "</span>
                                        </div>
                                        </div></div></div>";
                                                    if ($QuestionOptionIII->rowCount() > 0) {
                                                        while ($rowQuestionOptionIII = $QuestionOptionIII->fetch()) {
                                                            echo "<div class='card' style='padding: 25px 15px 25px 15px;'>
                                      <div class='form-wrapper' >
                                              <div class='row' style='display: flex;'>
                                              <section><span class='question-no'>" . $index++ . " ."."</span></section>
                                              <div class='col'>
                                              <section><span class='question'>".$rowQuestionOptionIII['question']. '<br><span style="color: red;">' . '( '. $rowQuestionOptionIII['points'] . ' Point/s )' . '</span>'. "</span></section>"
                                                                ."<div class='radio-group'>";
                                                            echo '<div class="row" style="justify-content: space-evenly;">
                                                            <div clas="col-4" style="text-align: center;">
                                                            <label>
                                                                <input class="btn radio-btn" type="radio" name="' . $rowQuestionOptionIII['question_no'] . '" value="' . $rowQuestionOptionIII['option_1'] . '">
                                                                <img class="img-option"src="assets/uploads/'.$department.'/'.$division.'/'.$id.'/'.$rowQuestionOptionIII['question_type'].'/'.$rowQuestionOptionIII['question_no'].'/'.'/option1/'.$rowQuestionOptionIII['img_1'].'"/>
                                                            </label>
                                                            </div>
                                                            <div clas="col-4" style="text-align: center;">
                                                            <label>
                                                                <input class="btn radio-btn" type="radio" name="' . $rowQuestionOptionIII['question_no'] . '" value="' . $rowQuestionOptionIII['option_2'] . '">
                                                                <img class="img-option"src="assets/uploads/'.$department.'/'.$division.'/'.$id.'/'.$rowQuestionOptionIII['question_type'].'/'.$rowQuestionOptionIII['question_no'].'/'.'/option2/'.$rowQuestionOptionIII['img_2'].'"/>
                                                            </label>
                                                            </div>
                                                            <div clas="col-4" style="text-align: center;">
                                                            <label>
                                                                <input class="btn radio-btn" type="radio" name="' . $rowQuestionOptionIII['question_no'] . '" value="' . $rowQuestionOptionIII['option_3'] . '">
                                                                <img class="img-option"src="assets/uploads/'.$department.'/'.$division.'/'.$id.'/'.$rowQuestionOptionIII['question_type'].'/'.$rowQuestionOptionIII['question_no'].'/'.'/option3/'.$rowQuestionOptionIII['img_3'].'"/>
                                                            </label>
                                                            </div>
                                                            <div clas="col-4" style="text-align: center;">
                                                            <label>
                                                                <input class="btn radio-btn" type="radio" name="' . $rowQuestionOptionIII['question_no'] . '" value="' . $rowQuestionOptionIII['option_4'] . '">
                                                                <img class="img-option"src="assets/uploads/'.$department.'/'.$division.'/'.$id.'/'.$rowQuestionOptionIII['question_type'].'/'.$rowQuestionOptionIII['question_no'].'/'.'/option4/'.$rowQuestionOptionIII['img_4'].'"/>
                                                            </label>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            </div>';
                                                        }
                                                        echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>";
                                                        $testIV = $testRes['Test_IV'];
                                                        if ($testIV == null || $testIV == 0 || $testIV == "") {
                                                            echo "<nav aria-label='Page navigation example'>
                                                  <ul class='pagination'>
                                                    <li class='page-item'><button class='page-link' type='button' id='backbtn2'>Previous</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back31'>1</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back32'>2</button></li>
                                                    <li class='page-item active'><button class='page-link' type='button' id='back33'>3</button></li>&nbsp&nbsp
                                                    <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                                  </ul>
                                                </nav>
                                        ";
                                                        }else{
                                                            if ($title['num_test'] == '4') {
                                                                echo "<nav aria-label='Page navigation example'>
                                                  <ul class='pagination'>
                                                    <li class='page-item'><button class='page-link' type='button' id='backbtn2'>Previous</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back31'>1</button></li>
                                                    <li class='page-item '><button class='page-link' type='button' id='back32'>2</button></li>
                                                    <li class='page-item active'><button class='page-link' type='button' id='back33'>3</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back34'>4</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='nextbtn4'>Next</button></li>
                                                  </ul>
                                                </nav>";
                                                            }else if ($title['num_test'] == '5') {
                                                                echo "<nav aria-label='Page navigation example'>
                                                  <ul class='pagination'>
                                                    <li class='page-item'><button class='page-link' type='button' id='backbtn2'>Previous</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back31'>1</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back32'>2</button></li>
                                                    <li class='page-item active'><button class='page-link' type='button' id='back33'>3</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back34'>4</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back35'>5</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='nextbtn4'>Next</button></li>
                                                  </ul>
                                                </nav>";
                                                            }
                                                        }
                                                        echo "</div>
                              </div>
                              </div>";

                                                    }
                                                }
                                                else if ($testIII == "Multiple Choice") {
                                                    echo "<div class='row' id='test3' style='display: none;'>
                        <div class='container'>
                                <div class='card card-secondary' >
                                  <div class='card-header' style='display: block;'>
                                  <div class='row'>
                                    <div class='col'>
                                    <h4> Test III: Multiple Choice </h4>
                                    <span>Directions: Choose the BEST answer for the following questions.</span>
                                    </div>
                                    <div class='col' style='text-align: right;'>
                                      <span class='align-text-top'>Page 3 of " . $title['num_test'] . "</span>
                                    </div>
                                    </div>
                                    </div>
                                    </div>";
                                                    if ($QuestionOptionIII->rowCount() > 0) {
                                                        while ($rowQuestionOptionIII = $QuestionOptionIII->fetch()) {
                                                            echo "<div class='card' style='padding: 25px 15px 25px 15px;'>
                                  <div class='form-wrapper' >
                                          <div class='row' style='display: flex;'>
                                          <section><span class='question-no'>" . $index++ . " ."."</span></section>
                                          <div class='col'>
                                          <section><span class='question'>".$rowQuestionOptionIII['question']. '<br><span style="color: red;">' . '( '. $rowQuestionOptionIII['points'] . ' Point/s )' . '</span>'. "</span></section>"
                                                                ."<div class='radio-group'>";

                                                            if ($rowQuestionOptionIII['img_1'] != null
                                                                && $rowQuestionOptionIII['img_2'] != null
                                                                && $rowQuestionOptionIII['img_3'] != null
                                                                && $rowQuestionOptionIII['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option1/' . $rowQuestionOptionIII['img_1'] . '" />
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option2/' . $rowQuestionOptionIII['img_2'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option3/' . $rowQuestionOptionIII['img_3'] . '"/>
                                                        <h4>III</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option4/' . $rowQuestionOptionIII['img_4'] . '"/>
                                                        <h4>IV</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIII['img_1'] != null
                                                                && $rowQuestionOptionIII['img_2'] != null
                                                                && $rowQuestionOptionIII['img_3'] != null
                                                                && $rowQuestionOptionIII['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option1/' . $rowQuestionOptionIII['img_1'] . '" />
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option2/' . $rowQuestionOptionIII['img_2'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option3/' . $rowQuestionOptionIII['img_3'] . '"/>
                                                        <h4>III</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIII['img_2'] != null
                                                                && $rowQuestionOptionIII['img_3'] != null
                                                                && $rowQuestionOptionIII['img_4'] != null
                                                                && $rowQuestionOptionIII['img_1'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option2/' . $rowQuestionOptionIII['img_2'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option3/' . $rowQuestionOptionIII['img_3'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option4/' . $rowQuestionOptionIII['img_4'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIII['img_1'] != null
                                                                && $rowQuestionOptionIII['img_3'] != null
                                                                && $rowQuestionOptionIII['img_4'] != null
                                                                && $rowQuestionOptionIII['img_2'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option1/' . $rowQuestionOptionIII['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option3/' . $rowQuestionOptionIII['img_3'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option4/' . $rowQuestionOptionIII['img_4'] . '"/>
                                                        <h4>III</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIII['img_1'] != null
                                                                && $rowQuestionOptionIII['img_2'] != null
                                                                && $rowQuestionOptionIII['img_4'] != null
                                                                && $rowQuestionOptionIII['img_3'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option1/' . $rowQuestionOptionIII['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option2/' . $rowQuestionOptionIII['img_1'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option4/' . $rowQuestionOptionIII['img_4'] . '"/>
                                                        <h4>III</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            //1-2 OR 2-1
                                                            else if ($rowQuestionOptionIII['img_1'] != null
                                                                && $rowQuestionOptionIII['img_2'] != null
                                                                && $rowQuestionOptionIII['img_3'] == null
                                                                && $rowQuestionOptionIII['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option1/' . $rowQuestionOptionIII['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option2/' . $rowQuestionOptionIII['img_2'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }

                                                            //1-3 OR 3-1
                                                            else if ($rowQuestionOptionIII['img_1'] != null
                                                                && $rowQuestionOptionIII['img_2'] == null
                                                                && $rowQuestionOptionIII['img_3'] != null
                                                                && $rowQuestionOptionIII['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option1/' . $rowQuestionOptionIII['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option3/' . $rowQuestionOptionIII['img_3'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            //1-4 OR 4-1
                                                            else if ($rowQuestionOptionIII['img_1'] != null
                                                                && $rowQuestionOptionIII['img_2'] == null
                                                                && $rowQuestionOptionIII['img_3'] == null
                                                                && $rowQuestionOptionIII['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option1/' . $rowQuestionOptionIII['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option4/' . $rowQuestionOptionIII['img_4'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            //2-3 OR 3-2
                                                            else if ($rowQuestionOptionIII['img_1'] == null
                                                                && $rowQuestionOptionIII['img_2'] != null
                                                                && $rowQuestionOptionIII['img_3'] != null
                                                                && $rowQuestionOptionIII['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option2/' . $rowQuestionOptionIII['img_2'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option3/' . $rowQuestionOptionIII['img_3'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            //2-4 OR 4-2
                                                            else if ($rowQuestionOptionIII['img_1'] == null
                                                                && $rowQuestionOptionIII['img_2'] != null
                                                                && $rowQuestionOptionIII['img_3'] == null
                                                                && $rowQuestionOptionIII['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option2/' . $rowQuestionOptionIII['img_2'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option4/' . $rowQuestionOptionIII['img_4'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            //3-4 OR 4-3
                                                            else if ($rowQuestionOptionIII['img_1'] == null
                                                                && $rowQuestionOptionIII['img_2'] == null
                                                                && $rowQuestionOptionIII['img_3'] != null
                                                                && $rowQuestionOptionIII['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option3/' . $rowQuestionOptionIII['img_3'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option4/' . $rowQuestionOptionIII['img_4'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }

                                                            else if ($rowQuestionOptionIII['img_1'] != null
                                                                && $rowQuestionOptionIII['img_2'] == null
                                                                && $rowQuestionOptionIII['img_3'] == null
                                                                && $rowQuestionOptionIII['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option1/' . $rowQuestionOptionIII['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIII['img_2'] != null
                                                                && $rowQuestionOptionIII['img_3'] == null
                                                                && $rowQuestionOptionIII['img_4'] == null
                                                                && $rowQuestionOptionIII['img_1'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option2/' . $rowQuestionOptionIII['img_2'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIII['img_3'] != null
                                                                && $rowQuestionOptionIII['img_4'] == null
                                                                && $rowQuestionOptionIII['img_1'] == null
                                                                && $rowQuestionOptionIII['img_2'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option3/' . $rowQuestionOptionIII['img_3'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIII['img_4'] != null
                                                                && $rowQuestionOptionIII['img_1'] == null
                                                                && $rowQuestionOptionIII['img_2'] == null
                                                                && $rowQuestionOptionIII['img_3'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIII['question_type'] . '/' . $rowQuestionOptionIII['question_no'] . '/option4/' . $rowQuestionOptionIII['img_4'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        </div>';
                                                            }

                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOptionIII['question_no'] . "'" . "value=" . "'" . $rowQuestionOptionIII['option_1'] . "'>"
                                                                . '<span id="spantxt">A. ' .  $rowQuestionOptionIII['option_1'] . "</span>"
                                                                . "</label>";
                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOptionIII['question_no'] . "'" . "value=" . "'" . $rowQuestionOptionIII['option_2'] . "'>"
                                                                . '<span id="spantxt">B. ' . $rowQuestionOptionIII['option_2']  . "</span>"
                                                                . "</label>";
                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOptionIII['question_no'] . "'" . "value=" . "'" . $rowQuestionOptionIII['option_3'] . "'>"
                                                                . '<span id="spantxt">C. ' . $rowQuestionOptionIII['option_3'] . "</span>"
                                                                . "</label>"
                                                            ;
                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOptionIII['question_no'] . "'" . "value=" . "'" . $rowQuestionOptionIII['option_4'] . "'>"
                                                                . '<span id="spantxt">D. ' . $rowQuestionOptionIII['option_4'] . "</span>"
                                                                . "</label>"
                                                                . "</div>"
                                                                . "</div>"
                                                                . "</div>"
                                                                . "</div></div>";
                                                        }
                                                        echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>";
                                                        $testIV = $testRes['Test_IV'];
                                                        if ($testIV == null || $testIV == 0 || $testIV == "") {
                                                            echo "<nav aria-label='Page navigation example'>
                                            <ul class='pagination'>
                                              <li class='page-item'><button class='page-link' type='button' id='backbtn2'>Previous</button></li>
                                              <li class='page-item'><button class='page-link' type='button' id='back31'>1</button></li>
                                              <li class='page-item'><button class='page-link' type='button' id='back32'>2</button></li>
                                              <li class='page-item active'><button class='page-link' type='button' id='back33'>3</button></li>&nbsp&nbsp
                                              <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                            </ul>
                                          </nav>
                                  ";
                                                        }else{
                                                            if ($title['num_test'] == '4') {
                                                                echo "<nav aria-label='Page navigation example'>
                                            <ul class='pagination'>
                                              <li class='page-item'><button class='page-link' type='button' id='backbtn2'>Previous</button></li>
                                              <li class='page-item'><button class='page-link' type='button' id='back31'>1</button></li>
                                              <li class='page-item '><button class='page-link' type='button' id='back32'>2</button></li>
                                              <li class='page-item active'><button class='page-link' type='button' id='back33'>3</button></li>
                                              <li class='page-item'><button class='page-link' type='button' id='back34'>4</button></li>
                                              <li class='page-item'><button class='page-link' type='button' id='nextbtn4'>Next</button></li>
                                            </ul>
                                          </nav>";
                                                            }else if ($title['num_test'] == '5') {
                                                                echo "<nav aria-label='Page navigation example'>
                                            <ul class='pagination'>
                                              <li class='page-item'><button class='page-link' type='button' id='backbtn2'>Previous</button></li>
                                              <li class='page-item'><button class='page-link' type='button' id='back31'>1</button></li>
                                              <li class='page-item'><button class='page-link' type='button' id='back32'>2</button></li>
                                              <li class='page-item active'><button class='page-link' type='button' id='back33'>3</button></li>
                                              <li class='page-item'><button class='page-link' type='button' id='back34'>4</button></li>
                                              <li class='page-item'><button class='page-link' type='button' id='back35'>5</button></li>
                                              <li class='page-item'><button class='page-link' type='button' id='nextbtn4'>Next</button></li>
                                            </ul>
                                          </nav>";
                                                            }
                                                        }
                                                        echo "</div>
                        </div>
                        </div>";

                                                    }
                                                }
                                                else if ($testIII == "Short Answer") {
                                                    echo "<div class='row' id='test3' style='display: none;'>
                        <div class='container'>
                                <div class='card card-secondary' >
                                  <div class='card-header' style='display: block;'> 
                                  <div class='row'>
                                    <div class='col'>
                                      <h4> Test III: Short Answer </h4>
                                      <span>Directions: Using your own words, answer each question in the space provided</span>
                                    </div>
                                    <div class='col' style='text-align: right;'>
                                      <span class='align-text-top'>Page 3 of " . $title['num_test'] . "</span>
                                    </div>
                                    </div>
                                    </div>
                                    </div>";
                                                    if ($QuestionOptionIII->rowCount() > 0) {
                                                        while ($rowQuestionOptionIII = $QuestionOptionIII->fetch()) {
                                                            echo "<div class='card' style='padding: 25px 15px 25px 15px;'>
                                     <div class='form-wrapper' >
                                          <div class='row' style='display: flex;'>
                                          <section><span class='question-no'>" . $index++ . " ."."</span></section>
                                          <div class='col'>
                                          <section><span class='question'>".$rowQuestionOptionIII['question']. '<br><span style="color: red;">' . '( '. $rowQuestionOptionIII['points'] . ' Point/s )' . '</span>'. "</span></section>
                                          <input class='form-control form-control-sm' id='shorttxt' oninput='this.value = this.value.toUpperCase()' type='text' name='" . $rowQuestionOptionIII['question_no'] . "' style='border-bottom: 1px solid #000;'>
                                          </div>
                                          </div>
                                        </div>
                                        </div>";
                                                        }
                                                    }
                                                    echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>";
                                                    $testIV = $testRes['Test_IV'];
                                                    if ($testIV == null || $testIV == 0 || $testIV == "") {
                                                        echo "<nav aria-label='Page navigation example'>
                                                  <ul class='pagination'>
                                                    <li class='page-item'><button class='page-link' type='button' id='backbtn2'>Previous</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back31'>1</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back32'>2</button></li>
                                                    <li class='page-item active'><button class='page-link' type='button' id='back33'>3</button></li>&nbsp&nbsp
                                                    <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                                  </ul>
                                                </nav>
                                        ";
                                                    }else{
                                                        if ($title['num_test'] == '4') {
                                                            echo "<nav aria-label='Page navigation example'>
                                                  <ul class='pagination'>
                                                    <li class='page-item'><button class='page-link' type='button' id='backbtn2'>Previous</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back31'>1</button></li>
                                                    <li class='page-item '><button class='page-link' type='button' id='back32'>2</button></li>
                                                    <li class='page-item active'><button class='page-link' type='button' id='back33'>3</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back34'>4</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='nextbtn4'>Next</button></li>
                                                  </ul>
                                                </nav>";
                                                        }else if ($title['num_test'] == '5') {
                                                            echo "<nav aria-label='Page navigation example'>
                                                  <ul class='pagination'>
                                                    <li class='page-item'><button class='page-link' type='button' id='backbtn2'>Previous</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back31'>1</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back32'>2</button></li>
                                                    <li class='page-item active'><button class='page-link' type='button' id='back33'>3</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back34'>4</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='back35'>5</button></li>
                                                    <li class='page-item'><button class='page-link' type='button' id='nextbtn4'>Next</button></li>
                                                  </ul>
                                                </nav>";
                                                        }
                                                    }
                                                    echo "</div>
                              </div>
                              </div>";
                                                }
                                                // Test III End


                                                // Test IV Start
                                                $testIV = $testRes['Test_IV'];
                                                // Test IV Query
                                                $QuestionOptionIV = $conn->query("SELECT a.`id`AS question_no, a.`question`, a.`question_type`, a.`points`, b.`option_1`, b.`option_2`,b.`option_3`,b.`option_4`,b.`img_1`,b.`img_2`,b.`img_3`,b.`img_4`, b.`ans`
                                                                                FROM `question` a 
                                                                                LEFT JOIN `options` b ON a.`id` = b.`id`
                                                                                WHERE a.`q_id` = '$id' AND b.`o_id` = '$id' AND a.`question_type` = '$testIV'");
                                                if ($testIV == "Essay") {
                                                    echo "<div class='row' id='test4' style='display: none;'>
                        <div class='container'>
                                <div class='card card-secondary' >
                                  <div class='card-header' style='display: block;'> 
                                  <div class='row'>
                                    <div class='col'>
                                    <h4> Test IV: Essay </h4>
                                    <span>Directions: Answer the question to the best of your knowledge. Write your answer in the space provided</span>
                                    </div>
                                    <div class='col' style='text-align: right;'>
                                      <span class='align-text-top'>Page 4 of " . $title['num_test'] . "</span>
                                    </div>
                                    </div>
                                    </div>
                                    </div>";
                                                    if ($QuestionOptionIV->rowCount() > 0) {
                                                        while ($rowQuestionOptionIV = $QuestionOptionIV->fetch()) {
                                                            echo "<div class='card' style='padding: 25px 15px 25px 15px;'>
                                     <div class='form-wrapper' >
                                          <div class='row' style='display: flex;'>
                                          <section><span class='question-no'>" . $index++ . " ."."</span></section>
                                          <div class='col'>
                                          <section><span class='question'>".$rowQuestionOptionIV['question']. '<br><span style="color: red;">' . '( '. $rowQuestionOptionIV['points'] . ' Point/s )' . '</span>'. "</span></section>
                                          <textarea class='form-control' style='margin-top: 20px;' rows='3' name='" . $rowQuestionOptionIV['question_no'] . "' ></textarea>
                                          </div>
                                          </div>
                                        </div></div>";
                                                        }
                                                    }
                                                    echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>";
                                                    $testV = $testRes['Test_V'];
                                                    if ($testV == null || $testV == 0 || $testV == "") {
                                                        echo "<nav aria-label='Page navigation example'>
                                                    <ul class='pagination'>
                                                      <li class='page-item'><button class='page-link' type='button'id='backbtn3'>Previous</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back41'>1</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back42'>2</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back43'>3</button></li>
                                                      <li class='page-item active'><button class='page-link' type='button' id='back44'>4</button></li>&nbsp&nbsp
                                                      <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                                    </ul>
                                                  </nav>
                                          ";
                                                    }else{
                                                        if ($title['num_test'] == '5') {
                                                            echo "<nav aria-label='Page navigation example'>
                                                    <ul class='pagination'>
                                                      <li class='page-item '><button class='page-link' type='button' id='backbtn3'>Previous</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back41'>1</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back42'>2</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back43'>3</button></li>
                                                      <li class='page-item  active'><button class='page-link' type='button' id='bac44'>4</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back45'>5</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='nextbtn5'>Next</button></li>
                                                    </ul>
                                                  </nav>";
                                                        }
                                                    }
                                                    echo "</div>
                              </div>
                              </div>";

                                                }
                                                else if ($testIV == "True/False") {
                                                    echo "<div class='row' id='test4' style='display: none;'>
                        <div class='container'>
                                <div class='card card-secondary' >
                                  <div class='card-header' style='display: block;'>
                                  <div class='row'>
                                    <div class='col'>
                                    <h4> Test IV: True or False </h4>
                                    <span>Directions: Read the statements carefully and tell whether the statement is TRUE or FALSE. Choose TRUE if the statement is correct and FALSE if otherwise.</span>
                                    </div>
                                    <div class='col' style='text-align: right;'>
                                      <span class='align-text-top'>Page 4 of " . $title['num_test'] . "</span>
                                    </div>
                                    </div>
                                    </div>
                                    </div>";
                                                    if ($QuestionOptionIV->rowCount() > 0) {
                                                        while ($rowQuestionOptionIV = $QuestionOptionIV->fetch()) {
                                                            echo "<div class='card' style='padding: 25px 15px 25px 15px;'>
                                          <div class='form-wrapper' >
                                          <div class='row' style='display: flex;'>
                                          <section><span class='question-no'>" . $index++ . " ."."</span></section>
                                          <div class='col'>
                                          <section><span class='question'>".$rowQuestionOptionIV['question']. '<br><span style="color: red;">' . '( '. $rowQuestionOptionIV['points'] . ' Point/s )' . '</span>'. "</span></section>"
                                                                . "<div class='radio-group'>";

                                                            if ($rowQuestionOptionIV['img_1'] != null
                                                                && $rowQuestionOptionIV['img_2'] != null
                                                                && $rowQuestionOptionIV['img_3'] != null
                                                                && $rowQuestionOptionIV['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option1/' . $rowQuestionOptionIV['img_1'] . '" />
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option2/' . $rowQuestionOptionIV['img_2'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option3/' . $rowQuestionOptionIV['img_3'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option4/' . $rowQuestionOptionIV['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIV['img_1'] != null
                                                                && $rowQuestionOptionIV['img_2'] != null
                                                                && $rowQuestionOptionIV['img_3'] != null
                                                                && $rowQuestionOptionIV['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option1/' . $rowQuestionOptionIV['img_1'] . '" />
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option2/' . $rowQuestionOptionIV['img_2'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option3/' . $rowQuestionOptionIV['img_3'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIV['img_2'] != null
                                                                && $rowQuestionOptionIV['img_3'] != null
                                                                && $rowQuestionOptionIV['img_4'] != null
                                                                && $rowQuestionOptionIV['img_1'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option2/' . $rowQuestionOptionIV['img_2'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option3/' . $rowQuestionOptionIV['img_3'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option4/' . $rowQuestionOptionIV['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIV['img_1'] != null
                                                                && $rowQuestionOptionIV['img_3'] != null
                                                                && $rowQuestionOptionIV['img_4'] != null
                                                                && $rowQuestionOptionIV['img_2'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option1/' . $rowQuestionOptionIV['img_1'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option3/' . $rowQuestionOptionIV['img_3'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option4/' . $rowQuestionOptionIV['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIV['img_1'] != null
                                                                && $rowQuestionOptionIV['img_2'] != null
                                                                && $rowQuestionOptionIV['img_4'] != null
                                                                && $rowQuestionOptionIV['img_3'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option1/' . $rowQuestionOptionIV['img_1'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option2/' . $rowQuestionOptionIV['img_1'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option4/' . $rowQuestionOptionIV['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            //1-2 OR 2-1
                                                            else if ($rowQuestionOptionIV['img_1'] != null
                                                                && $rowQuestionOptionIV['img_2'] != null
                                                                && $rowQuestionOptionIV['img_3'] == null
                                                                && $rowQuestionOptionIV['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option1/' . $rowQuestionOptionIV['img_1'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option2/' . $rowQuestionOptionIV['img_2'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }

                                                            //1-3 OR 3-1
                                                            else if ($rowQuestionOptionIV['img_1'] != null
                                                                && $rowQuestionOptionIV['img_2'] == null
                                                                && $rowQuestionOptionIV['img_3'] != null
                                                                && $rowQuestionOptionIV['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option1/' . $rowQuestionOptionIV['img_1'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option3/' . $rowQuestionOptionIV['img_3'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            //1-4 OR 4-1
                                                            else if ($rowQuestionOptionIV['img_1'] != null
                                                                && $rowQuestionOptionIV['img_2'] == null
                                                                && $rowQuestionOptionIV['img_3'] == null
                                                                && $rowQuestionOptionIV['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option1/' . $rowQuestionOptionIV['img_1'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option4/' . $rowQuestionOptionIV['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            //2-3 OR 3-2
                                                            else if ($rowQuestionOptionIV['img_1'] == null
                                                                && $rowQuestionOptionIV['img_2'] != null
                                                                && $rowQuestionOptionIV['img_3'] != null
                                                                && $rowQuestionOptionIV['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option2/' . $rowQuestionOptionIV['img_2'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option3/' . $rowQuestionOptionIV['img_3'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            //2-4 OR 4-2
                                                            else if ($rowQuestionOptionIV['img_1'] == null
                                                                && $rowQuestionOptionIV['img_2'] != null
                                                                && $rowQuestionOptionIV['img_3'] == null
                                                                && $rowQuestionOptionIV['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option2/' . $rowQuestionOptionIV['img_2'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option4/' . $rowQuestionOptionIV['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            //3-4 OR 4-3
                                                            else if ($rowQuestionOptionIV['img_1'] == null
                                                                && $rowQuestionOptionIV['img_2'] == null
                                                                && $rowQuestionOptionIV['img_3'] != null
                                                                && $rowQuestionOptionIV['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option3/' . $rowQuestionOptionIV['img_3'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option4/' . $rowQuestionOptionIV['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }

                                                            else if ($rowQuestionOptionIV['img_1'] != null
                                                                && $rowQuestionOptionIV['img_2'] == null
                                                                && $rowQuestionOptionIV['img_3'] == null
                                                                && $rowQuestionOptionIV['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option1/' . $rowQuestionOptionIV['img_1'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIV['img_2'] != null
                                                                && $rowQuestionOptionIV['img_3'] == null
                                                                && $rowQuestionOptionIV['img_4'] == null
                                                                && $rowQuestionOptionIV['img_1'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option2/' . $rowQuestionOptionIV['img_2'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIV['img_3'] != null
                                                                && $rowQuestionOptionIV['img_4'] == null
                                                                && $rowQuestionOptionIV['img_1'] == null
                                                                && $rowQuestionOptionIV['img_2'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option3/' . $rowQuestionOptionIV['img_3'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIV['img_4'] != null
                                                                && $rowQuestionOptionIV['img_1'] == null
                                                                && $rowQuestionOptionIV['img_2'] == null
                                                                && $rowQuestionOptionIV['img_3'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option4/' . $rowQuestionOptionIV['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }

                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOptionIV['question_no'] . "'" . " value='True'>"
                                                                . "<span id='spantxt'>TRUE</span>"
                                                                . "</label>";
                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOptionIV['question_no'] . "'" . " value='False'>"
                                                                . "<span id='spantxt'>FALSE</span>"
                                                                . "</label>"
                                                                . "</div>"
                                                                . "</div>"
                                                                . "</div>"
                                                                . "</div></div>";
                                                        }
                                                        echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>";
                                                        $testV = $testRes['Test_V'];
                                                        if ($testV == null || $testV == 0 || $testV == "") {
                                                            echo "<nav aria-label='Page navigation example'>
                                              <ul class='pagination'>
                                                <li class='page-item'><button class='page-link' type='button'id='backbtn3'>Previous</button></li>
                                                <li class='page-item'><button class='page-link' type='button' id='back41'>1</button></li>
                                                <li class='page-item'><button class='page-link' type='button' id='back42'>2</button></li>
                                                <li class='page-item'><button class='page-link' type='button' id='back43'>3</button></li>
                                                <li class='page-item active'><button class='page-link' type='button' id='back44'>4</button></li>&nbsp&nbsp
                                                <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                              </ul>
                                            </nav>
                                    ";
                                                        }else{
                                                            if ($title['num_test'] == '5') {
                                                                echo "<nav aria-label='Page navigation example'>
                                              <ul class='pagination'>
                                                <li class='page-item '><button class='page-link' type='button' id='backbtn3'>Previous</button></li>
                                                <li class='page-item'><button class='page-link' type='button' id='back41'>1</button></li>
                                                <li class='page-item'><button class='page-link' type='button' id='back42'>2</button></li>
                                                <li class='page-item'><button class='page-link' type='button' id='back43'>3</button></li>
                                                <li class='page-item  active'><button class='page-link' type='button' id='bac44'>4</button></li>
                                                <li class='page-item'><button class='page-link' type='button' id='back45'>5</button></li>
                                                <li class='page-item'><button class='page-link' type='button' id='nextbtn5'>Next</button></li>
                                              </ul>
                                            </nav>";
                                                            }
                                                        }
                                                        echo "</div>
                              </div>
                              </div>";

                                                    }
                                                }
                                                else if ($testIV == "Multiple Image") {
                                                    echo "<div class='row' id='test4' style='display: none;'>
                            <div class='container'>
                                    <div class='card card-secondary' >
                                      <div class='card-header' style='display: block;'>
                                      <div class='row'>
                                        <div class='col'>
                                          <h4> Test IV: Multiple Choice (Images) </h4>
                                          <span>Directions: Choose the BEST IMAGE answer for the following questions. </span>
                                        </div>
                                        <div class='col' style='text-align: right;'>
                                          <span class='align-text-top'>Page 4 of " . $title['num_test'] . "</span>
                                        </div>
                                        </div>
                                        </div>
                                        </div>";
                                                    if ($QuestionOptionIV->rowCount() > 0) {
                                                        while ($rowQuestionOptionIV = $QuestionOptionIV->fetch()) {
                                                            echo "<div class='card' style='padding: 25px 15px 25px 15px;'>
                                      <div class='form-wrapper' >
                                              <div class='row' style='display: flex;'>
                                              <section><span class='question-no'>" . $index++ . " ."."</span></section>
                                              <div class='col'>
                                              <section><span class='question'>".$rowQuestionOptionIV['question']. '<br><span style="color: red;">' . '( '. $rowQuestionOptionIV['points'] . ' Point/s )' . '</span>'. "</span></section>"
                                                                ."<div class='radio-group'>";
                                                            echo '<div class="row" style="justify-content: space-evenly;">
                                                            <div clas="col-4" style="text-align: center;">
                                                            <label>
                                                                <input class="btn radio-btn" type="radio" name="' . $rowQuestionOptionIV['question_no'] . '" value="' . $rowQuestionOptionIV['option_1'] . '">
                                                                <img class="img-option"src="assets/uploads/'.$department.'/'.$division.'/'.$id.'/'.$rowQuestionOptionIV['question_type'].'/'.$rowQuestionOptionIV['question_no'].'/'.'/option1/'.$rowQuestionOptionIV['img_1'].'"/>
                                                            </label>
                                                            </div>
                                                            <div clas="col-4" style="text-align: center;">
                                                            <label>
                                                                <input class="btn radio-btn" type="radio" name="' . $rowQuestionOptionIV['question_no'] . '" value="' . $rowQuestionOptionIV['option_2'] . '">
                                                                <img class="img-option"src="assets/uploads/'.$department.'/'.$division.'/'.$id.'/'.$rowQuestionOptionIV['question_type'].'/'.$rowQuestionOptionIV['question_no'].'/'.'/option2/'.$rowQuestionOptionIV['img_2'].'"/>
                                                            </label>
                                                            </div>
                                                            <div clas="col-4" style="text-align: center;">
                                                            <label>
                                                                <input class="btn radio-btn" type="radio" name="' . $rowQuestionOptionIV['question_no'] . '" value="' . $rowQuestionOptionIV['option_3'] . '">
                                                                <img class="img-option"src="assets/uploads/'.$department.'/'.$division.'/'.$id.'/'.$rowQuestionOptionIV['question_type'].'/'.$rowQuestionOptionIV['question_no'].'/'.'/option3/'.$rowQuestionOptionIV['img_3'].'"/>
                                                            </label>
                                                            </div>
                                                            <div clas="col-4" style="text-align: center;">
                                                            <label>
                                                                <input class="btn radio-btn" type="radio" name="' . $rowQuestionOptionIV['question_no'] . '" value="' . $rowQuestionOptionIV['option_4'] . '">
                                                                <img class="img-option"src="assets/uploads/'.$department.'/'.$division.'/'.$id.'/'.$rowQuestionOptionIV['question_type'].'/'.$rowQuestionOptionIV['question_no'].'/'.'/option4/'.$rowQuestionOptionIV['img_4'].'"/>
                                                            </label>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            </div>';
                                                        }
                                                        echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>";
                                                        $testV = $testRes['Test_V'];
                                                        if ($testV == null || $testV == 0 || $testV == "") {
                                                            echo "<nav aria-label='Page navigation example'>
                                                    <ul class='pagination'>
                                                      <li class='page-item'><button class='page-link' type='button'id='backbtn3'>Previous</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back41'>1</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back42'>2</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back43'>3</button></li>
                                                      <li class='page-item active'><button class='page-link' type='button' id='back44'>4</button></li>&nbsp&nbsp
                                                      <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                                    </ul>
                                                  </nav>
                                          ";
                                                        }else{
                                                            if ($title['num_test'] == '5') {
                                                                echo "<nav aria-label='Page navigation example'>
                                                    <ul class='pagination'>
                                                      <li class='page-item '><button class='page-link' type='button' id='backbtn3'>Previous</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back41'>1</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back42'>2</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back43'>3</button></li>
                                                      <li class='page-item  active'><button class='page-link' type='button' id='bac44'>4</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back45'>5</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='nextbtn5'>Next</button></li>
                                                    </ul>
                                                  </nav>";
                                                            }
                                                        }
                                                        echo "</div>
                              </div>
                              </div>";

                                                    }
                                                }
                                                else if ($testIV == "Multiple Choice") {
                                                    echo "<div class='row' id='test4' style='display: none;'>
                        <div class='container'>
                                <div class='card card-secondary' >
                                  <div class='card-header' style='display: block;'>
                                  <div class='row'>
                                    <div class='col'>
                                    <h4> Test IV: Multiple Choice </h4>
                                    <span>Directions: Choose the BEST answer for the following questions.</span>
                                    </div>
                                    <div class='col' style='text-align: right;'>
                                      <span class='align-text-top'>Page 4 of " . $title['num_test'] . "</span>
                                    </div>
                                    </div>
                                    </div>
                                    </div>";
                                                    if ($QuestionOptionIV->rowCount() > 0) {
                                                        while ($rowQuestionOptionIV = $QuestionOptionIV->fetch()) {
                                                            echo "<div class='card' style='padding: 25px 15px 25px 15px;'>
                                  <div class='form-wrapper' >
                                          <div class='row' style='display: flex;'>
                                          <section><span class='question-no'>" . $index++ . " ."."</span></section>
                                          <div class='col'>
                                          <section><span class='question'>".$rowQuestionOptionIV['question']. '<br><span style="color: red;">' . '( '. $rowQuestionOptionIV['points'] . ' Point/s )' . '</span>'. "</span></section>"
                                                                ."<div class='radio-group'>";
                                                            if ($rowQuestionOptionIV['img_1'] != null
                                                                && $rowQuestionOptionIV['img_2'] != null
                                                                && $rowQuestionOptionIV['img_3'] != null
                                                                && $rowQuestionOptionIV['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option1/' . $rowQuestionOptionIV['img_1'] . '" />
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option2/' . $rowQuestionOptionIV['img_2'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option3/' . $rowQuestionOptionIV['img_3'] . '"/>
                                                        <h4>III</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option4/' . $rowQuestionOptionIV['img_4'] . '"/>
                                                        <h4>IV</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIV['img_1'] != null
                                                                && $rowQuestionOptionIV['img_2'] != null
                                                                && $rowQuestionOptionIV['img_3'] != null
                                                                && $rowQuestionOptionIV['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option1/' . $rowQuestionOptionIV['img_1'] . '" />
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option2/' . $rowQuestionOptionIV['img_2'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option3/' . $rowQuestionOptionIV['img_3'] . '"/>
                                                        <h4>III</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIV['img_2'] != null
                                                                && $rowQuestionOptionIV['img_3'] != null
                                                                && $rowQuestionOptionIV['img_4'] != null
                                                                && $rowQuestionOptionIV['img_1'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option2/' . $rowQuestionOptionIV['img_2'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option3/' . $rowQuestionOptionIV['img_3'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option4/' . $rowQuestionOptionIV['img_4'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIV['img_1'] != null
                                                                && $rowQuestionOptionIV['img_3'] != null
                                                                && $rowQuestionOptionIV['img_4'] != null
                                                                && $rowQuestionOptionIV['img_2'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option1/' . $rowQuestionOptionIV['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option3/' . $rowQuestionOptionIV['img_3'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option4/' . $rowQuestionOptionIV['img_4'] . '"/>
                                                        <h4>III</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIV['img_1'] != null
                                                                && $rowQuestionOptionIV['img_2'] != null
                                                                && $rowQuestionOptionIV['img_4'] != null
                                                                && $rowQuestionOptionIV['img_3'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option1/' . $rowQuestionOptionIV['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option2/' . $rowQuestionOptionIV['img_1'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option4/' . $rowQuestionOptionIV['img_4'] . '"/>
                                                        <h4>III</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            //1-2 OR 2-1
                                                            else if ($rowQuestionOptionIV['img_1'] != null
                                                                && $rowQuestionOptionIV['img_2'] != null
                                                                && $rowQuestionOptionIV['img_3'] == null
                                                                && $rowQuestionOptionIV['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option1/' . $rowQuestionOptionIV['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option2/' . $rowQuestionOptionIV['img_2'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }

                                                            //1-3 OR 3-1
                                                            else if ($rowQuestionOptionIV['img_1'] != null
                                                                && $rowQuestionOptionIV['img_2'] == null
                                                                && $rowQuestionOptionIV['img_3'] != null
                                                                && $rowQuestionOptionIV['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option1/' . $rowQuestionOptionIV['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option3/' . $rowQuestionOptionIV['img_3'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            //1-4 OR 4-1
                                                            else if ($rowQuestionOptionIV['img_1'] != null
                                                                && $rowQuestionOptionIV['img_2'] == null
                                                                && $rowQuestionOptionIV['img_3'] == null
                                                                && $rowQuestionOptionIV['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option1/' . $rowQuestionOptionIV['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option4/' . $rowQuestionOptionIV['img_4'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            //2-3 OR 3-2
                                                            else if ($rowQuestionOptionIV['img_1'] == null
                                                                && $rowQuestionOptionIV['img_2'] != null
                                                                && $rowQuestionOptionIV['img_3'] != null
                                                                && $rowQuestionOptionIV['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option2/' . $rowQuestionOptionIV['img_2'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option3/' . $rowQuestionOptionIV['img_3'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            //2-4 OR 4-2
                                                            else if ($rowQuestionOptionIV['img_1'] == null
                                                                && $rowQuestionOptionIV['img_2'] != null
                                                                && $rowQuestionOptionIV['img_3'] == null
                                                                && $rowQuestionOptionIV['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option2/' . $rowQuestionOptionIV['img_2'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option4/' . $rowQuestionOptionIV['img_4'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            //3-4 OR 4-3
                                                            else if ($rowQuestionOptionIV['img_1'] == null
                                                                && $rowQuestionOptionIV['img_2'] == null
                                                                && $rowQuestionOptionIV['img_3'] != null
                                                                && $rowQuestionOptionIV['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option3/' . $rowQuestionOptionIV['img_3'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option4/' . $rowQuestionOptionIV['img_4'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }

                                                            else if ($rowQuestionOptionIV['img_1'] != null
                                                                && $rowQuestionOptionIV['img_2'] == null
                                                                && $rowQuestionOptionIV['img_3'] == null
                                                                && $rowQuestionOptionIV['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option1/' . $rowQuestionOptionIV['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIV['img_2'] != null
                                                                && $rowQuestionOptionIV['img_3'] == null
                                                                && $rowQuestionOptionIV['img_4'] == null
                                                                && $rowQuestionOptionIV['img_1'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option2/' . $rowQuestionOptionIV['img_2'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIV['img_3'] != null
                                                                && $rowQuestionOptionIV['img_4'] == null
                                                                && $rowQuestionOptionIV['img_1'] == null
                                                                && $rowQuestionOptionIV['img_2'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option3/' . $rowQuestionOptionIV['img_3'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionIV['img_4'] != null
                                                                && $rowQuestionOptionIV['img_1'] == null
                                                                && $rowQuestionOptionIV['img_2'] == null
                                                                && $rowQuestionOptionIV['img_3'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionIV['question_type'] . '/' . $rowQuestionOptionIV['question_no'] . '/option4/' . $rowQuestionOptionIV['img_4'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOptionIV['question_no'] . "'" . "value=" . "'" . $rowQuestionOptionIV['option_1'] . "'>"
                                                                . '<span id="spantxt">A. ' .  $rowQuestionOptionIV['option_1'] . "</span>"
                                                                . "</label>";
                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOptionIV['question_no'] . "'" . "value=" . "'" . $rowQuestionOptionIV['option_2'] . "'>"
                                                                . '<span id="spantxt">B. ' . $rowQuestionOptionIV['option_2']  . "</span>"
                                                                . "</label>";
                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOptionIV['question_no'] . "'" . "value=" . "'" . $rowQuestionOptionIV['option_3'] . "'>"
                                                                . '<span id="spantxt">C. ' . $rowQuestionOptionIV['option_3'] . "</span>"
                                                                . "</label>"
                                                            ;
                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOptionIV['question_no'] . "'" . "value=" . "'" . $rowQuestionOptionIV['option_4'] . "'>"
                                                                . '<span id="spantxt">D. ' . $rowQuestionOptionIV['option_4'] . "</span>"
                                                                . "</label>"
                                                                . "</div>"
                                                                . "</div>"
                                                                . "</div>"
                                                                . "</div></div>";
                                                        }
                                                        echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>";
                                                        $testV = $testRes['Test_V'];
                                                        if ($testV == null || $testV == 0 || $testV == "") {
                                                            echo "<nav aria-label='Page navigation example'>
                                                    <ul class='pagination'>
                                                      <li class='page-item'><button class='page-link' type='button'id='backbtn3'>Previous</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back41'>1</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back42'>2</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back43'>3</button></li>
                                                      <li class='page-item active'><button class='page-link' type='button' id='back44'>4</button></li>&nbsp&nbsp
                                                      <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                                    </ul>
                                                  </nav>
                                          ";
                                                        }else{
                                                            if ($title['num_test'] == '5') {
                                                                echo "<nav aria-label='Page navigation example'>
                                                    <ul class='pagination'>
                                                      <li class='page-item '><button class='page-link' type='button' id='backbtn3'>Previous</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back41'>1</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back42'>2</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back43'>3</button></li>
                                                      <li class='page-item  active'><button class='page-link' type='button' id='bac44'>4</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back45'>5</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='nextbtn5'>Next</button></li>
                                                    </ul>
                                                  </nav>";
                                                            }
                                                        }
                                                        echo "</div>
                              </div>
                              </div>";

                                                    }
                                                }
                                                else if ($testIV == "Short Answer") {
                                                    echo "<div class='row' id='test4' style='display: none;'>
                        <div class='container'>
                                <div class='card card-secondary' >
                                  <div class='card-header' style='display: block;'> 
                                  <div class='row'>
                                    <div class='col'>
                                      <h4> Test IV: Short Answer </h4>
                                      <span>Directions: Using your own words, answer each question in the space provided</span>
                                    </div>
                                    <div class='col' style='text-align: right;'>
                                      <span class='align-text-top'>Page 4 of " . $title['num_test'] . "</span>
                                    </div>
                                    </div>
                                    </div>
                                    </div>";
                                                    if ($QuestionOptionIV->rowCount() > 0) {
                                                        while ($rowQuestionOptionIV = $QuestionOptionIV->fetch()) {
                                                            echo "<div class='card' style='padding: 25px 15px 25px 15px;'>
                                     <div class='form-wrapper' >
                                          <div class='row' style='display: flex;'>
                                          <section><span class='question-no'>" . $index++ . " ."."</span></section>
                                          <div class='col'>
                                          <section><span class='question'>".$rowQuestionOptionIV['question']. '<br><span style="color: red;">' . '( '. $rowQuestionOptionIV['points'] . ' Point/s )' . '</span>'. "</span></section>
                                          <input class='form-control form-control-sm' id='shorttxt' oninput='this.value = this.value.toUpperCase()' type='text' name='" . $rowQuestionOptionIV['question_no'] . "' style='border-bottom: 1px solid #000;'>
                                          </div>
                                          </div>
                                        </div>
                                        </div>";
                                                        }
                                                    }
                                                    echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>";
                                                    $testV = $testRes['Test_V'];
                                                    if ($testV == null || $testV == 0 || $testV == "") {
                                                        echo "<nav aria-label='Page navigation example'>
                                                    <ul class='pagination'>
                                                      <li class='page-item '><button class='page-link' type='button'id='backbtn3'>Previous</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back41'>1</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back42'>2</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back43'>3</button></li>
                                                      <li class='page-item active'><button class='page-link' type='button' id='back44'>4</button></li>&nbsp&nbsp
                                                      <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                                    </ul>
                                                  </nav>
                                          ";
                                                    }else{
                                                        if ($title['num_test'] == '5') {
                                                            echo "<nav aria-label='Page navigation example'>
                                                    <ul class='pagination'>
                                                      <li class='page-item '><button class='page-link' type='button' id='backbtn3'>Previous</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back41'>1</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back42'>2</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back43'>3</button></li>
                                                      <li class='page-item  active'><button class='page-link' type='button' id='bac44'>4</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='back45'>5</button></li>
                                                      <li class='page-item'><button class='page-link' type='button' id='nextbtn5'>Next</button></li>
                                                    </ul>
                                                  </nav>";
                                                        }
                                                    }
                                                    echo "</div>
                              </div>
                              </div>";

                                                }
                                                // Test IV End


                                                // Test V Start
                                                $testV = $testRes['Test_V'];
                                                // Test V Query
                                                $QuestionOptionV = $conn->query("SELECT a.`id`AS question_no, a.`question`, a.`question_type`, a.`points`, b.`option_1`, b.`option_2`,b.`option_3`,b.`option_4`,b.`img_1`,b.`img_2`,b.`img_3`,b.`img_4`, b.`ans`
                                                                                FROM `question` a 
                                                                                LEFT JOIN `options` b ON a.`id` = b.`id`
                                                                                WHERE a.`q_id` = '$id' AND b.`o_id` = '$id' AND a.`question_type` = '$testV'");

                                                if ($testV == "Multiple Image") {
                                                    echo "<div class='row' id='test5' style='display: none;'>
                        <div class='container'>
                                <div class='card card-secondary' >
                                  <div class='card-header' style='display: block;'>
                                  <div class='row'>
                                    <div class='col'>
                                      <h4> Test V: Multiple Choice (Images) </h4>
                                      <span>Directions: Choose the BEST IMAGE answer for the following questions. </span>
                                    </div>
                                    <div class='col' style='text-align: right;'>
                                      <span class='align-text-top'>Page 5 of " . $title['num_test'] . "</span>
                                    </div>
                                    </div>
                                    </div>
                                    </div>";
                                                    if ($QuestionOptionV->rowCount() > 0) {
                                                        while ($rowQuestionOptionV = $QuestionOptionV->fetch()) {
                                                            echo "<div class='card' style='padding: 25px 15px 25px 15px;'>
                                  <div class='form-wrapper' >
                                          <div class='row' style='display: flex;'>
                                          <section><span class='question-no'>" . $index++ . " ."."</span></section>
                                          <div class='col'>
                                          <section><span class='question'>".$rowQuestionOptionV['question']. '<br><span style="color: red;">' . '( '. $rowQuestionOptionV['points'] . ' Point/s )' . '</span>'. "</span></section>"
                                                                ."<div class='radio-group'>";
                                                            echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <label>
                                                            <input class="btn radio-btn" type="radio" name="' . $rowQuestionOptionV['question_no'] . '" value="' . $rowQuestionOptionV['option_1'] . '">
                                                            <img class="img-option"src="assets/uploads/'.$department.'/'.$division.'/'.$id.'/'.$rowQuestionOptionV['question_type'].'/'.$rowQuestionOptionV['question_no'].'/'.'/option1/'.$rowQuestionOptionV['img_1'].'"/>
                                                        </label>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <label>
                                                            <input class="btn radio-btn" type="radio" name="' . $rowQuestionOptionV['question_no'] . '" value="' . $rowQuestionOptionV['option_2'] . '">
                                                            <img class="img-option"src="assets/uploads/'.$department.'/'.$division.'/'.$id.'/'.$rowQuestionOptionV['question_type'].'/'.$rowQuestionOptionV['question_no'].'/'.'/option2/'.$rowQuestionOptionV['img_2'].'"/>
                                                        </label>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <label>
                                                            <input class="btn radio-btn" type="radio" name="' . $rowQuestionOptionV['question_no'] . '" value="' . $rowQuestionOptionV['option_3'] . '">
                                                            <img class="img-option"src="assets/uploads/'.$department.'/'.$division.'/'.$id.'/'.$rowQuestionOptionV['question_type'].'/'.$rowQuestionOptionV['question_no'].'/'.'/option3/'.$rowQuestionOptionV['img_3'].'"/>
                                                        </label>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <label>
                                                            <input class="btn radio-btn" type="radio" name="' . $rowQuestionOptionV['question_no'] . '" value="' . $rowQuestionOptionV['option_4'] . '">
                                                            <img class="img-option"src="assets/uploads/'.$department.'/'.$division.'/'.$id.'/'.$rowQuestionOptionV['question_type'].'/'.$rowQuestionOptionV['question_no'].'/'.'/option4/'.$rowQuestionOptionV['img_4'].'"/>
                                                        </label>
                                                        </div>
                                                        </div>
                                                        </div>
                                                        </div>
                                                        </div>
                                                        </div>
                                                        </div>';
                                                        }
                                                        echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>
                                    <nav aria-label='Page navigation example'>
                                        <ul class='pagination'>
                                          <li class='page-item'><button class='page-link' type='button' id='backbtn4'>Previous</button></li>
                                          <li class='page-item'><button class='page-link' type='button' id='back51'>1</button></li>
                                          <li class='page-item'><button class='page-link' type='button' id='back52'>2</button></li>
                                          <li class='page-item'><button class='page-link' type='button' id='back53'>3</button></li>
                                          <li class='page-item'><button class='page-link' type='button' id='back54'>4</button></li>
                                          <li class='page-item active'><button class='page-link' type='button' id='back55'>5</button></li>
                                           <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                        </ul>
                                      </nav>
                                  </div>
                                </div>
                              </div>";

                                                    }
                                                }
                                                else if ($testV == "Multiple Choice") {
                                                    echo "<div class='row' id='test5' style='display: none;'>
                        <div class='container'>
                                <div class='card card-secondary' >
                                  <div class='card-header' style='display: block;'>
                                  <div class='row'>
                                    <div class='col'>
                                    <h4> Test V: Multiple Choice </h4>
                                    <span>Directions: Choose the BEST answer for the following questions.</span>
                                    </div>
                                    <div class='col' style='text-align: right;'>
                                      <span class='align-text-top'>Page 5 of " . $title['num_test'] . "</span>
                                    </div>
                                    </div>
                                    </div>
                                    </div>";
                                                    if ($QuestionOptionV->rowCount() > 0) {
                                                        while ($rowQuestionOptionV = $QuestionOptionV->fetch()) {
                                                            echo "<div class='card' style='padding: 25px 15px 25px 15px;'>
                                  <div class='form-wrapper' >
                                          <div class='row' style='display: flex;'>
                                          <section><span class='question-no'>" . $index++ . " ."."</span></section>
                                          <div class='col'>
                                          <section><span class='question'>".$rowQuestionOptionV['question']. '<br><span style="color: red;">' . '( '. $rowQuestionOptionV['points'] . ' Point/s )' . '</span>'. "</span></section>"
                                                                ."<div class='radio-group'>";
                                                            if ($rowQuestionOptionV['img_1'] != null
                                                                && $rowQuestionOptionV['img_2'] != null
                                                                && $rowQuestionOptionV['img_3'] != null
                                                                && $rowQuestionOptionV['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option1/' . $rowQuestionOptionV['img_1'] . '" />
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option2/' . $rowQuestionOptionV['img_2'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option3/' . $rowQuestionOptionV['img_3'] . '"/>
                                                        <h4>III</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option4/' . $rowQuestionOptionV['img_4'] . '"/>
                                                        <h4>IV</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionV['img_1'] != null
                                                                && $rowQuestionOptionV['img_2'] != null
                                                                && $rowQuestionOptionV['img_3'] != null
                                                                && $rowQuestionOptionV['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option1/' . $rowQuestionOptionV['img_1'] . '" />
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option2/' . $rowQuestionOptionV['img_2'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option3/' . $rowQuestionOptionV['img_3'] . '"/>
                                                        <h4>III</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionV['img_2'] != null
                                                                && $rowQuestionOptionV['img_3'] != null
                                                                && $rowQuestionOptionV['img_4'] != null
                                                                && $rowQuestionOptionV['img_1'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option2/' . $rowQuestionOptionV['img_2'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option3/' . $rowQuestionOptionV['img_3'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option4/' . $rowQuestionOptionV['img_4'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionV['img_1'] != null
                                                                && $rowQuestionOptionV['img_3'] != null
                                                                && $rowQuestionOptionV['img_4'] != null
                                                                && $rowQuestionOptionV['img_2'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option1/' . $rowQuestionOptionV['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option3/' . $rowQuestionOptionV['img_3'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option4/' . $rowQuestionOptionV['img_4'] . '"/>
                                                        <h4>III</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionV['img_1'] != null
                                                                && $rowQuestionOptionV['img_2'] != null
                                                                && $rowQuestionOptionV['img_4'] != null
                                                                && $rowQuestionOptionV['img_3'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option1/' . $rowQuestionOptionV['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option2/' . $rowQuestionOptionV['img_1'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option4/' . $rowQuestionOptionV['img_4'] . '"/>
                                                        <h4>III</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            //1-2 OR 2-1
                                                            else if ($rowQuestionOptionV['img_1'] != null
                                                                && $rowQuestionOptionV['img_2'] != null
                                                                && $rowQuestionOptionV['img_3'] == null
                                                                && $rowQuestionOptionV['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option1/' . $rowQuestionOptionV['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option2/' . $rowQuestionOptionV['img_2'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }

                                                            //1-3 OR 3-1
                                                            else if ($rowQuestionOptionV['img_1'] != null
                                                                && $rowQuestionOptionV['img_2'] == null
                                                                && $rowQuestionOptionV['img_3'] != null
                                                                && $rowQuestionOptionV['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option1/' . $rowQuestionOptionV['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option3/' . $rowQuestionOptionV['img_3'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            //1-4 OR 4-1
                                                            else if ($rowQuestionOptionV['img_1'] != null
                                                                && $rowQuestionOptionV['img_2'] == null
                                                                && $rowQuestionOptionV['img_3'] == null
                                                                && $rowQuestionOptionV['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option1/' . $rowQuestionOptionV['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option4/' . $rowQuestionOptionV['img_4'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            //2-3 OR 3-2
                                                            else if ($rowQuestionOptionV['img_1'] == null
                                                                && $rowQuestionOptionV['img_2'] != null
                                                                && $rowQuestionOptionV['img_3'] != null
                                                                && $rowQuestionOptionV['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option2/' . $rowQuestionOptionV['img_2'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option3/' . $rowQuestionOptionV['img_3'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            //2-4 OR 4-2
                                                            else if ($rowQuestionOptionV['img_1'] == null
                                                                && $rowQuestionOptionV['img_2'] != null
                                                                && $rowQuestionOptionV['img_3'] == null
                                                                && $rowQuestionOptionV['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option2/' . $rowQuestionOptionV['img_2'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option4/' . $rowQuestionOptionV['img_4'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            //3-4 OR 4-3
                                                            else if ($rowQuestionOptionV['img_1'] == null
                                                                && $rowQuestionOptionV['img_2'] == null
                                                                && $rowQuestionOptionV['img_3'] != null
                                                                && $rowQuestionOptionV['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option3/' . $rowQuestionOptionV['img_3'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option4/' . $rowQuestionOptionV['img_4'] . '"/>
                                                        <h4>II</h4>
                                                        </div>
                                                        </div>';
                                                            }

                                                            else if ($rowQuestionOptionV['img_1'] != null
                                                                && $rowQuestionOptionV['img_2'] == null
                                                                && $rowQuestionOptionV['img_3'] == null
                                                                && $rowQuestionOptionV['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option1/' . $rowQuestionOptionV['img_1'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionV['img_2'] != null
                                                                && $rowQuestionOptionV['img_3'] == null
                                                                && $rowQuestionOptionV['img_4'] == null
                                                                && $rowQuestionOptionV['img_1'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option2/' . $rowQuestionOptionV['img_2'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionV['img_3'] != null
                                                                && $rowQuestionOptionV['img_4'] == null
                                                                && $rowQuestionOptionV['img_1'] == null
                                                                && $rowQuestionOptionV['img_2'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option3/' . $rowQuestionOptionV['img_3'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionV['img_4'] != null
                                                                && $rowQuestionOptionV['img_1'] == null
                                                                && $rowQuestionOptionV['img_2'] == null
                                                                && $rowQuestionOptionV['img_3'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option4/' . $rowQuestionOptionV['img_4'] . '"/>
                                                        <h4>I</h4>
                                                        </div>
                                                        </div>';
                                                            }

                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOptionV['question_no'] . "'" . "value=" . "'" . $rowQuestionOptionV['option_1'] . "'>"
                                                                . '<span id="spantxt">A. ' .  $rowQuestionOptionV['option_1'] . "</span>"
                                                                . "</label>";
                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOptionV['question_no'] . "'" . "value=" . "'" . $rowQuestionOptionV['option_2'] . "'>"
                                                                . '<span id="spantxt">B. ' . $rowQuestionOptionV['option_2']  . "</span>"
                                                                . "</label>";
                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOptionV['question_no'] . "'" . "value=" . "'" . $rowQuestionOptionV['option_3'] . "'>"
                                                                . '<span id="spantxt">C. ' . $rowQuestionOptionV['option_3'] . "</span>"
                                                                . "</label>"
                                                            ;
                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOptionV['question_no'] . "'" . "value=" . "'" . $rowQuestionOptionV['option_4'] . "'>"
                                                                . '<span id="spantxt">D. ' . $rowQuestionOptionV['option_4'] . "</span>"
                                                                . "</label>"
                                                                . "</div>"
                                                                . "</div>"
                                                                . "</div>"
                                                                . "</div></div>";
                                                        }
                                                        echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>
                                  <nav aria-label='Page navigation example'>
                                      <ul class='pagination'>
                                        <li class='page-item'><button class='page-link' type='button' id='backbtn4'>Previous</button></li>
                                        <li class='page-item'><button class='page-link' type='button' id='back51'>1</button></li>
                                        <li class='page-item'><button class='page-link' type='button' id='back52'>2</button></li>
                                        <li class='page-item'><button class='page-link' type='button' id='back53'>3</button></li>
                                        <li class='page-item'><button class='page-link' type='button' id='back54'>4</button></li>
                                        <li class='page-item active'><button class='page-link' type='button' id='back55'>5</button></li>&nbsp;&nbsp;
                                         <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                      </ul>
                                    </nav>
                                </div>
                              </div>
                            </div>";

                                                    }
                                                }
                                                else if ($testV == "Short Answer") {
                                                    echo "<div class='row' id='test5' style='display: none;'>
                        <div class='container'>
                                <div class='card card-secondary' >
                                  <div class='card-header' style='display: block;'> 
                                  <div class='row'>
                                    <div class='col'>
                                      <h4> Test V: Short Answer </h4>
                                      <span>Directions: Using your own words, answer each question in the space provided</span>
                                    </div>
                                    <div class='col' style='text-align: right;'>
                                      <span class='align-text-top'>Page 5 of " . $title['num_test'] . "</span>
                                    </div>
                                    </div>
                                    </div>
                                    </div>";
                                                    if ($QuestionOptionV->rowCount() > 0) {
                                                        while ($rowQuestionOptionV = $QuestionOptionV->fetch()) {
                                                            echo "<div class='card' style='padding: 25px 15px 25px 15px;'>
                                     <div class='form-wrapper' >
                                          <div class='row' style='display: flex;'>
                                          <section><span class='question-no'>" . $index++ . " ."."</span></section>
                                          <div class='col'>
                                          <section><span class='question'>".$rowQuestionOptionV['question']. '<br><span style="color: red;">' . '( '. $rowQuestionOptionV['points'] . ' Point/s )' . '</span>'. "</span></section>
                                          <input class='form-control form-control-sm' id='shorttxt' oninput='this.value = this.value.toUpperCase()' type='text' name='" . $rowQuestionOptionV['question_no'] . "' style='border-bottom: 1px solid #000;'>
                                          </div>
                                          </div>
                                        </div>
                                        </div>";
                                                        }
                                                    }
                                                    echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>
                            <nav aria-label='Page navigation example'>
                                <ul class='pagination'>
                                  <li class='page-item'><button class='page-link' type='button' id='backbtn4'>Previous</button></li>
                                  <li class='page-item'><button class='page-link' type='button' id='back51'>1</button></li>
                                  <li class='page-item'><button class='page-link' type='button' id='back52'>2</button></li>
                                  <li class='page-item'><button class='page-link' type='button' id='back53'>3</button></li>
                                  <li class='page-item'><button class='page-link' type='button' id='back54'>4</button></li>
                                  <li class='page-item active'><button class='page-link' type='button' id='back55'>5</button></li>&nbsp;&nbsp;
                                   <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                </ul>
                              </nav>
                          </div>
                        </div>
                      </div>";

                                                }
                                                else if ($testV == "True/False") {
                                                    echo "<div class='row' id='test5' style='display:none;'>
                        <div class='container'>
                                <div class='card card-secondary' >
                                  <div class='card-header' style='display: block;'>
                                  <div class='row'>
                                    <div class='col'>
                                    <h4> Test V: True or False </h4>
                                    <span>Directions: Read the statements carefully and tell whether the statement is TRUE or FALSE. Choose TRUE if the statement is correct and FALSE if otherwise.</span>
                                    </div>
                                    <div class='col' style='text-align: right;'>
                                      <span class='align-text-top'>Page 5 of " . $title['num_test'] . "</span>
                                    </div>
                                    </div>
                                    </div>
                                    </div>";
                                                    if ($QuestionOptionV->rowCount() > 0) {
                                                        while ($rowQuestionOptionV = $QuestionOptionV->fetch()) {
                                                            echo "<div class='card' style='padding: 25px 15px 25px 15px;'>
                                    <div class='form-wrapper' >
                                          <div class='row' style='display: flex;'>
                                          <section><span class='question-no'>" . $index++ . " ."."</span></section>
                                          <div class='col'>
                                          <section><span class='question'>".$rowQuestionOptionV['question']. '<br><span style="color: red;">' . '( '. $rowQuestionOptionV['points'] . ' Point/s )' . '</span>'. "</span></section>"
                                                                . "<div class='radio-group'>";
                                                            if ($rowQuestionOptionV['img_1'] != null
                                                                && $rowQuestionOptionV['img_2'] != null
                                                                && $rowQuestionOptionV['img_3'] != null
                                                                && $rowQuestionOptionV['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option1/' . $rowQuestionOptionV['img_1'] . '" />
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option2/' . $rowQuestionOptionV['img_2'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option3/' . $rowQuestionOptionV['img_3'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option4/' . $rowQuestionOptionV['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionV['img_1'] != null
                                                                && $rowQuestionOptionV['img_2'] != null
                                                                && $rowQuestionOptionV['img_3'] != null
                                                                && $rowQuestionOptionV['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option1/' . $rowQuestionOptionV['img_1'] . '" />
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option2/' . $rowQuestionOptionV['img_2'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option3/' . $rowQuestionOptionV['img_3'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionV['img_2'] != null
                                                                && $rowQuestionOptionV['img_3'] != null
                                                                && $rowQuestionOptionV['img_4'] != null
                                                                && $rowQuestionOptionV['img_1'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option2/' . $rowQuestionOptionV['img_2'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option3/' . $rowQuestionOptionV['img_3'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option4/' . $rowQuestionOptionV['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionV['img_1'] != null
                                                                && $rowQuestionOptionV['img_3'] != null
                                                                && $rowQuestionOptionV['img_4'] != null
                                                                && $rowQuestionOptionV['img_2'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option1/' . $rowQuestionOptionV['img_1'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option3/' . $rowQuestionOptionV['img_3'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option4/' . $rowQuestionOptionV['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionV['img_1'] != null
                                                                && $rowQuestionOptionV['img_2'] != null
                                                                && $rowQuestionOptionV['img_4'] != null
                                                                && $rowQuestionOptionV['img_3'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option1/' . $rowQuestionOptionV['img_1'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option2/' . $rowQuestionOptionV['img_1'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-4" style="text-align: center;">
                                                        <img class="img-option" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option4/' . $rowQuestionOptionV['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }

                                                            //1-2 OR 2-1
                                                            else if ($rowQuestionOptionV['img_1'] != null
                                                                && $rowQuestionOptionV['img_2'] != null
                                                                && $rowQuestionOptionV['img_3'] == null
                                                                && $rowQuestionOptionV['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option1/' . $rowQuestionOptionV['img_1'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option2/' . $rowQuestionOptionV['img_2'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }

                                                            //1-3 OR 3-1
                                                            else if ($rowQuestionOptionV['img_1'] != null
                                                                && $rowQuestionOptionV['img_2'] == null
                                                                && $rowQuestionOptionV['img_3'] != null
                                                                && $rowQuestionOptionV['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option1/' . $rowQuestionOptionV['img_1'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option3/' . $rowQuestionOptionV['img_3'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            //1-4 OR 4-1
                                                            else if ($rowQuestionOptionV['img_1'] != null
                                                                && $rowQuestionOptionV['img_2'] == null
                                                                && $rowQuestionOptionV['img_3'] == null
                                                                && $rowQuestionOptionV['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option1/' . $rowQuestionOptionV['img_1'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option4/' . $rowQuestionOptionV['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            //2-3 OR 3-2
                                                            else if ($rowQuestionOptionV['img_1'] == null
                                                                && $rowQuestionOptionV['img_2'] != null
                                                                && $rowQuestionOptionV['img_3'] != null
                                                                && $rowQuestionOptionV['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option2/' . $rowQuestionOptionV['img_2'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option3/' . $rowQuestionOptionV['img_3'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            //2-4 OR 4-2
                                                            else if ($rowQuestionOptionV['img_1'] == null
                                                                && $rowQuestionOptionV['img_2'] != null
                                                                && $rowQuestionOptionV['img_3'] == null
                                                                && $rowQuestionOptionV['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option2/' . $rowQuestionOptionV['img_2'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option4/' . $rowQuestionOptionV['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            //3-4 OR 4-3
                                                            else if ($rowQuestionOptionV['img_1'] == null
                                                                && $rowQuestionOptionV['img_2'] == null
                                                                && $rowQuestionOptionV['img_3'] != null
                                                                && $rowQuestionOptionV['img_4'] != null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option3/' . $rowQuestionOptionV['img_3'] . '"/>
                                                        
                                                        </div>
                                                        <div clas="col-6" style="text-align: center;">
                                                        <img class="img-option" style="width: 250px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option4/' . $rowQuestionOptionV['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }

                                                            else if ($rowQuestionOptionV['img_1'] != null
                                                                && $rowQuestionOptionV['img_2'] == null
                                                                && $rowQuestionOptionV['img_3'] == null
                                                                && $rowQuestionOptionV['img_4'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option1/' . $rowQuestionOptionV['img_1'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionV['img_2'] != null
                                                                && $rowQuestionOptionV['img_3'] == null
                                                                && $rowQuestionOptionV['img_4'] == null
                                                                && $rowQuestionOptionV['img_1'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option2/' . $rowQuestionOptionV['img_2'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionV['img_3'] != null
                                                                && $rowQuestionOptionV['img_4'] == null
                                                                && $rowQuestionOptionV['img_1'] == null
                                                                && $rowQuestionOptionV['img_2'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option3/' . $rowQuestionOptionV['img_3'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }
                                                            else if ($rowQuestionOptionV['img_4'] != null
                                                                && $rowQuestionOptionV['img_1'] == null
                                                                && $rowQuestionOptionV['img_2'] == null
                                                                && $rowQuestionOptionV['img_3'] == null) {
                                                                echo '<div class="row" style="justify-content: space-evenly;">
                                                        <div clas="col-12" style="text-align: center;">
                                                        <img class="img-option" style="width: 300px; height: auto; border-radius: 2%;" src="assets/uploads/' . $department . '/' . $division . '/' . $id . '/' . $rowQuestionOptionV['question_type'] . '/' . $rowQuestionOptionV['question_no'] . '/option4/' . $rowQuestionOptionV['img_4'] . '"/>
                                                        
                                                        </div>
                                                        </div>';
                                                            }

                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOptionV['question_no'] . "'" . " value='True'>"
                                                                . "<span id='spantxt'>TRUE</span>"
                                                                . "</label>";
                                                            echo "<label id='optionlbl'>"
                                                                . "<input class='btn radio-btn' type='radio' name=" . "'" . $rowQuestionOptionV['question_no'] . "'" . " value='False'>"
                                                                . "<span id='spantxt'>FALSE</span>"
                                                                . "</label>"
                                                                . "</div>"
                                                                . "</div>"
                                                                . "</div>"
                                                                . "</div></div>";
                                                        }
                                                        echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>
                                  <nav aria-label='Page navigation example'>
                                      <ul class='pagination'>
                                        <li class='page-item'><button class='page-link' type='button' id='backbtn4'>Previous</button></li>
                                        <li class='page-item'><button class='page-link' type='button' id='back51'>1</button></li>
                                        <li class='page-item'><button class='page-link' type='button' id='back52'>2</button></li>
                                        <li class='page-item'><button class='page-link' type='button' id='back53'>3</button></li>
                                        <li class='page-item'><button class='page-link' type='button' id='back54'>4</button></li>
                                        <li class='page-item active'><button class='page-link' type='button' id='back55'>5</button></li>&nbsp;&nbsp;
                                         <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                      </ul>
                                    </nav>
                                </div>
                              </div>
                            </div>";

                                                    }
                                                }
                                                else if ($testV == "Essay") {
                                                    echo "<div class='row' id='test5' style='display: none;'>
                        <div class='container'>
                                <div class='card card-secondary' >
                                  <div class='card-header' style='display: block;'> 
                                  <div class='row'>
                                    <div class='col'>
                                    <h4> Test V: Essay </h4>
                                    <span>Directions: Answer the question to the best of your knowledge. Write your answer in the space provided</span>
                                    </div>
                                    <div class='col' style='text-align: right;'>
                                      <span class='align-text-top'>Page 5 of " . $title['num_test'] . "</span>
                                    </div>
                                    </div>
                                    </div>
                                    </div>";
                                                    if ($QuestionOptionV->rowCount() > 0) {
                                                        while ($rowQuestionOptionV = $QuestionOptionV->fetch()) {
                                                            echo "<div class='card' style='padding: 25px 15px 25px 15px;'>
                                     <div class='form-wrapper' >
                                          <div class='row' style='display: flex;'>
                                          <section><span class='question-no'>" . $index++ . " ."."</span></section>
                                          <div class='col'>
                                          <section><span class='question'>".$rowQuestionOptionV['question']. '<br><span style="color: red;">' . '( '. $rowQuestionOptionV['points'] . ' Point/s )' . '</span>'. "</span></section>
                                          <textarea class='form-control' style='margin-top: 20px;' rows='3' name='" . $rowQuestionOptionV['question_no'] . "' ></textarea>
                                          </div>
                                          </div>
                                        </div>
                                        </div>";
                                                        }
                                                    } echo "<div class='card-footer pb-0' style='background: #545454FF; position: fixed; bottom: 0; left: 0; right: 0; width 100%; display: flex; justify-content: center;'>
                            <nav aria-label='Page navigation example'>
                                <ul class='pagination'>
                                  <li class='page-item'><button class='page-link' type='button' id='backbtn4'>Previous</button></li>
                                  <li class='page-item'><button class='page-link' type='button' id='back51'>1</button></li>
                                  <li class='page-item'><button class='page-link' type='button' id='back52'>2</button></li>
                                  <li class='page-item'><button class='page-link' type='button' id='back53'>3</button></li>
                                  <li class='page-item'><button class='page-link' type='button' id='back54'>4</button></li>
                                  <li class='page-item active'><button class='page-link' type='button' id='back55'>5</button></li>&nbsp;&nbsp;
                                   <li class='page-item'><button type='button' class='btn btn-primary' id ='submitbtn1' name='submit'>Submit</button></li>
                                </ul>
                              </nav>
                          </div>
                        </div>
                      </div>";

                                                }
                                                // Test V End



                        }else { ?>
                            <script>
                              Swal.fire({
                                title: 'EXAM!',
                                html: 'NO QUESTIONS YET, <br> Please contact the administrator for more info.',
                                icon: 'info',
                                confirmButtonText: 'Ok',
                                allowOutsideClick: false,
                                closeOnConfirm: false
                              }).then((result) => {
                                if (result.isConfirmed) {
                                  window.location.href='index.php';
                                }
                              });
                      </script>

                        <?php }

                    } ?> <script>
                            $(document).ready(function (){
                                function onlogoutAdd(){
                                    $.post("queries/active_transaction.php", {delete:"delete", exam_id:"<?php echo $exam_id; ?>"});
                                }
                                window.onload = onLoadAdd();
                                function onLoadAdd() {
                                    $.post("queries/active_transaction.php", {insert: "insert", exam_id:"<?php echo $exam_id; ?>"});
                                }
                                $("#logoutbtn").click( function(){
                                  Swal.fire({
                                      title: "LOGOUT",
                                      html: "Are you sure you want to logout?",
                                      icon: "warning",
                                      showCancelButton: true,
                                      confirmButtonColor: "#1c3d77",
                                      confirmButtonText: "Yes, I am sure",
                                      cancelButtonText: "No, Stay logged in",
                                      closeOnConfirm: false,
                                      closeOnCancel: true
                                    }).then((result) => {
                                      if (result.isConfirmed) {
                                        window.onbeforeunload = function() {
                                          return "Data will be lost if you leave the page, are you sure?";
                                        };
                                        let timerInterval;
                                        Swal.fire({
                                          title: "LOGGING OUT....",
                                          html: "",
                                          timer: 1000,
                                          icon: "warning",
                                          showConfirmButton: false,
                                          timerProgressBar: true,
                                          allowOutsideClick: false,
                                          didOpen: () => {
                                            Swal.showLoading();
                                            const timer = Swal.getPopup().querySelector("b");
                                            timerInterval = setInterval(() => {
                                              timer.textContent = `${Swal.getTimerLeft()}`;
                                            }, 100);
                                          },
                                          willClose: () => {
                                            clearInterval(timerInterval);
                                          }
                                        }).then((result) => {
                                          if (result.dismiss === Swal.DismissReason.timer) {
                                            onlogoutAdd();
                                            window.location.href="queries/logout.php";
                                          }
                                        });
                                       
                                      }
                                  });
                                });
                                var credentials = setInterval(function () {
                                      $.post("queries/log_credentials.php", function (res){
                                        if (res == 1){
                                          clearInterval(credentials);
                                          Swal.fire({
                                            title: "YOU HAVE BEEN LOGGED OUT",
                                            html: "Press continue to proceed",
                                            icon: "warning",
                                            allowOutsideClick: false,
                                            confirmButtonColor: "#1c3d77",
                                            confirmButtonText: "Continue",
                                            closeOnConfirm: false,
                                          }).then((result) => {
                                            if (result.isConfirmed) {
                                              onlogoutAdd();
                                              window.location.href="queries/logout.php";
                                            }
                                          });
                                        }else if (res == 3){
                                          clearInterval(credentials);
                                          $.post("queries/active_transaction.php", {logout:"logout", user_id:"<?php echo $userID; ?>", exam_id:"<?php echo $exam_id; ?>"});
                                          Swal.fire({
                                            title: "YOU HAVE BEEN LOGGED OUT",
                                            html: "Press continue to proceed",
                                            icon: "warning",
                                            confirmButtonColor: "#1c3d77",
                                            allowOutsideClick: false,
                                            confirmButtonText: "Continue",
                                            closeOnConfirm: false,
                                          }).then((result) => {
                                            if (result.isConfirmed) {
                                              $.redirect("auth/auth-login.php");
                                            }
                                          });
                                        }
                                      });
                                    }, 2000);
                            });
                        </script>
                    <?php }
                    // Submit Query End //
                    }
            else { ?>
                <script>
                    $(document).ready(function (){
                        window.location.href = "index.php";
                    });
                </script>
            <?php }
            ?>
                    <button type='submit' class='btn btn-primary' id ='submitbtn' name='submit' style="display: none;">Submit</button>
                </fieldset>
            </form>
            <!-- Main Content End-->
        </div>
    </div>
</div>
<!-- Page Specific JS File -->
<script src="assets/js/page/exam.js"></script>
</body>
</html>