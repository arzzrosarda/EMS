<?php
global $conn;
require "../db/conn.php";
session_start();
if (isset($_SESSION['user'])) {
    $email = $_SESSION['user'];
    $query = $conn->query("SELECT * FROM user WHERE email = '$email' OR username = '$email'");
    $row = $query->fetch();
    if ($row['usertype'] == "admin") {
        header("location: ../admin/admin.php");
    }else if ($row['usertype'] == "examinee") {
        header("location: ../index.php");
    }else if ($row['usertype'] == "main") {
        header("location: ../admin/main/main_admin.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <link rel="shortcut icon" href="../assets/img/logo/Cavite_Province.png">
    <title>PGC Examination System - Register </title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="../assets/modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/modules/fontawesome/css/all.min.css">
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="../assets/modules/izitoast/css/iziToast.min.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <!-- Toast Alert -->

    <!-- General JS Scripts -->
    <script src="../assets/modules/jquery.min.js"></script>
    <script src="../assets/modules/popper.js"></script>
    <script src="../assets/modules/tooltip.js"></script>
    <script src="../assets/modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="../assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
    <script src="../assets/modules/moment.min.js"></script>
    <script src="../assets/js/project.js"></script>

    <!-- Sweet Alert JS-->
    <script src="../assets/modules/sweetalert/sweetalert2.all.min.js"></script>

    <!-- JS Libraies -->
    <script src="../assets/modules/izitoast/js/iziToast.min.js"></script>

    <!-- Page Specific JS File -->
    <script src="js/auth-register.js"></script>
    <!-- Template JS File -->
    <script src="../assets/js/scripts.js"></script>
</head>
<body>
<div id="app">
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
                    <div class="card card-primary">
                        <div class="card-header"><h3>Register</h3></div>
                        <div class="card-body">
                            <form method="get" name="register" enctype="multipart/form-data" action="">
                                <label>Note: All area with <label style="color:red;">*</label> is required</label><br>
                                <label>Exam Control No. <label style="color:red;">*</label></label>
                                <div class="row" style="align-items:center;">
                                    <div class="form-group col">
                                        <input id="txt_examno1"  name="txt_examno1" type="text" class="form-control" autofocus required>
                                    </div>
                                    <div class="form-group">
                                        <h5> - </h5>
                                    </div>
                                    <div class="form-group col">
                                        <input id="txt_examno2"  name="txt_examno2" type="text" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <h5> - </h5>
                                    </div>
                                    <div class="form-group col">
                                        <input id="txt_examno3" name="txt_examno2" type="text" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-4">
                                        <label for="last_name">Last Name <label style="color:red;">*</label></label>
                                        <input id="last_name" name="last_name" type="text" class="form-control" required>
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="first_name">First Name <label style="color:red;">*</label></label>
                                        <input id="first_name" name="first_name" type="text" class="form-control" required>
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="middle_name">Middle Name <label style="color:red;">(Optional)</label></label>
                                        <input id="middle_name" name="middle_name" type="text" class="form-control" >
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="username">Username <label style="color:red;">*</label></label>
                                        <input id="username" type="text" class="form-control" name="username" required>
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="email">Email <label style="color:red;">*</label></label>
                                        <input id="email" type="email" class="form-control" name="email" required>
                                        <span style="font-size: 10px;">Note: Please provide a valid and active email address</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="depselect">Department<label style="color:red;">*</label></label>
                                        <select id="depselect" name="depselect" class="form-control" required>
                                            <option value="">Select Department</option>
                                            <?php
                                            $department_query = $conn->query("SELECT * FROM department");
                                            while ($department = $department_query->fetch()){?>
                                                <option value="<?php echo $department['department']; ?>"><?php echo $department['department']; ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                        <span style="font-size: 10px;">Note: Choose what Department first to proceed on the division.</span>
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="divisgenderselion">Gender <label style="color:red;">*</label></label>
                                        <select id="gendersel" name="gendersel" class="form-control" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label for="divisgenderselion">Division <label style="color:red;">*</label></label>
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <div id="divisionsel">
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtno">Mobile Contact No. <label style="color:red;">*</label></label>
                                    <input id="txtno" name="txtno" type="text" pattern="\d*" maxlength="11" class="form-control" required>
                                </div>
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="password" class="d-block">Password <label style="color:red;">*</label></label>
                                        <div class="input-group">
                                            <input id="password" type="password" class="form-control pwstrength" data-indicator="pwindicator" name="password" required>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-eye" style="cursor:pointer;" id="eye"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <span style="font-size: 10px;">Note: Password must be 8 Characters long, 1 uppercase, 1 lowercase, and 1 number. </span>
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="password2" class="d-block">Password Confirmation <label style="color:red;">*</label></label>
                                        <div class="input-group">
                                            <input id="password2" type="password" class="form-control" name="password2" required>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-eye" style="cursor:pointer;" id="eye1"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-divider">
                                    <h6>Your Home</h6>
                                </div>
                                <div class="row">
                                    <div class="form-group col">
                                        <label>Address: (HOUSE/LOT/STREET/BUILDING/) <label style="color:red;">*</label></label>
                                        <input id="txtaddress" type="text" class="form-control" name="txt_address" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label>Barangay <label style="color:red;">*</label></label>
                                        <input id="txtbrgy" type="text" class="form-control" name="txt_brgy" required>
                                    </div>
                                    <div class="form-group col-6">
                                        <label>City/Municipality <label style="color:red;">*</label></label>
                                        <input id="txtcitymun" type="text" class="form-control" name="txt_citymun" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label>Province <label style="color:red;">*</label></label>
                                        <input id="txtprov" type="text" class="form-control" name="txtprov" required>
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Postal Code <label style="color:red;">*</label></label>
                                        <input id="txtpostal" type="text" class="form-control" name="txtpostal" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="agree" class="custom-control-input" id="agree" required>
                                        <label class="custom-control-label" for="agree">I agree with the terms and conditions <label style="color:red;">*</label></label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="button" class="btn btn-raised btn-primary btn-lg btn-block" id="btnCheckElse">
                                        Register
                                    </button>
                                    <button type="submit" class="btn btn-raised btn-primary btn-lg btn-block" id="btnSubmit" style="display: none;">
                                        Register
                                    </button>
                                </div>
                            </form>
                            <div class="mt-5 text-muted text-center">
                                <p>Already have an account? <a href="auth-login.php">Click Here</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
</body>
</html>