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
    }else if ($row['usertype'] == "main"){
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
  <title>PGC Examination System - Login </title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="../assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/modules/fontawesome/css/all.min.css">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="../assets/modules/jquery-selectric/selectric.css">
  <link rel="stylesheet" href="../assets/modules/izitoast/css/iziToast.min.css">
  
  <!-- Template CSS -->
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/components.css">
  <!-- Sweet Alert -->
  <script src="../assets/modules/sweetalert/sweetalert2.all.min.js"></script>
  <!-- General JS Scripts -->
  <script src="../assets/modules/jquery.min.js"></script>
  <script src="../assets/modules/popper.js"></script>
  <script src="../assets/modules/tooltip.js"></script>
  <script src="../assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="../assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="../assets/modules/moment.min.js"></script>
  
  <!-- JS Libraies -->
  <script src="../assets/modules/izitoast/js/iziToast.min.js"></script>

  <!-- Page Specific JS File -->
  <script src="js/auth-login.js"></script>
  
  <!-- Template JS File -->
  <script src="../assets/js/scripts.js"></script>

</head>

<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
              <div class="login-brand">
                  <img src="../assets/img/project_logo.svg" alt="logo" width="100" class="shadow-light rounded-circle">
              </div>
            <div class="card card-primary">
              <div class="card-header"><h3>Login</h3></div>
              <div class="card-body">
                  <div class="form-group">
                    <label for="txtemail">Email/Username</label>
                    <input id="txtemail" type="text" class="form-control" name="email" tabindex="1" required autofocus>
                  </div>
                  <div class="form-group">
                      <div class="d-block">
                          <label for="password" class="control-label">Password</label>
                      </div>
                      <div class="input-group">
                          <input type="password" id="txtpass" class="form-control" tabindex="2">
                          <div class="input-group-prepend">
                              <div class="input-group-text">
                                  <i class="fas fa-eye" style="cursor:pointer;" id="eye"></i>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="form-group">
                    <button type="button" id="btnlogin" class="btn btn-primary btn-lg btn-block" tabindex="3">
                      Login
                    </button>
                  </div>
                  <div class="mt-5 text-muted text-center" >
                    <p>Don't have an account? <a href="auth-register.php">Create One</a></p> 
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