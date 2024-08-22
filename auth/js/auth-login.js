function isValidPassword($pass) {
    var pattern = new RegExp(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/);
    return pattern.test($pass);
  }

$(function () {
  $("#eye").click(function () {
    $(this).toggleClass("fa-eye fa-eye-slash");
    var type = $(this).hasClass("fa-eye-slash") ? "text" : "password";
    $("#txtpass").attr("type", type);
  });
});

  $(document).ready(function(){
    $("#btnlogin").click(function(){
      txtemail = $("#txtemail").val();
      txtpass = $("#txtpass").val();
      if (txtemail == "") {
        $("#txtemail").focus();
        iziToast.warning({
          title: 'EMPTY!',
          message: 'Email is empty',
          position: 'topRight'
        });
      }else if (!isValidPassword(txtpass)) {
        $("#txtpass").focus();
        iziToast.warning({
          title: 'PASSWORD!',
          message: 'must be 8 Characters long, 1 Uppercase , 1 lowercase, and 1 Number',
          position: 'topRight'
        });
      }else {
        $.post("../queries/login.php", {emailtxt:txtemail, passtxt:txtpass}, function(res){
          if (res == 3){
            iziToast.warning({
              title: 'EMAIL OR USERNAME',
              message: 'Email or username is not yet registered!',
              position: 'topRight'
            });
          }else if(res == 2){
            iziToast.warning({
              title: 'PASSWORD!',
              message: 'Password is incorrect!',
              position: 'topRight'
            });
          }else if (res == 1) {
            let timerInterval;
            Swal.fire({
              title: "Success",
              html: "Successfully Logged In, <br> Redirecting....",
              timer: 1000,
              icon: "success",
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
                $.post("../queries/redirect.php", {emailtxt:txtemail, }, function(login){
                  if (login == 1) {
                    window.location.href="../admin/admin.php";
                  }else if (login == 2) {
                    window.location.href="../index.php";
                  }else if (login == 3) {
                    window.location.href="../admin/main/main_admin.php";
                  }
                });
              }
            });
          }
        });
      }
    });
  });